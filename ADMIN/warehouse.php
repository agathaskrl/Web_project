<!DOCTYPE html>
<html lan="en" and dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Warehouse</title>
    <link rel="stylesheet" href="adstyle.css?v=8">
</head>
<body>     
    <div class="main">
        <div class="navbar">
            <ul> 
                <li><a href="adindex.php">HOME</a></li>  
                <li><a href="warehouse.php">WAREHOUSE</a></li>
                <li><a href="statistics.php">STATISTICS</a></li>
                <li><a href="savsignup.php">CREATE ACCOUNT</a></li>
                <li><a href="announcement.php">ANNOUNCEMENT</a></li>
                <li><a href="logout.php">LOG OUT</a></li>              
            </ul>
        </div>

        <?php
        include_once 'connect_db.php';

        //function to check if the user is logged in
        function checkLoggedIn() { 
            session_start();
            if (!isset($_SESSION['username'])) {
                echo '<div style="text-align: center; padding: 80px; background-color: rgb(247, 240, 235); color: rgba(76, 56, 30, 1); ">';
                echo 'User not logged in. Please <a href="login.php">Log in!</a>.';
                echo '</div>';
                exit(); // Exit the script
            }
        }
        checkLoggedIn(); // Call the function to check if the user is logged in
        ?>

        <br>
        <div class="button-container">
            <button><i class="filterwarehouse"></i>Filter Warehouse</button>
            <button onclick="location.href='updatewarehouse.php'"><i class="Updatewarehouse"></i>Update Warehouse</button>
            <button onclick="location.href='editwarehouse.php'"><i class="Editwarehouse"></i>Edit Warehouse</button>
        </div>
        <br>
        <div class="form-box">
            <table>
                <thead>
                    <tr>
                        <th>Number</th>
                        <th>Item Name</th>
                        <th>Item Category</th>
                        <th>Quantity</th>
                        <th>Details</th>
                        <th>On Vehicle</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT products.id AS id, products.name AS name, categories.category_name AS category_name, products.quantity, products.detail_name, products.detail_value, products.on_vehicle 
                    FROM products 
                    JOIN categories ON products.category = categories.id;";
                    $result = mysqli_query($conn, $sql);

                    $counter = 1; //make a counter initialize 1 
                    //fetch and dispaly each products
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>{$counter}</td>"; // Output the counter
                        echo "<td>{$row['name']}</td>";
                        echo "<td>{$row['category_name']}</td>";
                        echo "<td>{$row['quantity']}</td>";
                        echo "<td>{$row['detail_name']} - {$row['detail_value']}</td>";
                        echo "<td>{$row['on_vehicle']}</td>";
                        echo "<td><form method='post'><input type='hidden' name='product_id' value='{$row['id']}'><button type='submit' class='delbutton' name='delete' onclick='delete.js'>Delete</button></form></td>";
                        echo "</tr>";
                        $counter++;
                    }

                    if (isset($_POST['delete'])) {
                        $product_id = $_POST['product_id'];
                        $delete_sql = "DELETE FROM products WHERE id = $product_id";
                        if (mysqli_query($conn, $delete_sql)) {
                            // Reload the page after successful deletion
                            echo "<meta http-equiv='refresh' content='0'>";
                        } else {
                            echo "Error deleting record: " . mysqli_error($conn);
                        }
                        
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
