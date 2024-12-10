<?php
session_start();
$user_id = $_SESSION['user_id']; // Assuming user_id is provided

include "../conn.php";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    if($action === 'employee'){
        $sql = "DELETE FROM workers WHERE id=$id and user_id=$user_id";
    
        if ($conn->query($sql) === TRUE) {
            echo "Employee deleted successfully!";
            header('Location: ../settings.php'); // Redirect to the main page after deletion
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }elseif($action ==='email'){
        $sql = "DELETE FROM forward_emails WHERE id=$id and user_id=$user_id";
    
        if ($conn->query($sql) === TRUE) {
            echo "Email deleted successfully!";
            header('Location: ../settings.php'); // Redirect to the main page after deletion
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }
   
}

$conn->close();
?>
