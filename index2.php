
<!DOCTYPE html>
<html>
<head>
    <title>Sistema Parqueo</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet" />

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
                    <br>
                    <label for="rating">Cantidad de sellos (Max 6)</label>
                    <input type="range" id="rating" name="rating" min="0" max="6" step="1" value="0" oninput="document.getElementById('ratingValue').innerText = this.value + ' Sellos';">
                    <span id="ratingValue">0 Sellos</span>

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
                    <label for="anulado">Anulado</label>
                   </div>

                     <div class="button-time-div" style="padding-top: 10px;">
                    <label for="in" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hora de Ingreso:</label>
                    <div class="flex">
                        <input type="time" name="in" id="in" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="00:00" required>
                      
                    </div>
                    
                    </div>
                   
                    <div id="out-div" class="button-time-div time-out-div" style="padding-top: 10px; display:none;">
                    <label for="out" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hora de Salida:</label>
                    <div class="flex">
                        <input type="time" name="out" id="out-log" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="00:00" required>
                      
                    </div>
                    <label for="placa">Placa</label>

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
function getCurrentTime() {
  const now = new Date();
  let hours = now.getHours();
  let minutes = now.getMinutes();
  let seconds = now.getSeconds();

  // Pad single digit numbers with a leading zero
  hours = hours < 10 ? '0' + hours : hours;
  minutes = minutes < 10 ? '0' + minutes : minutes;
  seconds = seconds < 10 ? '0' + seconds : seconds;
console.log("time " + hours + ":"+minutes);
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

        const timeDivs = document.querySelectorAll('.button-time-div, #calculate');

        var outDuration = document.getElementById("out-duration");
        var outPrice = document.getElementById("out-price");

        var timeIn = document.getElementById("in");
     var timeOut =  document.getElementById("out-log");
      var timeOut =  getCurrentTime();


        function toggleTimeDivs() {
            if (temporalRadio.checked){
                timeDivs.forEach(div => div.style.display = 'block');
                outDiv.style.display = 'none';
            } 
            else if(anuladoRadio.checked) {
                timeDivs.forEach(div => div.style.display = 'block');

            } else {
                timeDivs.forEach(div => div.style.display = 'none');
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

                    var timeIn = document.getElementById("in").value;
                    if(anuladoRadio.checked){
                        var timeOut =  document.getElementById("out-log").value;

                    }else{
                        var timeOut =  getCurrentTime();

                    }
                    console.log("timeOut current: " + timeOut);
                    console.log("timeIn current: " + timeIn);


                   // var timeOutUpdated = updatedTimeOut(timeOut.value);

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
                // Function to convert time (HH:MM) to minutes since midnight
                function timeToMinutes(time) {
                    const [hours, minutes] = time.split(':').map(Number);
                    return hours * 60 + minutes;
                }

                // Convert start and end times to minutes
                const startMinutes = timeToMinutes(startTime);
                const endMinutes = timeToMinutes(endTime);

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
                        var rating = document.getElementById('rating').value * 6;

                    }else{
                        var rating = document.getElementById('rating').value * 5;
                    }

                    var totalMinutes = duration.hours * 60 + duration.minutes;
                    var pricePer30Min = vehicleType === "carro" ? 6 : 5;
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

      



<script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>

</body>
</html>
