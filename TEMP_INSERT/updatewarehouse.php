<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Update Warehouse</title>
    <link rel="stylesheet" href="adstyle.css?v=4">
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
            <h1>Items File Upload</h1>
            <form id="uploadForm" enctype="multipart/form-data">
                <div>
                    <label for="file">Select File:</label>
                    <br><br>
                    <input type="file" id="file-selector" name="file" accept=".json"/>
                    <br><br>
                    <input type="submit" value="Upload"/> 
                </div>
            </form>
        </div>
    </div>



</body>
</html>

<?php
if (isset($_FILES['file'])) {
    include_once 'connect_db.php';

    // Function to check if the user is logged in
    function checkLoggedIn()
    {
        session_start();
        if (!isset($_SESSION['username'])) {
            echo '<div style="text-align: center; padding: 80px; background-color: rgb(247, 240, 235); color: rgba(76, 56, 30, 1); ">';
            echo 'User not logged in. Please <a href="login.php">Log in!</a>.';
            echo '</div>';
            exit(); // Exit the script
        }
    }
    checkLoggedIn(); // Call the function to check if the user is logged in 

    // Get the JSON data from the uploaded file
    $jsonData = file_get_contents($_FILES['file']['tmp_name']);

    // Decode the JSON data into an associative array
    $data = json_decode($jsonData, true);

    if ($data === null) {
        echo "Invalid JSON data.";
        exit();
    }

    // Prepare the statement outside the loop
    $sql = "INSERT INTO products (id, name, category, detail_name, detail_value) VALUES (?, ?, ?, ?, ?) 
        ON DUPLICATE KEY UPDATE name = VALUES(name), category = VALUES(category), detail_name = VALUES(detail_name), detail_value = VALUES(detail_value)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $id, $name, $category, $detail_name, $detail_value);
// Loop through each item in the data array
foreach ($data['items'] as $item) {
    // Check if the record already exists in the database
    $existingRecord = mysqli_query($conn, "SELECT * FROM products WHERE id = '{$item['id']}'");
    if (mysqli_num_rows($existingRecord) > 0) {
        echo "Record with ID {$item['id']} already exists. Skipping insertion.<br>";
        continue;
    }

    // Extract item details
    $id = $item['id'];
    $name = $item['name'];
    $category = $item['category'];
    foreach ($item['details'] as $detail) {
        $detail_name = isset($detail['detail_name']) ? $detail['detail_name'] : '';
        $detail_value = isset($detail['detail_value']) ? $detail['detail_value'] : '';

        // Execute the statement
        if ($stmt->execute()) {
            echo "Item inserted successfully.<br>";
        } else {
            echo "Error inserting item: " . $stmt->error;
        }
    }
}

// Close the prepared statement
$stmt->close();

// Close the database connection
$conn->close();
} else {
echo "No file uploaded!";
}
?>
