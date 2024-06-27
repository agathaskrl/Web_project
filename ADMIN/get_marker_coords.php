<?php

include_once 'connect_db.php';
//Query gia na ferei ta coordinates apo to pinaka vash_marker gia to marker ths vashs
$sql = "SELECT lat, lng FROM vash_marker WHERE id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $markcoords = array("lat" => $row["lat"], "lng" => $row["lng"]);
    echo json_encode($markcoords);
} else {
    echo "No marker coordinates found";
}
$conn->close();
