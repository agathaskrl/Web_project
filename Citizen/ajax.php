<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once 'connect_db.php';

if(isset($_POST['search'])){
    $search = mysqli_real_escape_string($conn,$_POST['search']);

    $query = "SELECT name FROM products WHERE name LIKE '%" . $search . "%'";
    $result = mysqli_query($conn,$query);

    $response = array();
    while($row = mysqli_fetch_array($result) ){
        $response[] = array("label"=>$row['name']);
    }

echo json_encode($response);
    

exit;
}