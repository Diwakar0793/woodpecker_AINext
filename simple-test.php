<?php
// Simple test to verify PHP is working
header('Content-Type: application/json');

echo json_encode([
    'success' => true, 
    'message' => 'PHP is working! Server time: ' . date('Y-m-d H:i:s'),
    'php_version' => phpversion(),
    'server_info' => [
        'method' => $_SERVER['REQUEST_METHOD'],
        'host' => $_SERVER['HTTP_HOST'],
        'script_name' => $_SERVER['SCRIPT_NAME']
    ]
]);
?>
