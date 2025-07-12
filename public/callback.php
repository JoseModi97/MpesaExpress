<?php

require_once __DIR__ . '/../vendor/autoload.php';

$callback_data = file_get_contents('php://input');

if (!is_dir(__DIR__ . '/../logs')) {
    mkdir(__DIR__ . '/../logs');
}

$log_file = __DIR__ . '/../logs/transactions.log';
file_put_contents($log_file, $callback_data . PHP_EOL, FILE_APPEND);

http_response_code(200);
