<?php
session_start();
include_once 'connect_db.php';

if (!isset($_SESSION['username'])) {
    echo "User not logged in.";
    exit;
}

//Pairnei ta lat ki lng toy xrhsth
$lat = $_POST['lat'];
$lng = $_POST['lng'];

//Pairnei to users id apo ton pinaka user
$username = $_SESSION['username'];
$sql_user = "SELECT id FROM user WHERE username = '$username'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc();
    $userid = $row_user['id'];

    //Elegxos me to id an yparxoun hdh coordinates
    $sql_coordinates = "SELECT userid, lat, lng FROM coordinates WHERE userid = '$userid'";
    $result_coordinates = $conn->query($sql_coordinates);

    if ($result_coordinates->num_rows > 0) {
        $row_coordinates = $result_coordinates->fetch_assoc();
        $old_lat = $row_coordinates['lat'];
        $old_lng = $row_coordinates['lng'];

        //An einai diaforetikes tis ananewnei  
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
        //An den exei katholou bazei ta coordinates
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
