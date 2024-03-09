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

    $sql = "INSERT INTO requests (req_product, demand) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $allreq_product, $alldemand);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>