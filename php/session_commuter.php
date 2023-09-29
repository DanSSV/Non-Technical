<?php
session_start();

// Check if the user has a valid session ID
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

// Include the database connection
include('../db/dbconn.php');

// Get the user's role from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT role FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $user_role = $row['role'];

    // Check if the user's role is "commuter"
    if ($user_role !== 'commuter') {
        header("Location: ../index.php");
        exit;
    }
} else {
    // Handle database query error
    // You might want to redirect or display an error message
}
?>