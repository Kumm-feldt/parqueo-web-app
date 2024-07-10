<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

$conn = new mysqli($servername, $username, $password, $dbname);
date_default_timezone_set('America/Mexico_City');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function checkTicket($conn, $ticket_p) {
    // Check log_in table
    $stmt = $conn->prepare("SELECT ticket FROM log_in WHERE ticket = ?");
    if (!$stmt) {
        die("Error preparing statement (log_in): " . $conn->error);
    }
    $stmt->bind_param("s", $ticket_p);
    if (!$stmt->execute()) {
        die("Error executing statement (log_in): " . $stmt->error);
    }
    $stmt->store_result();

    // Check log_out table
    $stmt_log_out = $conn->prepare("SELECT ticket FROM log_out WHERE ticket = ?");
    if (!$stmt_log_out) {
        die("Error preparing statement (log_out): " . $conn->error);
    }
    $stmt_log_out->bind_param("s", $ticket_p);
    if (!$stmt_log_out->execute()) {
        die("Error executing statement (log_out): " . $stmt_log_out->error);
    }
    $stmt_log_out->store_result();

    if ($stmt->num_rows > 0 || $stmt_log_out->num_rows > 0) {
echo "<div style='text-align: center; padding-top:50px;'>";
echo "<p>TICKET: " . $ticket_p . " ya fue usado por hoy, por favor ingresa uno nuevo. </p><br>";
echo "<button> <a href='index.php'>Ingresa nuevo ticket</a> </button>";
echo "</div>";

        $stmt->close();
        $stmt_log_out->close();
        return true;
    } else {
        $stmt->close();
        $stmt_log_out->close();
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicle_type = $_POST['vehicle_type'];
    $ticket = $_POST['ticket'];
    $time = $_POST['in'];

    // Check if the ticket already exists
    if (checkTicket($conn, $ticket)) {
        exit();
    }




    // Get current date
    $date = date('Y-m-d');

    // Combine current date and input time
    $time_in = $date . ' ' . $time;

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO log_in (vehicle_type, ticket, time_in) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Error preparing insert statement: " . $conn->error);
    }
    $stmt->bind_param("sss", $vehicle_type, $ticket, $time_in);
    if (!$stmt->execute()) {
        die("Error executing insert statement: " . $stmt->error);
    }

    $stmt->close();

    // Redirect after successful insertion
    header("Location: index.php");
    exit();
}

$conn->close();
?>
