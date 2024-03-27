<!DOCTYPE html>
<html lan="en" and dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Add Product</title>
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
                </head>
<body>
    <br>
<button onclick="location.href='addcategory.php'"><i class="addproduct"></i>Add Category</button>

<form class="box" method="POST">
    <h1>Add Product</h1>

    <div class="input-box">
    <span class="details">Category</span>
    <select name="category" id="category" required>
        <option value="">Select Category</option>
        <?php
        include_once 'connect_db.php'; 
        session_start();
        $category_query = "SELECT id, category_name FROM categories";
        $category_result = mysqli_query($conn, $category_query);
        while ($row = mysqli_fetch_assoc($category_result)) {
            echo "<option value='" . $row['id'] . "'>" . $row['category_name'] . "</option>";
        }   
        ?>
    </select>
</div>

    <div class="input-box">
        <span class="details">Item</span>
        <input type="text" name="item" placeholder="Item" id="item" required>
    </div>

    <div class="input-box">
        <span class="details">Quantity</span>
        <input type="number" name="quantity" placeholder="Quantity" id="quantity" required min="0"> 
    </div>

    <div class="input-box">
        <span class="details">Detail Name</span>
        <input type="text" name="detail_name" placeholder="ex.weight" id="detail_name" required> 
    </div>

    <div class="input-box">
        <span class="details">Detail Value</span>
        <input type="text" name="detail_value" placeholder="ex.200g" id="detail_value" required> 
    </div>

    <br>

    <div class="button">
        <input type="submit" value="Add Product">
    </div>
</form>


<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item = $_POST['item'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $detail_name = $_POST['detail_name'];
    $detail_value = $_POST['detail_value'];

    // Check if the product already exists
    $query = "SELECT * FROM products WHERE name = '$item'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Product already exists. Please choose a different name.');</script>";
    } else {
        $insert_query = "INSERT INTO products (name, category, quantity, detail_name, detail_value) VALUES ('$item', '$category', '$quantity', '$detail_name', '$detail_value')";
        if (mysqli_query($conn, $insert_query)) {
            echo "<script>alert('Product added successfully.');</script>";
            // Redirect to warehouse.php after successful insertion
            echo "<script>window.location.href='warehouse.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error adding product.');</script>";
        }
    }
}
?>

</body>
</html>
