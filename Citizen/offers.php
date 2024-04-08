<?php
session_start();
include_once 'connect_db.php';

// Fetch data from the database including ret_date, subm_date
$sql = "SELECT offer_id, item, quantity, subm_date, ret_date FROM offers";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Offers</title>
    <link rel="stylesheet" href="offers.css?v=2">
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
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No offers found.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>
