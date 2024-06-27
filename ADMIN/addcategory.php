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

    // Elegxos gia to an yparxei hdh ayth h kathgorias\
    $query = "SELECT * FROM categories WHERE category_name = '$category'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Category already exists. Please choose a different name.');</script>";
    } else {
        // Fernei to teleytaio id apo to pinaka categories
        $last_id_query = "SELECT id FROM categories ORDER BY id DESC LIMIT 1";
        $last_id_result = mysqli_query($conn, $last_id_query);

        if ($last_id_row = mysqli_fetch_assoc($last_id_result)) {
            $last_id = $last_id_row['id'];
            $new_id = $last_id + 1;
        } else {
            $new_id = 1; //An oxi jekinaei apo to 1
        }

        // Eisagei to neo prostethimeno id kata 1
        $insert_query = "INSERT INTO categories (id, category_name) VALUES ('$new_id', '$category')";
        if (mysqli_query($conn, $insert_query)) {
            echo "<script>alert('Category added successfully.');</script>";
            //Anakateythinsh sto warehouse an htan epityxhs
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
