<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Warehouse</title>
    <link rel="stylesheet" href="adstyle.css">
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
        <div class="button-container">
            <button onclick="location.href='updatewarehouse.php'"><i class="Updatewarehouse"></i>Update Warehouse</button>
            <button onclick="location.href='addproduct.php'"><i class="addproduct"></i>Add Product</button>
        </div>
        <!-- Button to toggle category checkboxes -->
        <button onclick="toggleCategories()" id="togbtn" class="togbtn">Filter Categories</button>
        <br>
        <div id="category-con" class="category-con">
            <?php
            $category_sql = "SELECT * FROM categories";
            $category_result = mysqli_query($conn, $category_sql);
            while ($category_row = mysqli_fetch_assoc($category_result)) {
                echo "<input type='checkbox' name='category[]' value='{$category_row['id']}' style='display: inline-block; margin-right: 5px;'>";
                echo "<label style='display: inline-block;'>{$category_row['category_name']}</label><br>";
            }
            ?>
        </div>
        <br>
        <button id="filterbtn" class="filterbtn">Apply</button>

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
                        <th>Delete</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody id="products">

                        <?php
                        $sql = "SELECT products.id AS id, products.name AS name, categories.category_name AS category_name, products.quantity, products.detail_name, products.detail_value, products.on_vehicle 
                        FROM products 
                        JOIN categories ON products.category = categories.id";
                

                        $result = mysqli_query($conn, $sql);

                        $counter = 1; 
                        // Fernei kai deixnei ta proionta san pinaka
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>{$counter}</td>"; 
                            echo "<td>{$row['name']}</td>";
                            echo "<td>{$row['category_name']}</td>";
                            echo "<td>{$row['quantity']}</td>";
                            echo "<td>{$row['detail_name']} - {$row['detail_value']}</td>";
                            echo "<td>{$row['on_vehicle']}</td>";
                            echo "<td>
                                    <form method='post'>
                                        <input type='hidden' name='product_id' value='{$row['id']}'>
                                        <button type='submit' class='delbutton' name='delete'>Delete</button>
                                    </form>
                                </td>";
                            echo "<td>
                                    <form method='get' action='editproduct.php'> 
                                        <input type='hidden' name='product_id' value='{$row['id']}'>
                                        <button type='submit' class='editbutton' name='edit'>Edit</button>
                                    </form>
                                </td>";
                            echo "</tr>";
                            $counter++;
                        }

                        if (isset($_POST['delete'])) {
                            $product_id = $_POST['product_id'];
                            $delete_sql = "DELETE FROM products WHERE id = $product_id";
                            if (mysqli_query($conn, $delete_sql)) {
                                // Epanafortwsh ean htan epityxhs
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



<script> 
//Fucntion gia na filtrarei tis kathgories me toggle button kai checkboxes
function toggleCategories() {
  var categoryContainer = document.getElementById("category-con");
  categoryContainer.style.display =
    categoryContainer.style.display === "none" ? "block" : "none";
}
document.getElementById("filterbtn").addEventListener("click", function() {
    applyFilter();
});

//Function gia na ginei h apaloifh tou filrou ki na fenrei ta proionta me sygkekrimens kathgories
function applyFilter() {
    var selectedCategories = [];
    var categoryCheckboxes = document.getElementsByName("category[]");

    for (var i = 0; i < categoryCheckboxes.length; i++) {
        if (categoryCheckboxes[i].checked) {
            selectedCategories.push(categoryCheckboxes[i].value);
        }
    }

    //Stelnei tis kathgories me AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "filter_products.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
        //update the table in the warehouse
        var productsTable = document.getElementById("products");
        productsTable.innerHTML = xhr.responseText;
    }
};

    xhr.send(JSON.stringify({ categories: selectedCategories }));
}

//Function gia epanafortwsh ths selidas ean kanena filtro den epilexgei
document.getElementById("filterbtn").addEventListener("click", function() {
    var categoryCheckboxes = document.getElementsByName("category[]");
    var anyChecked = false;

    // Elegxos ean exei epilgxei opoiadhpote kathgoria
    for (var i = 0; i < categoryCheckboxes.length; i++) {
        if (categoryCheckboxes[i].checked) {
            anyChecked = true;
            break;
        }
    }

    //An oxi fernei ola ta proionta opws prin 
    if (!anyChecked) {
        window.location.reload();
    } 
    //Alliws ginetai apaloifh tou filtrou
    else {
        applyFilter();
    }
});
</script>

</body>
</html>
