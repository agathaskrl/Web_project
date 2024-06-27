<?php
include_once 'connect_db.php';
session_start();

function debug_log($message) {
    error_log($message . "\n", 3, 'debug.log');
}

// elegxos an einai o xristis logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(["error" => "User is not logged in"]);
    debug_log("Error: User is not logged in");
    exit(); 
}

$username = $_SESSION['username'];
$data = json_decode(file_get_contents('php://input'), true);

// elegxos an yparxei 'items' sta JSON data
if (!isset($data['items'])) {
    echo json_encode(["error" => "No items data provided"]);
    debug_log("Error: No items data provided");
    exit(); 
}

$items = $data['items']; // dinei timi sto $items

// update query gia na kanei update to quantity ston pinaka products
$updateQuery = "UPDATE products SET quantity = quantity + ?, on_vehicle = NULL WHERE name = ?";
$updateStmt = $conn->prepare($updateQuery);
$deleteQuery = "UPDATE vehicle SET cargo = NULL, items = NULL WHERE sav_username = ?";
$deleteStmt = $conn->prepare($deleteQuery);

if ($updateStmt && $deleteStmt) {
    foreach ($items as $item) {
        $name = $item['name'];
        $cargo = $item['cargo'];

        // tyxaia dianomi tis posotitas tou cargo se pollapla entries proiontwn
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

    // adiasma items kai cargo apo ton pinaka vehicle
    $deleteStmt->bind_param("s", $username);
    if (!$deleteStmt->execute()) {
        echo json_encode(['error' => 'Failed to delete from vehicle']);
        debug_log("Error deleting from vehicle: " . $deleteStmt->error);
        exit();
    }

    // apantisi JSON gia epityximeno minima
    $successResponse = json_encode(['success' => 'Items updated in products and deleted from vehicle']);
    echo $successResponse;
} else {
//apantisi json gia minima error
    echo json_encode(['error' => 'Failed to prepare update or delete statement']);
    debug_log("Error preparing update or delete statement: " . $conn->error);
}

// kleinei ta statements
$updateStmt->close();
$deleteStmt->close();

// kleinei syndesi me th vash
mysqli_close($conn);
