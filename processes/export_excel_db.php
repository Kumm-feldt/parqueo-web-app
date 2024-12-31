<?php
require '../vendor/autoload.php'; // Make sure to switch between qa/production
//session_start();
date_default_timezone_set('America/Guatemala');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;




function export_excel_db($conn, $username, $spreadsheet, $date, $user_id){


    
    $created_date = date('Y-m-d');
    $created_time = date('H:i:s');

    
    $tempFile = tempnam(sys_get_temp_dir(), 'data - '.$date) . '.csv';
    $writer = new Csv($spreadsheet);
    $writer->save($tempFile);

// Read the file contents
$file_data = file_get_contents($tempFile);
$file_name = 'data - ' . $date . '.csv';


    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO excel_files (user_id,created_date, created_time, username, file_data, file_name) VALUES (?,?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $user_id, $created_date, $created_time, $username, $file_data, $file_name);
    
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
        // Clean up the temporary file
        unlink($tempFile);
    
}

?>
