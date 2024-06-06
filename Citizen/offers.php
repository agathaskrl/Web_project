<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Offers</title>
    <link rel="stylesheet" href="offers.css">
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

function checkLoggedIn() {
    if (!isset($_SESSION['username'])) {
        echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
        echo 'User not logged in!';
        echo '</div>';
        exit(); 
    }
    
    if (isset($_SESSION['role']) && ($_SESSION['role'] == "SAVIOR" || $_SESSION['role'] == "ADMIN")) {
        echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
        echo 'Unauthorized access!';
        echo '</div>';
        exit(); 
    }
}

checkLoggedIn();

$usrnm = $_SESSION['username'];
$sql = "SELECT * FROM offers WHERE cit_username = '$usrnm'";
$result = $conn->query($sql);
?>

<div class="form-box">
    <table>
        <thead>
        <tr>
            <th>Item</th>
            <th>Quantity</th>
            <th>Offer date</th>
            <th>Retrieval date</th>
            <th>Vehicle</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["item"] . "</td>";
                echo "<td>" . $row["quantity"] . "</td>";
                echo "<td>" . $row["subm_date"] . "</td>";
                echo "<td>" . $row["ret_date"] . "</td>";
                echo "<td>" . $row["usrnm_veh"] . "</td>";
                echo "<td>" . $row["status"] . "</td>";
                echo "<td>";
                
                if ($row["usrnm_veh"] === NULL) {
                    echo "<form method='post'>";
                    echo "<input type='hidden' name='offer_id' value='" . $row["offer_id"] . "'>";
                    echo "<input type='hidden' name='item' value='" . $row["item"] . "'>";
                    echo "<input type='hidden' name='quantity' value='" . $row["quantity"] . "'>";
                    echo "<button type='submit' id='cancel' class='cancel_offer' name='cancel_offer'>Cancel</button>";
                    echo "</form>";
                } else {
                    echo "Cannot Cancel";
                }
        
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No offers found.</td></tr>";
        }
        
        if (isset($_POST['cancel_offer'])) {
            $offer_id = $_POST['offer_id'];
            $item = $_POST['item'];
            $quantity = $_POST['quantity'];
            
            // Delete the offer
            $cancel_sql = "DELETE FROM offers WHERE offer_id = $offer_id";
            
            if (mysqli_query($conn, $cancel_sql)) {
                $update_ann_sql = "UPDATE announcements SET offer_made = NULL WHERE item = '$item' AND quantity = '$quantity'";
                if (mysqli_query($conn, $update_ann_sql)) {
                    echo "<meta http-equiv='refresh' content='0'>";
                } else {
                    echo "Error updating announcements: " . mysqli_error($conn);
                }
            } else {
                echo "Error deleting offer: " . mysqli_error($conn);
            }
        }
        ?>
      </tbody>
    </table>
</div>
</body>
</html>
