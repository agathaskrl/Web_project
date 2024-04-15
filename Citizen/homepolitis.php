<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Home</title>
    <link rel="stylesheet" href="home_style.css?v=2">
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
        //function to check if the user is logged in
        function checkLoggedIn() {
            session_start();
            if (!isset($_SESSION['username'])) {
                echo '<div style="text-align: center; padding: 80px; background-color: rgb(247, 240, 235); color: rgba(76, 56, 30, 1); ">';
                echo 'User not logged in. Please <a href="login.php">Log in!</a>.';
                echo '</div>';
                exit(); 
            }
        }
        checkLoggedIn(); //call the function
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

            //welcome message
            welcomeMessage.style.display = 'block';

            //timeout 30 secs
            setTimeout(function () {
                welcomeMessage.style.display = 'none';
            }, 30000);
        });
    </script>
        </div>
        <br>

        <div class="container">
            <input type="text" id="searchInput" placeholder="Search...">
            <!-- Removed the button -->
        </div>
        <button><i class="filtermap"></i>Filter Map</button>

        <div class="map" id="map" style="width: 100%; height: 450px;"></div>
    </div>
    <script src="map-pol.js"></script>


    <script>
        //fnction to get the location of the user
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }
         //show and get the coordinates
        function showPosition(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            // Send coordinates to server using AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "insert_coordinates.php", true); //other file not to get things mixed
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText);
                }
            };
            xhr.send("lat=" + lat + "&lng=" + lng);
        }

        //call getLocation() when the page loads so its automated 
        window.onload = getLocation;
    </script>



</body>

</html>
