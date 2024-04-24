

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Announcements</title>
    <link rel="stylesheet" href="announcements.css?v=3">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="saveoffer.js"></script>
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
    </div>

    <?php
session_start();
include_once 'connect_db.php';

// Function to check if the user is logged in and has appropriate role
function checkLoggedIn() {
    // Check if the user is not logged in
    if (!isset($_SESSION['username'])) {
        echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
        echo 'User not logged in!';
        echo '</div>';
        exit(); 
    }
    
    // Check if the user's role is "SAVIOR" or "ADMIN", and deny access
    if (isset($_SESSION['role']) && ($_SESSION['role'] == "SAVIOR" || $_SESSION['role'] == "ADMIN")) {
        echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
        echo 'Unauthorized access!';
        echo '</div>';
        exit(); 
    }
}

// Check if the user is logged in and has appropriate role
checkLoggedIn();

// Fetch data from the database
$sql = "SELECT ann_id, item, quantity FROM announcements";
$result_announcements = $conn->query($sql);

?>

    <div class="form-box">
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_announcements->num_rows > 0) {
                    while ($row = $result_announcements->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["item"] . "</td>";
                        echo "<td>" . $row["quantity"] . "</td>";
                        echo "<td>
                            <button class='offerbutton'>Make an offer</button>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No announcements found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>