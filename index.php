<?php
    date_default_timezone_set('America/Denver');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the database
$sql = "SELECT * FROM log_in";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

function calculateDuration($time_in) {
    $time_in = new DateTime($time_in);
    $now = new DateTime();
    $diff = $now->diff($time_in);
    return $diff;
}

function calculatePrice($duration) {
    // Calculate price based on duration (6Q per 30 minutes)
    $hours = $duration->h;
    $minutes = $duration->i;
    $total_minutes = $hours * 60 + $minutes;
    $type = $result->fetch_assoc();
    if($type["vehicle_type"] == "carro"){
        $price = ceil($total_minutes / 30) * 6; // Round up to the nearest 30 minutes
    }else{
    $price = ceil($total_minutes / 30) * 5; // Round up to the nearest 30 minutes

    }
    return $price;
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
return "$hours hora" . ($hours != 1 ? 's' : '') . " y $minutes minuto" . ($minutes != 1 ? 's' : '') . ".";

}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistema Parqueo</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="styles.css">
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
        <div class="table">
         <table>
            <thead>
                <tr class="tr-headers">
                    <th>Vehiculo</th>
                    <th>Ticket</th>

                    <th>Tiempo de entrada</th>
                    <th>Tiempo transcurrido</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    
                    <tr class="vehicle-row" id="<?php echo htmlspecialchars($row['id']); ?>" onclick="getDataFromRow(this)">
                    <td><?php echo htmlspecialchars($row['vehicle_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['ticket']); ?></td>
                            <td><?php echo htmlspecialchars($row['time_in']); ?></td>
                            <td><span id="timeDifference_<?php echo $row['id']; ?>"></span></td>

                          
                    </tr>
                 
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <div id="footer">
           
            <div class="buttons">
            <form action="previous.php" method="post">
                <button type="submit" class="icon-button">
                    <span class="material-symbols-outlined icon">history</span>
                </button>
            </form>
                <button class="icon-button" id="add_box">
                    <span class="material-symbols-outlined icon">add_box</span>
                </button>
                <button class="icon-button" id="accountButton">
                    <span class="material-symbols-outlined icon">account_circle</span>
                </button>
            </div>
        </div>
        <div id="userModal" class="modal">
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

        <div id="add-vehicle-modal" class="modal">
            <div class="modal-content">
                <span class="close" id="close-modal">&times;</span>
                <form method="post" action="log_vehicle.php">
                    <label for="vehicle_type">Tipo de vehiculo</label>
                    <select name="vehicle_type" id="vehicle_type">
                        <option value="carro">Carro</option>
                        <option value="motocicleta">Motocicleta</option>
                    </select>
                    <br><br>
                    <label for="tikcet">Numero de ticket:</label>
                    <input type="text" name="ticket" id="ticket" required><br><br>
                    <div class="button-time-div">
                    <label for="in">Hora de Ingreso:</label>
                     <input type="time" name="in" id="in" required>
                     <button type="button" class="get-time-button" id="get-time">Get Current Time</button>
                     </div>
                    <br>
                    <button type="submit">INGRESAR</button>
                </form>
            </div>
        </div>

        <!-- -->
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
    // Function to calculate and update time difference
    function updateTimeDifference(rowId, timeIn) {
        var timeInDate = new Date(timeIn);
        var now = new Date();
        var duration = new Date(now - timeInDate);
        var hours = duration.getUTCHours();
        var minutes = duration.getUTCMinutes();
        var timeDifference = `${hours} hour${hours !== 1 ? 's' : ''} and ${minutes} minute${minutes !== 1 ? 's' : ''}`;

        // Update the corresponding <span> or <div> element
        document.getElementById(`timeDifference_${rowId}`).textContent = timeDifference;
    }

    // Function to periodically update time differences
    function updateAllTimeDifferences() {
        <?php foreach ($data as $row): ?>
            updateTimeDifference(<?php echo $row['id']; ?>, '<?php echo $row['time_in']; ?>');
        <?php endforeach; ?>
    }

    // Initial update when the page loads
    updateAllTimeDifferences();

    // Set interval to update every minute (adjust interval as needed)
    setInterval(updateAllTimeDifferences, 30000); // 60000 milliseconds = 1 minute
</script>

                <script>


                      document.getElementById('get-time').addEventListener('click', function() {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    document.getElementById('in').value = `${hours}:${minutes}`;
                });
                    function calculateDuration(timeIn) {
                    var timeInDate = new Date(timeIn);
                    var now = new Date();
                    var duration = new Date(now - timeInDate);
                    var diff = {
                        hours: duration.getUTCHours(),
                        minutes: duration.getUTCMinutes()
                    };
                    return diff;
                }

                function calculatePrice(duration, vehicleType) {
                    var totalMinutes = duration.hours * 60 + duration.minutes;
                    var pricePer30Min = vehicleType === "carro" ? 6 : 5;
                    var price = Math.ceil(totalMinutes / 30) * pricePer30Min;
                    return price;
                }

                function getDataFromRow(row) {
                    // Retrieve the data from the row
                    var vehicleType = row.cells[0].textContent;
                    var ticket = row.cells[1].textContent;
                    var timeIn = row.cells[2].textContent;
                       // Calculate duration and price
                    var duration = calculateDuration(timeIn);
                    console.log(duration);
                    var price = calculatePrice(duration, vehicleType);
                    var durationFormatted = row.cells[3].textContent;
                      // Set hidden input field value
                    
                    document.getElementById("hidden-ticket").value = row.id;
                    document.getElementById("hidden-charge").value = price;
                    document.getElementById("hidden-out").value = duration;

                    var vehicleTypeGet = document.getElementById("out-vehicle-type");
                    var ticketGet =  document.getElementById("out-ticket");
                    var timeInGet =  document.getElementById("out-time-in");
                    var durationGet =  document.getElementById("out-duration");
                    var priceGet =  document.getElementById("out-price");

                    vehicleTypeGet.innerText = vehicleType;
                    ticketGet.innerText = ticket;
                    timeInGet.innerText =  timeIn;
                    durationGet.innerText =   durationFormatted;
                    priceGet.innerText = "Q. "+ price+".00";
                    
                   
                    document.getElementById('out-vehicle-modal').style.display = 'flex';
                    document.getElementById('out-close-modal').addEventListener('click', function() {
                    document.getElementById('out-vehicle-modal').style.display = 'none';
                });


                }
                </script>


    <script>


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
        document.getElementById('userModal').style.display = 'none';
    });

    window.onclick = function(event) {
        if (event.target == document.getElementById('userModal')) {
            document.getElementById('userModal').style.display = 'none';
        }
    }

    // Check if user is logged in
    fetch('check_user_logged_in.php')
        .then(response => response.json())
        .then(data => {
            if (!data.loggedIn) {
                document.getElementById('userModal').style.display = 'block';
                document.getElementById('userStatus').innerText = 'No user logged in. Please enter your name.';
            } else {
                document.getElementById('userStatus').innerText = 'Logged in as: ' + data.userName;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    </script>

      




</body>
</html>
