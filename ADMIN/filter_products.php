<?php
include_once 'connect_db.php';

//get he selected categories with ajax requests 
$data = json_decode(file_get_contents("php://input"));

if (isset($data->categories)) {
    $selectedCategories = implode(',', $data->categories);

    $sql = "SELECT products.id AS id, products.name AS name, categories.category_name AS category_name, products.quantity, products.detail_name, products.detail_value, products.on_vehicle 
            FROM products 
            JOIN categories ON products.category = categories.id
            WHERE products.category IN ($selectedCategories)";

    $result = mysqli_query($conn, $sql);

    //generate the products in warehouse page as they were 
    $output = '';
    $counter = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= "<tr>";
        $output .= "<td>{$counter}</td>";
        $output .= "<td>{$row['name']}</td>";
        $output .= "<td>{$row['category_name']}</td>";
        $output .= "<td>{$row['quantity']}</td>";
        $output .= "<td>{$row['detail_name']} - {$row['detail_value']}</td>";
        $output .= "<td>{$row['on_vehicle']}</td>";
        $output .= "<td>
                        <form method='post'>
                            <input type='hidden' name='product_id' value='{$row['id']}'>
                            <button type='submit' class='delbutton' name='delete'>Delete</button>
                        </form>
                    </td>";
        $output .= "<td>
                        <form method='get' action='editproduct.php'> 
                            <input type='hidden' name='product_id' value='{$row['id']}'>
                            <button type='submit' class='editbutton' name='edit'>Edit</button>
                        </form>
                    </td>";
        $output .= "</tr>";
        $counter++;
    }

    //output the products 
    echo $output;
}
?>
