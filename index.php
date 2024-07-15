<?php
session_start();

    date_default_timezone_set('America/Denver');

    
    //$username = "u659703897_localhost";
    //$password = "DT+xgyc|7";
    //$dbname = "u659703897_mydb";

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mydb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
        </style>
</head>
<body>
    <h2 id="main-title">INICIO:Sistema de parqueos</h2>
    <div class="wrapper">
      
       
        <div id="userModal" class="modal" >
            <div class="modal-content">
            <span class="close" id="closeUserModal">&times;</span>

                <h2>Informacion de Turnos</h2>
                <form id="userForm" action="set_user_name.php" method="post">
                    <label for="userName">Nombre:</label>
                    <input type="text" id="userName" name="userName" required><br><br>
                    <button type="submit">GUARDAR</button>
                </form>
                <p id="userStatus"></p>
            </div>
        </div>

        <div id="add-vehicle-modal" class="modal show-modal" style="display:flex;">
            <div class="modal-content">
                <form method="post" action="log_vehicle_out.php">
                 <input type="hidden" id="hidden-charge" name="charge">

                    <label for="vehicle_type">Tipo de vehiculo</label>
                    <select name="vehicle_type" id="vehicle_type">
                        <option value="carro">Carro</option>
                        <option value="motocicleta">Motocicleta</option>
                    </select>
                    <br><br>
                    <label for="ticket">Numero de ticket:</label>
                    <input type="text" name="ticket" id="ticket" required><br><br>
                   

                    <div class="button-time-div">
                    <label for="in">Hora de Ingreso:</label><br>
                     <input type="time" name="in" id="in" required>
                    </div>

                    <div class="button-time-div time-out-div">
                    <label for="out">Hora de Salida:</label><br>
                        <input type="time" name="out" id="out-log" required>
                    </div>

                    <div id="calculate">Calcular</div>
                    <br>
                    <table id="hidden-table-price">
                        <tr class="time-div">
                    <th>Tiempo Transcurrido:</th> <td id="out-duration"></td>
                      </tr>
                    <tr class="price-div">
                    <th>Precio a Pagar:</th><td id="out-price"> </td>
                    </tr>    
                    </table>
                    <button type="submit">INGRESAR</button>
                </form>
            </div>
        </div>
        <div id="footer">
           
           <div class="buttons">
           <form action="previous.php" method="post">
               <button type="submit" class="icon-button">
                   <span class="material-symbols-outlined icon" style="color: black;">history</span>
               </button>
           </form>
           
               <button class="icon-button" id="accountButton">
                   <span class="material-symbols-outlined icon">account_circle</span>
               </button>
           </div>
       </div>
  
    </div>
         <script>

            // Calculate
            document.getElementById('calculate').addEventListener('click', function() {
                    // Get time logs
                    var vehicleType = document.getElementById("vehicle-type");
                    var rowId = document.getElementById("ticket").value;

                    var timeIn = document.getElementById("in").value;
                    var timeOut =  document.getElementById("out-log").value;

                   // var timeOutUpdated = updatedTimeOut(timeOut.value);

                    // Calculate duration and price
                    var duration = calculateDuration(timeIn, timeOut);
                    var price = calculatePrice(duration, vehicleType);
                 
                    // Set Data on table
                    var outDuration = document.getElementById("out-duration");
                    var outPrice = document.getElementById("out-price");
                    var durationFormatted = duration.hours + " horas " + duration.minutes + " min";

                    outDuration.innerText = durationFormatted;
                    outPrice.innerText = "Q. " + price + ".00";
                    document.getElementById("hidden-charge").value = price;
                    document.getElementById("hidden-out").value = duration.hours + ":" + duration.minutes ;

            });

            function calculateDuration(startTime, endTime) {
                // Split the input string into individual times

                // Function to convert time (HH:MM) to minutes since midnight
                function timeToMinutes(time) {
                    const [hours, minutes] = time.split(':').map(Number);
                    return hours * 60 + minutes;
                }

                // Convert start and end times to minutes
                const startMinutes = timeToMinutes(startTime);
                const endMinutes = timeToMinutes(endTime);

                // Calculate the difference in minutes
                const diffMinutes = endMinutes - startMinutes;

                // Convert the difference back to hours and minutes
                const hours = Math.floor(diffMinutes / 60);
                const minutes = diffMinutes % 60;

                return { hours, minutes };
                }


                function calculatePrice(duration, vehicleType) {
                    var totalMinutes = duration.hours * 60 + duration.minutes;
                    var pricePer30Min = vehicleType === "carro" ? 6 : 5;
                    var price = Math.ceil(totalMinutes / 30) * pricePer30Min;
                    return price;
                }

                
               
                function updateOutDuration(time) {
                // Extracting hours and minutes from the time string
                const [hours, minutes] = time.split(':').map(Number);
                    // Creating a new Date object for the current date with the specified time
                    const now = new Date();
                    const timeOut = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hours, minutes);

                    // Formatting the date to YYYY-MM-DD
                    const formattedDate = timeOut.toISOString().split('T')[0];
                    
                    return formattedDate;
                }

                function updatedTimeOut(time){
     
                    // Extracting hours and minutes from the time string
                    const [hours, minutes] = time.split(':').map(Number);
                    // Creating a new Date object for the current date with the specified time
                    const now = new Date();
                    const timeOut = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hours-6, minutes);

                    // Formatting the date and time to YYYY-MM-DD HH:mm:ss
                    const formattedDateTime = timeOut.toISOString().slice(0, 19).replace('T', ' ');

                    return formattedDateTime;
                }

                </script>



<script>
  // USER CHECK
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

      




</body>
</html>
