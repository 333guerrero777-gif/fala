<?php

if (basename($_SERVER['PHP_SELF'] ?? '') === basename(__FILE__)) {
    http_response_code(403);
    exit('Forbidden');
}

function get_storage_file_path($filename) {
    $cleanName = basename($filename);
    $localPath = dirname(__DIR__) . '/js/' . $cleanName;

    if (is_writable($localPath) || is_writable(dirname($localPath))) {
        return $localPath;
    }

    $tmpDir = sys_get_temp_dir() . '/falabella_data';
    if (!is_dir($tmpDir)) {
        @mkdir($tmpDir, 0777, true);
    }

    $tmpPath = $tmpDir . '/' . $cleanName;
    if (!file_exists($tmpPath) && file_exists($localPath)) {
        @copy($localPath, $tmpPath);
    }

    return $tmpPath;
}

function storage_get_data($filename) {
    $filePath = get_storage_file_path($filename);
    if (!file_exists($filePath)) {
        return [];
    }
    $content = @file_get_contents($filePath);
    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

function storage_save_data($filename, array $data) {
    $filePath = get_storage_file_path($filename);
    $json = json_encode($data, JSON_PRETTY_PRINT);
    return (bool)@file_put_contents($filePath, $json, LOCK_EX);
}

function storage_update_item($filename, $key, $value) {
    $filePath = get_storage_file_path($filename);
    $fp = @fopen($filePath, 'c+');
    if ($fp && flock($fp, LOCK_EX)) {
        $content = stream_get_contents($fp);
        $data = json_decode($content, true);
        if (!is_array($data)) {
            $data = [];
        }
        $data[$key] = $value;
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
        flock($fp, LOCK_UN);
        fclose($fp);
        return true;
    }
    if ($fp) {
        fclose($fp);
    }

    $data = storage_get_data($filename);
    $data[$key] = $value;
    return storage_save_data($filename, $data);
}
