<?php

include_once 'connect_db.php';
session_start();

function debug_log($message) {
    error_log($message);
}

if (!isset($_SESSION['username'])) {
    echo json_encode(["error" => "User is not logged in"]);
    debug_log("Error: User is not logged in");
    exit(); 
}

$username = $_SESSION['username'];
$query = "SELECT items, cargo FROM vehicle WHERE sav_username = ?";
$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("s", $username);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $items = []; 
        if ($result->num_rows > 0) { 
            while ($row = $result->fetch_assoc()) {
                $itemsArray = explode(',', $row['items']);
                foreach ($itemsArray as $item) {
                    $items[] = ['name' => trim($item), 'cargo' => $row['cargo']];
                }
            }
        } else {
            echo json_encode(["error" => "No rows fetched"]); 
            debug_log("No rows fetched");
            exit();
        }

        // Construct JSON response for items
        $itemsResponse = json_encode(['items' => $items]); 
        echo $itemsResponse;
    } else {
        echo json_encode(["error" => "Failed to execute the query"]);
        debug_log("Error: Failed to execute the query");
    }
} else {
    echo json_encode(["error" => "Failed to prepare the statement"]);
    debug_log("Error: Failed to prepare the statement");
}

mysqli_close($conn);


