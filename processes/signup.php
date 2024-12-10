<?php
require("../conn.php");
include("email_sender.php");


// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $password = $_POST['password'];
    $confirmation_code = rand(100000, 999999); // Generate a 6-digit code


    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into the database
    $sql = "INSERT INTO users (name, email, company_name, phone_number, password,confirmation_code) VALUES ('$name', '$email', '$company_name', '$phone_number', '$hashed_password', '$confirmation_code')";

    if ($conn->query($sql) === TRUE) {
        echo "Se ha enviado un correo de confirmacion. Favor revisar correo electronico para completar el registro.";

    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // send confirmation code
    $mail->addAddress($email, 'Confirmacion');

    // Content
    $mail->Subject = 'Codigo de Confirmacion';
// Your email body with HTML formatting
$body = '
    <p>¡Hola ' . $name. '!</p>
    <p>Utiliza este código de verificación para completar tu registro.</p>
    <p><strong>' .$confirmation_code. '</strong></p>
    <p>Si no solicitaste este código, puedes ignorar este correo electrónico sin problemas.</p>
';
$mail->Body = $body;

    $mail->AltBody =  '¡Hola ' .$name.'!\nUtiliza este código de verificación para completar tu registro.\n'.$confirmation_code.'\nSi no solicitaste este código, puedes ignorar este correo electrónico sin problemas.';
    ;

    $mail->send();

    // Close connection
    $conn->close();

     // Redirect to the confirmation page
     header("Location: ../confirmation.php?email=$email");
     exit();

} else {
    echo "No se completo el registro";
}
?>
