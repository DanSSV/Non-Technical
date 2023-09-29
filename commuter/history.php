<?php
include('../php/session_commuter.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TriSakay | Commuter</title>

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
  include('../php/navbar_commuter.php');
  ?>
  
  <?php
  // Create a SQL query to retrieve data from the "history_1" table and join it with the "driver" table using the "route_id" as the primary key
  $sql = "SELECT h.route_id, h.time_date, h.status, d.name AS driver_name, d.body_number, h.starting_lat, h.starting_lng, h.new_lat, h.new_lng
        FROM history_1 h
        INNER JOIN driver d ON h.plate_number = d.plate_number
        WHERE h.status IN ('Completed', 'Cancelled')
        AND h.user_id = " . $_SESSION["user_id"]; // Filter by status and user_id
  
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
    echo '<th style="display:none;">Route ID</th>';
    echo '<th><i class="fa-solid fa-id-card-clip fa-sm" style="color: #000000;"></i> Driver</th>';
    echo '<th> Body#</th>'; // Added the Driver Body Number column
    echo '<th><i class="fa-regular fa-calendar-days fa-sm" style="color: #000000;"></i> Date</th>';

    echo '<th><i class="fa-solid fa-check fa-sm" style="color: #000000;"></i> Status</th>';
    echo '<th><i class="fa-solid fa-road fa-sm" style="color: #000000;"></i> Route</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody class="table-group-divider">';

    while ($row = mysqli_fetch_assoc($result)) {
      echo '<tr>';
      echo '<td style="display:none;">' . htmlspecialchars($row['route_id']) . '</td>';
      echo '<td>' . htmlspecialchars($row['driver_name']) . '</td>';
      echo '<td>' . htmlspecialchars($row['body_number']) . '</td>';
      echo '<td>' . htmlspecialchars($row['time_date']) . '</td>';
      // Display the Driver Body Number
      echo '<td style="color: ' . ($row['status'] == 'Completed' ? 'green' : 'red') . ';">' . htmlspecialchars($row['status']) . '</td>';
      $startingLat = $row['starting_lat'];
      $startingLng = $row['starting_lng'];
      $destinationLat = $row['new_lat'];
      $destinationLng = $row['new_lng'];
      echo '<td><span id="route_' . $row['route_id'] . '">Loading...</span>';
      echo '<script>
                    fetch("https://nominatim.openstreetmap.org/reverse?format=json&lat=' . $startingLat . '&lon=' . $startingLng . '&zoom=18&addressdetails=1")
                        .then(response => response.json())
                        .then(startingData => {
                            var startingAddress = startingData.display_name;
                            fetch("https://nominatim.openstreetmap.org/reverse?format=json&lat=' . $destinationLat . '&lon=' . $destinationLng . '&zoom=18&addressdetails=1")
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

      echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
  } else {
    echo 'No data found in the "history_1" table for the current user.';
  }

  // Close the database connection
  mysqli_close($conn);
  ?>
</body>

</html>