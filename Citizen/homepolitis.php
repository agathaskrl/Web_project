<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Home</title>
    <link rel="stylesheet" href="home_style.css">
    <!-- Leaflet CSS and JavaScript files -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
        integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
        integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
    <!--Search-->
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
</head>

<body>
    <div class="main">
        <div class="navbar">
            <ul>
                <li><a href="homepolitis.php">HOME</a></li>
                <li><a href="requests.php">REQUESTS</a></li>
                <li><a href="announcements.php">ANNOUNCEMENTS</a></li>
                <li><a href="offers.php">OFFERS</a></li>
                <li><a href="logout.php">LOGOUT</a></li>
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
    
    // Elegxos gia to an o rolos tou xrhsth einai  "SAVIOR" h "ADMIN" kai aporich peraitero prosvashs
    if (isset($_SESSION['role']) && ($_SESSION['role'] == "SAVIOR" || $_SESSION['role'] == "ADMIN")) {
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
                <script>
        document.addEventListener("DOMContentLoaded", function () {
            const welcomeMessage = document.querySelector('.welcome-message');

            welcomeMessage.style.display = 'block';

            //timeout 30 secs
            setTimeout(function () {
                welcomeMessage.style.display = 'none';
            }, 30000);
        });
    </script>
        </div>
        <br>

        <div class="map" id="map" style="width: 100%; height: 450px;"></div>
    </div>
    <script src="map-pol.js"></script>


    <script>
        //Funxtio ngia na pairnei thn topothesia tou xrhsth
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }
         //Deije kai fere tis syntetagmenes
        function showPosition(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            //Steleni ta coordinates ston server mesw AJAZ
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "insert_coordinates.php", true); 
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText);
                }
            };
            xhr.send("lat=" + lat + "&lng=" + lng);
        }

        //Klhsh toy geolocation gia na einai aytmatopoihmenh h selida
        window.onload = getLocation;
    </script>



</body>

</html>
