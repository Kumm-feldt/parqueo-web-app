<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
 }
 $user_id = $_SESSION['user_id'];



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    // Set the path to the image
    $image_path = '../logos/img_user_' . $user_id.'.png';  // Replace with the actual path to the image file

    // Check if the file exists and delete it
    if (file_exists($image_path)) {
        if (unlink($image_path)) {
            $_SESSION['error_message'] ="Image deleted successfully.";
        } else {
            $_SESSION['error_message'] = "Error deleting the image.";
        }
    } else {
        $_SESSION['error_message'] ="Image does not exist.";
    }

    // Optionally, redirect to the settings page
    header("Location: ../settings.php");  // Adjust the location accordingly
    exit();
}
?>
