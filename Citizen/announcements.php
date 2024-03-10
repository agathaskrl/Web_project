<!DOCTYPE html>
<html lan="en" and dir="ltr">
    <head>
        <meta charset="utf-8">
        <title> Requests </title>
        <link rel="stylesheet" href="requestslist.css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="script.js"></script>

</head>

<body>
<div class="main"> 
    <div class="navbar">
        <ul>
            <li><a href= "homepolitis.php">HOME</a></li>
            <li><a href= "requests.php">REQUESTS</a></li>
            <li><a href= "announcements.php">ANNOUNCEMENTS</a></li>
            <li><a href= "offers.php">OFFERS</a></li>
            <li><a href="logout.php">LOGOUT</a></li>
        </ul>
    </div>
</div>



<div class="form-box">
    <table>
        <thead>
            <tr>
<?php
include_once 'connect_db.php';

// Fetch data from the database
$sql = "SELECT item, quantity FROM announcements";
$result = $conn->query($sql);



// Check if there are any results
if ($result->num_rows > 0) {
echo "<table>";
echo "<thead>";
echo "<tr>";
echo "<th>Item</th>";
echo "<th>Quantity</th>";
echo "<th>Accept</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

// Output data of each row
while ($row = $result->fetch_assoc()) {
echo "<tr>";
echo "<td>" . $row["item"] . "</td>";
echo "<td>" . $row["quantity"] . "</td>";
echo "<td><form method='post'><input type='hidden' name='item'><button type='submit' class='accbutton' name='accept' onclick=''>Accept</button></form></td>";
echo "</tr>";
}

echo "</tbody>";
echo "</table>";
} else {
echo "No announcements found.";
}

// Close the database connection
$conn->close();
?>
</tr>
</thead>
<tbody>