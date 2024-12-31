<?php
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');


session_start();
date_default_timezone_set('America/Guatemala');

require '../vendor/autoload.php'; // make sure to change this between qa/production




use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include_once("export_excel_db.php");
include("../conn.php");

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM log_out where user_id=?";
$stmt = $conn->prepare($sql);

// Bind the parameter (assuming $auth_user is a string)
$stmt->bind_param('i', $user_id);

// Execute the query
$stmt->execute();

$result = $stmt->get_result();


// Get current date
$date = date('Y-m-d');


// select user Email
$sql_emails = "SELECT email FROM forward_emails where user_id = ?";
$stmt_emails = $conn->prepare($sql_emails);

$stmt_emails->bind_param('i', $user_id);
$stmt_emails->execute();
$result_emails = $stmt_emails->get_result();

if (isset($_SESSION['selected_user'])) {
    $user = $_SESSION['selected_user'];
}else{
    $user = "-";
}


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Data de Vehiculos');

$sheet->setCellValue('A1', 'Ticket')
      ->setCellValue('B1', 'Tipo de Vehiculo')
      ->setCellValue('C1', 'Hora de Ingreso')
      ->setCellValue('D1', 'Hora de Salida')
      ->setCellValue('E1', 'Total')
      ->setCellValue('F1', 'Turno')
      ->setCellValue('G1', 'Tipo')
      ->setCellValue('H1', 'Placa');


$row = 2;
if ($result->num_rows > 0) {
    while ($data = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $row, $data['ticket'])
              ->setCellValue('B' . $row, $data['vehicle_type'])
              ->setCellValue('C' . $row, $data['time_in'])
              ->setCellValue('D' . $row, $data['time_out'])
              ->setCellValue('E' . $row, $data['charge'])
              ->setCellValue('F' . $row, $data['person'])
              ->setCellValue('G' . $row, $data['park_type'])
              ->setCellValue('H' . $row, $data['placa']);

        $row++;
    }
}

// Save the Excel file to a temporary location
$tempFile = tempnam(sys_get_temp_dir(), 'data'.$date) . '.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($tempFile);

export_excel_db($conn, $user, $spreadsheet, $date, $user_id);

$sql_insert = $conn->prepare("INSERT INTO total_log_out SELECT * FROM log_out WHERE user_id = ?");
$sql_insert->bind_param("i", $user_id);

if ($sql_insert->execute()) {
    $sql_delete = $conn->prepare("DELETE FROM log_out WHERE user_id = ?");
    $sql_delete->bind_param("i", $user_id);

    if ($sql_delete->execute()) {
        echo "Se descargó correctamente el archivo Excel";
        echo "<button> <a href='/../index.php'>Regresar a Inicio</a> </button>";
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
   // $mail->addAddress('mercadeo@realdelparque.com', 'Mercadeo');
    //$mail->addAddress('administracion@realdelparque.com', 'Administracion');
  
    if ($result_emails->num_rows > 0) {
        while ($row = $result_emails->fetch_assoc()) {
            $mail->addAddress($row['email']);
    
        }
    }else{
        echo "No hay correo electronicos registrados";

        exit();
       
    }

    
 
    $mail->setFrom('updates@amiparqueo.com', 'amiparqueo.com');
    

    // Attachments
    $mail->addAttachment($tempFile, 'Data de Vehiculos - ' . $date . '.xlsx'); // Add attachments

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




// Clean up temporary file
unlink($tempFile);
header("Location: /../index.php");

?>
