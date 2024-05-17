<?php
session_start();
include_once 'connect_db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $item = $_POST['item'];
    $quantity = $_POST['quantity'];


    $usrnm = $_SESSION['username'];


    $query = "SELECT name, surname, phone FROM user WHERE username='$usrnm'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    // Check if user information is fetched successfully
    if ($user) {
        $name = addslashes($user['name']); 
        $surname = addslashes($user['surname']); 
        $phone = addslashes($user['phone']); 

        // Fetch coordinates for the current sessions user from the coordinates table
        $coordsQuery = "SELECT lat, lng FROM coordinates WHERE username='$usrnm'";
        $coordsResult = mysqli_query($conn, $coordsQuery);
        $coords = mysqli_fetch_assoc($coordsResult);

        // Check if coordinates are fetched successfully
        if ($coords) {
            $lat = $coords['lat'];
            $lng = $coords['lng'];


            $sql = "INSERT INTO offers (item, quantity, name, surname, phone, lat, lng, usrnm_veh, ret_date, status) 
                    VALUES ('$item', '$quantity', '$name', '$surname', '$phone', '$lat', '$lng', '$userm_veh', '$ret_date', '$status')";

            // Execute SQL query
            if (mysqli_query($conn, $sql)) {
                echo "Offer made successfully!";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else {
            echo "Error: Coordinates not found!";
        }
    } else {
        echo "Error: User information not found!";
    }
} else {
    echo "Invalid request!";
}
