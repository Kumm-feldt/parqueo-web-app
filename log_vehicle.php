<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

$conn = new mysqli($servername, $username, $password, $dbname);
date_default_timezone_set('America/Mexico_City');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicle_type = $_POST['vehicle_type'];
    $ticket = $_POST['ticket'];
    $time = $_POST['in'];

      // Get current date
      $date = date('Y-m-d');

      // Combine current date and input time
      $time_in = $date . ' ' . $time;

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO log_in (vehicle_type, ticket, time_in) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $vehicle_type, $ticket, $time_in);

    if ($stmt->execute() === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

