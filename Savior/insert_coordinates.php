<?php
session_start();
include_once 'connect_db.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "User not logged in.";
    exit;
}

//get the lat and lon 
$lat = $_POST['lat'];
$lng = $_POST['lng'];

//get the users id from user table
$username = $_SESSION['username'];
$sql_user = "SELECT id FROM user WHERE username = '$username'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc();
    $userid = $row_user['id'];

    //check id this current user has coordinates
    $sql_coordinates = "SELECT userid, lat, lng FROM coordinates WHERE userid = '$userid'";
    $result_coordinates = $conn->query($sql_coordinates);

    if ($result_coordinates->num_rows > 0) {
        $row_coordinates = $result_coordinates->fetch_assoc();
        $old_lat = $row_coordinates['lat'];
        $old_lng = $row_coordinates['lng'];

        //if coordinates are different then update them 
        if ($old_lat != $lat || $old_lng != $lng) {
            $sql_update = "UPDATE coordinates SET lat = '$lat', lng = '$lng' WHERE userid = '$userid'";
            if ($conn->query($sql_update) === TRUE) {
                echo "Coordinates updated successfully.";
            } else {
                echo "Error updating coordinates: " . $conn->error;
            }
        } else {
            echo "Coordinates are the same. No update needed.";
        }
    } else {
        //inert into the coordinates table 
        $sql_insert = "INSERT INTO coordinates (userid, username, lat, lng) VALUES ('$userid', '$username', '$lat', '$lng')";
        if ($conn->query($sql_insert) === TRUE) {
            echo "Coordinates saved successfully.";
        } else {
            echo "Error inserting coordinates: " . $conn->error;
        }
    }
} else {
    echo "User ID not found.";
}

$conn->close();
?>
