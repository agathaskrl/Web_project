<!DOCTYPE html>
<html lan="en" and dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>ANNOUNCEMENT</title>
        <link rel="stylesheet" href="adstyle.css?v=3">
    </head>
    <body>     
    
   
    <div class="main">
            <div class="navbar">
            <ul> 
                    <li><a href="adindex.php">HOME</a></li>  
                        <li><a href="warehouse.php">WAREHOUSE</a></li>
                        <li><a href="statistics.php">STATISTICS</a></li>
                        <li><a href="savsignup.php">CREATE ACCOUNT</a></li>
                        <li><a href="announcement.php">ANNOUNCEMENT</a></li>
                        <li><a href="logout.php">LOG OUT</a></li>              
                    </ul>
                </div>
                </head>
<body>
<?php
// Function to check if the user is logged in
function checkLoggedIn() {
    session_start();
    if (!isset($_SESSION['username'])) {
        echo '<div style="text-align: center; padding: 80px; background-color: rgb(247, 240, 235); color: rgba(76, 56, 30, 1); ">';
        echo 'User not logged in. Please <a href="login.php">Log in!</a>.';
        echo '</div>';
        exit(); // Exit the script
    }
}
checkLoggedIn(); // Call the function to check if the user is logged in
?>
<br>
<button onclick="history.back()"> <i class="arrow left"></i>Go Back</button>
<br>
<br>

<form class = "box" method="POST">
    <h1> 
      Create an announcement
    </h1>

      <div class="input-box">
      <span class="details">Need for Item</span>
      <input type="text" name="item" placeholder="Item" id="item" required>
      </div>

      <div class="input-box">
        <span class="details">Quantity</span>
        <input type="number" name="name" placeholder="Quantity" id="quantity" required> 
      </div>
   <br>
   

   <div class="button">
        <input type="submit" value="Create Announcement">
    </div>

</form>

</body>
