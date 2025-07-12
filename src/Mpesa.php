<?php

class Mpesa
{
    private $consumerKey;
    private $consumerSecret;
    private $passkey;
    private $shortcode;
    private $callbackUrl;
    private $transactionType;
    private $environment;

    public function __construct()
    {
        $this->consumerKey = $_ENV['MPESA_CONSUMER_KEY'];
        $this->consumerSecret = $_ENV['MPESA_CONSUMER_SECRET'];
        $this->passkey = $_ENV['MPESA_PASSKEY'];
        $this->shortcode = $_ENV['MPESA_SHORTCODE'];
        $this->callbackUrl = $_ENV['MPESA_CALLBACK_URL'];
        $this->transactionType = $_ENV['MPESA_TRANSACTION_TYPE'];
        $this->environment = $_ENV['MPESA_ENVIRONMENT'];
    }

    private function getAccessToken()
    {
        $url = $this->environment === 'sandbox' ? 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials' : 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        $credentials = base64_encode($this->consumerKey . ':' . $this->consumerSecret);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $credentials));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($result);
        return $result->access_token;
    }

    public function stkPush($phone, $amount, $reference, $description)
    {
        $url = $this->environment === 'sandbox' ? 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest' : 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

        $timestamp = date('YmdHis');
        $password = base64_encode($this->shortcode . $this->passkey . $timestamp);

        $curl_post_data = array(
            'BusinessShortCode' => $this->shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => $this->transactionType,
            'Amount' => $amount,
            'PartyA' => $phone,
            'PartyB' => $this->shortcode,
            'PhoneNumber' => $phone,
            'CallBackURL' => $this->callbackUrl,
            'AccountReference' => $reference,
            'TransactionDesc' => $description
        );

        $data_string = json_encode($curl_post_data);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $this->getAccessToken()));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        $curl_response = curl_exec($curl);
        curl_close($curl);

        return $curl_response;
    }
}
