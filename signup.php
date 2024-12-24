
<?php
include 'templates/header.php';
include 'conn.php';
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

    // Query the database for the user
    $sql = "SELECT name FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $error_message = "Este correo electronico ya esta vinculado a una cuenta.";
        
    }else{
        // add second form
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
        $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
        $password = $_POST['password'];
    
    
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
        // Insert data into the database
        $sql = "INSERT INTO users (name, email, company_name, phone_number, password) VALUES ('$name', '$email', '$company_name', '$phone_number', '$hashed_password')";
       
       
        if ($conn->query($sql) === TRUE) {
             $sql_get_id = "SELECT id FROM users WHERE email = '$email'";
             $result = $conn->query($sql_get_id);

             if ($result->num_rows == 1) {
                 // Fetch user data
                 $user = $result->fetch_assoc();
                $user_id = $user["id"];
                 $sql_fixed_events = "INSERT INTO fixed_events (user_id) VALUES ($user_id)";
                 $conn->query($sql_fixed_events);
             }

            
            $_SESSION['message'] = "Usted se ha registrado correctamente.";
    
        } else {
            $_SESSION['message'] = "Hubo un error en el registro. Por favor hazlo de nuevo.";
        }

        header("Location: message.php");

    }
    $conn->close();
}


?>

<main>

<div class="container" id="container">
    <div class="form-container sign-up-container">
    <form action="signup.php" method="post">
    <h1>Crear Cuenta</h1>
            <!--
            <div class="social-container">
                <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <span>o usa tu correo para registrarte</span>
-->

                <input type="text" id="name" name="name" placeholder="Your Name" required />

                <input type="email" id="email" name="email" placeholder="Your Email" required />

                <input type="text" id="company_name" name="company_name" placeholder="Your Company Name" required/>

                <input type="text" id="phone_number" name="phone_number" placeholder="Your Phone Number" />

                <input type="password" id="password" name="password" placeholder="Your Password" required />
                <?php
                 if (isset($error_message)){
                    echo "<p style='margin:0px;'id='error-message'>$error_message</p>";
                unset($_SESSION['error_message']); // Clear the message after displaying it

                }
                ?>
            <a href="../login.php">¿Ya tienes una cuenta? Ingresa</a>

            <button type="submit">Registrate</button>
        </form>

    </div>
    
    <div class="overlay-container">
        <div class="overlay">
            <img src="images/img2.png" class="img2" alt="img">
           
        </div>

    </div>
</div>
</main>
<script src="login/login.js"></script>

<?php
include 'templates/footer.php';


