<?php
include_once 'connect_db.php';
session_start();


function debug_log($message) {
    error_log($message . "\n", 3, 'debug.log');
}

if (!isset($_SESSION['username'])) {
    echo json_encode(["error" => "User is not logged in"]);
    debug_log("Error: User is not logged in");
    exit(); 
}

$username = $_SESSION['username'];


$input = file_get_contents('php://input');
debug_log("Received input: " . $input);
$itemsToTake = json_decode($input, true)['items'];

$sql = "SELECT cargo, items FROM vehicle WHERE sav_username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    echo json_encode(["error" => "Failed to fetch cargo and items data"]);
    debug_log("Error: Failed to fetch cargo and items data");
    exit(); 
}

$row = $result->fetch_assoc();
$currentCargo = $row['cargo'];
$currentItems = $row['items'];

// calculate the new quantity 
$newCargo = $currentCargo;
foreach ($itemsToTake as $item) {
    $newCargo += $item['quantity'];
}

// Append new items to the existing items string with quantity
$newItemsArray = explode(', ', trim($currentItems, ', '));
foreach ($itemsToTake as $item) {
    $newItemsArray[] = $item['name'];
}
$newItems = implode(', ', array_unique($newItemsArray));

// Check product quantities and ensure enough stock is available
foreach ($itemsToTake as $item) {
    $productCheck = "SELECT quantity FROM products WHERE name = ?";
    $productCheckStmt = $conn->prepare($productCheck);
    $productCheckStmt->bind_param("s", $item['name']);
    $productCheckStmt->execute();
    $productCheckResult = $productCheckStmt->get_result();
    if ($productCheckResult->num_rows > 0) {
        $productRow = $productCheckResult->fetch_assoc();
        if ($productRow['quantity'] < $item['quantity']) {
            echo json_encode(["error" => "Not enough stock for " . $item['name']]);
            debug_log("Error: Not enough stock for " . $item['name']);
            exit();
        }
    } else {
        echo json_encode(["error" => "Product " . $item['name'] . " not found"]);
        debug_log("Error: Product " . $item['name'] . " not found");
        exit();
    }
}

// Update the products table to decrement quantities
foreach ($itemsToTake as $item) {
    $productUpdate = "UPDATE products SET quantity = quantity - ? , on_vehicle = 'YES' WHERE name = ?";
    $productStmt = $conn->prepare($productUpdate);
    $productStmt->bind_param("is", $item['quantity'], $item['name']);
    $productUpdateResult = $productStmt->execute();

    if (!$productUpdateResult) {
        echo json_encode(["error" => "Failed to update product quantity for " . $item['name']]);
        debug_log("Error: Failed to update product quantity for " . $item['name']);
        exit();
    }
}

// Update the vehicle table 
$update = "UPDATE vehicle SET cargo = ?, items = ? WHERE sav_username = ?";
$updateStmt = $conn->prepare($update);
$updateStmt->bind_param("iss", $newCargo, $newItems, $username);
$updateres = $updateStmt->execute();

if (!$updateres) {
    echo json_encode(["error" => "Failed to update cargo and items data"]);
    debug_log("Error: Failed to update cargo and items data");
    exit(); 
}

echo json_encode(["message" => "Items added to cargo successfully and product quantities updated"]);
debug_log("Success: Items added to cargo and product quantities updated");


