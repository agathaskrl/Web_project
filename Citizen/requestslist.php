<!DOCTYPE html>
<html lan="en" and dir="ltr">
    <head>
        <meta charset="utf-8">
        <title> Requests List </title>
        <link rel="stylesheet" href="requestslist.css">
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
</body>
<div class="form-box">
            <table>
                <thead>
                    <tr>
<?php
include_once 'connect_db.php';

// Fetch data from the database
$sql = "SELECT req_product, demand, req_date, under_date, veh_username FROM requests";
$result = $conn->query($sql);



// Check if there are any results
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Item</th>";
    echo "<th>People in need</th>";
    echo "<th>Request Date</th>";
    echo "<th>Undertaken Date</th>";
    echo "<th>Vehicle</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["req_product"] . "</td>";
        echo "<td>" . $row["demand"] . "</td>";
        echo "<td>" . $row["req_date"] . "</td>";
        echo "<td>" . $row["under_date"] . "</td>";
        echo "<td>" . $row["veh_username"] . "</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
} else {
    echo "No requests found.";
}

// Close the database connection
$conn->close();
?>
</tr>
</thead>
<tbody>