<?php
// Include the database connection file
require_once('../db/dbconn.php');



// Check if the 'driver' session variable exists
if (isset($_SESSION['driver'])) {
    // Get the plate number from the session variable
    $plateNumber = $_SESSION['driver'];

    // Query to fetch driver details based on the plate number
    $sql = "SELECT Plate_Number, body_number, Name FROM driver WHERE Plate_Number = ?";

    // Prepare the SQL statement
    $stmt = mysqli_prepare($conn, $sql);

    // Bind the parameter
    mysqli_stmt_bind_param($stmt, "s", $plateNumber);

    // Execute the query
    mysqli_stmt_execute($stmt);

    // Store the result
    mysqli_stmt_store_result($stmt);

    // Check if driver details were found
    if (mysqli_stmt_num_rows($stmt) > 0) {
        // Bind the result variables
        mysqli_stmt_bind_result($stmt, $plateNumberResult, $bodyNumber, $name);

        // Fetch the results
        mysqli_stmt_fetch($stmt);

        // Close the statement
        mysqli_stmt_close($stmt);

        // Output the details with <h5> tags
        echo "<h5>Driver: " . $name . "</h5>";
        echo "<h5>Plate Number: " . $plateNumberResult . "</h5>";
        echo "<h5>Body Number: " . $bodyNumber . "</h5>";
    } else {
        // No driver details found for the given plate number
        echo "Driver not found.";
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // 'driver' session variable not set
    echo "Session variable 'driver' not set.";
}
?>