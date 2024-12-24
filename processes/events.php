<?php
session_start();
include '../conn.php'; // Ensure this includes the proper connection setup

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['form_type'])) {
        $user_id = $_SESSION['user_id'] ?? null; // Validate session variable exists

        if (!$user_id) {
            echo "User ID is missing in the session.";
            exit();
        }

        if ($_POST['form_type'] === 'add_event') {
            // -- Add event --

            $event = $_POST['event_name'] ?? null;
            $price = $_POST['event_price'] ?? null;

            if (!empty($event) && !empty($price)) {
                // Use prepared statement to avoid SQL injection
                $sql = "INSERT INTO events (user_id, event, price) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isi", $user_id, $event, $price);

                if ($stmt->execute()) {
                    $stmt->close();
                    header('Location: ../settings.php');
                    exit();
                } else {
                    echo "Error: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Event Name and Price Cannot Be Empty";
            }
        } elseif ($_POST['form_type'] === 'update_event') {
            // -- Update event --

            foreach ($_POST as $event=>$value) {
                // Skip non-event fields
                if ($event === 'form_type' or !$value) {
                    continue;
                }

                if($event === "dia y noche"){
                    $event = "dia_y_noche";
                }

                $sql = "UPDATE fixed_events SET $event = ? WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $value, $user_id);

                if ($stmt->execute()) {
                    // Optionally log success
                    error_log("Event price updated successfully for $event.");
                } else {
                    error_log("Error updating event: " . $stmt->error);
                    echo "Error updating event: " . $stmt->error;
                }

                $stmt->close();
            }

            header('Location: ../settings.php');
            exit();
        }else if($_POST['form_type'] === 'add_vehicles'){
            $vehicle = $_POST['vehicle_name'];
            $price = $_POST['vehicle_price'];

            $sql = "INSERT INTO vehicles (user_id, vehicle, price) VALUES (?,?,?)";
            $stmt = $conn->prepare($sql);


            $stmt->bind_param("isi", $user_id, $vehicle, $price);

            if ($stmt->execute()) {
                $stmt->close();
                header('Location: ../settings.php');
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}
?>
