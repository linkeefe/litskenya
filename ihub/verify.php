<?php
// Debug script to check received GET parameters

// Display all errors for debugging (optional for dev environments)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Output header
echo "<h2>Received Data</h2>";

// Check if any GET data is received
if (!empty($_GET)) {
    echo "<pre>";
    print_r($_GET); // Print all GET parameters
    echo "</pre>";
} else {
    echo "No data received.";
}
?>
