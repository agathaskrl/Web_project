<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="adstyle.css?v=10">
</head>
<body>     
    <div class="main">
        <div class="navbar">
            <ul> 
                <li><a href="adindex.php">HOME</a></li>  
                <li><a href="warehouse.php">WAREHOUSE</a></li>
                <li><a href="statistics.php">STATISTICS</a></li>
                <li><a href="savsignup.php">CREATE ACCOUNT</a></li>
                <li><a href="adminannouncement.php">ANNOUNCEMENT</a></li>
                <li><a href="logout.php">LOG OUT</a></li>              
            </ul>
        </div>
        
        <div class="form-box">
            <form class="box" method="POST">
                <h2>Edit Product</h2>


                <?php
                include_once 'connect_db.php';

                //check if the product id is set 
                if(isset($_GET['product_id'])) {
                    //Get the product details from its id 
                    $product_id = $_GET['product_id'];
                    $sql = "SELECT products.id AS id, products.name AS name, categories.category_name AS category, products.quantity, products.detail_name, products.detail_value, products.on_vehicle 
                    FROM products 
                    JOIN categories ON products.category = categories.id
                    WHERE products.id = $product_id"; // Add condition to select only the product with the specified ID
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);

                    //display in form like 
                    echo "<input type='hidden' name='product_id' value='{$row['id']}'>";
                    echo "<div class='input-box'>";
                    echo "<span class='details'>Item:</span>";
                    echo "<input type='text' name='item' value='{$row['name']}' placeholder='Item' id='item' readonly>";
                    echo "</div>";
                    echo "<div class='input-box'>";
                    echo "<span class='details'>Category:</span>";
                    echo "<input type='text' name='category' value='{$row['category']}' placeholder='Category' id='category' readonly>";
                    echo "</div>";
                    echo "<div class='input-box'>";
                    echo "<span class='details'>Quantity:</span>";
                    echo "<input type='number' name='quantity' value='{$row['quantity']}' placeholder='Quantity' id='quantity' required min='0'>";
                    echo "</div>";
                    echo "<div class='input-box'>";
                    echo "<span class='details'>Details:</span>";
                    echo "<input type='text' name='details' value='{$row['detail_name']} - {$row['detail_value']}' placeholder='Details' id='details' readonly>";
                    echo "</div>";
                    echo "<div class='button'>";
                    echo "<input type='submit' value='Edit Product'>";
                    echo "</div>";
                } else {
                    echo "Product ID not provided.";
                }

                
                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    if(isset($_POST['product_id']) && isset($_POST['quantity'])) {
                        // Sanitize input
                        $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
                        $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);

                        //update quantity in the table
                        $sql = "UPDATE products SET quantity = '$quantity' WHERE id = '$product_id'";
                        if(mysqli_query($conn, $sql)) {
                            //redirect to warehouse.php after successful update
                            header("Location: warehouse.php");
                            exit;
                        } else {
                            echo "Error updating record: " . mysqli_error($conn);
                        }
                    } else {
                            echo "Product ID not provided.";
                    }
                }
            ?>
            </form>
        </div>
    </div>
</body>
</html>
