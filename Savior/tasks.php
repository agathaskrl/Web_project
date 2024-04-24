<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Tasks</title>
    <link rel="stylesheet" href="tasks.css?v=3">
</head>
<body>
    <div class="main"> 
        <div class="navbar">
            <ul>
                <li><a href="homesavior.php">HOME</a></li>
                <li><a href="tasks.php">TASKS</a></li>
                <li><a href="logout.php">LOGOUT</a></li>
            </ul>
        </div>
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
        // echo $_SESSION['role']; 
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
    
    // Check if the user's role is "SAVIOR" or "ADMIN", and deny access
    if (isset($_SESSION['role']) && ($_SESSION['role'] == "CITIZEN" || $_SESSION['role'] == "ADMIN")) {
        echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
        echo 'Unauthorized access!';
        echo '</div>';
        exit(); 
    }
}

checkLoggedIn();
?>

    <br>
<!-- koumpi gia selida me tasks tou savior -->
    <a href="mytasks.php" >
    <button> My tasks </button></a>
</br>

    <div class="form-box">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Phone</th>
                    <th>Request date</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Type</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
              <?php
              /*
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
                    echo "<tr><td colspan='8'>No tasks found.</td></tr>";
                }
                */
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>