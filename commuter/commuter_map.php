<?php
include('../php/session_commuter.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route | TriSakay</title>
    <!-- Include Leaflet CSS and JavaScript -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="../css/connected_maps.css">
    <style>

    </style>
    <?php
    include('../php/dependencies.php');
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <?php
    include('../php/navbar_commuter.php');
    ?>
    <div id="map" style="height: 65vh;"></div>

    <div class="info">
        <?php
    include('user_find.php');
    ?>
    </div>

    

    <button id="cancel" hidden>Cancel</button>
    

    


    <script src="map.js"></script>
    <script>
        // Function to check the status every 5 seconds
        function checkStatus() {
            var plateNumber = "GHI-123"; // Replace with your plate number

            $.ajax({
                type: "POST",
                url: "check_status.php",
                data: { plateNumber: plateNumber },
                dataType: "json",
                success: function (response) {
                    // Check the status returned from the server
                    if (response.status === "match") {
                        // Redirect to driver_map.php
                        window.location.href = "receipt_cancel.php";
                    } else {
                        // Update the status on the web page
                        $("#status").text("Status: " + response.status);
                    }
                },
                complete: function () {
                    // Schedule the next check after 5 seconds
                    setTimeout(checkStatus, 5000);
                }
            });
        }

        // Start checking the status when the page loads
        $(document).ready(function () {
            checkStatus();
        });

    </script>
</body>

</html>