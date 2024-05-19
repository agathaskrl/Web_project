<?php
session_start();
include_once 'connect_db.php';

$_POST = json_decode(file_get_contents('php://input'), true);

if (isset($_POST['req_Id'])) {
    $req_Id = $_POST['req_Id'];

    if (isset($_SESSION['username'])) {
        $sav_usrnm = $_SESSION['username'];

        // Check the number of tasks for the vehicle
        $checkTasksQuery = "SELECT under_tasks FROM vehicle WHERE sav_username = '$sav_usrnm'";
        $tasksResult = $conn->query($checkTasksQuery);

        if ($tasksResult) {
            $row = $tasksResult->fetch_assoc();
            $underTasks = $row['under_tasks'];

            // Check if the vehicle can take on another task
            if ($underTasks < 4) {
                $underTasks++;

                // Update the request in the database
                $curen_time = date('Y-m-d H:i:s');
                $sql = "UPDATE requests 
                        SET veh_username = '$sav_usrnm', under_date = '$curen_time', status = 'ONGOING'
                        WHERE req_id = $req_Id";
                if ($conn->query($sql) === TRUE) {
                    if ($conn->affected_rows > 0) {
                        // Update the tasks count
                        $updateTasksQuery = "UPDATE vehicle SET under_tasks = $underTasks WHERE sav_username = '$sav_usrnm'";
                        if ($conn->query($updateTasksQuery)) {
                            echo json_encode(["message" => "Request taken on successfully"]);
                        } else {
                            echo json_encode(["error" => "Error updating under_tasks count: " . $conn->error]);
                        }
                    } else {
                        echo json_encode(["error" => "Request already taken or invalid offer ID."]);
                    }
                } else {
                    echo json_encode(["error" => "Error taking on request: " . $conn->error]);
                }
            } else {
                echo json_encode(["error" => "Cannot take on another task. You already have 4 tasks assigned."]);
            }
        } else {
            echo json_encode(["error" => "Error fetching under_tasks count: " . $conn->error]);
        }
    } else {
        echo json_encode(["error" => "Error: Session username not found!"]);
    }
} else {
    echo json_encode(["error" => "Error: Request ID not provided!"]);
}

$conn->close();