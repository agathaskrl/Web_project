<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'connect_db.php';

if(isset($_POST['search'])){
    $search = mysqli_real_escape_string($conn,$_POST['search']);

    //fetch product names
    $productQuery = "SELECT name FROM products WHERE name LIKE '%" . $search . "%'";
    $productResult = mysqli_query($conn,$productQuery);

    if (!$productResult) {
        die('Error in product query: ' . mysqli_error($conn));
    }

    $productResponse = array();
    while($row = mysqli_fetch_array($productResult) ){
        $productResponse[] = array("label"=>$row['name'], "type" => "Product");
    }

    //fetch category names 
    $categoryQuery = "SELECT category_name FROM categories WHERE category_name LIKE '%" . $search . "%'";
    $categoryResult = mysqli_query($conn,$categoryQuery);

    if (!$categoryResult) {
        die('Error in category query: ' . mysqli_error($conn));
    }

    $categoryResponse = array();
    while($row = mysqli_fetch_array($categoryResult)){
        $categoryResponse[] = array("label" => $row['category_name'], "type" => "Category");
    }

    $response = array_merge($productResponse, $categoryResponse); 

    echo json_encode($response);

    exit;
}