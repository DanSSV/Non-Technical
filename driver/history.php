<?php
include('../php/session_driver.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TriSakay | History</title>

    <?php
    include '../php/dependencies.php';
    ?>
    <?php
    include('../php/icon.php');
    ?>
    <link rel="stylesheet" href="../css/history.css">
</head>

<body>
    <?php
    include('../php/navbar_driver.php');
    ?>

    <?php
    // Include the database connection file
    include '../db/dbconn.php';

    // Function to calculate the distance between two coordinates
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

    // Create a SQL query to retrieve data from the "history_1" table and join it with the "driver" table using the "route_id" as the primary key
    $sql = "SELECT h.route_id, h.time_date, h.status, h.starting_lat, h.starting_lng, h.new_lat, h.new_lng, h.currentMarker_lat, h.currentMarker_long, h.user_id, u.name AS user_name
        FROM history_1 h
        INNER JOIN users u ON h.user_id = u.user_id
        WHERE h.status IN ('Completed', 'Cancelled')"; // Filter by status
    
    // Execute the query
    $result = mysqli_query($conn, $sql);

    // Check if the query was successful
    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }

    // Check if there are rows in the result set
    if (mysqli_num_rows($result) > 0) {
        // Create an HTML table to display the data
        echo '<table class="table">';
        echo '<thead class="table-light">';
        echo '<tr>';
        echo '<th><i class="fa-solid fa-id-card-clip fa-sm" style="color: #000000;"></i> User</th>';
        echo '<th><i class="fa-regular fa-calendar-days fa-sm" style="color: #000000;"></i> Date</th>';
        echo '<th><i class="fa-solid fa-check fa-sm" style="color: #000000;"></i> Status</th>';
        echo '<th><i class="fa-solid fa-road fa-sm" style="color: #000000;"></i> Route</th>';
        echo '<th> Fare</th>'; // Fare column added
        echo '</tr>';
        echo '</thead>';
        echo '<tbody class="table-group-divider">';

        while ($row = mysqli_fetch_assoc($result)) {
            $startingLat = $row['starting_lat'];
            $startingLng = $row['starting_lng'];
            $newLat = $row['new_lat'];
            $newLng = $row['new_lng'];

            // Calculate distance and fare
            if ($row['status'] == 'Cancelled') {
                $distance = calculateDistance($startingLat, $startingLng, $row['currentMarker_lat'], $row['currentMarker_long']);
            } else {
                $distance = calculateDistance($startingLat, $startingLng, $newLat, $newLng);
            }

            $cost = $distance <= 2 ? 30 : 30 + ($distance - 2) * 10;
            $cost = round($cost);

            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['user_name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['time_date']) . '</td>';
            echo '<td style="color: ' . ($row['status'] == 'Completed' ? 'green' : 'red') . ';">' . htmlspecialchars($row['status']) . '</td>';
            echo '<td><span id="route_' . $row['route_id'] . '">Loading...</span>';
            echo '<script>
                    fetch("https://nominatim.openstreetmap.org/reverse?format=json&lat=' . $startingLat . '&lon=' . $startingLng . '&zoom=18&addressdetails=1")
                        .then(response => response.json())
                        .then(startingData => {
                            var startingAddress = startingData.display_name;
                            fetch("https://nominatim.openstreetmap.org/reverse?format=json&lat=' . $newLat . '&lon=' . $newLng . '&zoom=18&addressdetails=1")
                                .then(response => response.json())
                                .then(destinationData => {
                                    var destinationAddress = destinationData.display_name;
                                    var startingParts = startingAddress.split(\', \');
                                    var destinationParts = destinationAddress.split(\', \');
                                    var startingLocation = startingParts[0];
                                    var destination = destinationParts[0];
                                    var rideRoute = "Ride from " + startingLocation + " to " + destination;
                                    document.getElementById("route_' . $row['route_id'] . '").textContent = rideRoute;
                                })
                                .catch(error => {
                                    console.log("Error: " + error);
                                    document.getElementById("route_' . $row['route_id'] . '").textContent = "Error";
                                });
                        })
                        .catch(error => {
                            console.log("Error: " + error);
                            document.getElementById("route_' . $row['route_id'] . '").textContent = "Error";
                        });
                </script></td>';
            echo '<td>₱' . $cost . '</td>';
            // Display the calculated fare
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo 'No data found in the "history_1" table.';
    }

    // Close the database connection
    mysqli_close($conn);
    ?>

</body>

</html>