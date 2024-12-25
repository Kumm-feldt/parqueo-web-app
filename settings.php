<?php
   session_start();
   if (!isset($_SESSION['user_id']) and !isset($_SESSION['m_u'])) {
           header('Location: login.php');
           exit;
   }
   if(isset($_SESSION['m_u']) and $_SESSION['m_u']==false){
       header('Location: index.php');
       exit;
   }
   
   
   
   include 'conn.php';
   
   $user_id = $_SESSION['user_id'];

   // default auth user to true -> later it will be changed
   $auth_user_exist = true;
   
   $sql = "SELECT email, authorized_user, authorized_user_password FROM users WHERE id = ?";
   $stmt = $conn->prepare($sql); // Prepare the statement to prevent SQL injection
   
   // Check if the statement was prepared successfully
   if ($stmt) {
       $stmt->bind_param('i', $user_id); // 'i' denotes the type as integer
       $stmt->execute(); // Execute the query
       $stmt->bind_result($user_email, $auth_user_email, $auth_user_password); // Bind the result to the variable
   
       if (!$stmt->fetch()) {
           $user_email = "No user found";
   
       } else{
            // Check if the authorized_user is set
               if (empty($auth_user_email)) {
                   // Handle the case where authorized_user is not set (null or empty)
                   $auth_user_exist = false;
               } 
   
       }
   
       $stmt->close(); // Close the statement
   } else {
      
   }
   
   
   
   ?>
<!DOCTYPE html>
<html>
   <head>
      <title>Sistema Parqueo</title>
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
      <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet" />
      <link rel="stylesheet" href="/css/styles.css">
      <link rel="stylesheet" href="/css/settings.css">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">

      <meta name="viewport" content="width=device-width, initial-scale=1">
   </head>



   <body>
      <div id="s-wrapper">
         <!-- Header -->    
         <div class="s-header">
            <div  class="s-subheader-t">
               <h1>Configuracion de la cuenta</h1>
               <div class="s-logout-div">
                  <a href="index.php" class="s-button-logout"><span class="material-symbols-outlined">
                  home
                  </span>
                  </a>
                  <a href="logout.php" class="s-button-logout"><span class="material-symbols-outlined">
                  logout
                  </span>
                  </a>
               </div>
            </div>
             <!--
            <div class="s-subheader">
               <h2>General</h2>
               <h2>Apariencia</h2>
            </div>
-->
         </div>
         <!-- Body -->   
         <div class="s-body">
            <div class="s-left">
               <div class="s-profile">
                  <h3 class="s-h3">Informacion Personal</h3>
                  <div class="s-profile-section">
                     <div class="s-profile-image">
                        <?php echo "             
                                   <img src='logos/img_user_".$user_id.".png' alt='Company Logo' id='s-company-logo' class='s-logo-circle'>

"?>
                     </div>
                     <div class="s-image-buttons">
                     <form action="processes/upload_images.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="logo" id="s-logo-file" class="s-upload-btn" style="display: none;" onchange="this.form.submit();">
                        <button type="button" class="s-upload-btn" onclick="document.getElementById('s-logo-file').click();">Upload Image</button>
                    </form>

                        <form action="processes/delete_image.php" method="post">
                           <button type="submit" name="delete" class="s-delete-btn">Delete Image</button>
                        </form>
                     </div>
                  </div>
                  <div class="s-user-info">
                     <div class="s-flex-column">
                        <p class="s-p s-title-p"><strong>Correo Electronico Master</strong></p>
                        <p class="s-p"><?php echo $user_email?></p>
                     </div>
                     <!-- if there is more users -->
                     <div class="s-flex-column">
                      
                        <?php 
                           if(!$auth_user_exist){
                                   echo"
                           <form action='processes/add_auth_user.php' method='POST'>
                           <input type='text' id='auth_user' name='auth_user' placeholder='Crea un Usuario' style='margin-right:30px; border:1px solid gray; border-radius:5px;'>
                           <input type='password' id='auth_user_p' name='auth_user_p' placeholder='Contraseña' style='margin-right:30px; border:1px solid gray; border-radius:5px;'>
                           
                           <input type='submit' value='Agregar' class='s-button'>
                           </form>
                                   ";
                           }else{
                               echo " 
                        <div class='row-flex'>


                        <div class='col-flex'>
                        <p class='s-p s-title-p'><strong>Usuario autorizado</strong></p>
                               <p class='s-p'> $auth_user_email</p>

                        </div>
                     <div id='s-change-auth-password' style='display:none;' class='col-flex'>
                        <form   action='processes/add_auth_user.php' method='POST'>
                        <input type='hidden' name='form_type' value='auth_pass'>
                           <input type='password' id='auth_user_p' name='auth_user_p' placeholder='Contraseña' style=' border:1px solid gray; border-radius:5px;'>
                           
                           <input type='submit' value='Agregar' class='s-button'>
                           </form>

                           </div>
                        <div class='col-flex' id='auth_password_div'>
                        
                        <p class='s-p s-title-p'><strong>Contraseña</strong></p>

                               <p class='s-p s-left-p'> $auth_user_password</p>
                               
                               </div>

                        <div class='col-flex s-icon-div'  style='margin-right:20%;'>

                               <span id='s-edit-auth' class='material-symbols-outlined'>
                                edit
                                </span>
                                <span id='s-cancel-auth' style='display:none;' class='material-symbols-outlined'>
                                        cancel
                                </span>
                                </div>
                               </div>";

                           }
                           ?>
                     </div>

                     <?php  
                        if (isset($_SESSION['error_message'])){
                            $error_message = $_SESSION['error_message'];
                        
                        echo "<p style='margin:0px;'id='error-message'>$error_message</p>";
                        unset($_SESSION['error_message']); // Clear the message after displaying it
                        
                        } ?>
                  </div>
               </div>
               <div class="s-changes">
                  <h3 class="s-h3">Cambios internos</h3>
                  <!-- Add employee -->
                  <form action="processes/add_employee.php" method="POST">
                  <input type="hidden" name="form_type" value="employee">

                     <input type="text" id="employee_name" name="employee_name" placeholder="Nombre del Empleado" style="margin-right:30px; border:1px solid gray; border-radius:5px;">
                     <input type="submit" value="Agregar" class="s-button">
                  </form>
                  <!-- Employees -->
                  <table id="s-table-employees">
                     <thead>
                        <tr>
                           <th colspan="2">Empleado</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                           // Query to get employee list
                           $sql_workers = "SELECT id, worker_name FROM workers where user_id = ?"; // Make sure to use the correct column names
                           $stmt = $conn->prepare($sql_workers);
                           $stmt->bind_param('i', $user_id);
                           $stmt->execute();                       
                           $result = $stmt->get_result();
                           
                           if ($result->num_rows < 1) {
                            // Output error if the query failed
                               echo   "<tr><td colspan='2'>No hay empleados registrados<td></tr>";
                           } else {
                               // Proceed with fetching and displaying data
                               while ($row = $result->fetch_assoc()) {
                                   echo "<tr>
                                           <td>{$row['worker_name']}</td>
                                           <td><a href='processes/delete_employee.php?id={$row['id']}&action=employee' onclick='return confirm(\"Are you sure?\");'><img src='images/delete.png' class='icon' alt='Delete'></a></td>
                                       </tr>";
                               }
                           }
                           ?>
                     </tbody>
                  </table>
                    <!-- Add email -->
                    <form action="processes/add_employee.php" method="POST" >
                    <input type="hidden" name="form_type" value="email">

                     <input type="text" id="email_form" name="email_form" placeholder="Email" class="s-inputs">
                     <input type="submit" value="Agregar" class="s-button">
                  </form>
                  <table class="s-forward-emails">  <thead>
                        <tr>
                           <th colspan="2">Correos</th>
                        </tr>
                     </thead>
                  <tbody>
                        <?php
                           // Query to get employee list
                           $sql_workers = "SELECT id, email FROM forward_emails where user_id = ?"; // Make sure to use the correct column names
                           $stmt = $conn->prepare($sql_workers);
                           $stmt->bind_param('i', $user_id);
                           $stmt->execute();                       
                           $result = $stmt->get_result();
                           
                           if ($result->num_rows < 1) {
                            // Output error if the query failed
                               echo   "<tr><td colspan='2'>No hay correos registrados<td></tr>";
                           } else {
                               // Proceed with fetching and displaying data
                               while ($row = $result->fetch_assoc()) {
                                   echo "<tr>
                                           <td>{$row['email']}</td>
                                           <td><a href='processes/delete_employee.php?id={$row['id']}&action=email' onclick='return confirm(\"Are you sure?\");'><img src='images/delete.png' class='icon' alt='Delete'></a></td>
                                       </tr>";
                               }
                           }
                           ?>
                     </tbody>
                  </table>
                  <div class="s-prices">
                        <h3 class="s-h3">Precios</h3>
                        


                        <?php

                        $sql = "SELECT evento, dia_y_noche, anulado, perdido FROM fixed_events WHERE user_id = ?";
                        $stmt = $conn->prepare($sql); // Prepare the statement to prevent SQL injection
                        
                        // Check if the statement was prepared successfully
                        if ($stmt) {
                            $stmt->bind_param('i', $user_id); // 'i' denotes the type as integer
                            $stmt->execute(); // Execute the query
                            $result = $stmt->get_result();
                           
                            if ($result->num_rows < 1) {
                             // Output error if the query failed
                                echo   "Agrega Eventos";
                            } else {
                                // Proceed with fetching and displaying data
                               
                                echo '
                                <form action="processes/events.php" method="POST" class="event-form">
                                    <input type="hidden" name="form_type" value="update_event">
                                    <div class="dynamic-inputs">'; // Add the wrapper div
                            
                                    while ($row = $result->fetch_assoc()) {
                                       foreach ($row as $column_title => $value) {
                                           
                                          if($column_title === 'dia_y_noche'){
                                             $column_title = "dia y noche";
                                          }
                                          echo '
                                             
                           
                                                   <input style="margin: 16px 0px 16px 14px;" 
                                                          class="s-inputs" 
                                                          type="text" 
                                                          placeholder="' . htmlspecialchars($column_title) . ": Q.". $value . '.00" 
                                                          name="' . htmlspecialchars($column_title) . '">
                                           
                                           ';
                                       }
                                   }
                                   
                            
                                echo '
                                    </div> <!-- Close the wrapper div -->
                                    <input type="submit" value="Actualizar" class="s-button"> 
                                </form>';
                            
                            
                            }
                           
                        
                            $stmt->close(); // Close the statement
                        }
                        
                        ?>

            <h3 class="s-h3">Vehiculos</h3>

                 <form action="processes/events.php" method="POST" class="event-form">
                  <input type="hidden" name="form_type" value="add_vehicles">
                  <input style="margin: 16px 0px 16px 14px; "type="text" id="vehicle_name" name="vehicle_name" placeholder="Vehiculo" class="s-inputs">
                  <input style="margin: 16px 0px 16px 14px; " type="number" id="vehicle_price" name="vehicle_price" placeholder="Precio por Hora" class="s-inputs">
                  <input  style="margin: 16px 0px 16px 14px; "type="submit" value="Agregar" class="s-button">
               </form>

                     
<!--
                 <form action="processes/events.php" method="POST" class="event-form">
                  <input type="hidden" name="form_type" value="add_event">
                  <input style="margin: 16px 0px 16px 14px; "type="text" id="event_name" name="event_name" placeholder="Evento" class="s-inputs">
                  <input style="margin: 16px 0px 16px 14px; " type="number" id="event_price" name="event_price" placeholder="Precio" class="s-inputs">
                  <input  style="margin: 16px 0px 16px 14px; "type="submit" value="Agregar" class="s-button">
               </form>

                     -->
                        
                    </div>
               </div>
            </div>
            <div class="s-right">
       
               <div class="s-password">
                  <h3 class="s-h3">Cambiar contraseña</h3>
                  <p class="s-p">Si necesitas cambiar la contraseña de tu Cuenta master preciona el siguiente boton.</p>
                  <a href="fpsw.php"><button class="s-button">Cambiar contraseña</button></a>
               </div>
               <div class="s-close-account">
                  <h3 class=" s-h3">Eliminar cuenta</h3>
                  <p class="s-p">Tener en cuenta que se borraran todos los datos y no podra ser reversible.</p>
                  <button class="s-delete-btn s-button">Eliminar cuenta</button>
               </div>
            </div>
         </div>
      </div>
      </div>
   </body>
</html>

<script>
    var passwordDiv = document.getElementById("auth_password_div");
    var passwordForm = document.getElementById("s-change-auth-password");

document.getElementById("s-edit-auth").addEventListener("click", function(){
    passwordDiv.style.display = "none";
    passwordForm.style.display = "flex";
    passwordForm.style.alignItems = "center";

    document.getElementById("s-edit-auth").style.display ="none";
    document.getElementById("s-cancel-auth").style.display ="block";

  
})
document.getElementById("s-cancel-auth").addEventListener("click", function(){

    passwordDiv.style.display = "flex";
    passwordDiv.style.flexDirection = "column";
    passwordDiv.style.margin = "0 10px";


    passwordForm.style.display = "none";
    document.getElementById("s-edit-auth").style.display ="block";
    document.getElementById("s-cancel-auth").style.display ="none";

})



</script>