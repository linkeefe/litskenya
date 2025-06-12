<?php
$invoiceId = 'ORD-INV-5058'; // Replace with actual invoice ID from v1
$accessToken = 'b7d71b3416fafd8c18c29488469fd55215a27b4b'; // OfficeRnD API v1 token

$url = "https://officernd.com/api/v1/invoices/{$invoiceId}/payments";

$data = [
    "amount" => 23.30,
    "date" => date("2025-06-12"), // Format: YYYY-MM-DD
    "method" => "Cash", // Valid methods: Cash, BankTransfer, CreditCard, etc.
    "note" => "Paid via webhook confirmation"
];

$payload = json_encode($data);

$headers = [
    "Authorization: Bearer $accessToken",
    "Content-Type: application/json"
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    echo "OfficeRnD API v1 Response:\n$response\n";
}

curl_close($ch);
