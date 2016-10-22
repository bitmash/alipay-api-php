<?php

require_once getcwd() . "/../Alipay.php";

$sale_id = uniqid();
$amount = 10.25;
$description = "A pair of shoes";
$uuid = uuid();
// Associate the sale id with uuid in your database for a look up once Alipay
// pings your notify_url
$return_url = "http://localhost/alipay/tests/return.php?sale_id=$sale_id";
$notify_url = "http://localhost/alipay/tests/notify.php?id=$uuid";


function uuid()
{
    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0010
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

$alipay = new Alipay();
// Generates a one-time URL to redirect the Buyer to
$approve = $alipay->createPayment($sale_id, $amount, "USD", $description, $return_url, $notify_url);
echo "<a href='$approve'>Test Transaction Link</a>";