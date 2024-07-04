<?php
require 'path/to/PHPExcel.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM vehicles";
$result = $conn->query($sql);

$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Vehicle Data');

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Ticket')
                                ->setCellValue('B1', 'Tipo de Vehiculo')
                                ->setCellValue('C1', 'Hora de Ingreso')
                                ->setCellValue('D1', 'Hora de Salida');
                                ->setCellValue('E1', 'Total');

                              

$row = 2;
if ($result->num_rows > 0) {
    while ($data = $result->fetch_assoc()) {
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $data['ticket'])
                                      ->setCellValue('B' . $row, $data['vehicle_type'])
                                      ->setCellValue('C' . $row, $data['time_in'])
                                      ->setCellValue('D' . $row, $data['time_out']);
                                      ->setCellValue('E' . $row, $data['charge']);
        $row++;
    }
}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('VehicleData.xlsx');

$conn->close();

echo "Excel file created successfully.";
?>
