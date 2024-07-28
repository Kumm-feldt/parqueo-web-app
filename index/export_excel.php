<?php
session_start();

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

  //$username = "u659703897_localhost";
    //$password = "DT+xgyc|7";
    //$dbname = "u659703897_mydb";

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mydb";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM log_out";
$result = $conn->query($sql);

// Get current date
$date = date('Y-m-d');

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Data de Vehiculos');

$sheet->setCellValue('A1', 'Ticket')
      ->setCellValue('B1', 'Tipo de Vehiculo')
      ->setCellValue('C1', 'Hora de Ingreso')
      ->setCellValue('D1', 'Hora de Salida')
      ->setCellValue('E1', 'Total')
      ->setCellValue('F1', 'Turno');

$row = 2;
if ($result->num_rows > 0) {
    while ($data = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $row, $data['ticket'])
              ->setCellValue('B' . $row, $data['vehicle_type'])
              ->setCellValue('C' . $row, $data['time_in'])
              ->setCellValue('D' . $row, $data['time_out'])
              ->setCellValue('E' . $row, $data['charge'])
              ->setCellValue('F' . $row, $data['person']);
        $row++;
    }
}

// Save the Excel file to a temporary location
$tempFile = tempnam(sys_get_temp_dir(), 'data') . '.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($tempFile);

// SQL statement to insert all data from log_out into log_out_copy
$sql_insert = "INSERT INTO total_log_out SELECT * FROM log_out";

if ($conn->query($sql_insert) === TRUE) {
    $sql_delete = "DELETE FROM log_out";
    if ($conn->query($sql_delete) === TRUE) {
        echo "Se descargó correctamente el archivo Excel";
        echo "<button> <a href='index.php'>Regresar a Inicio</a> </button>";
    } else {
        echo "Error deleting data: " . $conn->error;
    }
} else {
    echo "Error inserting data: " . $conn->error;
}

$conn->close();

// Now send the email with the Excel attachment using PHPMailer
$mail = new PHPMailer(true);

try {
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host = 'smtp.hostinger.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'updates@amiparqueo.com';
    $mail->Password = 'jhkalmi85!A'; 
    
    $mail->addReplyTo('updates@amiparqueo.com', 'amiparqueo.com'); // correo creado en hostinger
    $mail->addAddress('mercadeo@realdelparque.com', 'Mercadeo');

    $mail->setFrom('updates@amiparqueo.com', 'amiparqueo.com');
    

    // Attachments
    $mail->addAttachment($tempFile, 'Data de Vehiculos.xlsx'); // Add attachments

    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = 'Data de Vehiculos';
    $mail->Body    = 'Los datos de tu parqueo estan disponibles. Adjunto encontraras un archivo excel para visualizar';
    $mail->AltBody = 'Los datos de tu parqueo estan disponibles. Adjunto encontraras un archivo excel para visualizar';

    $mail->send();
    echo 'Mensaje enviado';
} catch (Exception $e) {
    echo "Mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
}
// Destroy the current session
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session



// Clean up temporary file
unlink($tempFile);
header("Location: index.php");

?>
