<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Statistics</title>
    <link rel="stylesheet" href="adstyle.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                // Check if the user is not logged in
                if (!isset($_SESSION['username'])) {
                    echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
                    echo 'User not logged in!';
                    echo '</div>';
                    exit(); 
                }
                
                if (isset($_SESSION['role']) && ($_SESSION['role'] == "SAVIOR" || $_SESSION['role'] == "CITIZEN")) {
                    echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
                    echo 'Unauthorized access!';
                    echo '</div>';
                    exit(); 
                }
            }

            checkLoggedIn();
        ?>

        <br>

        <div class="container-chart">
            <h1>Statistics</h1>
            <label for="time-period">Select Time Period:</label>
            <select id="time-period" onchange="updateChart()">
                <option value="7">Last 7 Days</option>
                <option value="30">Last Month</option>
                <option value="90">Last Three Months</option>
            </select>
            <br> <br>
            <canvas id="statistics" width="400" height="200"></canvas>

        </div>

        <script src="stat_func.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                updateChart();
            });
        </script>
    </div>
</body>
</html>
