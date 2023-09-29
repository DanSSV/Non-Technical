<?php
require_once "db/dbconn.php"; // Make sure to adjust the path if needed

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST["username"];
    $input_password = $_POST["password"];

    // Prepare and execute a SQL query to retrieve user data
    $stmt = $conn->prepare("SELECT user_id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $input_username);
    $stmt->execute();
    $stmt->store_result();

    // Bind the result to variables
    $stmt->bind_result($user_id, $password, $role);
    $stmt->fetch();

    // Verify the password and role
    if ($stmt->num_rows == 1 && $input_password == $password) {
        // Set custom session ID based on user_id
        session_id($user_id);
        session_start();

        $_SESSION["user_id"] = $user_id;
        // No need to store role in session if it's only for redirection

        if ($role == "driver") {
            header("Location: driver/driver.php"); // Redirect to the driver dashboard
        } elseif ($role == "commuter") {
            header("Location: commuter/commuter.php"); // Redirect to the commuter dashboard
        } elseif ($role == "admin") {
            header("Location: admin/admin.php"); // Redirect to the admin dashboard
        } else {
            // Handle redirection for other roles here
            // For example: header("Location: default.php");
        }

        $stmt->close(); // Close the statement
        exit();
    } else {
        $stmt->close(); // Close the statement

        header("Location: index.php?error=true"); // Redirect with error parameter
        exit();
    }
}

// Close the connection
$conn->close();
?>