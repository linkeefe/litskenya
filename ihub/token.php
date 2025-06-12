<?php

$orgSlug = 'ihub-nairobi'; // Replace with your organization slug
$invoiceId = 'ORD-INV-5058'; // Replace with the invoice ID
$accessToken = 'b7d71b3416fafd8c18c29488469fd55215a27b4b'; // Replace with your OAuth2 token

$url = "https://app.officernd.com/api/v2/organizations/{$orgSlug}/invoices/{$invoiceId}";

$data = json_encode([
    'isPaid' => true // Mark the invoice as paid
]);

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $accessToken",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);

if ($response === false) {
    echo "cURL Error: " . curl_error($ch);
} else {
    echo "Response: " . $response;
}

curl_close($ch);

?>

