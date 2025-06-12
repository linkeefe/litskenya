

<?php
$invoiceId = '6839964a9be726d5d0434d9e'; // Use correct v2 ID
$accessToken = 'b7d71b3416fafd8c18c29488469fd55215a27b4b';

$url = "https://api.officernd.com/v2/billing/invoices/{$invoiceId}/payments";

$data = [
    "amount" => 23.30,
    "paymentDate" => date("c"), // ISO 8601 format
    "paymentMethod" => "Cash",
    "notes" => "Webhook payment confirmation"
];

$payload = json_encode($data);

$headers = [
    "Authorization: Bearer $accessToken",
    "Content-Type: application/json"
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    echo "API v2 Response:\n$response\n";
}

curl_close($ch);
