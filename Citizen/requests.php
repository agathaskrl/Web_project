<?php
include_once 'connect_db.php';
session_start();
?>

<!DOCTYPE html>
<html lan="en" and dir="ltr">
    <head>
        <meta charset="utf-8">
        <title> Requests </title>
        <link rel="stylesheet" href="requests.css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
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





<?php
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $query = "SELECT role FROM user WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['role'] = $row['role']; 
        // echo $_SESSION['role']; 
    }
}

function checkLoggedIn() {
  
    // Elegxos gia to an o xrhsths einai syndedemenos
    if (!isset($_SESSION['username'])) {
        echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
        echo 'User not logged in!';
        echo '</div>';
        exit(); 
    }
     // Elegxos gia to an o rolos tou xrhsth einai  "SAVIOR" h "ADMIN" kai aporich peraitero prosvashs
    if (isset($_SESSION['role']) && ($_SESSION['role'] == "SAVIOR" || $_SESSION['role'] == "ADMIN")) {
        echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
        echo 'Unauthorized access!';
        echo '</div>';
        exit(); 
    }
}

checkLoggedIn();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $allreq_product = "";
    $alldemand = "";

    // Ta original items
    $orreq_product = $_POST['req_product-input'];
    $ordemand = $_POST['demand-input'];

    if (!empty($orreq_product) && !empty($ordemand)) {
        $allreq_product .= ($allreq_product == "" ? "" : ",") . $orreq_product;
        $alldemand .= ($alldemand == "" ? "" : ",") . $ordemand;
    }


    $usrnm = $_SESSION['username'];
    //Query gia na fernei ta stoixeia toy xrhsth apo ton pinaka user 
    $query_user = "SELECT name, surname, phone FROM user WHERE username='$usrnm'";
    $result_user = mysqli_query($conn, $query_user);
    $user = mysqli_fetch_assoc($result_user);
  //Query gia na fernei ta coordinate stou xrhsth
    $query_coordinates = "SELECT lat, lng FROM coordinates WHERE username='$usrnm'";
    $result_coordinates = mysqli_query($conn, $query_coordinates);
    $coordinates = mysqli_fetch_assoc($result_coordinates);

    if ($user && $coordinates) {
        $civ_name = addslashes($user['name']); 
        $civ_surname = addslashes($user['surname']); 
        $civ_phone = addslashes($user['phone']); 
        $lat = $coordinates['lat'];
        $lng = $coordinates['lng'];

        // Eisagwgh twn dedomenwn ston pinaka offers
        $sql = "INSERT INTO requests (req_product, demand, civ_name, civ_surname, civ_phone, lat, lng) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $allreq_product, $alldemand, $civ_name, $civ_surname, $civ_phone, $lat, $lng);

        if ($stmt->execute()) {
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?><br>
<!-- koumpi gia selida me listes -->
    <a href="requestslist.php" >
    <button> Requests List </button></a>
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
