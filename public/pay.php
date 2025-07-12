<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Mpesa.php';
require_once __DIR__ . '/../src/Database.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$mpesa = new Mpesa();
$db = new Database();

$phone = $_POST['phone'];
$amount = $_POST['amount'];
$reference = $_POST['reference'];
$description = $_POST['description'];

$response = $mpesa->stkPush($phone, $amount, $reference, $description);

$data = json_decode($response);

$merchant_request_id = $data->MerchantRequestID;
$checkout_request_id = $data->CheckoutRequestID;
$response_code = $data->ResponseCode;
$response_description = $data->ResponseDescription;

try {
    if ($response_code == 0) {
        $db->query("INSERT INTO transactions (merchant_request_id, checkout_request_id, phone_number, amount, status_code, status_description) VALUES (:merchant_request_id, :checkout_request_id, :phone_number, :amount, :status_code, :status_description)");
        $db->bind(':merchant_request_id', $merchant_request_id);
        $db->bind(':checkout_request_id', $checkout_request_id);
        $db->bind(':phone_number', $phone);
        $db->bind(':amount', $amount);
        $db->bind(':status_code', $response_code);
        $db->bind(':status_description', $response_description);
        $db->execute();
    } else {
        $db->query("INSERT INTO transactions (merchant_request_id, checkout_request_id, status_code, status_description) VALUES (:merchant_request_id, :checkout_request_id, :status_code, :status_description)");
        $db->bind(':merchant_request_id', $merchant_request_id);
        $db->bind(':checkout_request_id', $checkout_request_id);
        $db->bind(':status_code', $response_code);
        $db->bind(':status_description', $response_description);
        $db->execute();
    }
} catch (PDOException $e) {
    $log_file = __DIR__ . '/../logs/db_errors.log';
    if (!is_dir(__DIR__ . '/../logs')) {
        mkdir(__DIR__ . '/../logs');
    }
    file_put_contents($log_file, $e->getMessage() . PHP_EOL, FILE_APPEND);
}

echo $response;
