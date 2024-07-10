<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
$tempFile = tempnam(sys_get_temp_dir(), 'data');
$writer = new Xlsx($spreadsheet);
$writer->save($tempFile);

// Send headers to force download
header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="datos-' . $date . '.xlsx"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($tempFile));
readfile($tempFile);

// Clean up the temporary file
unlink($tempFile);

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
?>
