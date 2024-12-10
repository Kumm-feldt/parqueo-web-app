<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['userName'])) {
        $_SESSION['userName'] = htmlspecialchars($_POST['userName']);
        header("Location: /../index.php"); // Redirect to the previous page after setting the session
        exit();
    }
}
?>
