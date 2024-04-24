<?php
include_once 'connect_db.php';

//fere apo to post
$postData = file_get_contents("php://input");

//json data
$data = json_decode($postData);

//fere ta dedomena 
$lat = $data->lat;
$lng = $data->lng;

$stmt = $conn->prepare("UPDATE vash_marker SET lat=?, lng=? WHERE id=1"); 
$stmt->bind_param("dd", $lat, $lng);

if ($stmt->execute()) {
    echo "Marker data saved successfully";
} else {
    echo "Failed to save marker data: " . $stmt->error;
}
$stmt->close();

