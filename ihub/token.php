<?php
$accessToken = '6af19e151805e6633f96f37712947939f036bae8';
$invoiceId = '6839964a9be726d5d0434d9e';
$amount = 20.00;
$paymentDate = '2025-05-30';

$ch = curl_init("https://api.officernd.com/v2/payments");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $accessToken",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'invoiceId' => $invoiceId,
    'amount' => $amount,
    'paymentDate' => $paymentDate,
    'paymentMethod' => 'manual',
    'notes' => 'Paid via PesaPal - M-Pesa TXN: XYZ123'
]));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 201) {
    echo "✅ Payment recorded and invoice updated.\n";
} else {
    echo "❌ Failed to register payment.\n";
    echo "HTTP Code: $httpCode\n";
    echo "Response: $response\n";
}
?>
