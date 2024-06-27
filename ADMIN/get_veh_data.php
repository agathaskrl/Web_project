<?php
include_once 'connect_db.php';

$sql = 'SELECT vehicle.sav_username, vehicle.cargo, vehicle.under_tasks, coordinates.lat, coordinates.lng
   FROM vehicle
   INNER JOIN user ON vehicle.sav_username = user.username
   INNER JOIN coordinates ON vehicle.sav_username = coordinates.username';

$result = $conn->query($sql);

if ($result) {
    $vehicleData = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $vehicleData[] = $row;
        }
        echo json_encode($vehicleData);
    } else {
        echo json_encode(array()); // No data found
    }
} else {
    echo json_encode(array('error' => $conn->error));
}

$conn->close();
?>
