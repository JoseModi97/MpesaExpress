<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Mpesa.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$mpesa = new Mpesa();

$phone = $_POST['phone'];
$amount = $_POST['amount'];
$reference = $_POST['reference'];
$description = $_POST['description'];

$response = $mpesa->stkPush($phone, $amount, $reference, $description);

echo $response;
