<?php
session_start();

  //$username = "u659703897_localhost";
    //$password = "DT+xgyc|7";
    //$dbname = "u659703897_mydb";

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

    $ticket = $_POST['ticket'];
    $charge = $_POST['charge'];
    $time_in_get =  $_POST['in'];
    $time_out_get = $_POST['out'];
    $vehicle_type = $_POST['vehicle_type'];
    // Get current date
    $date = date('Y-m-d');

    // Combine current date and input time
    $time_in = $date . ' ' . $time_in_get;
    $time_out = $date . ' ' . $time_out_get;
    

    if ($ticket) {
        // Insert into log_out table
        $stmt = $conn->prepare("INSERT INTO log_out (log_in_id, vehicle_type, ticket, time_in, time_out, charge, person) VALUES (?,?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("issssis", $ticket, $vehicle_type, $ticket, $time_in, $time_out, $charge, $user);

        if ($stmt->execute() === TRUE) {
            header("Location: index.php");
        } else {
            echo "Error insertando: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: Ticket ID not found in log_in table. ticket: ". $ticket. " id: ". $id;
    }
}

$conn->close();
?>
