<?php
include '../conn.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $code = $_POST['code'];

    // Check if the code matches the one in the database
    $stmt = $conn->prepare("SELECT confirmation_code FROM users WHERE email = ? AND is_confirmed = 0");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($db_code);
    $stmt->fetch();
    $stmt->close();

    if ($code == $db_code) {
        // Update user as confirmed
        $stmt = $conn->prepare("UPDATE users SET is_confirmed = 1 WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->close();

        echo "Su correo se ha confirmado correctamente";
        echo "<div style='text-align: center; padding-top:50px;'>";
        echo "<button> <a href='../login.php'>Ingresar</a> </button>";
        echo "</div>";
    } else {
        echo "Codigo de confirmacion incorrecto";
        echo "<div style='text-align: center; padding-top:50px;'>";
        echo "<button> <a href='../confirmation.php'>Intentar nuevamente</a> </button>";
        echo "</div>";
    }
}
?>
