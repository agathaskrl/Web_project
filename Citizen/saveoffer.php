<?php
session_start();
include_once 'connect_db.php';

// Check if data is received via POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve item and quantity from POST data
    $item = $_POST['item'];
    $quantity = $_POST['quantity'];

    // Retrieve username from session
    $usrnm = $_SESSION['username'];

    // Fetch user information from the database
    $query = "SELECT name, surname, phone FROM user WHERE username='$usrnm'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    // Check if user information is fetched successfully
    if ($user) {
        $name = addslashes($user['name']); // Escape special characters
        $surname = addslashes($user['surname']); // Escape special characters
        $phone = addslashes($user['phone']); // Escape special characters

        // Example of inserting data into a database table
        $sql = "INSERT INTO offers (product_type, quantity, name, surname, phone) VALUES ('$item', '$quantity', '$name', '$surname', '$phone')";

        // Execute SQL query
        if (mysqli_query($conn, $sql)) {
            echo "Offer made successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "Error: User information not found!";
    }
} else {
    echo "Invalid request!";
}
?>