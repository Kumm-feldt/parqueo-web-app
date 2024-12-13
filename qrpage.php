<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>QR Code Generator</title>
      <link rel="stylesheet" href="css/qrpage.css">
   </head>
   <body>
      <div id="wrapper">
         <h1>Imprimir Ticket</h1>
         <?php
            session_start();
            $_SESSION["submitted"] = false;
            
             function getURL ($randomNumber){
                return "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=$randomNumber";
            
            };
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['callFunction'])) {
            // Generate a random number
            $randomNumber = rand(2000, 7000);
            
            // Generate the QR code URL
            $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=$randomNumber";
            
            // Insert into the database (if required)
            
            // Redirect to the same page with the random number as a query parameter
            header("Location: qrpage.php?ticketNumber=$randomNumber&qrCodeUrl=" . urlencode($qrCodeUrl));
            exit;
            }
            
            // Display QR code if the query parameters are present
            if (isset($_GET['ticketNumber']) && isset($_GET['qrCodeUrl'])) {
            $randomNumber = htmlspecialchars($_GET['ticketNumber']);
            $qrCodeUrl = htmlspecialchars($_GET['qrCodeUrl']);
            
            echo "<div class='qr-code'>";
            echo "<p>Numero de Ticket: $randomNumber</p>";
            echo "<img src='$qrCodeUrl' alt='QR Code'>";
            echo "</div>";
            
            $_SESSION["submitted"] = true;
            
            // automatically print...
            
            echo "<p>¿No ves ningun codigo?\n
            Comunicate con nosotros.</p>
            ";

            // after printing is successfull
            
            }else{
            
            }
            ?>
         <?php if($_SESSION["submitted"] == false){
            echo '
            <div class="qrgenerator-div">
                <form id ="generator-form" method="POST" action="qrpage.php">
                        <button type="submit" name="callFunction">Generar Ticket</button>
                </form>
            </div>
            
            ';
            }
            
            ?>
         <div class="qrgenerator-div">
            <a href="qrpage.php">Home</a>
         </div>
      </div>
      <!-- 
         STEPS FOR QR CODE GENERATIONS:
         1. Select event and vehicle
         2. Button GENERATE CODE
         3. Ask in the database if code exists in recent days
          -> if not exist INSERT
          -> else generate another code
         
         
         STEPS FOR QR CODE SCAN:
         1. With scanner scan code
             -> if code is retrieved then output total
             -> else enter manually
         2. Customer Pays
         3. It enters the DB
         
         -->
   </body>
</html>