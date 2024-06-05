<?php
include_once 'connect_db.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(["error" => "User is not logged in"]);
    exit(); 
}

$sql = 'SELECT name, quantity FROM products'; 

$items = []; 
$result = $conn->query($sql); 
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row; 
    }
    $result->free(); 
} else {
    echo json_encode(["error" => $conn->error]); // Log SQL query error
    exit();
}
echo json_encode(["items" => $items]);