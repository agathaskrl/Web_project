<?php
session_start();
include_once 'connect_db.php';

// Fetch data from the database
$sql = "SELECT ann_id, item, quantity FROM announcements";
$result = $conn->query($sql);

?>

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
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
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
