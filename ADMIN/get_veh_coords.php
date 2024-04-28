<?php
include_once 'connect_db.php';

$sql = 'SELECT lat, lng FROM coordinates INNER JOIN user ON user.username = coordinates.username WHERE user.role = "SAVIOR"';
$result = $conn->query($sql);

$coords_vehicles = array();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $coords_vehicles[] = array("lat" => $row["lat"], "lng" => $row["lng"]);
    }
    echo json_encode($coords_vehicles);
} else {
    echo json_encode(array()); 
}
$conn->close();

