<?php

$merchantCode = 'DS36346';
$apiKey = '246821d7b37256f34522c854de9c8bbb';
$amount = 100000;
$datetime = date('Y-m-d H:i:s');

$stringToSign = $merchantCode . $amount . $datetime;
$signature = hash_hmac('sha256', $stringToSign, $apiKey);

echo "=== GET PAYMENT METHOD TEST ===\n";
echo "MC: $merchantCode\n";
echo "Amount: $amount\n";
echo "Datetime: $datetime\n";
echo "String to sign: $stringToSign\n";
echo "Signature: $signature\n\n";

$params = [
    'merchantcode' => $merchantCode,
    'amount'       => $amount,
    'datetime'     => $datetime,
    'signature'    => $signature,
];

$params_string = json_encode($params);

$url = 'https://sandbox.duitku.com/webapi/api/merchant/paymentmethod/getpaymentmethod';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($params_string)
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

$request = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "HTTP Code: $httpCode\n";
echo "Response: $request\n";

// Parse & display kalo sukses
if ($httpCode == 200) {
    $results = json_decode($request, true);
    echo "\n=== PAYMENT METHODS ===\n";
    if (isset($results['paymentFee'])) {
        foreach ($results['paymentFee'] as $pm) {
            echo "- {$pm['paymentMethod']}: {$pm['paymentName']} (fee: {$pm['totalFee']})\n";
        }
    } else {
        echo "No payment methods found\n";
    }
}