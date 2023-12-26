<!DOCTYPE html>
<html lan="en" and dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Warehouse</title>
        <link rel="stylesheet" href="adstyle.css?v=4">
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
<div class="button-container">
      <button><i class="filterwarehouse"></i>Filter Warehouse</button>
      <button onclick="location.href='updatewarehouse.php'"><i class="Updatewarehouse"></i>Update Warehouse</button>

    </div>
<br>
<div class="form-container">
  <form>
    <div class="form-group">
      <label for="item">Item Name:</label>
      <input type="text" id="itemname" name="itemnaame">
    </div>

    <div class="form-group">
      <label for="itemctgr">Item Category:</label>
      <input type="text" id="itemctgr" name="itemctgr" >
    </div>

    <div class="form-group">
      <label for="quantity">Quantity:</label>
      <input type="quantity" id="quantity" name="quantity" >
    </div>

    <div class="form-group">
      <label for="isinveh">On Vehicle:</label>
      <input type="isinvehy" id="isinveh" name="isnveh" >
    </div>
    <div class="form-group">
    </div>
  </form>
</div>

</body>

</html>
