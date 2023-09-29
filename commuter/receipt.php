<?php
include('../php/session_commuter.php');
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
    include('../php/navbar_commuter.php');
    ?>

    <div class="receipt">
        <div class="items">
            <?php
            include('../db/dbconn.php');

            function calculateDistance($starting_lat, $starting_lng, $new_lat, $new_lng)
            {
                $waypoints = "$starting_lng,$starting_lat;$new_lng,$new_lat";
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
                $updateStatusQuery = "UPDATE history_1 SET status = 'Completed' WHERE route_id = '$confirmedRouteId'";
                mysqli_query($conn, $updateStatusQuery);

                // Redirect to commuter.php
                header('Location: commuter.php');
                exit();
            }

            // Fetch the latest record from history_1 table where user_id = $_SESSION["user_id"] and status = 'active'
            $user_id = $_SESSION["user_id"];
            $sql = "SELECT route_id, time_date, starting_lat, starting_lng, new_lat, new_lng, plate_number FROM history_1 WHERE user_id = '$user_id' AND status = 'active' ORDER BY time_date DESC LIMIT 1";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $route_id = $row['route_id']; // Store the route_id in a variable
                $distance = calculateDistance($row['starting_lat'], $row['starting_lng'], $row['new_lat'], $row['new_lng']);
                $cost = $distance <= 2 ? 30 : 30 + ($distance - 2) * 10;
                $cost = round($cost);

                // Retrieve driver info from the driver table
                $driverPlateNumber = $row['plate_number'];
                $driverInfoQuery = "SELECT name, body_number, plate_number FROM driver WHERE plate_number='$driverPlateNumber'";
                $driverInfoResult = mysqli_query($conn, $driverInfoQuery);
                $driverInfo = mysqli_fetch_assoc($driverInfoResult);

                echo "<p>Date: {$row['time_date']}</p>";
                echo "<hr>";
                echo "<p>Driver: {$driverInfo['name']}</p>";
                echo "<p>Body Number: {$driverInfo['body_number']}</p>";
                echo "<hr>";
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