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
    $ticket_id = $_POST['ticket_id'];
    $charge = $_POST['charge'];
    $date = $_POST['out'];

      // Get current date
      $date = date('Y-m-d');

      // Combine current date and input time
      $time_out = $date . ' ' . $time;

    

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE vehicles SET charge = ?, time_out = ? WHERE id = ?");
    $stmt->bind_param("dsi", $charge, $time_out, $ticket_id);

    if ($stmt->execute() === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

