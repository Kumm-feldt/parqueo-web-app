<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$vehicle_type = $_POST['vehicle_type'];
$ticket = $_POST['ticket'];
$action = $_POST['action'];
$current_time = date('Y-m-d H:i:s');

if ($action == 'in') {
    $sql = "INSERT INTO vehicles (vehicle_type, ticket, time_in, charge) VALUES ('$vehicle_type', '$ticket', '$current_time', 0)";
} else {
    $sql = "UPDATE vehicles SET time_out='$current_time' WHERE ticket='$ticket' AND time_out IS NULL";
}

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
