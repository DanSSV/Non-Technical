<?php
// Include your database connection code here
include('../db/dbconn.php');

// Get the values from the HTML form
$startingMarkerInfo = $_POST['startingMarkerInfo'];
$newMarkerInfo = $_POST['newMarkerInfo'];

// Insert the values into the database
$query = "INSERT INTO history_1 (starting_marker, new_marker) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $startingMarkerInfo, $newMarkerInfo);

if ($stmt->execute()) {
    // Insertion successful
    
} else {
    // Error occurred
     $stmt->error;
}

// Close the database connection
$stmt->close();
$conn->close();
?>