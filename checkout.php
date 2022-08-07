<?php

require_once "common.php";

function createOrder($amount){
    $headers = array(
        "content-type: application/json",
        "x-client-id: " . CashfreeConfig::$appId,
        "x-client-secret: " . CashfreeConfig::$secret,
        "x-api-version: " . CashfreeConfig::$apiVersion,
    );

    $data = array(
        "order_amount" => $amount,
        "order_currency" => "INR",
        "customer_details" => array(
            "customer_id" => "1234",
            "customer_email" => "support@cashfree.com",
            "customer_phone" => "9908612345"
        ),
        "order_meta" => array(
            "return_url" => CashfreeConfig::$returnHost . "/cashfree-api-pg/backend/return.php?orderId={order_id}&token={order_token}",
            "notify_url" => "",
        )
        );
    $postResp = doPost(CashfreeConfig::$baseUrl . "/orders", $headers, $data);
    return $postResp;
}

$amount = 100;
$resp = createOrder($amount);
if($resp["code"] == 200){
    $paymentLink = $resp["data"]["payment_link"];
    header("Location: $paymentLink");
} else {
    echo "Something went wrong with order creation! \n";
    echo json_encode($resp["data"]);
}

?>
