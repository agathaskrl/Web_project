<?php
include_once 'connect_db.php';
session_start();

?>
<!DOCTYPE html>
<html lan="en" and dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Home</title>
    <link rel="stylesheet" href="home_style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
</head>
<body>
<div class="main"> 
    <div class="navbar">
        <ul>
            <li><a href="homesavior.php">HOME</a></li>
            <li><a href="tasks.php">TASKS</a></li>
            <li><a href="logout.php">LOGOUT</a></li>
        </ul>
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
        // Check if the user is not logged in
        if (!isset($_SESSION['username'])) {
            echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
            echo 'User not logged in!';
            echo '</div>';
            exit(); 
        }
        
        if (isset($_SESSION['role']) && ($_SESSION['role'] == "ADMIN" || $_SESSION['role'] == "CITIZEN")) {
            echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
            echo 'Unauthorized access!';
            echo '</div>';
            exit(); 
        }
    }

    checkLoggedIn();

    $username = $_SESSION['username'];
    ?>
    <div class="welcome-message">
        <?php
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            echo "Welcome, $username!";
        } 
        ?>
    </div>
</div>
<div class="container"> 
    <input type="text" id="searchInput" placeholder="Search...">
    <button>Search</button>
</div>
<button><i class="filtermap"></i>Filter Map</button>
<div class="map" id="map" style="width: 100%; height: 450px;"></div>
<script src="map-sav.js"></script>
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
<?php


// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo 'User not logged in!';
    exit();
}

// Fetch savior's coordinates from the database
$username = $_SESSION['username'];
$query_savior = "SELECT lat, lng FROM coordinates";
$result_savior = mysqli_query($conn, $query_savior);

if ($result_savior && mysqli_num_rows($result_savior) > 0) {
    $row_savior = mysqli_fetch_assoc($result_savior);
    $savior_lat = $row_savior['lat'];
    $savior_lng = $row_savior['lng'];
} else {
    // Handle case where savior coordinates are not found
    echo 'Savior coordinates not found!';
    exit();
}

// Fetch vash's coordinates from the database
$query_vash = "SELECT lat, lng FROM vash_marker ";
$result_vash = mysqli_query($conn, $query_vash);

if ($result_vash && mysqli_num_rows($result_vash) > 0) {
    $row_vash = mysqli_fetch_assoc($result_vash);
    $vash_lat = $row_vash['lat'];
    $vash_lng = $row_vash['lng'];
} else {
    // Handle case where vash coordinates are not found
    echo 'Vash coordinates not found!';
    exit();
}
?>

</body>
</html>