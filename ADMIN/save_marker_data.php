<?php
include_once 'connect_db.php';

//bring ht edata from the post
$postData = file_get_contents("php://input");

//json data
$data = json_decode($postData);

//data lan,lng 
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

