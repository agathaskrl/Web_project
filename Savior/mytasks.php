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

    // Fetch offers from the database
    $query1 = "SELECT * FROM offers WHERE usrnm_veh = '$username'";
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
                        <th>offer</th>
                        <th>Quantity</th>
                        <th>Retrieved Date</th>
                        <th>Vehicle</th>
                        <th>Status</th>
                        <th>Complete</th>
                        <th>Cancel</th>
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
                            <td>
                            <?php if ($row["status"] != 'COMPLETE') { ?>
                                <form action="" method="POST">
                                    <input type="hidden" name="offer_id" value="<?php echo $row["offer_id"]; ?>">
                                    <button type="submit" id="complete" name="complete_offer">Complete</button>
                                </form>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($row["status"] != 'COMPLETE') { ?>
                                <form action="" method="POST">
                                    <input type="hidden" name="offer_id" value="<?php echo $row["offer_id"]; ?>">
                                    <button type="submit" id="cancel" name="cancel_offer">Cancel</button>
                                </form>
                            <?php } ?>
                        </td>
                    <?php } 
                    if (isset($_POST['complete_offer'])) {
                        $offer_id = $_POST['offer_id'];
                        $update_sql = "UPDATE offers SET status = 'COMPLETE' WHERE offer_id = $offer_id";
                        if (mysqli_query($conn, $update_sql)) {
                            // Reload the page after successful 
                            echo "<meta http-equiv='refresh' content='0'>";
                        } else {
                            echo "Error updating record: " . mysqli_error($conn);
                        }
                    }
                    if(isset($_POST['cancel_offer'])) {
                        $offer_id = $_POST['offer_id'];
                        $cancel_sql = "UPDATE offers SET status = NULL, ret_date = NULL, usrnm_veh = NULL WHERE offer_id = $offer_id";
                        if (mysqli_query($conn, $cancel_sql)) {
                            // Reload the page after successful 
                            echo "<meta http-equiv='refresh' content='0'>";
                        } else {
                            echo "Error updating record: " . mysqli_error($conn);
                        }
                    }
                    ?>
                </tbody>
            </table>
            <?php } else { ?>
        <div style="text-align: center;">
            <p>You have not undertaken any offers.</p>
        </div>
    <?php } ?>
    </div>

    <?php
    // Fetch requests from the database
    $query2 = "SELECT req_id,civ_name , civ_surname, civ_phone, req_date , req_product, demand, under_date, veh_username, status  
            FROM requests
            WHERE veh_username  = '$username'";
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
                        <th>offer</th>
                        <th>Demand</th>
                        <th>Retrieved Date</th>
                        <th>Vehicle</th>
                        <th>Status</th>
                        <th>Complete</th>
                        <th>Cancel</th>
                    </tr>
                </thead>
                <form>
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
                            <td>
                            <?php if ($row["status"] != 'COMPLETE') { ?>
                                <form action="" method="POST">
                                    <input type="hidden" name="req_id" value="<?php echo $row["req_id"]; ?>">
                                    <button type="submit" id="complete" name="complete_request">Complete</button>
                                </form>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($row["status"] != 'COMPLETE') { ?>
                                <form action="" method="POST">
                                    <input type="hidden" name="req_id" value="<?php echo $row["req_id"]; ?>">
                                    <button type="submit" id="cancel" name="cancel_request">Cancel</button>
                                </form>
                            <?php } ?>
                        </td> </tr> 
                    <?php } 
                    if (isset($_POST['complete_request'])) {
                        $request_id = $_POST['req_id'];
                        $update_sql = "UPDATE requests SET status = 'COMPLETE' WHERE req_id = $request_id";
                        if (mysqli_query($conn, $update_sql)) {
                            // Reload the page after successful 
                            echo "<meta http-equiv='refresh' content='0'>";
                        } else {
                            echo "Error updating record: " . mysqli_error($conn);
                        }
                    }
                    if(isset($_POST['cancel_request'])) {
                        $request_id = $_POST['req_id'];
                        $cancel_sql = "UPDATE requests SET status = NULL, under_date = NULL, veh_username = NULL WHERE req_id = $request_id";
                        if (mysqli_query($conn, $cancel_sql)) {
                            // Reload the page after successful 
                            echo "<meta http-equiv='refresh' content='0'>";
                        } else {
                            echo "Error updating record: " . mysqli_error($conn);
                        }
                    }
                    ?>
                </tbody>
            </table>
            <?php } else { ?>
        <div style="text-align: center;">
            <p>You have not undertaken any requests.</p>
        </div>
    <?php } ?>
    </div>
    <?php $conn->close(); ?>
</body>
</html>
