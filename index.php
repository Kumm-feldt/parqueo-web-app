<?php
 include_once("nicetime.php");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the database
$sql = "SELECT vehicle_type, ticket, time_in FROM vehicles";
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
    if($type["vehicle_type"] == "car"){
        $price = ceil($total_minutes / 30) * 6; // Round up to the nearest 30 minutes
    }else{
    $price = ceil($total_minutes / 30) * 5; // Round up to the nearest 30 minutes

    }
    return $price;
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

        .modal {
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

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.5s;
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
            border-radius: 10px;
            font-family: Arial, sans-serif;
        }


        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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
    <h2 id="main-title">Sistema de parqueos</h2>
    <div class="wrapper">
        <div class="table">
         <table>
            <thead>
                <tr class="tr-headers">
                    <th>Vehiculo</th>
                    <th>Ticket</th>

                    <th>Tiempo de entrada</th>
                    <th>Tiempo transcurrido</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    <tr class="vehicle-row" data-vehicle-type="<?php echo htmlspecialchars($row['vehicle_type']); ?>"
                            data-ticket="<?php echo htmlspecialchars($row['ticket']); ?>"
                            data-time-in="<?php echo htmlspecialchars($row['time_in']); ?>"
>
                            <td><?php echo htmlspecialchars($row['vehicle_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['ticket']); ?></td>

                            <td><?php echo htmlspecialchars($row['time_in']); ?></td>
                            <td><?php NiceTime($row["time_in"]) ?></td>
                            <td>
                                <span class="material-symbols-outlined price-icon">
                                    price_check
                                </span>
                            </td>
                        </tr>
                 
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <div id="footer">
            <div class="img-send">
                <img src="button.png" alt="send">
            </div>
            <div class="buttons">
                <button class="icon-button">
                    <span class="material-symbols-outlined icon">history</span>
                </button>
                <button class="icon-button" id="add_box">
                    <span class="material-symbols-outlined icon">add_box</span>
                </button>
                <button class="icon-button">
                    <span class="material-symbols-outlined icon">account_circle</span>
                </button>
            </div>
        </div>
        <div id="add-vehicle-modal" class="modal">
            <div class="modal-content">
                <span class="close" id="close-modal">&times;</span>
                <form method="post" action="log_vehicle.php">
                    <label for="vehicle_type">Tipo de vehiculo</label>
                    <select name="vehicle_type" id="vehicle_type">
                        <option value="car">Carro</option>
                        <option value="motorcycle">Motocicleta</option>
                    </select>
                    <br><br>
                    <label for="license_plate">Numero de ticket:</label>
                    <input type="text" name="license_plate" id="ticket" required><br><br>
                    <div class="button-time-div">
                    <label for="in">Hora de Ingreso:</label>
                     <input type="time" name="in" id="in" required>
                     <button type="button" class="get-time-button" id="get-time">Hora Actual</button>
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
            <form method="post" action="log_vehicle.php">
                <p><strong>Tipo de Vehiculo:</strong> <span id="out-vehicle-type"></span></p>
                <p><strong>Numero de Ticket:</strong> <span id="out-ticket"></span></p>
                <p><strong>Hora de Ingreso:</strong> <span id="out-time-in"></span></p>
                <p><strong>Tiempo Transcurrido:</strong> <span id="out-duration"></span></p>
                <p><strong>Precio a Pagar:</strong> Q<span id="out-price"></span></p>
                <button type="submit">CORRECTO</button>
            </form>
        </div>
    </div>

    </div>
    <script>
        document.getElementById('add_box').addEventListener('click', function() {
            document.getElementById('add-vehicle-modal').style.display = 'flex';
        });

        document.getElementById('close-modal').addEventListener('click', function() {
            document.getElementById('add-vehicle-modal').style.display = 'none';
        });

    
        window.onclick = function(event) {
            if (event.target == document.getElementById('add-vehicle-modal')) {
                document.getElementById('add-vehicle-modal').style.display = 'none';
            }
        }

        document.getElementById('get-time').addEventListener('click', function() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            document.getElementById('in').value = `${hours}:${minutes}`;
        });
    </script>
</body>
</html>
