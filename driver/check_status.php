<?php

require '../db/dbconn.php';

$plateNumber = $_POST['plateNumber'];

$query = "SELECT * FROM history_1 WHERE status = 'active' AND plate_number = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $plateNumber);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(array('status' => 'match'));
} else {
    echo json_encode(array('status' => 'no match'));
}

$stmt->close();
$conn->close();
?>