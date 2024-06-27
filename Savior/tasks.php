<?php
    include_once 'connect_db.php';
    session_start();
    function checkLoggedIn() {
        // elegxos an einai logged in o xristis
        if (!isset($_SESSION['username'])) {
            echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
            echo 'User not logged in!';
            echo '</div>';
            exit(); 
        }
        
        if (isset($_SESSION['role']) && ($_SESSION['role'] == "CITIZEN" || $_SESSION['role'] == "ADMIN")) {
            echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
            echo 'Unauthorized access!';
            echo '</div>';
            exit(); 
        }
    }
    
    checkLoggedIn();

    $username = $_SESSION['username'];
    ?>

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

    <br>
   
    <a href="mytasks.php" >
        <button> My tasks </button>
    </a>
    <br>
<?php
    // Fetch offers apo ti vasi
    $query1 = "SELECT name, surname, phone, subm_date, item, quantity, ret_date, usrnm_veh, status 
            FROM offers";
    $result1 = $conn->query($query1);

    ?>

    <div class="form-box">
        <?php if ($result1->num_rows > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <h3> Offers <h3>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Phone</th>
                        <th>Request date</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Retrived Date</th>
                        <th>Vehicle</th>
                        <th>Status</th>

                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result1->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row["name"]; ?></td>
                            <td><?php echo $row["surname"]; ?></td>
                            <td><?php echo $row["phone"]; ?></td>
                            <td><?php echo $row["subm_date"]; ?></td>
                            <td><?php echo $row["item"]; ?></td>
                            <td><?php echo $row["quantity"]; ?></td>
                            <td><?php echo $row["ret_date"]; ?></td>
                            <td><?php echo $row["usrnm_veh"]; ?></td>
                            <td><?php echo $row["status"]; ?></td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
        <div style="text-align: center;">
            <p>No offers found!</p>
        </div>
    <?php } ?>
    </div>

    <?php
    // Fetch requests apo ti vash
    $query2 = "SELECT civ_name , civ_surname, civ_phone, req_date , req_product , demand, under_date, veh_username, status  
            FROM requests";
    $result2 = $conn->query($query2);

    ?>

    <div class="form-box">
        <?php if ($result2->num_rows > 0) { ?>
            <table>
                <thead>
                    <tr>
                    <h3> Requests <h3>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Phone</th>
                        <th>Request date</th>
                        <th>Product</th>
                        <th>Demand</th>
                        <th>Retrived Date</th>
                        <th>Vehicle</th>
                        <th>Status</th>

                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result2->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row["civ_name"]; ?></td>
                            <td><?php echo $row["civ_surname"]; ?></td>
                            <td><?php echo $row["civ_phone"]; ?></td>
                            <td><?php echo $row["req_date"]; ?></td>
                            <td><?php echo $row["req_product"]; ?></td>
                            <td><?php echo $row["demand"]; ?></td>
                            <td><?php echo $row["under_date"]; ?></td>
                            <td><?php echo $row["veh_username"]; ?></td>
                            <td><?php echo $row["status"]; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
        <div style="text-align: center;">
            <p>No requests found!</p>
        </div>
    <?php } ?>
    </div>
    <?php $conn->close(); ?>
</body>
</html>
