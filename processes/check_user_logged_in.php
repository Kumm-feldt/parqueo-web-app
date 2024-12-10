<?php
session_start();

if (isset($_SESSION['userName'])) {
    echo json_encode(['loggedIn' => true, 'userName' => $_SESSION['userName']]);
} else {
    echo json_encode(['loggedIn' => false]);
}
?>
