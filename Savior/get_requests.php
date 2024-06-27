<?php
include_once 'connect_db.php';

$sql = "SELECT req_id, civ_name, civ_surname, civ_phone, req_product, demand, lat, lng, veh_username,req_date, under_date, status FROM requests";
$result = $conn->query($sql);

// Check an uparxoun apotelesmata
if ($result->num_rows > 0) {

    $requests = array();

    // Fetch kai apothikeusi kathe request
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
            'veh_username' => $row['veh_username'], 
            'req_date'=> $row['req_date'],
            'under_date' => $row['under_date'],
            'status' => $row['status'], 
        );
        // prosthiki tou request ston pinaka requests
        $requests[] = $request;
    }

    // metatropi tou pinaka requests se JSON morfi
    $jsonRequests = json_encode($requests);

    // ektypwsi JSON data
    header('Content-Type: application/json');
    echo $jsonRequests;
} else {
    // minima No requests found
    echo json_encode(array('message' => 'No requests found'));
}
?>
