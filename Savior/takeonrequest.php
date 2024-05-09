<?php
session_start();
include_once 'connect_db.php';

// Check if data is received via POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the request is being taken by a savior
    if (isset($_SESSION['username'])) {
        // Retrieve request ID from POST data
        $req_Id = $_POST['req_id'];

        // Retrieve session username (savior's username)
        $saviorUsername = $_SESSION['username'];

        // Get the current timestamp
        $currentTimestamp = date('Y-m-d H:i:s');

        // Update the requests record in the database with savior's username and timestamp
        $updateQuery = "UPDATE requests SET under_date = '$currentTimestamp', veh_username = '$saviorUsername' WHERE req_id = $req_Id";

        if (mysqli_query($conn, $updateQuery)) {
            echo "Request details saved successfully!";
        } else {
            echo "Error updating request details: " . mysqli_error($conn);
        }
    } else {
        echo "Error: Session username not found!";
    }
} else {
    echo "Invalid request!";
}
?>