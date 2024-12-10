<?php
session_start();


    date_default_timezone_set('America/Guatemala');

 // Retrieve the last selected user from the session, if available
$selected_user = isset($_SESSION['selected_user']) ? $_SESSION['selected_user'] : '';   

include 'conn.php';


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistema Parqueo</title>
    <!-- Include Bootstrap CDN 
	<link href=
"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
		rel="stylesheet">
	<script src=
"https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js">
	</script>
	<script src=
"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js">
	</script>

	 Include Moment.js CDN 
	<script type="text/javascript" src=
"https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js">
	</script>

	 Include Bootstrap DateTimePicker CDN 
	<link
		href=
"https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css"
		rel="stylesheet">

	<script src=
"https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js">
		</script>
   CDN and CSS 
    
     -->
     <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

     <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet" /> 
  
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

        
.show-modal{
  position: relative;
  z-index: 0;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: white;

  overflow: auto;
  justify-content: center;
  align-items: center;
}

.show-modal .modal-content{
  border: 1px solid ;
  background-color: rgba(255, 255, 255, 0.651);
  box-shadow: none;
}

/* calculator table*/
#hidden-table-price {
  width: 100%;
  border-collapse: collapse;
  margin: 25px 0;
  font-size: 18px;
  text-align: left;
}

#hidden-table-price th, 
#hidden-table-price td {
  padding: 12px 15px;
}

#hidden-table-price tr {
  border-bottom: 1px solid #dddddd;
}

#hidden-table-price th {
  background-color: #009879;
  color: #ffffff;
  width: 50%;
}

#hidden-table-price td {
  background-color: #f3f3f3;
}

.time-div:hover, 
.price-div:hover {
  background-color: #f1f1f1;
}

#calculate {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  text-align: center;
  
}

#calculate {
  margin-top: 20px;
  background-color: #4CAF50;
  color: white;
  border: none;
  cursor: pointer;
}
#calculate:hover {
  background-color: #054d02;
}
#out-log{
  width: 70%;
}
@media only screen and (max-width: 600px) {
    #out-log, #in{
      width: 90%;
    }
    .time-out-div{
  padding-top: 0px;
}
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
    <h2 id="main-title">INICIO:Sistema de parqueos</h2>
    <div class="wrapper">
      
       
        <div id="userModal" class="modal" >
            <div class="modal-content">

          

            <span class="close" id="closeUserModal">&times;</span>

                <h2>Ajustes No Disponibles</h2>
                
            </div>
        </div>

        <div id="add-vehicle-modal" class="modal show-modal" style="display:flex;">
            <div class="modal-content">
                <form method="post" action="log_vehicle_out.php">

                <!-- ------------------ USER ------------------------- -->
                <label for="users"><strong> -- Turno --</strong></label>
                <select name="users" id="users">
                    <option value="Alex Vasquez" <?php if ($selected_user == "Alex Vasquez") echo 'selected'; ?>>Alex Vasquez</option>
                    <option value="Armando Hernandez" <?php if ($selected_user == "Armando Hernandez") echo 'selected'; ?>>Armando Hernandez</option>
                    <option value="Ivan Lopez" <?php if ($selected_user == "Ivan Lopez") echo 'selected'; ?>>Ivan Lopez</option>
                    <option value="Soemia Monzon" <?php if ($selected_user == "Soemia Monzon") echo 'selected'; ?>>Soemia Monzon</option>
                    <option value="Yenifer Calderas" <?php if ($selected_user == "Yenifer Calderas") echo 'selected'; ?>>Yenifer Calderas</option>
                    <option value="Esdras Liquez" <?php if ($selected_user == "Esdras Liquez") echo 'selected'; ?>>Esdras Liquez                    </option>
                    <option value="Werner Luther" <?php if ($selected_user == "Werner Luther") echo 'selected'; ?>>Werner Luther</option>
                
                </select>


                 <input type="hidden" id="hidden-charge" name="charge">

                    <label for="vehicle_type">Tipo de vehiculo</label>
                    <select name="vehicle_type" id="vehicle_type">
                        <option value="carro">Carro</option>
                        <option value="motocicleta">Motocicleta</option>
                    </select>
                    <br>
                    <label for="rating">Cantidad de Stickers (Max 20)</label>
                    <input type="number" id="rating" name="rating" min="0" max="20" step="1" value = "0">
                    <!-- <input type="range" id="rating" name="rating" min="0" max="6" step="1" value="0" oninput="document.getElementById('ratingValue').innerText = this.value + ' Stickers';">
                    <span id="ratingValue">0 Stickers</span>
                    -->
                    <br>
                    <label for="ticket">Numero de ticket:</label>
                    <input type="text" name="ticket" id="ticket" required><br><br>
                   <div class="options-radio">
                   <input type="radio" id="temporal" name="tipo_parqueo" value="Por Hora" checked="checked">
                    <label for="temporal">Por Hora</label><br>
                    <input type="radio" id="evento" name="tipo_parqueo" value="Tarifa Evento">
                    <label for="evento">Tarifa Evento</label><br>
                    <input type="radio" id="dia_noche" name="tipo_parqueo" value="Tarifa dia/noche">
                    <label for="dia_noche">Tarifa dia/noche</label><br>
                    <input type="radio" id="anulado" name="tipo_parqueo" value="Anulado">
                    <label for="anulado">Anulado</label><br>
                    <input type="radio" id="perdido" name="tipo_parqueo" value="Ticket Perdido">
                    <label for="perdido">Ticket Perdido</label>
                   </div>

                   <!-- Time in / Time out-->
                   
                     <div class="button-time-div" style="padding-top: 10px;">
                    <label for="in" >Hora de Ingreso:</label>
                    <div class="flex">
                    <input type="time" name="in" id="in"  value="00:00" required>
                    </div>
                    
                    </div>
                   
                    <div id="out-div" class="button-time-div time-out-div" style="padding-top: 10px; display:none;">
                    <label for="out" >Hora de Salida:</label>
                    <div class="flex">
                    <input type="time" name="out" id="out-log"  value="00:00" required>
                    </div>


                   
                   
                    </div> 
                    <div class="placa-div">
                    <label id="placa-label" for="placa">Placa</label> <br>
                    <input type="text" id="placa" name="placa">
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
           <button class="icon-button">

           <a href="previous.php" >
                   <span class="material-symbols-outlined icon" style="color: black;">history</span>
            </a>
</button>
           
            <button class="icon-button" id="accountButton">
                    <span class="material-symbols-outlined icon">settings</span>
                </button>
           </div>
       </div>
  
    </div>





    <script>
function getCurrentTime() {
  const now = new Date();
  let hours = now.getHours();
  let minutes = now.getMinutes();
  let seconds = now.getSeconds();

  // Pad single digit numbers with a leading zero
  hours = hours < 10 ? '0' + hours : hours;
  minutes = minutes < 10 ? '0' + minutes : minutes;
  seconds = seconds < 10 ? '0' + seconds : seconds;
  return `${hours}:${minutes}`;
}
function toggleRequiredAttribute(enable) {
        const inTimeInput = document.getElementById('in');
       const outTimeInput = document.getElementById('out-log');

        if (enable) {
         inTimeInput.required = true;
           // outTimeInput.required = true;
        } else {
         inTimeInput.required = false;
        outTimeInput.required = false;
        }
    }


    document.addEventListener('DOMContentLoaded', function () {
        // hidden out
        const outDiv = document.getElementById("out-div");

        const temporalRadio = document.getElementById('temporal');
        const eventoRadio = document.getElementById('evento');
        const diaNocheRadio = document.getElementById('dia_noche');
        const anuladoRadio = document.getElementById('anulado');
        const perdidoRadio = document.getElementById('perdido');


        const timeDivs = document.querySelectorAll('.button-time-div, #calculate');

        var outDuration = document.getElementById("out-duration");
        var outPrice = document.getElementById("out-price");

        var timeIn = document.getElementById("in");
        var timeOut =  document.getElementById("out-log");
        var timeOut =  getCurrentTime();

        var placaInput = document.querySelector('.placa-div');


        function toggleTimeDivs() {
            if (temporalRadio.checked){
                timeDivs.forEach(div => div.style.display = 'block');
                outDiv.style.display = 'none';
                placaInput.style.display = 'none';

            } 
            else if(anuladoRadio.checked) {
                timeDivs.forEach(div => div.style.display = 'block');
                placaInput.style.display = 'block';


            } else if(perdidoRadio.checked){
                timeDivs.forEach(div => div.style.display = 'none');

                placaInput.style.display = 'block';
            }
            else {
                timeDivs.forEach(div => div.style.display = 'none');
                placaInput.style.display = 'none';

            }

            if (eventoRadio.checked) {
                outDuration.innerText = "Tarifa Evento";
                outPrice.innerText = "Q. 45.00";
                document.getElementById("hidden-charge").value = 45;
                toggleRequiredAttribute(false);


            }
            else if(diaNocheRadio.checked){
                outDuration.innerText = "Tarifa dia/noche";
                outPrice.innerText = "Q. 60.00";

                document.getElementById("hidden-charge").value = 60;
                toggleRequiredAttribute(false);


            }else if(anuladoRadio.checked){
                outDuration.innerText = "-";
                outPrice.innerText = "Q. 0.00";
                document.getElementById("hidden-charge").value = 0;
            }else if(perdidoRadio.checked){
                outDuration.innerText = "Ticket Perdido";
                outPrice.innerText = "Q. 150.00";

                document.getElementById("hidden-charge").value = 150;
            }
            else{
                outDuration.innerText = "";
                outPrice.innerText = "";
                toggleRequiredAttribute(true);

            }
        }

        // Add event listeners to the radio buttons
        temporalRadio.addEventListener('change', toggleTimeDivs);
        eventoRadio.addEventListener('change', toggleTimeDivs);
        diaNocheRadio.addEventListener('change', toggleTimeDivs);
        anuladoRadio.addEventListener('change', toggleTimeDivs);
        perdidoRadio.addEventListener('change', toggleTimeDivs);



        // Initial check to set the correct visibility on page load
        toggleTimeDivs();
    });
</script>

         <script>
        const anuladoRadio = document.getElementById('anulado');
           
            // Calculate
            document.getElementById('calculate').addEventListener('click', function() {
                    // Get time logs
                    var vehicleType = document.getElementById("vehicle_type").value;
                    var rowId = document.getElementById("ticket").value;

                    var timeIn = (document.getElementById("in").value);
                    

                    if(anuladoRadio.checked){
                        var timeOut =  (document.getElementById("out-log").value);

                    }else{
                        var timeOut =  getCurrentTime();

                    }

                    // Calculate duration and price
                    var duration = calculateDuration(timeIn, timeOut);
                    var price = calculatePrice(duration, vehicleType);
                 
                    // Set Data on table
                    var outDuration = document.getElementById("out-duration");
                    var outPrice = document.getElementById("out-price");
                    var durationFormatted = duration.hours + " horas " + duration.minutes + " min";

                    outDuration.innerText = durationFormatted;

                    if(anuladoRadio.checked){
                        outPrice.innerText = "Q. 0.00";
                        document.getElementById("hidden-charge").value = 0;
                    }else{
                        outPrice.innerText = "Q. " + price + ".00";
                        document.getElementById("hidden-charge").value = price;
                    }
                   
                    setTimeout(function() {
                        document.querySelector('form').submit(); // This submits the form
                    }, 15000); // 15000 milliseconds = 15 seconds



            });
        
   function calculateDuration(startTime, endTime) {
    
    // function to conver to 24 hour format
        function convertTo24HourFormat(time12h) {
        // Split the input time into time and period (AM/PM)
        let [time, period] = time12h.split(' ');
        let [hours, minutes] = time.split(':');
        hours = parseInt(hours, 10);

        // Handle 12 AM case
        if (period === 'am' && hours === 12) {
            hours = 0;
        }
        // Handle PM cases except for 12 PM
        else if (period === 'pm' && hours !== 12) {
            hours += 12;
        }

        // Format the hours and minutes to always have two digits
        hours = hours.toString().padStart(2, '0');
        minutes = minutes.padStart(2, '0');

        return `${hours}:${minutes}`;
    }


    function timeToMinutes(time) {
        time = convertTo24HourFormat(time);
        // Split the time into its components
        let [hours, minutes] = time.split(':').map(Number);

        return hours * 60 + minutes;
    }


    startMinutes = timeToMinutes(startTime);
    endMinutes = timeToMinutes(endTime);
    


    // Calculate the difference in minutes
    let diffMinutes = endMinutes - startMinutes;


    // Adjust if the time period crosses midnight
    if (diffMinutes < 0) {

            diffMinutes += 24 * 60; // Add 24 hours worth of minutes
        

    }

    // Convert the difference back to hours and minutes
    const hours = Math.floor(diffMinutes / 60);
    const minutes = diffMinutes % 60;

    return { hours, minutes };
}


                function calculatePrice(duration, vehicleType) {
                    if (vehicleType == 'carro'){
                        var rating = document.getElementById('rating').value * 8;

                    }else{
                        var rating = document.getElementById('rating').value * 5;
                    }

                    var totalMinutes = duration.hours * 60 + duration.minutes;
                    var pricePer30Min = vehicleType === "carro" ? 8 : 5;
                    var price = (Math.ceil(totalMinutes / 30) * pricePer30Min) - rating;
                    if(price < 0){
                        price = 0;
                    }
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
                function formatTime(time){
                    // Split the time and the AM/PM part
                var parts = time.split(' ');

                // The first part is the time in 'hh:mm' format
                var correctTime = parts[0];
                return correctTime;
                }

                </script>



<script>
  // USER CHECK
  document.getElementById('accountButton').addEventListener('click', function() {
        document.getElementById('userModal').style.display = 'block';
    });



    window.onclick = function(event) {
        if (event.target == document.getElementById('userModal')) {
            document.getElementById('userModal').style.display = 'none';
        }
    }

    </script>

      



<script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>


</body>
</html>

