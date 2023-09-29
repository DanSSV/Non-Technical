<?php
include('../php/session_commuter.php');
?>
<?php

include('driver_connect.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TriSakay | Commuter</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
        integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
        integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <?php
    include '../php/dependencies.php';
    ?>
    <link rel="stylesheet" href="../css/search.css">
    <?php
    include('../php/icon.php');
    ?>
</head>

<body>
    <?php
    include('../php/navbar_commuter.php');

    ?>



    <div class="search">
        <input id="search-input" type="text" placeholder="Where are you heading to?">
        <button id="search-button"><i class="fa-solid fa-magnifying-glass-location fa-lg" style="color: #ffffff;"></i>
            Search</button>
    </div>

    <div class="loc">
        <div id="map"></div>
    </div>

    <div class="info">
        <h1>Double click to select a destination</h1>
        <h1 id="startingMarkerInfo"></h1>
        <h1 id="newMarkerInfo"></h1>

        <h1>currentMarker latlang</h1>
        <h1></h1>
    </div>
    <!-- <form action="save_coordinates.php" method="POST">
    

    <input type="hidden" id="startingMarkerInfoInput" name="startingMarkerInfo" value="">
    <input type="hidden" id="newMarkerInfoInput" name="newMarkerInfo" value="">

    <button type="submit">Submit</button>
</form> -->
<form id="saveMarkersForm">
    <!-- Your existing form elements -->

    <!-- Add hidden input fields to store marker values -->
    <input type="hidden" id="startingMarkerInfoInput" name="startingMarkerInfo" value="">
    <input type="hidden" id="newMarkerInfoInput" name="newMarkerInfo" value="">

    <!-- Your existing submit button -->
    <button type="button" id="saveMarkersButton">Save Markers</button>
</form>



    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-2">
                <button type="submit" class="btn btn-default back" onclick="redirectToCommuter()">
                    <i class="fa-solid fa-rotate-left fa-lg" style="color: #00000;"></i> Back
                </button>
            </div>
            <div class="col-md-6 mb-2">

                <button type="submit" class="btn btn-default confirm" id="confirmButton">
                    <i class="fa-solid fa-check fa-lg" style="color: white"></i> Confirm
                </button>

            </div>
        </div>
    </div>

    <script src="../js/button.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        const searchInput = document.getElementById("search-input");
        const searchButton = document.getElementById("search-button");

        // Attach an event listener to the input field
        searchInput.addEventListener("keyup", function (event) {
            // Check if the "Enter" key is pressed (key code 13)
            if (event.keyCode === 13) {
                // Simulate a click on the search button
                searchButton.click();
            }
        });

        // Attach an event listener to the search button
        searchButton.addEventListener("click", function () {
            // Perform your search action here
            console.log("Search button clicked or Enter key pressed");
            // Replace the above line with your actual search logic
        });
    </script>

    <script>
        var map = L.map("map").setView([14.954324668012775, 120.90080124844123], 17);
        L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 21,
        }).addTo(map);

        var userLocation;
        var confirmationMade = false;
        var startingMarker;
        var newMarker;
        var routePolyline;
        var currentMarker; // Define the currentMarker variable
        var currentLocationMarker;
        var startingMarkerLatLng = null;
        var newMarkerLatLng = null;



        map.on("locationfound", function (e) {
            userLocation = e.latlng;
            startingMarkerLatLng = userLocation; // Save the starting marker's latlng
            startingMarker = L.marker(userLocation).addTo(map);

            // Get latitude and longitude
            var lat = userLocation.lat.toFixed(6);
            var lng = userLocation.lng.toFixed(6);
            var startingMarkerInfo = document.getElementById("startingMarkerInfo");
            startingMarkerInfo.textContent = userLocation.lat.toFixed(6) + ", " + userLocation.lng.toFixed(6);

            startingMarker.bindPopup("Starting point<br>Latitude: " + lat + "<br>Longitude: " + lng).openPopup();
        });


        map.on("locationerror", function (e) {
            alert(e.message);
        });

        map.locate({ setView: true, maxZoom: 20, enableHighAccuracy: true });

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    userLocation = L.latLng(position.coords.latitude, position.coords.longitude);
                    map.setView(
                        [position.coords.latitude, position.coords.longitude],
                        13
                    );
                    axios
                        .get(
                            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${userLocation[0]}&lon=${userLocation[1]}`
                        )
                        .then(response => {
                            const address = response.data.display_name;
                            const h1 = document.querySelector("h1");
                            h1.textContent = address;
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
                function () {
                    map.setView([14.954324668012775, 120.90080124844123], 13);
                }
            );
        } else {
            map.setView([14.954324668012775, 120.90080124844123], 13);
        }

        map.on("dblclick", function (e) {
            if (!confirmationMade) {
                if (newMarker) {
                    map.removeLayer(newMarker);
                }
                if (routePolyline) {
                    map.removeLayer(routePolyline);
                }
                newMarker = L.marker(e.latlng).addTo(map);

                // Save the new marker's latlng
                newMarkerLatLng = e.latlng;

                // Update the content of the newMarkerInfo <h1> element
                var newMarkerInfo = document.getElementById("newMarkerInfo");
                newMarkerInfo.textContent = e.latlng.lat.toFixed(6) + ", " + e.latlng.lng.toFixed(6);

                var markerLocation = newMarker.getLatLng();
                var distance = userLocation.distanceTo(markerLocation) / 1000;
                var time = distance / 0.23233333;
                var cost = distance <= 2 ? 30 : 30 + (distance - 2) * 10;
                cost = Math.round(cost);

                // Display latitude and longitude in the popup content
                var popupContent = "Latitude: " + markerLocation.lat.toFixed(6) + "<br>Longitude: " + markerLocation.lng.toFixed(6) + "<br>Distance: " + distance.toFixed(2) + " km" + "<br>Time: " + time.toFixed(0) + " min" + "<br>Fare: ₱" + cost.toFixed(2);

                newMarker
                    .bindPopup(popupContent)
                    .openPopup();
                updateHeader();

                var waypoints = [
                    [userLocation.lng, userLocation.lat],
                    [markerLocation.lng, markerLocation.lat]
                ];
                var url = 'https://router.project-osrm.org/route/v1/driving/' + waypoints.join(';') + '?geometries=geojson';
                fetch(url)
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        var geojson = data.routes[0].geometry;
                        routePolyline = L.geoJSON(geojson, {
                            style: { color: 'blue', weight: 6 }
                        }).addTo(map);
                    })
                    .catch(function (error) {
                        console.log(error);
                        location.reload();
                    });
            }
        });




        document.querySelector('.confirm').addEventListener('click', showConfirmDialog);

        function showConfirmDialog() {
            if (!newMarker) {
                alert("Please double-click on the map to set the destination marker before confirming.");
                return;
            }

            if (window.confirm('Are you sure about your destination?')) {
                console.log('Confirmed!');



                confirmationMade = true;
                document.querySelector('.search').style.display = 'none';
                document.querySelector('.back').style.display = 'none';
                document.querySelector('.confirm').style.display = 'none';
                document.getElementById('map').style.height = '600px';
                document.querySelector('.loc').style.height = '600px';

                // document.getElementById('startingMarkerInfoInput').value = document.getElementById('startingMarkerInfo').textContent;
                // document.getElementById('newMarkerInfoInput').value = document.getElementById('newMarkerInfo').textContent;

                document.getElementById('startingMarkerInfoInput').value = document.getElementById('startingMarkerInfo').textContent;
                document.getElementById('newMarkerInfoInput').value = document.getElementById('newMarkerInfo').textContent;

                // Send data to the server using AJAX
                var formData = new FormData(document.getElementById('saveMarkersForm'));
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'save_coordinates.php', true);

                // Handle the AJAX response
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Success message or additional handling if needed
                        console.log('Markers saved successfully.');
                    } else if (xhr.readyState === 4 && xhr.status !== 200) {
                        // Handle errors here
                        console.error('Error saving markers: ' + xhr.statusText);
                    }
                };

                // Send the request
                xhr.send(formData);

                // Submit the form
                document.querySelector('form').submit();


                // Geolocation tracking and real-time marker update
                const watchOptions = {
                    enableHighAccuracy: true,
                    maximumAge: 0,
                    timeout: 5000,
                };

                const watchId = navigator.geolocation.watchPosition(success, error, watchOptions);

                function success(pos) {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    const signalStrength = pos.coords.accuracy;

                    updateMarker(lat, lng, signalStrength);

                    map.setView([lat, lng]); // Center the map to the user's position
                }

                function error(err) {
                    console.error(err);
                }

                // Clean up the watch when the page is unloaded
                window.addEventListener('beforeunload', () => {
                    navigator.geolocation.clearWatch(watchId);
                });

                // Function to update the marker position
                function updateMarker(lat, lng, signalStrength) {
                    if (!currentMarker) {
                        currentMarker = L.marker([lat, lng], {
                            icon: L.icon({
                                iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                                iconSize: [25, 41],
                                iconAnchor: [12, 41],
                                popupAnchor: [1, -34],
                                tooltipAnchor: [16, -28],
                                shadowSize: [41, 41]
                            })
                        }).addTo(map);
                    } else {
                        currentMarker.setLatLng([lat, lng]);
                    }

                    // Calculate distance between current marker and newMarker
                    var newMarkerLocation = newMarker.getLatLng();
                    var distance = currentMarker.getLatLng().distanceTo(newMarkerLocation) / 1000;
                    var time = distance / 0.23233333;
                    var cost = distance <= 2 ? 30 : 30 + (distance - 2) * 10;
                    cost = Math.round(cost);

                    // Get latitude and longitude
                    var latLngPopup = "<br>Latitude: " + lat.toFixed(6) + "<br>Longitude: " + lng.toFixed(6);
                    // Determine signal strength category and text color based on accuracy
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

                    // Display distance, time, and cost in the popup
                    var popupContent = `
    Signal Strength: <span style="color: ${textColor};">${signalStrengthCategory}</span>
    <br>Distance to Destination: ${distance.toFixed(2)} km
    <br>Estimated Time to Destination: ${time.toFixed(0)} min
    <br>Fare: ₱${cost.toFixed(2)}
    ${latLngPopup}
    <br>
    <?php
    require_once '../db/dbconn.php';

    $driverName = "";
    $bodyNumber = "";

    if (isset($_SESSION['driver'])) {
        $plateNumber = $_SESSION['driver'];

        $query = "SELECT  body_number, name FROM driver WHERE plate_number = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $plateNumber);
        $stmt->execute();
        $result = $stmt->get_result();
        $driverData = $result->fetch_assoc();

        if ($driverData) {
            $driverName = $driverData['name'];
            $bodyNumber = $driverData['body_number'];
        }

        $stmt->close();
    }

    $conn->close();
    ?>
    <b>Driver Name:</b> <?php echo $driverName; ?><br>
    <b>Body Number:</b> <?php echo $bodyNumber; ?>
`;



                    currentMarker.bindPopup(popupContent).openPopup();

                    // Check if distance is less than or equal to 0.01 km (10 meters)
                    if (distance <= 0.01) {
                        window.location.href = 'receipt.php'; // Redirect to receipt.php
                    }
                }
            } else {
                console.log('Cancelled!');
            }
        }



        document.getElementById("search-button").addEventListener("click", function () {
            var searchValue = document.getElementById("search-input").value;
            axios.get("https://nominatim.openstreetmap.org/search?q=" + searchValue + "&format=json&limit=1")
                .then(function (response) {
                    var result = response.data[0];
                    map.setView([result.lat, result.lon], 18);
                })
                .catch(function (error) {
                    console.log(error);
                });
        });

        function updateHeader() {
            var markerLocation = newMarker.getLatLng();
            var url = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=" + markerLocation.lat + "&lon=" + markerLocation.lng + "&_=" + new Date().getTime();
            fetch(url)
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    var address = data.display_name;
                    var addressArray = address.split(",");
                    addressArray.splice(-6);
                    var header = document.querySelector("h1");
                    header.innerHTML = "Destination: " + addressArray.join(", ");
                })
                .catch(function (error) {
                    console.log(error);
                    var header = document.querySelector("h1");
                    header.innerHTML = "Destination: " + markerLocation.lat.toFixed(6) + ", " + markerLocation.lng.toFixed(6);
                });
        }
    </script>

</body>

</html>