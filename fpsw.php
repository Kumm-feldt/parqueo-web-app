<?php
include 'templates/header.php';
?>
<style>
.form-container {
    position: relative;
    width: 100%;
    padding: 0 100px;
}
    </style>
<main>
<div id="container" class="container">
    <div class="form-container">
    <form action="processes/forgot_password.php" method="POST">
    <h2>Cambiar contraseña</h2>

        <label>Ingresa tu correo electronico:</label>
        <input type="email" name="email" required>
        <button type="submit">Confirmar</button>
    </form>

</div>
</div>
</main>

<?php
    include 'templates/footer.php';
?>