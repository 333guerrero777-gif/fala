<?php

if (basename($_SERVER['PHP_SELF'] ?? '') === basename(__FILE__)) {
    http_response_code(403);
    exit('Forbidden');
}

function init_secure_session() {
    if (session_status() === PHP_SESSION_NONE) {
        if (!headers_sent()) {
            $isHttps = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
                       (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

            $tmpSession = sys_get_temp_dir() . '/falabella_sessions';
            if (!is_dir($tmpSession)) {
                @mkdir($tmpSession, 0777, true);
            }
            if (is_dir($tmpSession) && is_writable($tmpSession)) {
                @session_save_path($tmpSession);
            }

            @ini_set('session.use_strict_mode', '1');
            @ini_set('session.use_only_cookies', '1');

            @session_set_cookie_params([
                'lifetime' => 0,
                'path'     => '/',
                'domain'   => '',
                'secure'   => $isHttps,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            @session_start();
        } else {
            @session_start();
        }
    }
}

function send_security_headers() {
    if (!headers_sent()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }
}

function get_client_ip() {
    $keys = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_REAL_IP',
        'REMOTE_ADDR'
    ];

    foreach ($keys as $key) {
        if (!empty($_SERVER[$key])) {
            $ips = explode(',', $_SERVER[$key]);
            $ip = trim($ips[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }

    return '127.0.0.1';
}

function check_rate_limit($actionKey = 'global', $maxAttempts = 15, $windowSeconds = 60) {
    $ip = get_client_ip();
    $dir = dirname(__DIR__) . '/data_limits';

    if (!is_dir($dir) || !is_writable($dir)) {
        $dir = sys_get_temp_dir() . '/falabella_limits';
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
    }

    $hash = hash('sha256', $ip . '_' . $actionKey);
    $file = $dir . '/' . $hash . '.json';
    $now = time();

    $data = ['count' => 0, 'first_time' => $now];

    if (file_exists($file)) {
        $content = @file_get_contents($file);
        $decoded = json_decode($content, true);
        if (is_array($decoded) && isset($decoded['count'], $decoded['first_time'])) {
            $data = $decoded;
        }
    }

    if (($now - $data['first_time']) > $windowSeconds) {
        $data = ['count' => 1, 'first_time' => $now];
    } else {
        $data['count']++;
    }

    @file_put_contents($file, json_encode($data), LOCK_EX);

    if ($data['count'] > $maxAttempts) {
        http_response_code(429);
        header('Retry-After: ' . ($windowSeconds - ($now - $data['first_time'])));
        exit('Demasiadas solicitudes. Por favor, intenta de nuevo en unos momentos.');
    }
}

function clean_num($str) {
    return preg_replace('/\D/', '', (string)$str);
}

function is_bot_user_agent() {
    $ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
    if (empty($ua) || strlen($ua) < 12) {
        return true;
    }

    $badBots = [
        'bot', 'crawl', 'spider', 'slurp', 'mediapartners', 'curl', 'wget',
        'python', 'httpclient', 'libwww', 'headless', 'phantomjs', 'selenium',
        'puppeteer', 'scrapy', 'postman', 'insomnia', 'http-client', 'go-http-client'
    ];

    foreach ($badBots as $b) {
        if (strpos($ua, $b) !== false) {
            return true;
        }
    }

    return false;
}

function generate_bot_token($formName = 'default') {
    init_secure_session();
    $timestamp = time();
    $nonce = bin2hex(random_bytes(8));
    $signature = hash_hmac('sha256', $formName . '|' . $timestamp . '|' . $nonce, 'falabella_salt_sec_key');
    $token = $nonce . '.' . $signature;

    $key = 'bot_token_' . $formName;
    $_SESSION[$key] = [
        'token' => $token,
        'time'  => $timestamp
    ];
    return [
        'token'     => $token,
        'timestamp' => $timestamp
    ];
}

function validate_bot_submission($formName = 'default', $minSeconds = 1, $maxSeconds = 7200) {
    init_secure_session();

    if (is_bot_user_agent()) {
        http_response_code(403);
        exit('Acceso denegado (detección de agente automatizado).');
    }

    if (!empty($_POST['website_url']) || !empty($_POST['email_secondary'])) {
        http_response_code(403);
        exit('Solicitud bloqueada por filtro de protección.');
    }

    $postToken = (string)($_POST['antispam_token'] ?? '');
    $postTime  = (int)($_POST['form_render_time'] ?? 0);
    $key       = 'bot_token_' . $formName;

    $sessionData = $_SESSION[$key] ?? null;
    $now = time();

    if ($sessionData && is_array($sessionData)) {
        if (!hash_equals($sessionData['token'], $postToken)) {
            http_response_code(403);
            exit('Token de seguridad inválido.');
        }
        $elapsedSession = $now - $sessionData['time'];
        if ($elapsedSession < $minSeconds) {
            http_response_code(429);
            exit('Formulario completado demasiado rápido.');
        }
        unset($_SESSION[$key]);
        return;
    }

    if (!empty($postToken) && strpos($postToken, '.') !== false) {
        list($nonce, $sig) = explode('.', $postToken, 2);
        $expectedSig = hash_hmac('sha256', $formName . '|' . $postTime . '|' . $nonce, 'falabella_salt_sec_key');
        if (hash_equals($expectedSig, $sig)) {
            $elapsedClient = $now - $postTime;
            if ($elapsedClient < $minSeconds) {
                http_response_code(429);
                exit('Formulario completado demasiado rápido.');
            }
            if ($elapsedClient > $maxSeconds) {
                http_response_code(403);
                exit('El formulario ha expirado. Por favor, recarga e intenta de nuevo.');
            }
            return;
        }
    }

    http_response_code(403);
    exit('Sesión expirada o token no válido. Por favor, recarga la página.');
}
