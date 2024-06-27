<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Update Warehouse</title>
    <link rel="stylesheet" href="adstyle.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="insert.js"></script>
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
        <br>

        <div class="container">
            <h1>File Upload</h1>
            <form id="uploadForm" enctype="multipart/form-data">
                <div>
                    <label for="file">Select File:</label>
                    <br><br>
                    <input type="file" id="file-selector" name="file" accept=".json"/>
                    <br><br>
                    <input type="submit" value="Upload"/> 
                </div>
            </form>
            <br>
           <div> 
            <input type="button" id="deleteelementsbtn" class="deleteelementsbtn" value="Delete Elements"/> 
        </div>
    </div>
</div>



</body>
</html>

<?php
include_once 'connect_db.php';
if (isset($_POST['truncate_elements'])) {
    // Truncate the products table
    $truncateProducts = mysqli_query($conn, "TRUNCATE TABLE products");
    if ($truncateProducts) {
        echo "Products table truncated successfully.";
    } else {
        echo "Error truncating products table: " . mysqli_error($conn);
    }

    // Truncate the categories table
    $truncateCategories = mysqli_query($conn, "TRUNCATE TABLE categories");
    if ($truncateCategories) {
        echo "Categories table truncated successfully.";
    } else {
        echo "Error truncating categories table: " . mysqli_error($conn);
    }

    exit();
}
if (isset($_FILES['file'])) {
    
    //json data from te file
    $jsonData = file_get_contents($_FILES['file']['tmp_name']);

    //decode the json into an array 
    $data = json_decode($jsonData, true);

    if ($data === null) {
        echo "Invalid JSON data.";
        exit();
    }

    $sql = "INSERT INTO products (id, name, category, detail_name, detail_value, quantity) VALUES (?, ?, ?, ?, ?, FLOOR(RAND()*(200-50+1))+50) 
        ON DUPLICATE KEY UPDATE name = VALUES(name), category = VALUES(category), detail_name = VALUES(detail_name), detail_value = VALUES(detail_value), quantity = FLOOR(RAND()*(200-50+1))+50;";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $id, $name, $category, $detail_name, $detail_value);
foreach ($data['items'] as $item) {
    //check if any record exists in the database
    $existingRecord = mysqli_query($conn, "SELECT * FROM products WHERE id = '{$item['id']}'");
    if (mysqli_num_rows($existingRecord) > 0) {
        echo "Record with ID {$item['id']} already exists. Skipping insertion.<br>";
        continue;
    }

    //extract the data from the array 
    $id = $item['id'];
    $name = $item['name'];
    $category = $item['category'];
    foreach ($item['details'] as $detail) {
        $detail_name = isset($detail['detail_name']) ? $detail['detail_name'] : '';
        $detail_value = isset($detail['detail_value']) ? $detail['detail_value'] : '';
 
        if ($stmt->execute()) {
            echo "Item inserted successfully.<br>";
        } else {
            echo "Error inserting item: " . $stmt->error;
        }
    }
}
if (isset($_FILES['file'])) {
    $sql2 = "INSERT INTO categories (id, category_name) VALUES (?, ?) ON DUPLICATE KEY UPDATE category_name = VALUES(category_name)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("is", $id, $category_name);
    //We choose categories bracause thats what its called in the json so we can extract the data 
    foreach ($data['categories'] as $category) {
        $id = $category['id'];
        $category_name = $category['category_name'];

        
        $existingRecord = mysqli_query($conn, "SELECT * FROM categories WHERE id = '{$id}'");
        if (mysqli_num_rows($existingRecord) > 0) {
            echo "Record with ID {$id} already exists. Skipping insertion.<br>";
            continue;
        }
        
        if ($stmt2->execute()) {
            echo "Item inserted successfully.<br>";
            
        } else {
            echo "Error inserting item: " . $stmt2->error;
        }
    }
}


$stmt->close();
$stmt2->close();
$conn->close();
} 
?>
