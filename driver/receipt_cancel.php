<?php
include('../php/session_driver.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <?php
    include('../php/dependencies.php');
    ?>
    <link rel="stylesheet" href="../css/receipt.css">
</head>

<body>
    <?php
    include('../php/navbar_driver.php');
    ?>

    <div class="receipt">
        <div class="items">
            <?php
            include('../db/dbconn.php');

            function calculateDistance($starting_lat, $starting_lng, $currentMarker_lat, $currentMarker_long)
            {
                $waypoints = "$starting_lng,$starting_lat;$currentMarker_long,$currentMarker_lat";
                $url = "https://router.project-osrm.org/route/v1/driving/$waypoints?geometries=geojson";
                $data = file_get_contents($url);
                $response = json_decode($data, true);
                $distanceInMeters = $response['routes'][0]['distance'];
                $distanceInKilometers = $distanceInMeters / 1000;
                return $distanceInKilometers;
            }

            if (isset($_POST['confirm'])) {
                // Get the route_id from the submitted form
                $confirmedRouteId = $_POST['confirm'];
                // Update the status to 'completed' for the specified route_id
                $updateStatusQuery = "UPDATE history_1 SET status = 'Cancelled' WHERE route_id = '$confirmedRouteId'";
                mysqli_query($conn, $updateStatusQuery);

                // Redirect to driver.php
                header('Location: driver.php');
                exit();
            }

            // Fetch the latest record from history_1 table where status = 'active'
            $sql = "SELECT route_id, time_date, starting_lat, starting_lng, currentMarker_lat, currentMarker_long, user_id, plate_number FROM history_1 WHERE  status = 'active' ORDER BY time_date DESC LIMIT 1";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $route_id = $row['route_id']; // Store the route_id in a variable
                $distance = calculateDistance($row['starting_lat'], $row['starting_lng'], $row['currentMarker_lat'], $row['currentMarker_long']);
                $cost = $distance <= 2 ? 30 : 30 + ($distance - 2) * 10;
                $cost = round($cost);

                // Retrieve user info from the users table using user_id
                $userId = $row['user_id'];
                $userInfoQuery = "SELECT name FROM users WHERE user_id='$userId'";
                $userInfoResult = mysqli_query($conn, $userInfoQuery);
                $userInfo = mysqli_fetch_assoc($userInfoResult);

                // Retrieve driver info from the driver table
                $driverPlateNumber = $row['plate_number'];
                $driverInfoQuery = "SELECT name, body_number, plate_number FROM driver WHERE plate_number='$driverPlateNumber'";
                $driverInfoResult = mysqli_query($conn, $driverInfoQuery);
                $driverInfo = mysqli_fetch_assoc($driverInfoResult);

                echo "<p>Date: {$row['time_date']}</p>";
                echo "<h5>Please confirm to cancel the ride.</h5>";
                echo "<hr>";
                echo "<p>Driver: {$driverInfo['name']}</p>";
                echo "<p>Body Number: {$driverInfo['body_number']}</p>";
                echo "<hr>";
                echo "<p>Passenger: {$userInfo['name']}</p>"; // Display user's name
                echo "<p>Distance traveled: {$distance} km</p>";
                echo "<p>Fare: ₱{$cost}</p>";
                echo "<hr>";

                // Add a form with a hidden input for the route to confirm
                echo "<form method='post'>";
                echo "<input type='hidden' name='confirm' value='{$row['route_id']}'>";
                echo "<button type='submit' class='btn btn-default custom-btn'>";
                echo "<i class='fa-solid fa-check fa-lg' style='color: #ffffff;'></i> Confirm";
                echo "</button>";
                echo "</form>";
            } else {
                echo "<p>No records found</p>";
            }

            mysqli_close($conn);
            ?>
        </div>
    </div>
</body>

</html>