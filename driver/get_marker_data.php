<?php
require_once '../db/dbconn.php'; // Include the database connection

// Query the database for the latest data based on time_date
$sql = "SELECT starting_lat, starting_lng, new_lat, new_lng FROM history_1 WHERE status = 'active' AND plate_number = 'GHI-123' ORDER BY time_date DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Return data as JSON
    echo json_encode($row);
} else {
    echo json_encode(array()); // Return an empty JSON object if no data found
}

$conn->close();
?>