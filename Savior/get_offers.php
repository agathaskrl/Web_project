<?php
include_once 'connect_db.php';

// Query to fetch offers from the database
$sql = "SELECT item, quantity, lat, lng FROM offers";
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Initialize an array to store offers
    $offers = array();

    // Fetch and store each offer
    while ($row = $result->fetch_assoc()) {
        $offer = array(
            'item' => $row['item'],
            'quantity' => $row['quantity'],
            'lat' => $row['lat'],
            'lng' => $row['lng']
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
    // No offers found
    echo json_encode(array('message' => 'No offers found'));
}
?>