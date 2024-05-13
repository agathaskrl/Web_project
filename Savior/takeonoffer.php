<?php
session_start();
include_once 'connect_db.php';

$_POST = json_decode(file_get_contents('php://input'), true);

if(isset($_POST['offerId'])) {

    $offerId = $_POST['offerId'];
    
    if(isset($_SESSION['username'])) {
        $sav_usrnm = $_SESSION['username'];
        

        $curen_time = date('Y-m-d H:i:s');

        $sql = "UPDATE offers 
                SET usrnm_veh = '$sav_usrnm', ret_date = '$curen_time' 
                WHERE offer_id = $offerId";

        if($conn->query($sql) === TRUE) {
            if($conn->affected_rows > 0) {
                echo "Offer taken on successfully";
            } else {
                echo "Offer already taken or invalid offer ID.";
            }
        } else {
            echo "Error taking on offer: " . $conn->error;
        }
    } else {
        echo "Error: Session username not found!";
    }
} else {
    echo "Error: Offer ID not provided!";
}

// Close the database connection
$conn->close();

