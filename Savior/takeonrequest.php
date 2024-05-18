<?php
session_start();
include_once 'connect_db.php';

$_POST = json_decode(file_get_contents('php://input'), true);

if(isset($_POST['req_Id'])) {

    $req_Id = $_POST['req_Id'];
    
    if(isset($_SESSION['username'])) {
        $sav_usrnm = $_SESSION['username'];
        

        $curen_time = date('Y-m-d H:i:s');
         
        $query = "SELECT under_tasks FROM vehicle WHERE veh_username = '$sav_usrnm'";
        $result = mysqli_query($conn, $query);

        $sql = "UPDATE requests 
                SET veh_username = '$sav_usrnm', under_date = '$curen_time' , status = 'ONGOING'
                WHERE req_id = $req_Id";
        if($result == 4 ) { 
            echo'You reached the maximum limit of tasks!';
            exit();
        if($conn->query($sql) === TRUE) {
            if($conn->affected_rows > 0) {
                echo "Request taken on successfully";
            } else {
                echo "Request already taken or invalid offer ID.";
            }
        } else {
            echo "Error taking on request: " . $conn->error;
        }
    } else {
        echo "Error: Session username not found!";
    }
} else {
    echo "Error: Request ID not provided!";
}
}
$conn->close();

