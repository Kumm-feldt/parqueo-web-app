<?php
    date_default_timezone_set('America/Guatemala');
    session_start();
    error_reporting(E_ALL);
    ini_set('log_errors', 1);
    ini_set('error_log', 'error.log');
    
    include 'conn.php';

// Fetch data from the database
$sql = "SELECT * FROM log_out";
$result = $conn->query($sql);
$noData = false;
$data = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}else{
$noData = true;
}


   // Function to convert time to minutes
   function timeToMinutes($time) {
    // Split the time into its components
    list($date, $time) = explode(' ', $time);
    list($hours, $minutes, $seconds) = explode(':', $time);
    $hours = (int)$hours;
    $minutes = (int)$minutes;

    return $hours * 60 + $minutes;
}
function calculateDuration($startTime, $endTime) {
 

    $startMinutes = timeToMinutes($startTime);
    $endMinutes = timeToMinutes($endTime);

    // Calculate the difference in minutes
    $diffMinutes = $endMinutes - $startMinutes;

    // Adjust if the time period crosses midnight
    if ($diffMinutes < 0) {
        $diffMinutes += 24 * 60; // Add 24 hours worth of minutes
    }
    $hours = floor($diffMinutes / 60); // Calculate the total hours
    $minutes = $diffMinutes % 60; // Calculate the remaining minutes

    error_log("checking" . $hours . " and " .$minutes );

    if($hours == 0 and $minutes == 0){
        return "-";
    
        }else{
            return "$hours horas y $minutes min";
    
        }

}





function calculateTime($logInTime){
    // Convert the given time to a DateTime object
    $logInDateTime = new DateTime($logInTime);

    // Get the current time as a DateTime object
    $currentDateTime = new DateTime();

    // Calculate the difference between the two DateTime objects
    $interval = $currentDateTime->diff($logInDateTime);

    // Get the difference in hours and minutes
    $hours = $interval->h + ($interval->days * 24);
    $minutes = $interval->i;

    // Display the difference in a human-readable format
    return "$hours hora" . ($hours != 1 ? 's' : '') . " y $minutes min" . ($minutes != 1 ? 's' : '') . ".";

}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistema Parqueo</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        .material-symbols-outlined {
            font-variation-settings:
            'FILL' 0,
            'wght' 400,
            'GRAD' 0,
            'opsz' 24
        }


        .out-modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }

        .out-modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.5s;
        }


        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }

       
        .get-time-button {
            margin-left: 10px;
            padding: 5px 10px;
            font-size: 14px;
            cursor: pointer;
        }
        .price-icon{
color:#48752C;
        }    
        
#logo{
    width: 90px;
    height: auto;
    margin-left: 30px;
    
}
@media only screen and (max-width: 945px){
    .logoimage{
    display: flex;
    justify-content: center;
    padding-left: 0px;
    
    }
}
        </style>
</head>
<body>
<header>
        <div class="logoimage">
        <img id="logo" src="LOGO.png" alt="logo">
        </div>
  
</header>
    <h2 id="main-title">HISTORIAL EN TURNO ACTUAL</h2>
<div class="search-bar">

    <form method="GET" action="previous.php">
            <input type="text" name="search" placeholder="Buscar..." required>
            <button class="search-button"type="submit"><span class="material-symbols-outlined">
            search
            </span>
            </button>
            <a href="previous.php" class="refresh-button">
                      <span class="material-symbols-outlined">
                    refresh
                    </span>
                 

                </a>  
    </form>
  

</div>
    <div class="wrapper">
        <div class="table">
         <table>
            <thead>
                <tr class="tr-headers">
                    <th>No.</th>
                    <th>Vehiculo</th>
                    <th>Ticket</th>
                    <th>Hora de entrada</th>
                    <th>Hora de salida</th>

                    <th>Tiempo transcurrido</th>
                    <th>Total Cobrado</th>
                    <th>Turno</th>
                    <th>Tipo</th>




                </tr>
            </thead>
            <tbody id="search-table">

                <?php
                    if($_SERVER["REQUEST_METHOD"] == "GET") {
                       // Database connection (update with your database credentials)
                    include 'conn.php';
                    try {
                        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Check if search term is provided
                        if (isset($_GET['search'])) {
                            $search = $_GET['search'];

                            // Prepare SQL query with a wildcard search
                            $stmt = $pdo->prepare("SELECT * FROM log_out WHERE ticket LIKE :search");
                            $stmt->execute(['search' => '%' . $search . '%']);

                            // Fetch results
                            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Display results

                            if ($results) {

                                echo '<tr><td colspan="8">Resultados:</td></tr>';

                                // Loop through each result row and output data in the table
                                foreach ($results as $row) {

                                    echo '<tr class="vehicle-row" id="' . htmlspecialchars($row["id"]) . '">
                                    <td> - </td>
                                        
                                    <td>' . htmlspecialchars($row["vehicle_type"]) . '</td>
                                        <td>' . htmlspecialchars($row["ticket"]) . '</td>
                                        <td>' . htmlspecialchars($row["time_in"]) . '</td>
                                        <td>' . htmlspecialchars($row["time_out"]) . '</td>
                                        <td>' . calculateDuration($row["time_in"], $row["time_out"]) . '</td>
                                        <td>' . htmlspecialchars($row["charge"]) . '</td>
                                        <td>' . htmlspecialchars($row["person"]) . '</td>
                                        <td>' . htmlspecialchars($row["park_type"]) . '</td>
                                    </tr>';
                                }
                                    echo '<tr><td colspan="9" style="text-align: center;">___________________________________________________________________________</td></tr>';
                            } else {
                            echo '<tr><td  colspan="9">No resultados encontrados.</td> </tr>' ;
                            }
                        }
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }

                    
                    }
                        ?>
            
        </tbody>
            <tbody>
                <?php 
                    $counter = 1;
                
                foreach ($data as $row): ?>
                    
                    <tr class="vehicle-row" id="<?php echo htmlspecialchars($row['id']); ?>">
                    <td><?php echo $counter; ?></td>
                    
                    <td><?php echo htmlspecialchars($row['vehicle_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['ticket']); ?></td>
                            <td><?php echo htmlspecialchars($row['time_in']); ?></td>
                            <td><?php echo htmlspecialchars($row['time_out']); ?></td>

                            <td><?php echo calculateDuration($row['time_in'], $row['time_out']) ?></td>
                            <td><?php echo htmlspecialchars($row['charge']); ?></td>
                            <td><?php echo htmlspecialchars($row['person']); ?></td>
                            <td><?php echo htmlspecialchars($row['park_type']); ?></td>


                          <?php $counter++;?>
                    </tr>
                 
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <div id="footer">
        <?php if ($noData == false): ?>
            <div class="img-send">
                <form action="export_excel.php" method="post" onsubmit="return checkPasscode(event)">
                    <button class="img-button" type="submit">
                        <img src="button.png" alt="send">
                    </button>
                </form>
            </div>
        <?php endif; ?>

            <div class="buttons">
                <button class="icon-button">
                <a href="index.php"><span class="material-symbols-outlined icon">
                home
                </span></a>
                </button>
              
                <button class="icon-button" id="accountButton">
                    <span class="material-symbols-outlined icon">settings</span>
                </button>
            </div>
        </div>

        <!-- USER MODAL -->
        <div id="userModal" class="modal">
            <div class="modal-content">
        
            <span class="close" id="closeUserModal">&times;</span>

                <h2>Ajustes no disponibles</h2>
                
            </div>
        </div>
        <!-- Checkout Modal -->
    <div id="out-vehicle-modal" class="out-modal">
        <div class="out-modal-content">
            <span class="out-close" id="out-close-modal">&times;</span>
            <form method="post" action="log_vehicle_out.php">
            <input type="hidden" id="hidden-ticket" name="ticket_id">
            <input type="hidden" id="hidden-charge" name="charge">
            <input type="hidden" id="hidden-out" name="out">
            
                <table id="data-table">
                <tr><th>Tipo de Vehiculo:</th> <td id="out-vehicle-type"></td></tr>
                <tr><th>Numero de Ticket:</th> <td id="out-ticket"></td></tr>
                <tr><th>Hora de Ingreso:</th> <td id="out-time-in"></td></tr>
                <tr><th>Tiempo Transcurrido:</th> <td id="out-duration"></td></tr>
                <tr><th>Precio a Pagar:</th><td id="out-price"> </td></tr>
                </table>
                <button type="submit">CORRECTO</button>
            </form>
        </div>
    </div>

    </div>
    <script>
  


document.getElementById('get-time').addEventListener('click', function() {
const now = new Date();
const hours = String(now.getHours()).padStart(2, '0');
const minutes = String(now.getMinutes()).padStart(2, '0');
document.getElementById('in').value = `${hours}:${minutes}`;
});

        document.getElementById('add_box').addEventListener('click', function() {
            document.getElementById('add-vehicle-modal').style.display = 'flex';
        });

        document.getElementById('close-modal').addEventListener('click', function() {
            document.getElementById('add-vehicle-modal').style.display = 'none';
        });


        // checkout
        document.getElementById('close-modal-out').addEventListener('click', function() {
            document.getElementById('out-vehicle-modal').style.display = 'none';
        });

    
        window.onclick = function(event) {
            if (event.target == document.getElementById('add-vehicle-modal')) {
                document.getElementById('add-vehicle-modal').style.display = 'none';
            }
        }
    </script>
<script>
  // add user
  document.getElementById('accountButton').addEventListener('click', function() {
        document.getElementById('userModal').style.display = 'block';
    });

    

    window.onclick = function(event) {
        if (event.target == document.getElementById('userModal')) {
            document.getElementById('userModal').style.display = 'none';
        }
    }
    function checkPasscode(event) {
    // Prompt the user for a passcode
    const passcode = prompt("Ingresa el codigo:");

    // Validate the passcode
    if (passcode === "1234") {
        alert("Enviado Correctamente");
        return true; // Allow form submission
    } else {
        alert("Codigo incorrecto. Se cancela el envio.");
        return false; // Prevent form submission
    }
}

     async function reloadPage() {

        // Wait for 10 seconds asynchronously
        await new Promise(resolve => setTimeout(resolve, 4000));

        // After waiting, reload the page
        window.location.reload();

    }
    </script>

</body>
</html>
