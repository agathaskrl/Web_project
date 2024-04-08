<?php
session_start();
include_once 'connect_db.php';

//cancel button 
if(isset($_POST['cancel'])) {
    // Check if offer_id is set and is a valid integer
    if(isset($_POST['offer_id']) && filter_var($_POST['offer_id'], FILTER_VALIDATE_INT)) {
        $offer_id = $_POST['offer_id'];
        
        // Delete the offer from the database
        $delete_sql = "DELETE FROM offers WHERE offer_id = $offer_id";
        if ($conn->query($delete_sql) === TRUE) {
            // Offer deleted successfully, you can redirect or show a success message here
        } else {
            // Error occurred while deleting the offer
            echo "Error: " . $delete_sql . "<br>" . $conn->error;
        }
    }
}

// Fetch data from the database including ret_date, subm_date
$sql = "SELECT offer_id, item, quantity, subm_date, ret_date FROM offers";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Offers</title>
    <link rel="stylesheet" href="offers.css?v=3">
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
                echo "<td></td>"; // Placeholder for veh username column
                echo "<td></td>"; // Placeholder for status column
                echo "<td>
                <form method='post'>
                        <input type='hidden' name='offer_id' value='" . $row["offer_id"] . "'>
                        <button type='submit' class='cancelbutton' name='cancel'>Cancel</button>
                        </form>
                </td>"; 
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No offers found.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>
