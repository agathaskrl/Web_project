<?php
session_start();
include_once 'connect_db.php';

// Check if data is received via POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the offer is being taken by a savior
    if (isset($_SESSION['username'])) {
        // Retrieve offer ID from POST data
        $offerId = $_POST['offer_id'];

        // Retrieve session username (savior's username)
        $saviorUsername = $_SESSION['username'];

        // Get the current timestamp
        $currentTimestamp = date('Y-m-d H:i:s');

        // Update the offer record in the database with savior's username and timestamp
        $updateQuery = "UPDATE offers SET ret_date = '$currentTimestamp', usrnm_veh = '$saviorUsername' WHERE offer_id = $offerId";

        if (mysqli_query($conn, $updateQuery)) {
            echo "Offer details saved successfully!";
        } else {
            echo "Error updating offer details: " . mysqli_error($conn);
        }
    } else {
        echo "Error: Session username not found!";
    }
} else {
    echo "Invalid request!";
}
?>