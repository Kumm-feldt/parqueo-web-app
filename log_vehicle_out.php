<?php
session_start();
   
include 'conn.php';

date_default_timezone_set('America/Guatemala');

$user = $_POST['users'];

if (empty(trim($user)) || empty(trim($_POST['ticket']))) { 

    header("Location: index.php");
    
    }else{

    

       

$_SESSION['selected_user'] = $user;
// Retrieve the last selected user from the session, if available
$selected_user = isset($_SESSION['selected_user']) ? $_SESSION['selected_user'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $ticket = $_POST['ticket'];
    error_log("->" .$ticket);

    // Check if the ticket already exists
    if (checkTicket($conn, $ticket) or empty(trim($ticket)) ) {
        exit();

    }
    $charge = $_POST['charge'];

    $vehicle_type = $_POST['vehicle_type'];
    $park_type = $_POST['tipo_parqueo'];

    $placa = $_POST['placa'];
    $num_sellos = $_POST['rating']; // Number of "sellos" from the form

    // check for DISCOTECA constraints ==================================
    // Get current day and time
    $current_month = date('n'); // 1 (Monday) to 7 (Sunday)
    $current_day = date('N'); // 1 (Monday) to 7 (Sunday)
    $current_time = date('H:i'); // 24-hour format

    // Check if it is between 20:00 pm and 23:50pm
    $is_valid_night_schedule = ($current_time >= '20:00' && $current_time <= '23:59');

    // Check if it's Friday (5) or Saturday (6)
    $is_weekend_night = ($current_day == 5 || $current_day == 6) && $is_valid_night_schedule;



    if($park_type == "Discoteca"){
        if (!$is_weekend_night ) {
            echo "<div style='text-align: center; padding-top:50px;'>";
            echo "<p>Esta opcion solo es valida: VIERNES y SABADO \n 8:00 p.m. - 11:59 p.m</p><br>";
            echo "<button> <a href='index.php'>Ingresa nuevo ticket</a> </button>";
            echo "</div>";
            exit();
        } 
    }
    if($park_type == "Noche"){
        if (!$is_valid_night_schedule ) {
            echo "<div style='text-align: center; padding-top:50px;'>";
            echo "<p>Esta opcion solo es valida: \n 8:00 p.m. - 11:59 p.m</p><br>";
            echo "<button> <a href='index.php'>Ingresa nuevo ticket</a> </button>";
            echo "</div>";
            exit();
        } 
    }
   
   
    // +++++++++++++++++


    
      // Get current date
      $date = date('Y-m-d');


    // Check which radio button was selected
    if ($park_type == 'Por Hora' or $park_type == 'Anulado' ) {
        $time_in_get =  $_POST['in'];
        if($park_type == 'Por Hora'){
            $time_out_get = getNow();
        }else{
            $time_out_get = $_POST['out'];
            $charge = 0;
        }


        $time_in = $date . ' ' . $time_in_get;
        $time_out = $date . ' ' . $time_out_get;

        if($park_type == "Por Hora"){

            $duration = calculateDuration($time_in, $time_out);
    
            $charge = ($current_month == 3) ? calculatePriceMarch($duration, $vehicle_type, $num_sellos) : calculatePrice($duration, $vehicle_type, $num_sellos);
          
            if($charge == 0){
                $park_type = "Cortesia";
            }

        }

    } else  {
        $time_in = $date ;
        $time_out = $date;

    }

   if ($park_type != 'Anulado' or $park_type != 'Ticket Perdidos'){
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
        echo "Error: Ticket ID not found in log_in table.";
    }
    }else{
        echo "Error: Algo salio mal.";

    }

$conn->close();
}
function getNow() {
    // Get the current time in 'H:i' format
    $currentTime = date('H:i');
    return $currentTime;
}



function calculateDuration($startTime, $endTime) {
    // Function to convert time to minutes
    function timeToMinutes($time) {
        // Split the time into its components
        list($date, $time) = explode(' ', $time);
        list($hours, $minutes, $seconds) = explode(':', $time);
        $hours = (int)$hours;
        $minutes = (int)$minutes;

        return $hours * 60 + $minutes;
    }

    $startMinutes = timeToMinutes($startTime);
    $endMinutes = timeToMinutes($endTime);

    // Calculate the difference in minutes
    $diffMinutes = $endMinutes - $startMinutes;

    // Adjust if the time period crosses midnight
    if ($diffMinutes < 0) {
        $diffMinutes += 24 * 60; // Add 24 hours worth of minutes
    }

    error_log("minutes " . $diffMinutes);
    return $diffMinutes;
}



// Function to calculate price based on duration and vehicle type
function calculatePrice($duration, $vehicle_type, $num_sellos = 0) {

    $base_rate = $vehicle_type == "carro" ?  7: 5; // 7Q for cars, 5Q for motorcycles

    // Calculate the base price
    $base_price = ceil($duration / 30) * $base_rate; // Round up to the nearest 30 minutes

    if($num_sellos != 0){
    // Calculate the discount
    $discount = $num_sellos * ($vehicle_type == "carro" ? 7 : 5);
    }else{
        $discount = 0;
    }


    // Final price after discount
    $final_price = max(0, $base_price - $discount); // Ensure the final price is not negative

    return $final_price;
}
 

 // Function to calculate price based on duration and vehicle type
function calculatePriceMarch($duration, $vehicle_type, $num_sellos = 0) {

    if ($vehicle_type == "carro") {
        $base_rate = 7; // 7Q per 30 minutes
        $base_price = ceil($duration / 30) * $base_rate; // Round up to nearest 30 minutes
    } else {
        $base_rate = 5; // 5Q per full hour
        $base_price = ceil($duration / 60) * $base_rate; // Round up to nearest hour
    }

    // Calculate the discount
    $discount = $num_sellos * ($vehicle_type == "carro" ? 7 : 5);

    // Final price after discount (ensuring it doesn't go negative)
    $final_price = max(0, $base_price - $discount);

    return $final_price;
}

function checkTicket($conn, $ticket_p) {
   

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

    if ( $stmt_log_out->num_rows > 0) {
echo "<div style='text-align: center; padding-top:50px;'>";
echo "<p>TICKET: " . $ticket_p . " ya fue usado por hoy, por favor ingresa uno nuevo. </p><br>";
echo "<button> <a href='index.php'>Ingresa nuevo ticket</a> </button>";
echo "</div>";

        $stmt_log_out->close();
        return true;
    } else {
        $stmt_log_out->close();
        return false;
    }
}
?>
