<?php
include('../db/dbconn.php');

// Get the plateNumber from the session variable named 'driver'

$plateNumber = $_SESSION['driver'];
$status = 'active';

// Step 2: Query the history_1 table to find user_id
$query = "SELECT user_id FROM history_1 WHERE plate_number = ? AND status = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $plateNumber, $status);
$stmt->execute();
$stmt->bind_result($userId);
$stmt->fetch();
$stmt->close();

// Step 3: Query the users table to find the passenger's name
if (!empty($userId)) {
    $query = "SELECT name FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($name);
    $stmt->fetch();
    $stmt->close();

    // Step 4: Display the passenger's name in the <h5> tag
    echo '<h5 style="color: white;">Passenger: ' . $name . '</h5>';
} else {
    echo '<h5 style="color: white;">Passenger not found</h5>';
}

// Step 5: Close the database connection
$conn->close();
?>