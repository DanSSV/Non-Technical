<?php
include('../php/session_commuter.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TriSakay | My Location</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
        integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
        integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <?php
    include '../php/dependencies.php';
    ?>
    <link rel="stylesheet" href="../css/done.css">
    <?php
    include('../php/icon.php');
    ?>

</head>

<body>
    <?php
    include('../php/navbar_commuter.php');
    ?>
    <div class="loc">
        <div id="map"></div>
    </div>

    <div class="info">
        <h1>Current Location</h1>

        <div class="mb-2">
            <button type="submit" class="btn btn-default custom-btn" onclick="redirectToCommuter()">
                <i class="fa-solid fa-rotate-left fa-lg" style="color: #00000;"></i> Back
            </button>
        </div>
        <script src="../js/button.js"></script>
    </div>
    <script>
        var map = L.map("map").setView([14.954324668012775, 120.90080124844123], 17);
        L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 17,
            zoomControl: false // Remove zoom controls
        }).addTo(map);
        map.zoomControl.remove();

        var userLocationMarker;

        map.on("locationfound", function (e) {
            if (userLocationMarker) {
                map.removeLayer(userLocationMarker);
            }
            var signalStrength = e.accuracy; // Signal strength based on accuracy
            var signalStrengthCategory, textColor;

            if (signalStrength <= 40) {
                signalStrengthCategory = 'Good';
                textColor = 'green';
            } else if (signalStrength <= 80) {
                signalStrengthCategory = 'Fair';
                textColor = 'yellow';
            } else {
                signalStrengthCategory = 'Bad';
                textColor = 'red';
            }

            userLocation = e.latlng;
            userLocationMarker = L.marker(userLocation).addTo(map);
            userLocationMarker.bindPopup(`Signal Strength: <span style="color: ${textColor};">${signalStrengthCategory}</span><br>You are here!`).openPopup();
        });

        map.on("locationerror", function (e) {
            alert(e.message);
        });

        var watchID = navigator.geolocation.watchPosition(
            function (position) {
                userLocation = [position.coords.latitude, position.coords.longitude];
                map.setView(userLocation, 17);

                if (userLocationMarker) {
                    map.removeLayer(userLocationMarker);
                }

                var signalStrength = position.coords.accuracy; // Signal strength based on accuracy
                var signalStrengthCategory, textColor;

                if (signalStrength <= 40) {
                    signalStrengthCategory = 'Good';
                    textColor = 'green';
                } else if (signalStrength <= 80) {
                    signalStrengthCategory = 'Fair';
                    textColor = 'yellow';
                } else {
                    signalStrengthCategory = 'Bad';
                    textColor = 'red';
                }

                userLocationMarker = L.marker(userLocation).addTo(map);
                userLocationMarker.bindPopup(`Signal Strength: <span style="color: ${textColor};">${signalStrengthCategory}</span><br>You are here!`).openPopup();

                axios
                    .get(
                        `https://nominatim.openstreetmap.org/reverse?format=json&lat=${userLocation[0]}&lon=${userLocation[1]}`
                    )
                    .then(response => {
                        const address = response.data.display_name;
                        const h1 = document.querySelector("h1");
                        const addressParts = address.split(",");
                        const filteredAddressParts = addressParts.slice(0, addressParts.length - 3);
                        const filteredAddress = filteredAddressParts.join(",");
                        h1.textContent = filteredAddress;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            function (error) {
                console.log("Error getting user location:", error);
            },
            { enableHighAccuracy: true, maximumAge: 10000, timeout: 10000 }
        );
    </script>
</body>

</html>