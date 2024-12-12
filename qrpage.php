<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
</head>
<body>
    <h1>Tickets</h1>
    <?php
    // Generate a random number
    $randomNumber = rand(2000, 7000);

    // Google Charts API URL
    $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=$randomNumber";

    // Display the QR code
    echo "<p>Numero de Ticket: $randomNumber</p>";
    echo "<img src='$qrCodeUrl' alt='QR Code'>";
    ?>
</body>
</html>
