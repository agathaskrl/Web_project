<!DOCTYPE html>
<html lan="en" and dir="ltr">
    <head>
        <meta charset="utf-8">
        <title> Requests </title>
        <link rel="stylesheet" href="requests.css?v=12" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="script.js"></script>

</head>

<body>
<div class="main"> 
    <div class="navbar">
        <ul>
            <li><a href= "homepolitis.php">HOME</a></li>
            <li><a href= "requests.php">REQUESTS</a></li>
            <li><a href= "announcements.php">ANNOUNCEMENTS</a></li>
            <li><a href= "offers.php">OFFERS</a></li>
            <li><a href="logout.php">LOGOUT</a></li>
        </ul>
    </div>
</div>

<br>
<!-- koumpi gia selida me listes -->
    <a href="requestslist.php" >
    <button> REQUESTS LIST </button></a>
</br>
<!-- box formas -->
<form class="box" method="POST">

<h1> Create a new request </h1>
<div class="input-box">
<label for="autocomplete"> Item:</label>
<input type="text" id= "autocomplete" name="req_product-input" placeholder="ex. water" required>

</div>
<div class="input-box">
<label for="demand"> People in need:</label>
<input type="number" id="demand" name="demand-input" placeholder="ex. 30" required>
</div>


<div class="button">
        <input type="submit" value="Submit Request">
    </div>
</form>


</body>

<?php
session_start(); // Start the session if it's not started already
include_once 'connect_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $allreq_product = "";
    $alldemand = "";

    // Original items
    $orreq_product = $_POST['req_product-input'];
    $ordemand = $_POST['demand-input'];

    if (!empty($orreq_product) && !empty($ordemand)) {
        $allreq_product .= ($allreq_product == "" ? "" : ",") . $orreq_product;
        $alldemand .= ($alldemand == "" ? "" : ",") . $ordemand;
    }

    // Retrieve username from session
    $usrnm = $_SESSION['username'];

    // Fetch user information from the database
    $query = "SELECT name, surname, phone FROM user WHERE username='$usrnm'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    // Check if user information is fetched successfully
    if ($user) {
        $civ_name = addslashes($user['name']); // Escape special characters
        $civ_surname = addslashes($user['surname']); // Escape special characters
        $civ_phone = addslashes($user['phone']); // Escape special characters

        // Insert data into the requests table
        $sql = "INSERT INTO requests (req_product, demand, civ_name, civ_surname, civ_phone) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $allreq_product, $alldemand, $civ_name, $civ_surname, $civ_phone);

        if ($stmt->execute()) {
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>