<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
 }
 $user_id = $_SESSION['user_id'];

include '../conn.php'; // Ensure this includes the proper connection setup

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; // Current logged-in user ID

    if($_POST['form_type']){
        if($_POST['form_type'] == 'auth_pass'){

            $pass = $_POST['auth_user_p'];

            // Update the user with the new authorized user and password
            $sql_update = "UPDATE users SET authorized_user_password = ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param('si', $pass, $user_id); // 'ssi' stands for string, string, integer
            if ($stmt_update->execute()) {
                $_SESSION['error_message'] = "Contraseña asignada correctamente";

                // Redirect to the settings page (or another page)
                header('Location: ../settings.php');
                exit();
            } else {
                $_SESSION['error_message'] = "Error al asignar el usuario autorizado. Inténtalo de nuevo.";
                header('Location: ../settings.php');
                exit();
            }

        }else{
            $_SESSION['error_message'] = "Error al asignar el usuario autorizado. Inténtalo de nuevo.";

            header('Location: ../settings.php');
        }



    }else{

    
    $auth_user = $_POST['auth_user']; // Get the authorized user from the form
    $auth_user_p = $_POST['auth_user_p']; // Get the authorized user password from the form
    
    // Check if both authorized user and password are provided
    if (empty($auth_user) || empty($auth_user_p)) {
        $_SESSION['error_message'] = "Por favor ingresa un nombre de usuario autorizado y una contraseña.";
        header('Location: ../settings.php');
        exit();
    }
    
        // Prepare the SQL query to check if the authorized user already exists
        $sql_check = "SELECT authorized_user FROM users WHERE authorized_user = ?";

        // Use a prepared statement to safely bind the value
        $stmt_check = $conn->prepare($sql_check);

        // Bind the parameter (assuming $auth_user is a string)
        $stmt_check->bind_param('s', $auth_user);

        // Execute the query
        $stmt_check->execute();

        // Get the result
        $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $_SESSION['error_message'] = "Este usuario ya existe, porfavor intentar con otro.";
        header('Location: ../settings.php');
        exit();
    }
    
    // Update the user with the new authorized user and password
    $sql_update = "UPDATE users SET authorized_user = ?, authorized_user_password = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param('ssi', $auth_user, $auth_user_p, $user_id); // 'ssi' stands for string, string, integer
    if ($stmt_update->execute()) {
        $_SESSION['error_message'] = "Usuario assignado correctamente.";

        // Redirect to the settings page (or another page)
        header('Location: ../settings.php');
        exit();
    } else {
        $_SESSION['error_message'] = "Error al asignar el usuario autorizado. Inténtalo de nuevo.";
        header('Location: ../settings.php');
        exit();
    }
    
    $stmt_update->close();
}

}

// Close the database connection
$conn->close();
?>
