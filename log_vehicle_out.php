<?php
session_start();
$username = "u659703897_localhost";
$password = "DT+xgyc|7";
$dbname = "u659703897_mydb";

 //$servername = "localhost";
  // $username = "root";
 // $password = "";
 // $dbname = "mydb";
//$conn = new mysqli($servername, $username, $password, $dbname);
date_default_timezone_set('America/Denver');


if (!isset($_SESSION['userName']) or trim($_SESSION['userName']) == "") { 

    header("Location: index.php");
    
    }else{

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  
    $ticket = $_POST['ticket'];
      // Check if the ticket already exists
      if (checkTicket($conn, $ticket)) {
        exit();
    }
    $charge = $_POST['charge'];

    $vehicle_type = $_POST['vehicle_type'];
    $park_type = $_POST['tipo_parqueo'];
    $placa = $_POST['placa'];
    $num_sellos = $_POST['rating']; // Number of "sellos" from the form


    $user = $_SESSION['userName'];
    
      // Get current date
      $date = date('Y-m-d');


    // Check which radio button was selected
    if ($park_type == 'Por Hora' or $park_type == 'Anulado' ) {
        $time_in_get =  $_POST['in'];
        $time_out_get = $_POST['out'];

        $time_in = $date . ' ' . $time_in_get;
        $time_out = $date . ' ' . $time_out_get;

        if($park_type == "Por Hora" and $charge == 0){
            $duration = calculateDuration($time_in);
            $charge = calculatePrice($duration, $vehicle_type, $num_sellos);

        }


    } else  {
        $time_in = $date ;
        $time_out = $date;

    }
   if ($park_type != 'Anulado'){
    $placa = "-";
   }

    if ($ticket) {
        // Insert into log_out table
        $stmt = $conn->prepare("INSERT INTO log_out (log_in_id, vehicle_type, ticket, time_in, time_out, charge, person, park_type, placa) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("issssisss", $ticket, $vehicle_type, $ticket, $time_in, $time_out, $charge, $user, $park_type, $placa);

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
}

//function to calculate duration in minutes
function calculateDuration($time_in) {
    $time_in = new DateTime($time_in);
    $now = new DateTime();
    $diff = $now->diff($time_in);

    // Total minutes of duration
    $total_minutes = $diff->h * 60 + $diff->i;

    return $total_minutes;
}

// Function to calculate price based on duration and vehicle type
function calculatePrice($duration, $vehicle_type, $num_sellos) {
    $base_rate = $vehicle_type == "carro" ? 6 : 5; // 6Q for cars, 5Q for motorcycles

    // Calculate the base price
    $base_price = ceil($duration / 30) * $base_rate; // Round up to the nearest 30 minutes

    // Calculate the discount
    $discount = $num_sellos * ($vehicle_type == "carro" ? 6 : 5);

    // Final price after discount
    $final_price = max(0, $base_price - $discount); // Ensure the final price is not negative

    return $final_price;
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
?>
