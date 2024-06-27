<?php
session_start();
include_once 'connect_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ann_id = intval($_POST['ann_id']);
    $item = $_POST['item'];
    $quantity = $_POST['quantity'];
    $subm_date = $_POST['subm_date'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $phone = $_POST['phone'];

    $usrnm = $_SESSION['username'];
   //Query gia na fernei ta stoixeia tou syndedemenou xrhsh
    $query = "SELECT name, surname, phone FROM user WHERE username='$usrnm'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $name = addslashes($user['name']); 
        $surname = addslashes($user['surname']); 
        $phone = addslashes($user['phone']); 

        // Query gia na fenrei ta coordinates tou xrhsth
        $coordsQuery = "SELECT lat, lng FROM coordinates WHERE username='$usrnm'";
        $coordsResult = mysqli_query($conn, $coordsQuery);
        $coords = mysqli_fetch_assoc($coordsResult);

        
        if ($coords) {
            $lat = $coords['lat'];
            $lng = $coords['lng'];
            //Eisagwgh twn deomenwn sto offers
            $sql = "INSERT INTO offers (item, quantity, name, surname, cit_username, phone, lat, lng) 
                    VALUES ('$item', '$quantity', '$name', '$surname', '$usrnm' ,'$phone', '$lat', '$lng')";

           
            if (mysqli_query($conn, $sql)) {
                //Update to announcements an egine offer
                $sql2 = "UPDATE announcements SET offer_made = 'YES' WHERE ann_id = $ann_id";
                if (mysqli_query($conn, $sql2)) {
                    echo "Offer made successfully!";
                } else {
                    echo "Error updating offer_made field: " . mysqli_error($conn);
                }
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

$conn->close();
