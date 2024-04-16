<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Admin Index</title>
    <link rel="stylesheet" href="adstyle.css">
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
        // echo $_SESSION['role']; 
    }
}

function checkLoggedIn() {
    // Check if the user is not logged in
    if (!isset($_SESSION['username'])) {
        echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
        echo 'User not logged in!';
        echo '</div>';
        exit(); 
    }
    
    // Check if the user's role is "SAVIOR" or "ADMIN", and deny access
    if (isset($_SESSION['role']) && ($_SESSION['role'] == "SAVIOR" || $_SESSION['role'] == "CITIZEN")) {
        echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
        echo 'Unauthorized access!';
        echo '</div>';
        exit(); 
    }
}

checkLoggedIn();
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
    <button><i class="filtermap"></i>Filter Map</button>
     
    <div class="map" id="map" style="width: 100%; height: 450px;"></div>

    <script src="adminmap.js"></script>
    </div>



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
