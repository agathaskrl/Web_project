<?php
include_once 'connect_db.php';

// Check if the timePeriod parameter is set and valid
if (isset($_GET['timePeriod'])) {
    $timePeriod = $_GET['timePeriod'];
} else {
    $timePeriod = 7;
}

// Calculate start date based on the selected time period
switch ($timePeriod) {
    case '7':
        $startDate = date('Y-m-d', strtotime('-7 days'));
        break;
    case '30':
        $startDate = date('Y-m-d', strtotime('-1 month'));
        break;
    case '90':
        $startDate = date('Y-m-d', strtotime('-3 months'));
        break;
    default:
        $startDate = date('Y-m-d', strtotime('-7 days')); 
        break;
}

try {
    $data = [
        'labels' => ['New Requests', 'New Offers', 'Complete Requests', 'Complete Offers'],
        'datasets' => []
    ];

    // Prepare and execute queries
    $queries = [
        "SELECT COUNT(*) AS count FROM requests WHERE req_date >= ? AND status = ''" => 'New Requests',
        "SELECT COUNT(*) AS count FROM offers WHERE subm_date >= ? AND status = ''" => 'New Offers',
        "SELECT COUNT(*) AS count FROM requests WHERE status = 'COMPLETE' AND req_date >= ?" => 'Complete Requests',
        "SELECT COUNT(*) AS count FROM offers WHERE status = 'COMPLETE' AND subm_date >= ?" => 'Complete Offers'
    ];

    $counts = [];
    foreach ($queries as $query => $label) {
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }
        $stmt->bind_param('s', $startDate);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        $counts[] = $count;
    }

    $data['datasets'] = [
        [
            'label' => 'New Requests',
            'data' => [$counts[0]],
            'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
            'borderColor' => 'rgba(75, 192, 192, 1)',
            'borderWidth' => 1
        ],
        [
            'label' => 'New Offers',
            'data' => [$counts[1]],
            'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
            'borderColor' => 'rgba(255, 99, 132, 1)', 
            'borderWidth' => 1
        ],
        [
            'label' => 'Complete Requests',
            'data' => [$counts[2]],
            'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
            'borderColor' => 'rgba(255, 206, 86, 1)',
            'borderWidth' => 1
        ],
        [
            'label' => 'Complete Offers',
            'data' => [$counts[3]],
            'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
            'borderColor' => 'rgba(153, 102, 255, 1)',
            'borderWidth' => 1
        ]
    ];

   
    header('Content-Type: application/json');

    echo json_encode($data);

} catch (Exception $e) {
    echo json_encode(['error' => 'Caught exception: ' . $e->getMessage()]);
}
