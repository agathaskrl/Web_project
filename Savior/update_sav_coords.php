<?php
include_once 'connect_db.php';
session_start();

// elegxos an to request method einai POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Decode ta JSON data pou stelnontai sto swma tou request 
    $data = json_decode(file_get_contents("php://input"));

    // elegxos an ta lat kai lng parameters einai orismena sta decoded data
    if (isset($data->lat) && isset($data->lng)) {
        // pairnei ta lat kai lng values apo ta decoded data
        $lat = $data->lat;
        $lng = $data->lng;

        // elegxos an o xristis einai logged in
        if (isset($_SESSION['username'])) {
            // pairnei username from session
            $username = $_SESSION['username'];

            // Prepare SQL statement gia na kanei update tis syntetagmenes sti vash
            $sql = "UPDATE coordinates SET lat = ?, lng = ? WHERE username = ?";

            // Prepare kai bind parameters
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("dds", $lat, $lng, $username);

            // Ektelesi SQL statement
            if ($stmt->execute()) {
                echo "Coordinates updated successfully: Lat: $lat, Lng: $lng";
            } else {
                echo "Error updating coordinates: " . $stmt->error;
            }

            // kleinei to statement
            $stmt->close();
        } else {
            // an o xristis den einai logged in, error response
            http_response_code(401); // Unauthorized
            echo "Error: User not logged in";
        }
    } else {
        // an ta lat kai lng den einai parametroi sta decoded dedomena, error response
        http_response_code(400); // Bad Request
        echo "Error: Missing lat or lng parameters";
    }
} else {
    // an to request method den einai POST, error response
    http_response_code(405); // den einai apodekti i methodos
    echo "Invalid request method";
}
?>
