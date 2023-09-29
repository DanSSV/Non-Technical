<?php
// Include the database connection file (dbconn.php)
require_once "../db/dbconn.php";

// Start a PHP session


// Check if the user is logged in (you may have your own authentication logic)
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page or handle unauthorized access as needed
    header("Location: login.php");
    exit();
}

// Get the user's session_id and plateNumber from the session
$user_id = $_SESSION['user_id'];
$plateNumber = $_SESSION['driver'];

// Update the database with the new plate_number
$sql = "UPDATE users SET plate_number = ? WHERE user_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("si", $plateNumber, $user_id);
    if ($stmt->execute()) {
        // Database update was successful
        // You can add a success message here if needed
    } else {
        // Error in executing the query
        echo "Error updating plate number: " . $stmt->error;
    }
    $stmt->close();
} else {
    // Error in preparing the SQL statement
    echo "Error preparing statement: " . $conn->error;
}

// Close the database connection
$conn->close();
?>