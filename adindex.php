<!DOCTYPE html>
<html lan="en" and dir="ltr">
    <head>
        <meta charset="utf-8">
        <title> </title>
        <link rel="stylesheet" href="adstyle.css?v=1">
        <!-- Leaflet CSS and JavaScript files -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
        <!-- gia to search  -->
        <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css"/>
    

    </head>
    <body>     
    
   
    <div class="main">
            <div class="navbar">
            <div class="welcome-message">
            <?php
            session_start();
            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                echo "Welcome, $username!";
            } else {
                echo "User not logged in, please log in"; 
            }
            ?>
            <script>
                    const welcomeMessage = document.getElementById('welcome-message');

                    //Synarthsh gia na feygei to kalwsorisma meta apo 30 deytera kaei to message na fainetai none
                    function hideWelcomeMessage() 
                    {
                        welcomeMessage.style.display = 'none';
                    }
                    //sbhnei to mhnyma meta apo 30000 millisecond
                    setTimeout(hideWelcomeMessage, 30000);
            </script>
        </div>
        <ul> 
                    <li><a href="adindex.php">HOME</a></li>  
                        <li><a href="warehouse.php">WAREHOUSE</a></li>
                        <li><a href="statistics.php">STATISTICS</a></li>
                        <li><a href="savsignup.php">CREATE ACCOUNT</a></li>
                        <li><a href="announcement.php">ANNOUNCEMENT</a></li>
                        <li><a href="logout.php">LOG OUT</a></li>              
                    </ul>
                </div>
            
            </div>
            <br></br>
            <div class="container"> 
                <input type="text" id="searchInput" placeholder="Search...">
                <button>Search</button>
                <br></br>
                <div class="map" id="map" style="width: 100%px; height: 400px; "></div>
                <br></br>
                <br></br>
            </div>
            <script src="map.js?v=1"> </script>
        </div>



            
        </body>
    </html>