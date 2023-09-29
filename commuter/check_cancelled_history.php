<?php
include('../db/dbconn.php'); // Include the database connection file

$sessionDriver = $_SESSION['driver'];

function checkForCancelledHistory()
{
    global $conn, $sessionDriver;

    $sql = "SELECT * FROM history_1 WHERE user_id = 2 AND plate_number = '$sessionDriver' AND status = 'Cancelled' ORDER BY time_date DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        header("Location: receipt_cancel.php");
        exit();
    }
}
checkForCancelledHistory();
?>