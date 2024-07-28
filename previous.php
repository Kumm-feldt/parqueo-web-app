<?php
    date_default_timezone_set('America/Denver');
    session_start();
    
    $username = "u659703897_localhost";
    $password = "DT+xgyc|7";
    $dbname = "u659703897_mydb";
    
     //$servername = "localhost";
      // $username = "root";
     // $password = "";
     // $dbname = "mydb";
    //$conn = new mysqli($servername, $username, $password, $dbname);

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

function calculateDuration($time_in, $time_out) {
    $time_in_dt = new DateTime($time_in);
    $time_out_dt = new DateTime($time_out);
    $diff = $time_out_dt->diff($time_in_dt);

    // Format the duration into a readable string
    $hours = $diff->h + ($diff->days * 24);
    $minutes = $diff->i;
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
    <div class="wrapper">
        <div class="table">
         <table>
            <thead>
                <tr class="tr-headers">
                    <th>Vehiculo</th>
                    <th>Ticket</th>
                    <th>Hora de entrada</th>
                    <th>Tiempo transcurrido</th>
                    <th>Total Cobrado</th>
                    <th>Turno</th>
                    <th>Tipo</th>




                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    
                    <tr class="vehicle-row" id="<?php echo htmlspecialchars($row['id']); ?>">
                    <td><?php echo htmlspecialchars($row['vehicle_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['ticket']); ?></td>
                            <td><?php echo htmlspecialchars($row['time_in']); ?></td>
                            <td><?php echo calculateDuration($row['time_in'], $row['time_out']) ?></td>
                            <td><?php echo htmlspecialchars($row['charge']); ?></td>
                            <td><?php echo htmlspecialchars($row['person']); ?></td>
                            <td><?php echo htmlspecialchars($row['park_type']); ?></td>


                          
                    </tr>
                 
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <div id="footer">
        <?php if ($noData == false): ?>
            <div class="img-send">
                <form action="export_excel.php" method="post" >
                    <button class="img-button" type="submit" onclick="reloadPage()">
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
                    <span class="material-symbols-outlined icon">account_circle</span>
                </button>
            </div>
        </div>

        <!-- USER MODAL -->
        <div id="userModal" class="modal">
            <div class="modal-content">
            <span class="close" id="closeUserModal">&times;</span>

                <h2>Informacion de turnos</h2>
                <form id="userForm" action="set_user_name.php" method="post">
                    <label for="userName">Nombre:</label>
                    <input type="text" id="userName" name="userName" required><br><br>
                    <button type="submit">Guardar</button>
                </form>
                <p id="userStatus"></p>
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

    document.getElementById('closeUserModal').addEventListener('click', function() {
        fetch('check_user_logged_in.php')
                .then(response => response.json())
                .then(data => {
                    if (!data.loggedIn) {

                    }else{
        document.getElementById('userModal').style.display = 'none';

                }})
            
    });

    window.onclick = function(event) {
        if (event.target == document.getElementById('userModal')) {
            document.getElementById('userModal').style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
            // Check if user is logged in
            fetch('check_user_logged_in.php')
                .then(response => response.json())
                .then(data => {
                    if (!data.loggedIn) {
                        document.getElementById('userModal').style.display = 'block';
                        document.getElementById('userStatus').innerText = 'No hay turno ingresado. Ingrese su nombre.';
                    } else {
                        document.getElementById('userStatus').innerText = 'Turno: ' + data.userName;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    </script>
<script>
     async function reloadPage() {

        // Wait for 10 seconds asynchronously
        await new Promise(resolve => setTimeout(resolve, 4000));

        // After waiting, reload the page
        window.location.reload();
        alert("Enviado Correctamente");

    }
    </script>

</body>
</html>
