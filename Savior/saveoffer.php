<?php
session_start();
include_once 'connect_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item = $_POST['item'];
    $quantity = $_POST['quantity'];
    $subm_date = $_POST['subm_date'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $phone = $_POST['phone'];

    $usrnm = $_SESSION['username'];

    $query = "SELECT name, surname, phone FROM user WHERE username='$usrnm'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    // elegxos an pairnei ta stoixeia tou xristi
    if ($user) {
        $name = addslashes($user['name']); 
        $surname = addslashes($user['surname']); 
        $phone = addslashes($user['phone']); 

        // Fetch syntetagmenes apo to session tou xristi pou apothikeyontai ston pinaka coordinates sti vash
        $coordsQuery = "SELECT lat, lng FROM coordinates WHERE username='$usrnm'";
        $coordsResult = mysqli_query($conn, $coordsQuery);
        $coords = mysqli_fetch_assoc($coordsResult);

        // elegxos an pairnei swsta tis syntetagmenes
        if ($coords) {
            $lat = $coords['lat'];
            $lng = $coords['lng'];

            $sql = "INSERT INTO offers (item, quantity, name, surname, phone, lat, lng) 
                    VALUES ('$item', '$quantity', '$name', '$surname', '$phone', '$lat', '$lng')";

            // ektelesi SQL query me minimata elegxou
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

// kleinei h syndesh me ti vash
$conn->close();
?>
