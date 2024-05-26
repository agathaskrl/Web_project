<?php
include_once 'connect_db.php';

// fetch offers from the database
$sql = "SELECT offer_id, name, surname, phone, item, quantity, lat, lng, usrnm_veh, subm_date, ret_date, status FROM offers";
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Initialize an array to store offers
    $offers = array();

    // Fetch and store each offer
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
        // Add the offer to the offers array
        $offers[] = $offer;
    }

    // Convert offers array to JSON format
    $jsonOffers = json_encode($offers);

    // Output JSON data
    header('Content-Type: application/json');
    echo $jsonOffers;
} else {
    echo json_encode(array('message' => 'No offers found'));
}

