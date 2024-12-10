<?php
include 'templates/header.php';
include 'conn.php';
session_start();

$error = ""; // Initialize error variable

// Check if GET parameters are set for reset link
if (isset($_GET["key"]) && isset($_GET["email"]) && isset($_GET["action"]) 
    && ($_GET["action"] == "reset") && !isset($_POST["action"])) {
    
    $key = $_GET["key"];
    $email = $_GET["email"];
    $curDate = date("Y-m-d H:i:s");
    
    // Query to check the reset link validity
    $query = mysqli_query($conn, 
        "SELECT * FROM `password_reset_temp` WHERE `key` = '$key' AND `email` = '$email';"
    );
    
    if (mysqli_num_rows($query) == 0) {
        $error = '<h2>Invalid Link</h2>
            <p>The link is invalid or expired. Either you did not copy the correct link 
            from the email, or you have already used the key, in which case it is 
            deactivated.</p>';
    } else {
        $row = mysqli_fetch_assoc($query);
        $expDate = $row['expDate'];
        
        // Check if the link is expired
        if ($expDate >= $curDate) {
            // Display the password reset form
            ?>
            <main>
                <div id="container" class="container">
                    <div class="form-container">
                        <form action="" method="POST" name="update">
                            <?php echo"<label><strong>$email</strong></label></br>"?>
                            <label>Enter New Password:</label>
                            <input type="password" name="password" required>
                            <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>">
                            <input type="hidden" name="action" value="reset"> <!-- Add action field -->
                            <button type="submit">Confirm</button>
                        </form>
                    </div>
                </div>
            </main>
            <?php
        } else {
            $error = "<h2>Link Expired</h2>
                <p>The link has expired. You are trying to use an expired link, 
                which was only valid for 24 hours after the request.</p>";
        }
    }
    
    if (!empty($error)) {
        echo "<div class='error'>".$error."</div><br />";
    }
}

// Password reset form submission handling
if (isset($_POST["email"]) && isset($_POST["action"]) && ($_POST["action"] == "reset")) {
    $pass1 = mysqli_real_escape_string($conn, $_POST["password"]);
    $email = $_POST["email"];
    $curDate = date("Y-m-d H:i:s");

    if (empty($pass1)) {
        $error = "Password cannot be empty.";
    } else {
        // Use password_hash instead of md5 for security
        $hashed_password = password_hash($pass1, PASSWORD_BCRYPT);
        
        // Update user's password
        mysqli_query($conn, 
            "UPDATE `users` SET `password` = '$hashed_password'
             WHERE `email` = '$email';"
        );
        
        // Delete reset entry
        mysqli_query($conn, 
            "DELETE FROM `password_reset_temp` WHERE `email` = '$email';"
        );
        $_SESSION['message'] = "Su contraseña ha sido actualizada correctamente.";

        header("Location: ../message.php");
        exit();
    }

    if (!empty($error)) {
        echo "<div class='error'>".$error."</div><br />";
    }
}

?>

<!-- Some basic styling for the form -->
<style>
.form-container {
    position: relative;
    width: 100%;
}
</style>

<?php
include 'templates/footer.php';
?>
