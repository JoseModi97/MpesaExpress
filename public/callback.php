<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Database.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$db = new Database();

$callback_data = file_get_contents('php://input');

$log_file = __DIR__ . '/../logs/transactions.log';
file_put_contents($log_file, $callback_data . PHP_EOL, FILE_APPEND);

$data = json_decode($callback_data);

$merchant_request_id = $data->Body->stkCallback->MerchantRequestID;
$checkout_request_id = $data->Body->stkCallback->CheckoutRequestID;
$result_code = $data->Body->stkCallback->ResultCode;
$result_desc = $data->Body->stkCallback->ResultDesc;

if ($result_code == 0) {
    $amount = $data->Body->stkCallback->CallbackMetadata->Item[0]->Value;
    $mpesa_receipt_number = $data->Body->stkCallback->CallbackMetadata->Item[1]->Value;
    $transaction_date = $data->Body->stkCallback->CallbackMetadata->Item[3]->Value;
    $phone_number = $data->Body->stkCallback->CallbackMetadata->Item[4]->Value;

    $db->query("INSERT INTO transactions (merchant_request_id, checkout_request_id, phone_number, amount, mpesa_receipt_number, transaction_date, status_code, status_description) VALUES (:merchant_request_id, :checkout_request_id, :phone_number, :amount, :mpesa_receipt_number, :transaction_date, :status_code, :status_description)");
    $db->bind(':merchant_request_id', $merchant_request_id);
    $db->bind(':checkout_request_id', $checkout_request_id);
    $db->bind(':phone_number', $phone_number);
    $db->bind(':amount', $amount);
    $db->bind(':mpesa_receipt_number', $mpesa_receipt_number);
    $db->bind(':transaction_date', $transaction_date);
    $db->bind(':status_code', $result_code);
    $db->bind(':status_description', $result_desc);
    $db->execute();
} else {
    $db->query("INSERT INTO transactions (merchant_request_id, checkout_request_id, status_code, status_description) VALUES (:merchant_request_id, :checkout_request_id, :status_code, :status_description)");
    $db->bind(':merchant_request_id', $merchant_request_id);
    $db->bind(':checkout_request_id', $checkout_request_id);
    $db->bind(':status_code', $result_code);
    $db->bind(':status_description', $result_desc);
    $db->execute();
}

http_response_code(200);
