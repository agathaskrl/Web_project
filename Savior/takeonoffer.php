<?php
session_start();
include_once 'connect_db.php';

$_POST = json_decode(file_get_contents('php://input'), true);

if (isset($_POST['offerId'])) {
    $offerId = $_POST['offerId'];

    if (isset($_SESSION['username'])) {
        $sav_usrnm = $_SESSION['username'];

        // elegxos tou arithmou twn tasks gia ton savior
        $checkTasksQuery = "SELECT under_tasks FROM vehicle WHERE sav_username = '$sav_usrnm'";
        $tasksResult = $conn->query($checkTasksQuery);

        if ($tasksResult) {
            $row = $tasksResult->fetch_assoc();
            $underTasks = $row['under_tasks'];

            // elegxos an mporei na parei allo task
            if ($underTasks < 4) {
                $underTasks++;

                // Update to offer sti vash
                $curen_time = date('Y-m-d H:i:s');
                $sql = "UPDATE offers 
                        SET usrnm_veh = '$sav_usrnm', ret_date = '$curen_time', status = 'ONGOING'
                        WHERE offer_id = $offerId";
                if ($conn->query($sql) === TRUE) {
                    if ($conn->affected_rows > 0) {
                        $updateTasksQuery = "UPDATE vehicle SET under_tasks = $underTasks WHERE sav_username = '$sav_usrnm'";
                        if ($conn->query($updateTasksQuery)) {
                            echo json_encode(["message" => "Offer taken on successfully"]);
                        } else {
                            echo json_encode(["error" => "Error updating tasks count: " . $conn->error]);
                        }
                    } else {
                        echo json_encode(["error" => "Offer already taken or invalid offer ID."]);
                    }
                } else {
                    echo json_encode(["error" => "Error taking on offer: " . $conn->error]);
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
    echo json_encode(["error" => "Error: Offer ID not provided!"]);
}


$conn->close();

