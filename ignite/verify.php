<?php

// Define valid credentials
$validUsername = 'admin'; // Replace with your desired username
$validPassword = '123'; // Replace with your desired password

// Check if the user has sent credentials
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
    // Prompt the user for a username and password
    header('WWW-Authenticate: Basic realm="Restricted Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'You must enter a valid username and password to access this page.';
    exit;
}

// Verify the provided credentials
if ($_SERVER['PHP_AUTH_USER'] !== $validUsername || $_SERVER['PHP_AUTH_PW'] !== $validPassword) {
    // Invalid credentials
    header('WWW-Authenticate: Basic realm="Restricted Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Invalid username or password.';
    exit;
}

// If the credentials are correct, proceed with the database connection and functionality
// Database connection parameters
$host = 'localhost';
$dbname = 'ignite';
$username = 'root';
$password = '';

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle "Verify" action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Update status to "verified" if it's not already verified
    $stmt = $pdo->prepare("SELECT `status` FROM attendees WHERE `id` = ?");
    $stmt->execute([$id]);
    $attendee = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($attendee && $attendee['status'] !== 'verified') {
        $updateStmt = $pdo->prepare("UPDATE attendees SET `status` = 'verified' WHERE `id` = ?");
        $updateStmt->execute([$id]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Fetch all records from the attendees table
try {
    $stmt = $pdo->query("SELECT * FROM attendees");
    $attendees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ensure $attendees is an array even if no rows are returned
    if (!$attendees) {
        $attendees = [];
    }
} catch (PDOException $e) {
    $attendees = [];
    error_log("Database query failed: " . $e->getMessage());
    echo "<p>Failed to fetch data from the database. Please try again later.</p>";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>IGNITE CONCERT Attendees</title>
    <!-- Include jQuery and DataTables CSS/JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .verify-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        .verify-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>IGNITE CONCERT Attendees</h1>

    <!-- Display DataTable -->
    <table id="attendeesTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Particulars</th>
                <th>Amount</th>
                <th>Ref Num</th>
                <th>Created At</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($attendees as $attendee): ?>
                <tr>
                    <td><?= htmlspecialchars($attendee['id']) ?></td>
                    <td><?= htmlspecialchars($attendee['Particulars']) ?></td>
                    <td><?= htmlspecialchars($attendee['Amount']) ?></td>
                    <td><?= htmlspecialchars($attendee['Ref Num']) ?></td>
                    <td><?= htmlspecialchars($attendee['Created At']) ?></td>
                    <td><?= htmlspecialchars($attendee['phone']) ?></td>
                    <td><?= htmlspecialchars($attendee['status']) ?></td>
                    <td>
                        <?php if ($attendee['status'] !== 'verified'): ?>
                            <form method="POST" action="">
                                <input type="hidden" name="id" value="<?= $attendee['id'] ?>">
                                <button type="submit" class="verify-button">Verify</button>
                            </form>
                        <?php else: ?>
                            Verified
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        // Initialize DataTables
        $(document).ready(function() {
            $('#attendeesTable').DataTable({
                "pageLength": 10, // Set the number of rows per page
                "searching": true, // Enable search
                "ordering": true,  // Enable sorting
                "lengthChange": true, // Allow changing rows per page
                "info": true, // Show table information
            });
        });
    </script>
</body>
</html>
