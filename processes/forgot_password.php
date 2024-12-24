<?php
require("../conn.php");
include("email_sender.php");
session_start();


// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $confirmation_code = rand(100000, 999999); // Generate a 6-digit code
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Insert data into the database
    $sql = "SELECT email FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    // Check if the query was successful and returned a row
    if ($result && $result->num_rows > 0) {

        
    $expFormat = mktime(
    date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y")
    );
    $expDate = date("Y-m-d H:i:s",$expFormat);

    $key = md5((2418*2).($email));
    
    $addKey = substr(md5(uniqid(rand(),1)),3,10);
    
    $key = $key . $addKey;
    // Insert Temp Table
    mysqli_query($conn,
    "INSERT INTO `password_reset_temp` (`email`, `key`, `expDate`)
    VALUES ('".$email."', '".$key."', '".$expDate."');");
         

    $mail->addAddress($email, 'Cambio de contrasena');

    $mail->Subject = 'Cambio de contrasena';

    $output = '<a href="http://localhost:8000/confirmation.php?key='.$key.'&email='.$email.'&action=reset" target="_blank" style="color: #ffffff; background-color: #007bff; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Cambiar contraseña</a>';
    
    $body = '
        <div style="font-family: Arial, sans-serif; color: #333333; padding: 20px; background-color: #f9f9f9;">
            <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1); overflow: hidden;">
                <div style="background-color: #007bff; color: #ffffff; padding: 10px 20px;">
                    <h1 style="margin: 0; font-size: 24px;">Cambio de contraseña</h1>
                </div>
                <div style="padding: 20px;">
                    <p>¡Hola querido usuario!</p>
                    <p>Has solicitado un cambio de contraseña. Haz clic en el siguiente botón para proceder:</p>
                    <p style="text-align: center; margin: 20px 0;">' . $output . '</p>
                    <p>Si no solicitaste este cambio, puedes ignorar este correo electrónico sin problemas.</p>
                </div>
                <div style="background-color: #f1f1f1; padding: 10px; text-align: center;">
                    <p style="margin: 0;">&copy; ' . date('Y') . ' Amiparqueo. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    ';
    
    $mail->Body = $body;    

    $mail->send();
    // send.php
    $_SESSION['message'] = "El link para cambiar contraseña fue enviado correctamente a su correo electronico.";
    header("Location: ../message.php");
    exit();


    } else {
        echo "Error: Correo no existe en nuestros registros.";
    }


    // Close connection
    $conn->close();

     // Redirect to the confirmation page
    // header("Location: ../confirmation.php?email=$email");
     exit();

} else {
    echo "No se completo el registro";
}
?>
