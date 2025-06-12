<?php

$orgSlug = 'ihub-nairobi'; // Replace with your organization slug
$paymentId = '6839964a9be726d5d0434d9e'; // Replace with the payment ID
$accessToken = 'b7d71b3416fafd8c18c29488469fd55215a27b4b'; // Replace with your OAuth2 token

$url = "https://app.officernd.com/api/v2/organizations/{$orgSlug}/payments/{$paymentId}";


// Initialize cURL session
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $accessToken",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

// Execute request
$response = curl_exec($ch);

// Check for errors
if ($response === false) {
    echo "cURL Error: " . curl_error($ch);
} else {
    echo "Response: " . $response;
}

// Close cURL session
curl_close($ch);

?>
