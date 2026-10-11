<?php

require_once __DIR__ . '/config.php';

$botToken = $BOT_TOKEN;

$isHttps = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
           (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
           (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'vercel.app') !== false);

$protocol = $isHttps ? "https" : "http";
$domain   = $_SERVER['HTTP_HOST'] ?? 'fala-sable.vercel.app';
$webhookUrl = "https://" . $domain . "/webhook.php";

echo "<h1>Configurando Webhook de Telegram</h1>";
echo "<p>URL detectada para el webhook: <b>$webhookUrl</b></p>";

if ($protocol !== 'https') {
    echo "<p style='color:red;'>ADVERTENCIA: Telegram requiere estrictamente que tu servidor tenga HTTPS (SSL) para usar Webhooks. Si estás en localhost o http sin SSL, esto fallará.</p>";
}

$webhookParams = [
    'url' => $webhookUrl,
    'secret_token' => $WEBHOOK_SECRET
];

$url = "https://api.telegram.org/bot$botToken/setWebhook?" . http_build_query($webhookParams);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

echo "<h3>Respuesta de Telegram:</h3>";
echo "<pre>" . print_r(json_decode($response, true), true) . "</pre>";

echo "<p>Si dice <b>'ok': true</b>, tu bot ya está configurado y funcionando de manera 100% pasiva y eficiente. Ya no necesitas procesos en segundo plano.</p>";
