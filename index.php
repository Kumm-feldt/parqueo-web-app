<?php
   session_start();
   if (!isset($_SESSION['user_id'])) {
      header('Location: login.php');
      exit;
   }
   
   $user_id = $_SESSION['user_id'];
   
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
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
      <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet" />
      <link rel="stylesheet" href="/css/styles.css">
      <link rel="icon" href="images/favicon.ico" type="image/x-icon">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="/css/index.css">
      <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">


   </head>
   <body>
      <header>
      <div class="logoimage">
    <a href="index.php">
        <img id="logo" 
             <?php 
                    $logoPath = "/logos/img_user_" . $user_id . ".png";
                    echo "src='" . (file_exists($_SERVER['DOCUMENT_ROOT'] . $logoPath) ? $logoPath : "/logos/default.png") . "'"; 
                    ?> 
                    alt="logo">
                
            </a>
            <h2>amiparqueo</h2>
        </div>

      </header>
      <!--  <h2 id="main-title">INICIO:Sistema de parqueos</h2>-->
      <div class="wrapper">
         <div id="add-vehicle-modal" class="modal show-modal" style="display:flex;">
            <div class="modal-content">
               <form method="post" action="/processes/log_vehicle_out.php">
                  <div class="col-flex">
                     <label for="users"><strong> Turno</strong></label>
                     <select name="users" id="users">
                     <?php
                        // Query to get employee list
                        $sql_workers = "SELECT * FROM workers WHERE user_id = ?"; // Use correct column names
                        $stm = $conn->prepare($sql_workers);  // Prepare the statement
                        $stm->bind_param('i', $_SESSION['user_id']); // Bind the user_id parameter from session
                        
                        // Execute the statement
                        if ($stm->execute()) {
                            $result = $stm->get_result();  // Get the result set from the prepared statement
                        
                            // Check if the result contains data
                            if ($result->num_rows > 0) {
                                // Fetch and display data
                                while ($row = $result->fetch_assoc()) {
                                    $user_n = $row['worker_name'];
                                    $selected = ($selected_user == $user_n) ? "selected" : ""; 
                                    echo "<option value='$user_n' $selected>$user_n</option>";
                                }
                            }
                        }
                        ?>          
                     </select>
                  </div>
                  <div class="row-flex wrapper-row">
                     <div class="col-flex first-col">
                        <input type="hidden" id="hidden-charge" name="charge">
                        <div class="row-flex sub-flex">
                           <label for="vehicle_type">Tipo de vehiculo</label>
                           <label for="ticket" class="exeptional">Numero de ticket:</label>
                        </div>
                        <div class="row-flex sub-flex">
                           <select name="vehicle_type" id="vehicle_type">
                           <?php
                              $sql = "SELECT vehicle, price FROM vehicles WHERE user_id = ?";
                              $stmt = $conn->prepare($sql);
                              $stmt->bind_param('i', $user_id);
                              
                              // Execute the query
                              $stmt->execute();
                              
                              $result = $stmt->get_result();
                              
                              if ($result->num_rows > 0) {
                              
                                  while ($row = $result->fetch_assoc()){
                                      $vehicle = $row["vehicle"];
                                      $price = $row["price"];
                                      echo "<option value='$vehicle' data-price='$price'>$vehicle</option>";
                              
                                              
                                  }
                              
                              
                              }
                              
                              ?>
                           </select>
                           <input type="text" name="ticket" id="ticket" required>
                        </div>
                        <div class="col-flex range-input">
                           <label for="rating">Cantidad de Stickers (Max 20)</label>
                           <input type="number" id="rating" name="rating" min="0" max="20"  value="0" >
                        </div>
                        <br>
                        <div class="options-radio">
                        <?php

                            $sql = "SELECT * FROM fixed_events WHERE user_id = ?";
                              $stmt = $conn->prepare($sql);
                              $stmt->bind_param('i', $user_id);
                              
                              // Execute the query
                              $stmt->execute();
                              
                              $result = $stmt->get_result();
                              
                              if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                echo '
                                <input type="radio" id="temporal"  name="tipo_parqueo" value="Por Hora" checked="checked">
                           <label for="temporal">Por Hora</label><br>
                           <input type="radio" id="evento" name="tipo_parqueo" data-price="' . $row["evento"] .  '" value="Tarifa Evento">
                           <label for="evento">Tarifa Evento</label><br>
                           <input type="radio" id="dia_noche" name="tipo_parqueo" data-price="' . $row["dia_y_noche"] .  '" value="Tarifa dia/noche">
                           <label for="dia_noche">Tarifa dia/noche</label><br>
                           <input type="radio" id="anulado" name="tipo_parqueo" data-price="' . $row["anulado"] .  '"  value="Anulado">
                           <label for="anulado">Anulado</label><br>
                           <input type="radio" id="perdido" name="tipo_parqueo" data-price="' . $row["perdido"] .  '"  value="Ticket Perdido">
                           <label for="perdido">Ticket Perdido</label>
                                
                                
                                ';
                              }

                              ?>
                           
                        </div>
                        <br>
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
                     </div>
                     <!-- SECOND COLUMN -->
                     <div class="col-flex second-col">
                        <div id="calculate">Calcular</div>
                        <br>
                        <table id="hidden-table-price">
                           <tr class="time-div">
                              <th>Tiempo Transcurrido:</th>
                              <td id="out-duration"></td>
                           </tr>
                           <tr class="price-div">
                              <th>Precio a Pagar:</th>
                              <td id="out-price"> </td>
                           </tr>
                        </table>
                        <button type="submit">INGRESAR</button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
         <div id="footer">
            <div class="buttons">
               <a href="previous.php" >
               <button type="submit" class="icon-button">
               <span class="material-symbols-outlined icon" style="color: black;">history</span>
               </button>
               </a>
               <?php
                  if($_SESSION['m_u'] == true){
                      echo "
                         
                      <a href='historial.php'>
                      <button type='submit' class='icon-button' id='accountButton'>
                          <span class='material-symbols-outlined icon'>manage_search</span>
                      </button>
                  </a>
                   <a href='settings.php' >
                  
                     <button type='submit' class='icon-button' id='accountButton'>
                      
                         <span class='material-symbols-outlined icon'>settings</span>
                     </button>
                      </a>
                  
                  
                  ";
                  }
                  ?>
               <a href="logout.php" >
               <button type="submit" class="icon-button" id="accountButton">
               <span class="material-symbols-outlined icon">logout</span>
               </button>
               </a>
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
         
         function getSelectedPrice() {
            const selectedRadio = document.querySelector('input[name="tipo_parqueo"]:checked'); // Get selected radio
            if (selectedRadio) {
                const price = selectedRadio.getAttribute('data-price'); // Retrieve the data-price attribute
                console.log("PRICE:", price);
                return price;
            } else {
                console.log("No radio button selected.");
                return null;
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
                let price_vehicle = getSelectedPrice();
                 if (eventoRadio.checked) {
                     outDuration.innerText = "Tarifa Evento";
                     outPrice.innerText = "Q."+  price_vehicle + ".00";
                     document.getElementById("hidden-charge").value = price_vehicle;
                     toggleRequiredAttribute(false);
         
         
                 }
                 else if(diaNocheRadio.checked){

                     outDuration.innerText = "Tarifa dia/noche";
                     outPrice.innerText = "Q." +price_vehicle+".00";
         
                     document.getElementById("hidden-charge").value = price_vehicle;
                     toggleRequiredAttribute(false);
         
         
                 }else if(anuladoRadio.checked){

                     outDuration.innerText = "-";
                     outPrice.innerText = "Q." + price_vehicle +".00";
                     document.getElementById("hidden-charge").value = price_vehicle;
                 }else if(perdidoRadio.checked){
                    
                     outDuration.innerText = "Ticket Perdido";
                     outPrice.innerText = "Q."+price_vehicle +".00";
         
                     document.getElementById("hidden-charge").value = price_vehicle;
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
                    
                     //document.getElementById("hidden-out").value = duration.hours + ":" + duration.minutes ;
         
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
         
         //diffMinutes += 12 * 60; // Add 12 hours worth of minutes
             diffMinutes += 24 * 60; // Add 24 hours worth of minutes
         
         
         }
         
         // Convert the difference back to hours and minutes
         const hours = Math.floor(diffMinutes / 60);
         const minutes = diffMinutes % 60;
         
         return { hours, minutes };
         }
         
         
                 function calculatePrice(duration, vehicleType) {
                
                    var selectElement = document.getElementById('vehicle_type'); // Reference the <select> element
                    var selectedOption = selectElement.options[selectElement.selectedIndex]; // Get the selected <option>
                    var price_vehicle = selectedOption.getAttribute('data-price'); // Retrieve the data-price attribute           
                

                 
                    var rating = document.getElementById('rating').value * price_vehicle;                     
         
                     var totalMinutes = duration.hours * 60 + duration.minutes;
                     var pricePer30Min = price_vehicle;
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
         
                 
                 function formatTime(time){
                     // Split the time and the AM/PM part
                 var parts = time.split(' ');
         
                 // The first part is the time in 'hh:mm' format
                 var correctTime = parts[0];
                 return correctTime;
                 }
         
                 
      </script>

      <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>
   </body>
</html>