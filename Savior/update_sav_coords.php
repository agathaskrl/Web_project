<?php
include_once 'connect_db.php';
session_start();

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Decode the JSON data sent in the request body
    $data = json_decode(file_get_contents("php://input"));

    // Check if lat and lng parameters are set in the decoded data
    if (isset($data->lat) && isset($data->lng)) {
        // Get lat and lng values from the decoded data
        $lat = $data->lat;
        $lng = $data->lng;

        // Check if user is logged in
        if (isset($_SESSION['username'])) {
            // Get username from session
            $username = $_SESSION['username'];

            // Prepare SQL statement to update coordinates in the database
            $sql = "UPDATE coordinates SET lat = ?, lng = ? WHERE username = ?";

            // Prepare and bind parameters
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("dds", $lat, $lng, $username);

            // Execute the SQL statement
            if ($stmt->execute()) {
                echo "Coordinates updated successfully: Lat: $lat, Lng: $lng";
            } else {
                echo "Error updating coordinates: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        } else {
            // If user is not logged in, return an error response
            http_response_code(401); // Unauthorized
            echo "Error: User not logged in";
        }
    } else {
        // If lat and lng parameters are not set in the decoded data, return an error response
        http_response_code(400); // Bad Request
        echo "Error: Missing lat or lng parameters";
    }
} else {
    // If the request method is not POST, return an error response
    http_response_code(405); // Method Not Allowed
    echo "Invalid request method";
}
?>