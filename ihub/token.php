

<?php
$invoiceId = 'ORD-INV-5058'; // Replace with actual Invoice ID
$accessToken = 'b7d71b3416fafd8c18c29488469fd55215a27b4b'; // Replace with your OfficeRnD API token

$url = "https://api.officernd.com/v2/billing/invoices/{$invoiceId}/payments";

$data = [
    "amount" => 23.20, // Replace with actual payment amount
    "paymentDate" => date("c"), // Current ISO 8601 date (e.g., 2025-06-12T12:00:00Z)
    "paymentMethod" => "Cash", // Or "CreditCard", "BankTransfer", etc.
    "notes" => "Payment received via webhook"
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
    echo 'Error:' . curl_error($ch);
} else {
    echo "Response from OfficeRnD:\n";
    echo $response;
}

curl_close($ch);

