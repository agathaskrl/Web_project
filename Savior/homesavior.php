<!DOCTYPE html>
<html lan="en" and dir="ltr">
    <head>
        <meta charset="utf-8">
        <title> Home </title>
        <link rel="stylesheet" href="home_style.css?v=2">
        <!-- Leaflet CSS and JavaScript files -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
    <!--Search-->
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css"/>
</head>


<body>
<div class="main"> 
    <div class="navbar">
        <ul>
            <li><a href= "homesavior.php">HOME</a></li>
            <li><a href= "tasks.php">TASKS</a></li>
            <li><a href="logout.php">LOGOUT</a></li>
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
</div>

<div class="container"> 
        <input type="text" id="searchInput" placeholder="Search...">
        <button>Search</button>
    </div>
    
<button><i class="filtermap"></i>Filter Map</button>
    
            <div class="map" id="map" style="width: 100%; height: 450px;"></div>
    </div>
        <script src="map.js"> </script>
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