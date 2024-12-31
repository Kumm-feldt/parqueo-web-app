<?php
session_start();
   
require '../conn.php';

date_default_timezone_set('America/Guatemala');

$user = $_POST['users'];
$user_id = $_SESSION['user_id'];

if (empty(trim($user))) { 

    header("Location: /../index.php");
    
    }else{

$_SESSION['selected_user'] = $user;
// Retrieve the last selected user from the session, if available
$selected_user = isset($_SESSION['selected_user']) ? $_SESSION['selected_user'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ticket = $_POST['ticket'];
    error_log("++++++++++++++++++++");

    error_log("Ticket: " . $ticket);
    // Check if the ticket already exists
    if (checkTicket($conn, $ticket, $user_id)) {
        exit();
    }
    $charge = $_POST['charge'];

    $vehicle_type = $_POST['vehicle_type'];
    $park_type = $_POST['tipo_parqueo'];

    $placa = $_POST['placa'];
    $num_sellos = $_POST['rating']; // Number of "sellos" from the form


    
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
            $charge = calculatePrice($duration, $vehicle_type, $num_sellos, $user_id);

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
        $stmt = $conn->prepare("INSERT INTO log_out (user_id, vehicle_type, ticket, time_in, time_out, charge, person, park_type, placa) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("issssisss", $user_id, $vehicle_type, $ticket, $time_in, $time_out, $charge, $user, $park_type, $placa);

        if ($stmt->execute() === TRUE) {
            header("Location: /../index.php");
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
function calculatePrice($duration, $vehicle_type, $num_sellos, $user_id) {
    global $conn;  // Add this line to access the global $conn variable

    $sql = "SELECT vehicle, price FROM vehicles WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    
    // Execute the query
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    $base_rate = 0; // default to 0

    if ($result->num_rows > 0) {
    
        while ($row = $result->fetch_assoc()){
            $vehicle = $row["vehicle"];
            error_log("Vehicle: " . $vehicle);
            if($vehicle == $vehicle_type){
            $base_rate = $row["price"];
                break;
            }    
                    
        }
    
    
    }

    // Calculate the base price
    $base_price = ceil($duration / 30) * $base_rate; // Round up to the nearest 30 minutes

    // Calculate the discount
    $discount = $num_sellos * ($base_rate);

    // Final price after discount
    $final_price = max(0, $base_price - $discount); // Ensure the final price is not negative
    error_log("calculate price");
    error_log($base_rate);

    error_log($base_price);
    error_log($discount);
    error_log($final_price);



    return $final_price;
}






// ***********************************************


function checkTicket($conn, $ticket_p, $user_id) {

    // Check log_out table
    $stmt_log_out = $conn->prepare("SELECT ticket FROM log_out WHERE ticket = ? and user_id = ?");
    if (!$stmt_log_out) {
        die("Error preparing statement (log_out): " . $conn->error);
    }
    $stmt_log_out->bind_param("ii", $ticket_p, $user_id);
    if (!$stmt_log_out->execute()) {
        die("Error executing statement (log_out): " . $stmt_log_out->error);
    }
    $stmt_log_out->store_result();

    if ($stmt_log_out->num_rows > 0) {
        echo "<div style='text-align: center; padding-top:50px;'>";
        echo "<p>TICKET: " . $ticket_p . " ya fue usado por hoy, por favor ingresa uno nuevo. </p><br>";
        echo "<button> <a href='/../index.php'>Ingresa nuevo ticket</a> </button>";
        echo "</div>";

        $stmt_log_out->close();
        return true;
    } else {
        $stmt_log_out->close();
        return false;
    }
}
?>
