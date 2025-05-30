<?php
// Read raw payload
$rawInput = file_get_contents("php://input");
$data = json_decode($rawInput, true);

// Optional: log raw data
file_put_contents("invoice_webhook_log.txt", date('c') . " - " . $rawInput . PHP_EOL, FILE_APPEND);

// Validate event type
if (!isset($data['eventType']) || $data['eventType'] !== 'invoice.created') {
    http_response_code(400);
    echo "Invalid or missing eventType.";
    exit;
}

$invoice = $data['data']['object'] ?? null;

if (!$invoice) {
    http_response_code(400);
    echo "Invalid data payload.";
    exit;
}

// Extract necessary fields
$invoiceId      = $invoice['_id'];
$invoiceNumber  = $invoice['number'];
$status         = $invoice['status'];
$invoiceDate    = date('Y-m-d H:i:s', strtotime($invoice['date']));
$dueDate        = date('Y-m-d H:i:s', strtotime($invoice['dueDate']));
$payableAmount  = $invoice['payableAmount'];
$currency       = $invoice['currency'];
$createdAt      = date('Y-m-d H:i:s', strtotime($invoice['createdAt']));

$description    = $invoice['lines'][0]['description'] ?? null;
$unitPrice      = $invoice['lines'][0]['unitPrice'] ?? 0;

// Connect to DB
$host = 'localhost';
$dbname = 'hgardtegth';
$user = 'hgardtegth';
$pass = '5qyRJqQtQT';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Save or update invoice
    $stmt = $pdo->prepare("
        INSERT INTO officernd_invoices (
            invoice_id, invoice_number, status, invoice_date, due_date,
            payable_amount, currency, description, unit_price, created_at
        )
        VALUES (
            :invoice_id, :invoice_number, :status, :invoice_date, :due_date,
            :payable_amount, :currency, :description, :unit_price, :created_at
        )
        ON DUPLICATE KEY UPDATE
            status = VALUES(status),
            payable_amount = VALUES(payable_amount),
            due_date = VALUES(due_date),
            unit_price = VALUES(unit_price),
            description = VALUES(description),
            created_at = VALUES(created_at)
    ");

    $stmt->execute([
        ':invoice_id'      => $invoiceId,
        ':invoice_number'  => $invoiceNumber,
        ':status'          => $status,
        ':invoice_date'    => $invoiceDate,
        ':due_date'        => $dueDate,
        ':payable_amount'  => $payableAmount,
        ':currency'        => $currency,
        ':description'     => $description,
        ':unit_price'      => $unitPrice,
        ':created_at'      => $createdAt,
    ]);

    http_response_code(200);
    echo "Invoice saved successfully.";
} catch (PDOException $e) {
    http_response_code(500);
    echo "DB error: " . $e->getMessage();
}
?>
