<?php
$clientId = '9CWdnstUmrbWYUNV';
$clientSecret = 'GRKqHGSPhYQTQcb8N2IMqG58oIkbCRnr';

$url = "https://app.officernd.com/oauth/token";

$data = http_build_query([
    'grant_type' => 'client_credentials',
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'audience' => 'https://api.officernd.com'
]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/x-www-form-urlencoded"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    $result = json_decode($response, true);
    $accessToken = $result['access_token'];
    echo "✅ Access token: " . $accessToken;
} else {
    echo "❌ Failed to get token. Response: $response";
}
?>
