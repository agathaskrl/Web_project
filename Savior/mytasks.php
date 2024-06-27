<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Tasks</title>
    <link rel="stylesheet" href="tasks.css">
    <script>
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371000; // Rad tis gis se metra
            const φ1 = (lat1 * Math.PI) / 180;
            const φ2 = (lat2 * Math.PI) / 180;
            const Δφ = ((lat2 - lat1) * Math.PI) / 180;
            const Δλ = ((lon2 - lon1) * Math.PI) / 180;

            const a =
                Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            const distance = R * c; // apostasi se metra
            return distance;
        }

        function checkvehandoffersdis(vehicleCoords, offerCoords) {
            const distance = calculateDistance(
                vehicleCoords.lat,
                vehicleCoords.lng,
                offerCoords.lat,
                offerCoords.lng
            );
            console.log("Distance between vehicle and offer:", distance, "meters");
            return distance <= 50; // Returns true an i apostasi einai 50 metra i ligotero
        }

        function checkvehandreqdis(vehicleCoords, requestCoords) {
            const distance = calculateDistance(
                vehicleCoords.lat,
                vehicleCoords.lng,
                requestCoords.lat,
                requestCoords.lng
            );
            console.log("Distance between vehicle and request:", distance, "meters");
            return distance <= 50; // Returns true an i apostasi einai 50 metra i ligotero
        }
    </script>
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
    include_once "connect_db.php";
    session_start();

    // Function gia elegxo an o xristis einai logged in
    function checkLoggedIn() {
        if (!isset($_SESSION["username"])) {
            echo "<div style='text-align: center; padding: 80px; color: rgba(76, 56, 30, 1);'>";
            echo "User not logged in!";
            echo "</div>";
            exit(); 
        }
    }

    checkLoggedIn();

    $username = $_SESSION["username"];

   
    $vehicle_query = "SELECT lat, lng FROM coordinates WHERE username = '$username'";
    $vehicle_result = $conn->query($vehicle_query);
    if ($vehicle_result && $vehicle_result->num_rows > 0) {
        $vehicle_data = $vehicle_result->fetch_assoc();
        $vehicle_lat = $vehicle_data['lat'];
        $vehicle_lng = $vehicle_data['lng'];
    } else {
        $vehicle_lat = 0;
        $vehicle_lng = 0;
    }

    if (isset($_POST["complete_offer"])) {
        $offer_id = $_POST["offer_id"];
    
        
        $offer_sql = "SELECT quantity, item FROM offers WHERE offer_id = $offer_id";
        $offer_result = $conn->query($offer_sql);
        if ($offer_result && $offer_result->num_rows > 0) {
            $offer_data = $offer_result->fetch_assoc();
            $offer_quantity = $offer_data['quantity'];
            $offer_item = $offer_data['item'];
    
            
            $vehicle_sql = "SELECT cargo, items FROM vehicle WHERE sav_username = '$username'";
            $vehicle_result = $conn->query($vehicle_sql);
            if ($vehicle_result && $vehicle_result->num_rows > 0) {
                $vehicle_data = $vehicle_result->fetch_assoc();
                $current_cargo = $vehicle_data['cargo'];
                $current_items = $vehicle_data['items'];
    
              
                $new_cargo = $current_cargo + $offer_quantity;
                $new_items = empty($current_items) ? $offer_item : $current_items . ', ' . $offer_item;
    
                
                $update_sql = "UPDATE offers SET status = 'COMPLETE' WHERE offer_id = $offer_id";
                $update_sql2 = "UPDATE vehicle SET under_tasks = under_tasks - 1, cargo = $new_cargo, items = '$new_items' WHERE sav_username = '$username'";
                $update_sql3 = "UPDATE products SET on_vehicle = NULL WHERE name ='$offer_item'";
                if (mysqli_query($conn, $update_sql) && mysqli_query($conn, $update_sql2) && mysqli_query($conn, $update_sql3)) {
                    // ananewsi selidas meta apo update
                    echo "<meta http-equiv='refresh' content='0'>";
                } else {
                    echo "Error updating record: " . mysqli_error($conn);
                }
            } else {
                echo "Error fetching vehicle data: " . mysqli_error($conn);
            }
        } else {
            echo "Error fetching offer data: " . mysqli_error($conn);
        }
    }
    

        if (isset($_POST["cancel_offer"])) {
            $offer_id = $_POST["offer_id"];
            $cancel_sql = "UPDATE offers SET status = NULL, ret_date = NULL, usrnm_veh = NULL WHERE offer_id = $offer_id";
            $cancel_sql2 = "UPDATE vehicle SET under_tasks = under_tasks - 1 WHERE sav_username = '$username'";
            if (mysqli_query($conn, $cancel_sql) && mysqli_query($conn, $cancel_sql2)) {
                // ananewsi selidas meta apo update
                echo "<meta http-equiv='refresh' content='0'>";
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
        }

        if (isset($_POST["complete_request"])) {
            $request_id = $_POST["req_id"];
        
            
            $request_sql = "SELECT demand, req_product FROM requests WHERE req_id = $request_id";
            $request_result = $conn->query($request_sql);
            if ($request_result && $request_result->num_rows > 0) {
                $request_data = $request_result->fetch_assoc();
                $request_quantity = $request_data['demand'];
                $request_item = $request_data['req_product'];
        
               
                $vehicle_sql = "SELECT cargo, items FROM vehicle WHERE sav_username = '$username'";
                $vehicle_result = $conn->query($vehicle_sql);
                if ($vehicle_result && $vehicle_result->num_rows > 0) {
                    $vehicle_data = $vehicle_result->fetch_assoc();
                    $current_cargo = $vehicle_data['cargo'];
                    $current_items = $vehicle_data['items'];
        
                    
                    $new_cargo = $current_cargo - $request_quantity;
        
                   
                    $items_array = explode(', ', $current_items);
                    $item_index = array_search($request_item, $items_array);
                    if ($item_index !== false) {
                        unset($items_array[$item_index]);
                    }
                    $new_items = implode(', ', $items_array);
        
                    
                    $update_sql = "UPDATE requests SET status = 'COMPLETE' WHERE req_id = $request_id";
                    $update_sql2 = "UPDATE vehicle SET under_tasks = under_tasks - 1, cargo = $new_cargo, items = '$new_items' WHERE sav_username = '$username'";
        
                    if (mysqli_query($conn, $update_sql) && mysqli_query($conn, $update_sql2)) {
                        // ananewsi selidas meta apo update
                        echo "<meta http-equiv='refresh' content='0'>";
                    } else {
                        echo "Error updating record: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error fetching vehicle data: " . mysqli_error($conn);
                }
            } else {
                echo "Error fetching request data: " . mysqli_error($conn);
            }
        }

        if (isset($_POST["cancel_request"])) {
            $request_id = $_POST["req_id"];
            $cancel_sql = "UPDATE requests SET status = NULL, under_date = NULL, veh_username = NULL WHERE req_id = $request_id";
            $cancel_sql2 = "UPDATE vehicle SET under_tasks = under_tasks - 1 WHERE sav_username = '$username'";
            if (mysqli_query($conn, $cancel_sql) && mysqli_query($conn, $cancel_sql2)) {
              
                echo "<meta http-equiv='refresh' content='0'>";
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
        }
    

    // Fetch offers apo ti vasi
    $query1 = "SELECT * FROM offers WHERE usrnm_veh = '$username'";
    $result1 = $conn->query($query1);
    ?>

    <div class="form-box">
        <?php if ($result1->num_rows > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <h3> Offers </h3>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Phone</th>
                        <th>Request date</th>
                        <th>Offer</th>
                        <th>Quantity</th>
                        <th>Retrieved Date</th>
                        <th>Vehicle</th>
                        <th>Status</th>
                        <th>Complete</th>
                        <th>Cancel</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result1->fetch_assoc())
                    {  ?>
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
                            <?php 
                            $offer_lat = $row["lat"];
                            $offer_lng = $row["lng"];
                            if ($row["status"] != "COMPLETE") { ?>
                                <form action="" method="POST">
                                    <input type="hidden" name="offer_id" value="<?php echo $row["offer_id"]; ?>">
                                    <?php
                                   // elegxos apostasis metaksi vehicle kai offer
                                    echo "<script>
                                         var distance = checkvehandoffersdis({lat: $vehicle_lat, lng: $vehicle_lng}, {lat: $offer_lat, lng: $offer_lng});
                                        if (distance) {
                                            document.write('<button type=\"submit\" id=\"complete\" name=\"complete_offer\">Complete</button>');
                                        }
                                    </script>";
                                    ?>
                                </form>
                            <?php } ?>
                            </td>
                            <td>
                            <?php if ($row["status"] != "COMPLETE") { ?>
                                <form action="" method="POST">
                                    <input type="hidden" name="offer_id" value="<?php echo $row["offer_id"]; ?>">
                                    <button type="submit" id="cancel" name="cancel_offer">Cancel</button>
                                </form>
                            <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div style="text-align: center;">
                <p>You have not undertaken any offers.</p>
            </div>
        <?php } ?>
    </div>

    <?php
    // Fetch requests apo ti vasi
    $query2 = "SELECT * FROM requests WHERE veh_username = '$username'";
    $result2 = $conn->query($query2);
    ?>

    <div class="form-box">
        <?php if ($result2->num_rows > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <h3> Requests </h3>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Phone</th>
                        <th>Request date</th>
                        <th>Request</th>
                        <th>Demand</th>
                        <th>Retrieved Date</th>
                        <th>Vehicle</th>
                        <th>Status</th>
                        <th>Complete</th>
                        <th>Cancel</th>
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
                            <td>
                            <?php 
                            $request_lat = $row["lat"];
                            $request_lng = $row["lng"];
                            if ($row["status"] != "COMPLETE") { ?>
                                <form action="" method="POST">
                                    <input type="hidden" name="req_id" value="<?php echo $row["req_id"]; ?>">
                                    <?php
                                    // elegxos apostasis metaksi vehicle kai request
                                    echo "<script>
                                        var distance = checkvehandreqdis({lat: $vehicle_lat, lng: $vehicle_lng}, {lat: $request_lat, lng: $request_lng});
                                        if (distance) {
                                            document.write('<button type=\"submit\" id=\"complete\" name=\"complete_request\">Complete</button>');
                                        }
                                    </script>";
                                    ?>
                                </form>
                            <?php } ?>
                            </td>
                            <td>
                            <?php if ($row["status"] != "COMPLETE") { ?>
                                <form action="" method="POST">
                                    <input type="hidden" name="req_id" value="<?php echo $row["req_id"]; ?>">
                                    <button type="submit" id="cancel" name="cancel_request">Cancel</button>
                                </form>
                            <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div style="text-align: center;">
                <p>You have not undertaken any requests.</p>
            </div>
        <?php } ?>
    </div>
</body>
</html>
