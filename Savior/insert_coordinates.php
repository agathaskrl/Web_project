<?php
session_start();
include_once 'connect_db.php';

// elegxos an o xristis einai logged in
if (!isset($_SESSION['username'])) {
    echo "User not logged in.";
    exit;
}

//pairnei ta lat kai lon 
$lat = $_POST['lat'];
$lng = $_POST['lng'];

//pairnei to id tou xristi apo ton pinaka users sti vash
$username = $_SESSION['username'];
$sql_user = "SELECT id FROM user WHERE username = '$username'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc();
    $userid = $row_user['id'];

    //elegxos an uparxoun syntetagmenes tou xristi sti vash
    $sql_coordinates = "SELECT userid, lat, lng FROM coordinates WHERE userid = '$userid'";
    $result_coordinates = $conn->query($sql_coordinates);

    if ($result_coordinates->num_rows > 0) {
        $row_coordinates = $result_coordinates->fetch_assoc();
        $old_lat = $row_coordinates['lat'];
        $old_lng = $row_coordinates['lng'];

        //update tis syntetagmenes an einai diaforetikes apo aytes sti vash 
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
        //eisagwgi ston pinaka coordinates 
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
