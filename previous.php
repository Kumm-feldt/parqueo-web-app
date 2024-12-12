<?php
   date_default_timezone_set('America/Denver');
   session_start();
   error_reporting(E_ALL);
   ini_set('log_errors', 1);
   ini_set('error_log', 'error.log');
   
   include 'conn.php';
   if (!isset($_SESSION['user_id'])) {
       header('Location: login.php');
       exit;
    }
    $user_id = $_SESSION['user_id'];
   
   
   // Fetch data from the database
   $sql = "SELECT * FROM log_out where user_id = ?";
   $stmt = $conn->prepare($sql);
   
   // Bind the parameter (assuming $auth_user is a string)
   $stmt->bind_param('s', $user_id);
   
   // Execute the query
   $stmt->execute();
   
   $result = $stmt->get_result();
   
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
      <link rel="stylesheet" href="/css/styles.css">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="/css/previous.css">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">

   </head>
   <body>
      <header>
         <div class="logoimage">
           <?php echo "             
                                   <img src='logos/img_user_".$user_id.".png' alt='Company Logo' id='logo' class='s-logo-circle'>

"?>
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
              
                        // Check if search term is provided
                        if (isset($_GET['search'])) {
                            $search = $_GET['search'];

                          // Prepare your SQL query
                            $sql = "SELECT * FROM log_out WHERE ticket LIKE ? AND user_id = ?";

                            // Prepare the statement
                            $stmt = $conn->prepare($sql);

                            // Bind parameters
                            $stmt->bind_param("ss", $search, $user_id);

                            // Set parameters
                            $search = '%' . $_GET['search'] . '%';
                            $user_id = 'your_user_id'; // Replace with actual user ID

                            // Execute the query
                            $stmt->execute();

                            // Get the result set
                            $results= $stmt->get_result();

                            if ($results) {
                            // Fetch and display results
                            echo '<tr><td colspan="8">Resultados:</td></tr>';

                            while ($row = $results->fetch_assoc()) {
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
                   

                    
                    }
                        ?>
            
        </tbody>
               <tbody>
                
                  <?php foreach ($data as $row): 
                    $counter = 1;
                    ?>
                     <tr class="vehicle-row" id="<?php echo htmlspecialchars($row['id']); ?>">
                     <td><?php echo $counter; ?></td>
                  <tr class="vehicle-row" id="<?php echo htmlspecialchars($row['id']); ?>">
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
               <form action="/processes/export_excel.php" method="post" >
                  <button class="img-button" type="submit" onclick="reloadPage()">
                  <img src="/images/button.png" alt="send">
                  </button>
               </form>
            </div>
            <?php endif; ?>
            <div class="buttons">
           <a href="index.php" >
               <button type="submit" class="icon-button">
                   <span class="material-symbols-outlined icon" style="color: black;">home</span>
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
         
         
           
           document.getElementById('closeUserModal').addEventListener('click', function() {
         //      fetch('check_user_logged_in.php')
            //           .then(response => response.json())
              //         .then(data => {
                //           if (!data.loggedIn) {
         
                  //         }else{
              document.getElementById('userModal').style.display = 'none';
         
                //       }})
                   
         });
         
           window.onclick = function(event) {
               if (event.target == document.getElementById('userModal')) {
                   document.getElementById('userModal').style.display = 'none';
               }
           }
         
           
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