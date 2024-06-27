<!DOCTYPE html>
<html lan="en" and dir="ltr">
<head>
    <meta charset="utf-8">
    <title>ANNOUNCEMENT</title>
    <link rel="stylesheet" href="adstyle.css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="adanscript.js"></script>
</head>
<body>

<div class="main">
    <div class="navbar">
        <ul> 
            <li><a href="adindex.php">HOME</a></li>  
            <li><a href="warehouse.php">WAREHOUSE</a></li>
            <li><a href="statistics.php">STATISTICS</a></li>
            <li><a href="savsignup.php">CREATE ACCOUNT</a></li>
            <li><a href="adminannouncement.php">ANNOUNCEMENT</a></li>
            <li><a href="logout.php">LOG OUT</a></li>              
        </ul>
    </div>

    <?php
    include_once 'connect_db.php';
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $query = "SELECT role FROM user WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['role'] = $row['role']; 
    }
}

function checkLoggedIn() {

    if (!isset($_SESSION['username'])) {
        echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
        echo 'User not logged in!';
        echo '</div>';
        exit(); 
    }
    

    if (isset($_SESSION['role']) && ($_SESSION['role'] == "SAVIOR" || $_SESSION['role'] == "CITIZEN")) {
        echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
        echo 'Unauthorized access!';
        echo '</div>';
        exit(); 
    }
}

checkLoggedIn();
?>
<form class="box" id="form-ann" method="POST">
        <h1>Create an announcement</h1>

      <div class="input-box" > 
       <label for="autocomplete"> Need for item:</label>
       <input type="text" id= "autocomplete" name="product-input" placeholder="ex. water" required>
      </div>

      <div class="input-box">
        <span class="details">Quantity</span>
        <input type="number" name="quantity-input" placeholder="Quantity" id="quantity" min=1 required> 
      </div>
        <br>

        <div class="button">
            <input type="submit" value="Create Announcement">
        </div>
       <br>
        <button type="button" id="addann" class="plus-btn"></button>

        <input type="hidden" id="hiddenInput" name="hiddenInput">

    </form>
  
</div>

</body>
</html>


<?php
include_once 'connect_db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //need this for counting dynamic values
    $valuejs = $_POST['hiddenInput'];

    $allitems = "";
    $allquan = "";

     //for dynamic form values
    for ($i = 0; $i <= $valuejs; $i++) {
        $item = $_POST['product-input-' . $i];
        $quantity = $_POST['quantity-input-' . $i];

        //get a comma between when values are more than one 
        if (!empty($item) && !empty($quantity)) {
            $allitems .= ($allitems == "" ? "" : ",") . $item;
            $allquan .= ($allquan == "" ? "" : ",") . $quantity;
        }
    }
    //for original items
    $oritems = $_POST['product-input'];
    $orquan = $_POST['quantity-input'];

    if (!empty($oritems) && !empty($orquan)) {
        $allitems .= ($allitems == "" ? "" : ",") . $oritems;
        $allquan .= ($allquan == "" ? "" : ",") . $orquan;
    }


    $sql = "INSERT INTO announcements (item, quantity) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $allitems, $allquan);

    if ($stmt->execute()) {
        //redirect when success
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit(); 
    } else {
        echo "Error: " . $stmt->error;
    }

    //close stmmt
    $stmt->close();
}
?>

