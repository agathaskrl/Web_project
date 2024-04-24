<?php

include_once 'connect_db.php';

// Check if a user is logged in
session_start();
if (!isset($_SESSION['username'])) {
    echo "User is not logged in";
    exit(); // Stop further execution
}

// Get the current session's username
$username = $_SESSION['username'];

// Get the coordinates for the current session's savior from the database
$sql = "SELECT coordinates.lat, coordinates.lng 
        FROM coordinates 
        INNER JOIN user ON coordinates.username = user.username 
        WHERE user.role = 'citizen' AND user.username = '$username'"; // Assuming 'username' is the session identifier

$result = $conn->query($sql);

$markerData = array(); // Initialize an array to hold the coordinates

if ($result->num_rows > 0) {
    // Fetch each row and store it in the array
    while ($row = $result->fetch_assoc()) {
        $markerData[] = array("lat" => $row["lat"], "lng" => $row["lng"]);
    }
} else {
    echo "No marker coordinates found for the current session's citizen";
}

// Encode the array as JSON and output it
echo json_encode($markerData);

// Close the database connection
$conn->close(); 
?>