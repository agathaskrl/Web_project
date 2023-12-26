<!DOCTYPE html>
<html lan="en" and dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Warehouse</title>
        <link rel="stylesheet" href="adstyle.css?v=4">
    </head>     
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

<div class="container">
      <h1>Items File Upload</h1>
      <form method="POST" enctype="multipart/form-data">
        <div>
          <label for="file">Select File:</label>
          <br>
          <br>
          <input type="file" id="file-selector" name="file" accept=".json" onclick="Upload()"/>
          <br>
          <br>
        <input type="submit" value="Upload"/> 
        <br>
        <div>
            <br>
        <button id="emptyitems">Empty Items Table</button>
        </div>
      </form>
    </div>
</body>

</html>
