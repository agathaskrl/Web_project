<?php
include_once 'connect_db.php';
session_start();

// elegxos an o user einai logged in
if (!isset($_SESSION['username'])) {
    echo "User is not logged in";
    exit(); // Stop further execution
}

// Pairnei to username tou xristi tou trexontos session
$username = $_SESSION['username'];

// pairnei tis syntetagmenes toy savior apo ti vash
$sql = "SELECT lat, lng FROM coordinates WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// elegxos an vrethikan syntetagmenes
if ($result->num_rows > 0) {
    // Fetch tis syntetagmenes kai metatropi tous se JSON
    $row = $result->fetch_assoc();
    $coordinates = array("lat" => $row["lat"], "lng" => $row["lng"]);
    echo json_encode($coordinates);
} else {
    // error message an den brethikan systetagmenes
    echo "No savior coordinates found for user: $username";
}

// Kleinei to statement kai i syndesi me vash
$stmt->close();
$conn->close();
?>
