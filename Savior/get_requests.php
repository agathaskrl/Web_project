<?php
include_once 'connect_db.php';

$sql = "SELECT req_id, civ_name, civ_surname, civ_phone, req_product, demand, lat, lng, veh_username FROM requests";
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {

    $requests = array();

    // Fetch and store each request
    while ($row = $result->fetch_assoc()) {
        $request = array(
            'req_id' => $row['req_id'],
            'civ_name' => $row['civ_name'],
            'civ_surname' => $row['civ_surname'],
            'civ_phone' =>$row['civ_phone'],
            'req_product' => $row['req_product'],
            'demand' => $row['demand'],
            'lat' => $row['lat'],
            'lng' => $row['lng'],
            'veh_username' => $row['veh_username'] 
        );
        // Add the request to the requests array
        $requests[] = $request;
    }

    // Convert requests array to JSON format
    $jsonRequests = json_encode($requests);

    // Output JSON data
    header('Content-Type: application/json');
    echo $jsonRequests;
} else {
    // No requests found
    echo json_encode(array('message' => 'No requests found'));
}
?>