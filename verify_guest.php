<?php
session_start(); // Start the session

require_once 'db/dbconn.php';

$plateNumber = $_POST['plate_number'];

$query = "SELECT COUNT(*) AS count FROM driver WHERE plate_number = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $plateNumber);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data["count"] > 0) {
    $_SESSION['driver'] = $plateNumber; // Set the license plate number as session variable 'driver'
    $response = array("exists" => true);
} else {
    $response = array("exists" => false);
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>