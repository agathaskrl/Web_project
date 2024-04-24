<?php
include_once 'connect_db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "User is not logged in";
    exit(); // Stop further execution
}

// Get the current session's username
$username = $_SESSION['username'];

// Fetch the savior's coordinates from the database
$sql = "SELECT lat, lng FROM coordinates WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if any coordinates were found
if ($result->num_rows > 0) {
    // Fetch the coordinates and encode them as JSON
    $row = $result->fetch_assoc();
    $coordinates = array("lat" => $row["lat"], "lng" => $row["lng"]);
    echo json_encode($coordinates);
} else {
    // If no coordinates were found, return an error message
    echo "No savior coordinates found for user: $username";
}

// Close prepared statement and database connection
$stmt->close();
$conn->close();
?>