<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Tasks</title>
    <link rel="stylesheet" href="tasks.css">
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

    // Function to check if user is logged in and authorized
    function checkLoggedIn() {
        if (!isset($_SESSION['username'])) {
            echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
            echo 'User not logged in!';
            echo '</div>';
            exit(); 
        }
    }

    checkLoggedIn();

    $username = $_SESSION['username'];

    // Fetch data from the database
    $sql = "SELECT name, surname, phone, request_date, product_type, quantity, retrieved_date, usrnm_vehicle, status 
            FROM tasks 
            WHERE usrnm_vehicle = '$username'";
    $result = $conn->query($sql);

    ?>

    <div class="form-box">
        <?php if ($result->num_rows > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Phone</th>
                        <th>Request date</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Retrived Date</th>
                        <th>Vehicle</th>
                        <th>Status</th>
                        <th>Complete</th>
                        <th>Cancel</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row["name"]; ?></td>
                            <td><?php echo $row["surname"]; ?></td>
                            <td><?php echo $row["phone"]; ?></td>
                            <td><?php echo $row["request_date"]; ?></td>
                            <td><?php echo $row["product_type"]; ?></td>
                            <td><?php echo $row["quantity"]; ?></td>
                            <td><?php echo $row["retrieved_date"]; ?></td>
                            <td><?php echo $row["usrnm_vehicle"]; ?></td>
                            <td><?php echo $row["status"]; ?></td>
                            <td><button id="complete">Complete</button></td>
                            <td><button id="cancel">Cancel</button></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No requests found.</p>
        <?php } ?>
    </div>

    <?php $conn->close(); ?>
</body>
</html>
