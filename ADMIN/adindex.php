<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Admin Index</title>
    <link rel="stylesheet" href="adstyle.css?">
    <!-- Leaflet CSS && JavaScript files -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    // Elegxos gia to an o xrhsths einai syndedemenos
    if (!isset($_SESSION['username'])) {
        echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
        echo 'User not logged in!';
        echo '</div>';
        exit(); 
    }
    
    // Elegxos gia to an o rolos tou xrhsth einai  "SAVIOR" h "CITIZEN" kai aporich peraitero prosvashs
    if (isset($_SESSION['role']) && ($_SESSION['role'] == "SAVIOR" || $_SESSION['role'] == "CITIZEN")) {
        echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
        echo 'Unauthorized access!';
        echo '</div>';
        exit(); 
    }
}

checkLoggedIn();
?>
    //mhnhma kalwsorismatos me to username tou xrhsth
    <div class="welcome-message">
        <?php
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            echo "Welcome, $username!";
        } 
        ?>
    </div>
    <br>

    <button id="dropdownButton" class="dropbtn">Filter Map</button>
            <div id="dropdownContent" class="dropdown-content">
                <label><input type="checkbox" id="showOpenOffers" checked> Show Open Offers</label>
                <label><input type="checkbox" id="showTakenOffers" checked> Show Taken Offers</label>
                <label><input type="checkbox" id="showOpenRequests" checked> Show Open Requests</label>
                <label><input type="checkbox" id="showUndertakenRequests" checked> Show Undertaken Requests</label>
                <label><input type="checkbox" id="showAvailableVehicles" checked> Show Available Vehicles</label>
                <label><input type="checkbox" id="showOccupiedVehicles" checked> Show Occupied Vehicles</label>
                <label><input type="checkbox" id="showLines" checked> Show Lines</label>

            </div>
    <div class="container"> 
        
        </div>
     
    <div class="map" id="map" style="width: 100%; height: 450px;"></div>

    <script src="adminmap.js"></script>
    </div>



<script>
    document.addEventListener("DOMContentLoaded", function () {
        const welcomeMessage = document.querySelector('.welcome-message');

        // Emfanise to welcome message
        welcomeMessage.style.display = 'block';

        // Vazei timeout 30 seconds gia to mhnyma
        setTimeout(function () {
            welcomeMessage.style.display = 'none';
        }, 30000);
    });
</script>
</body>
</html>
