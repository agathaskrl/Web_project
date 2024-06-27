<?php

include_once 'connect_db.php';

// Elegxos gia to an o xrhsths einai syndedemenos
session_start();
if (!isset($_SESSION['username'])) {
    echo "User is not logged in";
    exit(); // Stop further execution
}

// Pairnoyme to username toy syndedomenou xrhsth
$username = $_SESSION['username'];

// painroume ta coordiantes toy syndendmenou xrhsth
$sql = "SELECT coordinates.lat, coordinates.lng 
        FROM coordinates 
        INNER JOIN user ON coordinates.username = user.username 
        WHERE user.role = 'citizen' AND user.username = '$username'"; 

$result = $conn->query($sql);

$markerData = array(); //Piankas gia ta coordinates

if ($result->num_rows > 0) {
    // Fernei kathe grammmh 
    while ($row = $result->fetch_assoc()) {
        $markerData[] = array("lat" => $row["lat"], "lng" => $row["lng"]);
    }
} else {
    echo "No marker coordinates found for the current session's citizen";
}

// Apokodikopoihsh ton pinaka san JSON 
echo json_encode($markerData);

//Kleisimo ths syndeshs
$conn->close(); 
?>
