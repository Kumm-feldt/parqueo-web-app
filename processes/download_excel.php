

<?php
include '/../conn.php';
date_default_timezone_set('America/Denver');
session_start();
require '../vendor/autoload.php'; // Make sure you have included the autoloader for PHPSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $user_id = $_Session['user_id'];
    $sql = "SELECT file_name, file_data FROM excel_files WHERE id = ? and user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($file_name, $file_data);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        // Create a temporary file for the CSV data
        $tempCsvFile = tempnam(sys_get_temp_dir(), 'data') . '.csv';
        file_put_contents($tempCsvFile, $file_data);

        // Load the CSV data into a Spreadsheet object
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tempCsvFile);

        // Clean up the temporary CSV file
        unlink($tempCsvFile);

        // Send the correct headers to prompt the download
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . pathinfo($file_name, PATHINFO_FILENAME) . '.csv"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        // Output the CSV file
        $writer = new Csv($spreadsheet);
        $writer->save('php://output');
        exit;
    } else {
        echo "File not found.";
    }

    $stmt->close();
}

$conn->close();
?>
