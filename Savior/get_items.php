<?php
include_once 'connect_db.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(["error" => "User is not logged in"]);
    exit(); 
}

$username = $_SESSION['username'];
$items = [];


$sql_vehicle_items = "SELECT items FROM vehicle WHERE sav_username = ?";
$stmt_vehicle_items = $conn->prepare($sql_vehicle_items);
$stmt_vehicle_items->bind_param("s", $username);
$stmt_vehicle_items->execute();
$result_vehicle_items = $stmt_vehicle_items->get_result();
while ($row = $result_vehicle_items->fetch_assoc()) {
    $items[] = $row['items'];
}
$stmt_vehicle_items->close();


$sql_offer_items = "SELECT item FROM offers WHERE usrnm_veh = ? AND status = 'ONGOING'";
$stmt_offer_items = $conn->prepare($sql_offer_items);
$stmt_offer_items->bind_param("s", $username);
$stmt_offer_items->execute();
$result_offer_items = $stmt_offer_items->get_result();
while ($row = $result_offer_items->fetch_assoc()) {
    $items[] = $row['item'];
}
$stmt_offer_items->close();


$sql_req_products = "SELECT req_product FROM requests WHERE veh_username = ? AND status = 'ONGOING'";
$stmt_req_products = $conn->prepare($sql_req_products);
$stmt_req_products->bind_param("s", $username);
$stmt_req_products->execute();
$result_req_products = $stmt_req_products->get_result();
while ($row = $result_req_products->fetch_assoc()) {
    $items[] = $row['req_product'];
}
$stmt_req_products->close();
$conn->close();

$output = [
    'items' => $items
];

echo json_encode($output);
