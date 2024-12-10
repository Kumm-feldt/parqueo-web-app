<?php
include 'templates/header.php';
include 'conn.php';
// message.php
session_start();
?>
<style>
#message-div{
    background: #fff;
    display: flex;
    flex-direction: column;
    padding:  0 50px;
    height: 100%;
    justify-content: center;
    align-items: center;
    text-align: center;
}
    </style>
<main>
<div id="container" class="container">
    <?php
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            echo "<div id='message-div'>";
            echo "<p>$message</p>";
            echo '<a href="index.php" id="message-link">Login</a>';
            echo "</div>";
            unset($_SESSION['message']); // Clear the message after displaying it
        }
        ?>
</div>
</main>

<?php
include 'templates/footer.php';