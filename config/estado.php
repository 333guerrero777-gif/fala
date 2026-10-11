<?php

require_once __DIR__ . '/security.php';
require_once __DIR__ . '/storage.php';
send_security_headers();

header('Content-Type: application/json; charset=utf-8');

$doc    = isset($_GET['doc'])  ? clean_num(substr(trim($_GET['doc']), 0, 15)) : '';
$dinaId = isset($_GET['dina']) ? preg_replace('/[^a-zA-Z0-9_]/', '', substr(trim($_GET['dina']), 0, 50)) : '';
$estado = 'pending';

if ($doc !== '') {
    $logins = storage_get_data('logins.json');
    if (isset($logins[$doc]['estado'])) {
        $estado = $logins[$doc]['estado'];
    }

    echo json_encode(['tipo' => 'login', 'estado' => $estado]);
    exit;
}

if ($dinaId !== '') {
    $din = storage_get_data('dinamicas.json');
    if (isset($din[$dinaId]['estado'])) {
        $estado = $din[$dinaId]['estado'];
    }

    echo json_encode(['tipo' => 'dinamica', 'estado' => $estado]);
    exit;
}

echo json_encode(['error' => 'Sin parámetros doc o dina', 'estado' => 'error']);
exit;
