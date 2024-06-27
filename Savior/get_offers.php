<?php
include_once 'connect_db.php';

// fetch offers apo vash
$sql = "SELECT offer_id, name, surname, phone, item, quantity, lat, lng, usrnm_veh, subm_date, ret_date, status FROM offers";
$result = $conn->query($sql);

// Check an uparxoun apotelesmata
if ($result->num_rows > 0) {
    // Initialize an array to store offers
    $offers = array();

    // Fetch kai apothikeusi kathe offer
    while ($row = $result->fetch_assoc()) {
        $offer = array(
            'offer_id' => $row['offer_id'], 
            'name' => $row['name'],
            'surname' => $row['surname'],
            'phone' => $row['phone'],
            'item' => $row['item'],
            'quantity' => $row['quantity'],
            'lat' => $row['lat'],
            'lng' => $row['lng'],
            'usrnm_veh' => $row['usrnm_veh'], 
            'subm_date' => $row['subm_date'],
            'ret_date' => $row['ret_date'], 
            'status' => $row['status'], 
        );
        // prosthiki tou offer ston pinaka offers
        $offers[] = $offer;
    }

    // metatropi tou offers pinaka se JSON morfi
    $jsonOffers = json_encode($offers);

    // ektupwsi JSON data
    header('Content-Type: application/json');
    echo $jsonOffers;
} else {
    echo json_encode(array('message' => 'No offers found'));
}

