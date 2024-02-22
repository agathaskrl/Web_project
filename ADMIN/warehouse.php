<!DOCTYPE html>
<html lan="en" and dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Warehouse</title>
        <link rel="stylesheet" href="adstyle.css?v=7">
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
<body>
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
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT products.name AS name, categories.category_name AS category_name, products.quantity, products.detail_name, products.detail_value 
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
                        echo "</tr>";
                        $counter++;
                    }
                    ?>
                </tbody>
            </table>
        </div>

</body>

</html>
