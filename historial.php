<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
 }
 $user_id = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>visualizar Datos CSV</title>
    <link href="/css/historialSheet.css" rel="stylesheet" />
    <link href="/css/styles.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
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
    <div class="container">
        <h1>Historial de archivos</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha creada</th>
                    <th>Hora creada</th>
                    <th>Turno</th>
                    <th>Nombre del archivo</th>
                    <th>Descargar</th>
                </tr>
            </thead>
            <tbody>
                <?php
          
               include 'conn.php';
               // Assuming $conn is your database connection object and already established
               
               $user_id = $_SESSION['user_id'];
                $sql = "SELECT id, created_date, created_time, username, file_name FROM excel_files where user_id=?";
                 // Use a prepared statement to safely bind the value
                $stmt = $conn->prepare($sql);

                // Bind the parameter (assuming $auth_user is a string)
                $stmt->bind_param('s', $user_id);

                // Execute the query
                $stmt->execute();

                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['created_date']}</td>
                                <td>{$row['created_time']}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['file_name']}</td>
                                <td><a class='download-link' href='/processes/download_excel.php?id={$row['id']}'>Download</a></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay archivos disponibles</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    
        <div id="footer">
           
           <div class="buttons">
           <a href="index.php" >
               <button type="submit" class="icon-button">
                   <span class="material-symbols-outlined icon" style="color: black;">home</span>
               </button>
                </a>
       
           
            <?php
            if($_SESSION['m_u'] == true){
                echo "
                   
                <a href='previous.php'>
                <button type='submit' class='icon-button' id='accountButton'>
                    <span class='material-symbols-outlined icon'>history</span>
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
</body>
</html>
