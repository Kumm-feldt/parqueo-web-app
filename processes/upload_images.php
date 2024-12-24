<?php
// Ensure the /logos directory exists
$targetDir = "../logos/";

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
 }
 $user_id = $_SESSION['user_id'];


if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);  // Create the directory with write permissions
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['logo'])) {
        // Extract file extension and create a unique filename with the user_id appended
        $fileExtension = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $newFileName = "img" . "_user_" . $user_id . '.' . $fileExtension;

        // Define the target file path
        $targetFile = $targetDir . $newFileName;

        $uploadOk = 1;

        // Check if the file is a valid image
        $check = getimagesize($_FILES["logo"]["tmp_name"]);
        if ($check === false) {
            $_SESSION['error_message'] = "File is not an image.";
            $uploadOk = 0;
        }

        // Allow only PNG and JPEG files
        if ($fileExtension != "png") {
            $_SESSION['error_message'] = "Only PNG files are allowed.";
            $uploadOk = 0;
        }

   

        // Check file size (limit set to 5MB)
        if ($_FILES["logo"]["size"] > 5000000) {
            $_SESSION['error_message'] =  "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Attempt to upload file if all checks are passed
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["logo"]["tmp_name"], $targetFile)) {
                $_SESSION['error_message'] = "The file " . htmlspecialchars($newFileName) . " has been uploaded.";
            } else {
            $_SESSION['error_message'] = "Sorry, there was an error uploading your file.";
            }

        }
        header('Location: ../settings.php');

    } else {
       $_SESSION['error_message'] = "No file uploaded.";
       header('Location: ../settings.php');

    }
}
?>
