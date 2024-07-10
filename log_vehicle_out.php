<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

date_default_timezone_set('America/Denver');


if (isset($_SESSION['userName'])) {
    $user = $_SESSION['userName'];
} else {
    echo "Error enviando datos, no hay persona registrada: " . $stmt->error;
}




$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['ticket_id'];
    $charge = $_POST['charge'];
    $time_out = date('Y-m-d H:i:s', time());

    // Retrieve log_in_id from the log_in table using the ticket_id
    $stmt = $conn->prepare("SELECT vehicle_type, time_in, ticket FROM log_in WHERE id = ? ORDER BY time_in DESC LIMIT 1");
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($vehicle_type, $time_in, $ticket);
    $stmt->fetch();
    $stmt->close();

    if ($ticket) {
        // Insert into log_out table
        $stmt = $conn->prepare("INSERT INTO log_out (log_in_id, vehicle_type, ticket, time_in, time_out, charge, person) VALUES (?,?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("issssis", $id, $vehicle_type, $ticket, $time_in, $time_out, $charge, $user);

        if ($stmt->execute() === TRUE) {
            // Delete entry from log_in table
            $stmt_d = $conn->prepare("DELETE FROM log_in WHERE id = ?");
            if ($stmt_d === false) {
                die("Prepare failed: " . htmlspecialchars($conn->error));
            }
            $stmt_d->bind_param("i", $id);
            $stmt_d->execute();
            $stmt_d->close();

            header("Location: index.php");
        } else {
            echo "Error inserting into log_out: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: Ticket ID not found in log_in table. ticket: ". $ticket. " id: ". $id;
    }
}

$conn->close();
?>
