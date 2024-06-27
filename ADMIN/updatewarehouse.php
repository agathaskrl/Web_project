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
        <?php
    include_once 'connect_db.php';
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $query = "SELECT role FROM user WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['role'] = $row['role']; 

    }
}

function checkLoggedIn() {
      // Elegxos gia to an o xrhsths einai syndedemenos
    if (!isset($_SESSION['username'])) {
        echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
        echo 'User not logged in!';
        echo '</div>';
        exit(); 
    }
        // Elegxos gia to an o rolos tou xrhsth einai  "SAVIOR" h "CITIZEN" kai aporich peraitero prosvashs
    if (isset($_SESSION['role']) && ($_SESSION['role'] == "SAVIOR" || $_SESSION['role'] == "CITIZEN")) {
        echo '<div style="text-align: center; padding: 80px; color: rgba(76, 56, 30, 1); ">';
        echo 'Unauthorized access!';
        echo '</div>';
        exit(); 
    }
}

checkLoggedIn();
?>
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
    // Adiasma tou pinaka procucst
    $truncateProducts = mysqli_query($conn, "TRUNCATE TABLE products");
    if ($truncateProducts) {
        echo "Products table truncated successfully.";
    } else {
        echo "Error truncating products table: " . mysqli_error($conn);
    }

    // Adiasma toy pinaka categories
    $truncateCategories = mysqli_query($conn, "TRUNCATE TABLE categories");
    if ($truncateCategories) {
        echo "Categories table truncated successfully.";
    } else {
        echo "Error truncating categories table: " . mysqli_error($conn);
    }

 
    exit();
}
if (isset($_FILES['file'])) {
    
    //Dedomena json apo to arxeio 
    $jsonData = file_get_contents($_FILES['file']['tmp_name']);

    //Apokodikopoihsh twn deodmenen se pinaka
    $data = json_decode($jsonData, true);

    if ($data === null) {
        echo "Invalid JSON data.";
        exit();
    }

    //Proetimasia twn quries
    $sql = "INSERT INTO products (id, name, category, detail_name, detail_value, quantity) VALUES (?, ?, ?, ?, ?, FLOOR(RAND()*(200-50+1))+50) 
        ON DUPLICATE KEY UPDATE name = VALUES(name), category = VALUES(category), detail_name = VALUES(detail_name), detail_value = VALUES(detail_value), quantity = FLOOR(RAND()*(200-50+1))+50;";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $id, $name, $category, $detail_name, $detail_value);
//Gia kathe item 
foreach ($data['items'] as $item) {
    //Elegxei ean yparxei sth vash ston pinaka products
    $existingRecord = mysqli_query($conn, "SELECT * FROM products WHERE id = '{$item['id']}'");
    if (mysqli_num_rows($existingRecord) > 0) {
        echo "Record with ID {$item['id']} already exists. Skipping insertion.<br>";
        continue;
    }

    //Ejagwgh twn deodmenwn apo ton pinaka
    $id = $item['id'];
    $name = $item['name'];
    $category = $item['category'];
    foreach ($item['details'] as $detail) {
        $detail_name = isset($detail['detail_name']) ? $detail['detail_name'] : '';
        $detail_value = isset($detail['detail_value']) ? $detail['detail_value'] : '';

        //Eketelash twn queries
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
    //Epilegoume na ta onomasou categories giati etsi einai orismeno sto json arxeio 
    foreach ($data['categories'] as $category) {
        //Exagwgh
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


// Kleisimo twn syndesewn
$stmt->close();
$stmt2->close();
$conn->close();
} 
?>
