<?php

if (basename($_SERVER['PHP_SELF'] ?? '') === basename(__FILE__)) {
    http_response_code(403);
    exit('Forbidden');
}

$BOT_TOKEN = '8878826469:AAEiQuRQn-PzvPSiL-tKRJ9pw8TmwGpVJ2M';
$CHAT_ID   = '-5594012756';
$WEBHOOK_SECRET = 'FB_SecTok_' . hash('sha256', $BOT_TOKEN . '_falabella_sec');
