<?php
require("conn.php");
session_start();

// If Session is still active
if(isset($_SESSION["user_name"])){
    header("Location: index.php");
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Query the database for the master user
    $sql = "SELECT id, name, password FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Fetch user data
        $user = $result->fetch_assoc();
        $hashed_password = $user['password'];

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct
            echo "Login successful! Welcome, " . $user['name'] . ".";
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['company_name'];
            $_SESSION['m_u'] = true; // master user is true

            header("Location: index.php"); 
            exit();
        } else {
            // Incorrect password
            $error_message = "Incorrect username or password";
        }
    } else {
        // Query the database for the master user
        $sql = "SELECT id, authorized_user_password FROM users WHERE authorized_user = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // Fetch user data
            $user = $result->fetch_assoc();
            $input_password = $_POST['password'];

            if($input_password == $user['authorized_user_password']){
                // Password is correct
                echo "Login successful! Welcome, " . $user['name'] . ".";
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['m_u'] = false; // master user is false
                $_SESSION['user_name'] = $user['company_name'];
                header("Location: index.php");
                exit();

            }else{

            $error_message = "Incorrect username or password";
        }

        }else{
            // User not found
            $error_message = "Incorrect username or password";
        }    
      
    }

    // Close connection
    $conn->close();
}

include 'templates/header.php';
?>
<main>


<div class="container" id="container">
    
    <div class="form-container sign-in-container">
    <form action="login.php" method="post">
            <h1>Login</h1>
            <!--

            <div class="social-container">
                <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <span>o usa tu cuenta</span>
-->
            <input type="text" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Contraseña" required />
            <?php 
            if (isset($error_message)){
                echo "<p id='error-message'>$error_message</p>";
                unset($_SESSION['error_message']); // Clear the message after displaying it

            }
        ?>
            <a href="fpsw.php">¿Olvidaste tu contraseña?</a>
            <a href="signup.php">¿Aun no tienes una cuenta? Registrate</a>

            <button type="submit">Ingresar</button>

        </form>
    

    </div>
    <div class="overlay-container">
        <div class="overlay">
            <img src="images/img1.jpg" class="img2" alt="img">
           
        </div>

    </div>
</div>
</main>
<script src="scripts/login.js"></script>

<?php
include 'templates/footer.php';?>


