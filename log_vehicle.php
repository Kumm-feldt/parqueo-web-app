<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicle_type = $_POST['vehicle_type'];
    $license_plate = $_POST['license_plate'];
    $time_in = $_POST['in'];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO vehicles (vehicle_type, ticket, time_in, charge) VALUES (?, ?, ?, 0)");
    $stmt->bind_param("sss", $vehicle_type, $license_plate, $time_in);

    if ($stmt->execute() === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

