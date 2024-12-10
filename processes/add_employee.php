<?php
session_start();
include '../conn.php'; // Ensure this includes the proper connection setup

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['form_type'])) {
        $user_id = $_SESSION['user_id']; // Assuming user_id is provided

        if ($_POST['form_type'] === 'employee') {
                $worker_name = $_POST['employee_name']; // Get the worker name from the form

                if (!empty($worker_name) && !empty($user_id)) {
                    $sql = "INSERT INTO workers (user_id, worker_name) VALUES ('$user_id', '$worker_name')";
                    
                    if ($conn->query($sql) === TRUE) {
                        header('Location: ../settings.php');
                        exit();
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                } else {
                    echo "Worker name and user ID cannot be empty.";
                }
            }elseif ($_POST['form_type'] === 'email'){
                $email = $_POST['email_form']; 
                $user_id = $_SESSION['user_id']; 

                if (!empty($email) && !empty($user_id)) {
                    // Prepare an SQL query to insert the worker
                    $sql = "INSERT INTO forward_emails (user_id, email) VALUES ('$user_id', '$email')";
                    
                    if ($conn->query($sql) === TRUE) {
                        header('Location: ../settings.php');
                        exit();
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                } else {
                    echo "Email cannot be empty.";
                }
            }

            
            }
}

// Close the database connection
$conn->close();
?>
