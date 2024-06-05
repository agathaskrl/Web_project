<?php
include_once 'connect_db.php';
session_start();

function debug_log($message) {
    error_log($message . "\n", 3, 'debug.log');
}

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(["error" => "User is not logged in"]);
    debug_log("Error: User is not logged in");
    exit(); 
}

$username = $_SESSION['username'];
$data = json_decode(file_get_contents('php://input'), true);

// Check if 'items' key exists in the JSON data
if (!isset($data['items'])) {
    echo json_encode(["error" => "No items data provided"]);
    debug_log("Error: No items data provided");
    exit(); 
}

$items = $data['items']; // Assign the value to $items

// Prepare the update query to update the quantity in the products table
$updateQuery = "UPDATE products SET quantity = quantity + ?, on_vehicle = NULL WHERE name = ?";
$updateStmt = $conn->prepare($updateQuery);
$deleteQuery = "UPDATE vehicle SET cargo = NULL, items = NULL WHERE sav_username = ?";
$deleteStmt = $conn->prepare($deleteQuery);

if ($updateStmt && $deleteStmt) {
    foreach ($items as $item) {
        $name = $item['name'];
        $cargo = $item['cargo'];

        // Randomly distribute the cargo quantity into multiple products entries
        while ($cargo > 0) {
            $quantity = rand(1, min($cargo, 100)); 
            $updateStmt->bind_param("is", $quantity, $name);
            if (!$updateStmt->execute()) {
                echo json_encode(['error' => 'Failed to update products']);
                debug_log("Error updating products: " . $updateStmt->error);
                exit();
            }
            $cargo -= $quantity;
        }
    }

    // Clear items and cargo from vehicle table
    $deleteStmt->bind_param("s", $username);
    if (!$deleteStmt->execute()) {
        echo json_encode(['error' => 'Failed to delete from vehicle']);
        debug_log("Error deleting from vehicle: " . $deleteStmt->error);
        exit();
    }

    // Construct JSON response for success message
    $successResponse = json_encode(['success' => 'Items updated in products and deleted from vehicle']);
    echo $successResponse;
} else {
    echo json_encode(['error' => 'Failed to prepare update or delete statement']);
    debug_log("Error preparing update or delete statement: " . $conn->error);
}

// Close prepared statements
$updateStmt->close();
$deleteStmt->close();

// Close database connection
mysqli_close($conn);
