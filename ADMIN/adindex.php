<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Your Title Here</title>
    <link rel="stylesheet" href="adstyle.css?v=4">
    <!-- Leaflet CSS and JavaScript files -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
    <!-- For search -->
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css"/>
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

    <div class="welcome-message">
        <?php
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            echo "Welcome, $username!";
        } 
        ?>
    </div>
    <br>
    <div class="container"> 
        <input type="text" id="searchInput" placeholder="Search...">
        <button>Search</button>
    </div>
    <button><i class="filtermap"></i>Filter Map</button>
    <br>
    <br>
    <div class="map" id="map" style="width: 100%px; height: 300px;"></div>
    <br>
    <br>
    <script src="map.js"></script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const welcomeMessage = document.querySelector('.welcome-message');

        // Show the welcome message
        welcomeMessage.style.display = 'block';

        // Set a timeout to hide the welcome message after 30 seconds
        setTimeout(function () {
            welcomeMessage.style.display = 'none';
        }, 30000);
    });
</script>

</body>
</html>
