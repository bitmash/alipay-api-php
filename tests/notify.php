<?php

require_once getcwd() . "/../Alipay.php";

$tid = $_POST['out_trade_no'];
$tno = $_POST['trade_no'];
$total_amount = $_POST['total_fee']; // don't forget to substract Alipay Transaction fee
$alipay = new Alipay();

// Verify system transaction ID hasn't been used by looking it up in your DB.

try {
    if ($alipay->verifyPayment($_POST) === false) // Transaction isn't complete
    {
        echo "Unable to verify payment.";
        return false;
    }
} catch (Exception $e) { // Connection error
    echo $e->getMessage();
    return false;
} catch (AlipayException $e) { // Hash or invalid transaction error
    echo $e->getMessage();
    return false;
}

// Update the transaction in your DB and add funds for user