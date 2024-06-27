<?php
include_once 'connect_db.php';
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $query = "SELECT role FROM user WHERE username=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['role'] = $row['role']; 
    }
}

function checkLoggedIn() {
     // Elegxos gia to an o xrhsths einai syndedemenos
    if (!isset($_SESSION['username'])) {
        echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
        echo 'User not logged in!';
        echo '</div>';
        exit(); 
    }
    // Elegxos gia to an o rolos tou xrhsth einai  "SAVIOR" h "ADMIN" kai aporich peraitero prosvashs
    if (isset($_SESSION['role']) && ($_SESSION['role'] == "SAVIOR" || $_SESSION['role'] == "ADMIN")) {
        echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
        echo 'Unauthorized access!';
        echo '</div>';
        exit(); 
    }
}

checkLoggedIn();
//Query gia na fenrei ta stoiceia tou xrhsth
$sql1 = "SELECT name, surname FROM user WHERE username=?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("s", $username);
$stmt1->execute();
$result1 = $stmt1->get_result();
$row1 = $result1->fetch_assoc();
$civ_name = $row1['name'];
$civ_surname = $row1['surname'];
//Query gia na fernei ta requets pou exei kanei o xrhsths
$sql = "SELECT * FROM requests WHERE civ_name = ? AND civ_surname = ?";
$stmt2 = $conn->prepare($sql);
$stmt2->bind_param("ss", $civ_name, $civ_surname);
$stmt2->execute();
$result = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Requests List</title>
    <link rel="stylesheet" href="requestslist.css">
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
                <th>People in need</th>
                <th>Request Date</th>
                <th>Undertaken Date</th>
                <th>Vehicle</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            //fernei ta dedomenna se pinaka
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["req_product"] . "</td>";
                    echo "<td>" . $row["demand"] . "</td>";
                    echo "<td>" . $row["req_date"] . "</td>";
                    echo "<td>" . $row["under_date"] . "</td>";
                    echo "<td>" . $row["veh_username"] . "</td>";
                    echo "<td>" . $row["status"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' style='text-align: center;'>No requests found!</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
