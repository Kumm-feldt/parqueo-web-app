<?php
$username = "u659703897_localhost";
$password = "DT+xgyc|7";
$dbname = "u659703897_mydb";

// $servername = "localhost";
//   $username = "root";
//  $password = "";
//  $dbname = "mydb";
$conn = new mysqli($servername, $username, $password, $dbname);


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
