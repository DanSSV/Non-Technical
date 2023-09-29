<?php
// Include your database connection code (e.g., dbconn.php)
include '../db/dbconn.php';

// Check if latitude, longitude, and plate_number are provided
if (isset($_POST['lat']) && isset($_POST['lng']) && isset($_POST['plate_number'])) {
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $plateNumber = $_POST['plate_number'];

    // Find the latest row with status 'active,' plate_number 'GHI-123,' and the latest time_date
    $sql = "SELECT route_id FROM history_1 WHERE status = 'active' AND plate_number = ? ORDER BY time_date DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $plateNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Fetch the ID of the latest row
        $row = $result->fetch_assoc();
        $latestId = $row['route_id'];

        // Update the currentMarker_lat and currentMarker_long for the latest row
        $updateSql = "UPDATE history_1 SET currentMarker_lat = ?, currentMarker_long = ? WHERE route_id = ?";
        $stmt = $conn->prepare($updateSql);

        // Bind parameters and execute the statement
        $stmt->bind_param("ddi", $lat, $lng, $latestId);

        if ($stmt->execute()) {
            echo "Coordinates updated successfully.";
        } else {
            echo "Error updating coordinates: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "No active records found for plate number $plateNumber.";
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Latitude, longitude, or plate_number not provided.";
}
?>
