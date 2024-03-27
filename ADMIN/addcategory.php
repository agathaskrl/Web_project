<!DOCTYPE html>
<html lan="en" and dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Add Product</title>
        <link rel="stylesheet" href="adstyle.css?v=16">
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
<form class="box" method="POST">
    <h1>Add Category</h1>


    <div class="input-box">
        <span class="details">Category</span>
        <input type="text" name="category" placeholder="Category" id="category" required>
    </div>

   

    <div class="button">
        <input type="submit" value="Add Category">
    </div>
</form>


<?php
include_once 'connect_db.php'; 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];

    // Check if the category already exists
    $query = "SELECT * FROM categories WHERE category_name = '$category'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Category already exists. Please choose a different name.');</script>";
    } else {
        // Get the last ID from the categories table
        $last_id_query = "SELECT id FROM categories ORDER BY id DESC LIMIT 1";
        $last_id_result = mysqli_query($conn, $last_id_query);

        if ($last_id_row = mysqli_fetch_assoc($last_id_result)) {
            $last_id = $last_id_row['id'];
            $new_id = $last_id + 1;
        } else {
            $new_id = 1; // If no categories exist, start from 1
        }

        // Insert the new category with the incremented ID
        $insert_query = "INSERT INTO categories (id, category_name) VALUES ('$new_id', '$category')";
        if (mysqli_query($conn, $insert_query)) {
            echo "<script>alert('Category added successfully.');</script>";
            // Redirect to warehouse.php after successful insertion
            echo "<script>window.location.href='warehouse.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error adding category.');</script>";
        }
    }
}
?>

</body>
</html>