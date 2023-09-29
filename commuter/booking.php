<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    <!-- Include Leaflet CSS and JavaScript -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <!-- Include Leaflet Routing Machine for route calculation -->
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

    <style>
        /* Add some CSS to set the map's height */
        #map {
            height: 400px;
        }
    </style>
</head>

<body>
    <!-- Create a div element for the map -->
    <div id="map"></div>

    <!-- Create an initially hidden h3 element for adding a pick-up point -->
    <h3 id="pickupHeader" style="display: none;">Add your pick-up point</h3>

    <!-- Create a button to add a pick-up point and disable double-click -->
    <button id="addPickupPoint" onclick="confirmAddPickupPoint()">Add Pick-up Point</button>

    <!-- Create an initially hidden h3 element for adding a drop-off point -->
    <h3 id="dropoffHeader" style="display: none;">Add your drop-off point</h3>

    <!-- Create a button to add a drop-off point and disable double-click -->
    <button id="addDropoffPoint" onclick="confirmAddDropoffPoint()" disabled>Add Drop-off Point</button>

    <!-- Create an h4 element to display the user's location -->
    <h4 id="userLocation">Current location: Loading...</h4>

    <!-- Create an initially hidden h4 element for the pick-up point -->
    <h4 id="pickupPoint" style="display: none;">Pick-up Point: Loading...</h4>

    <!-- Create an initially hidden h4 element for the drop-off point -->
    <h4 id="dropoffPoint" style="display: none;">Drop-off Point: Loading...</h4>
    <!-- Create an initially hidden button for undoing the markers -->
    <button id="undoButton" onclick="undoMarkers()" style="display: none;">Undo</button>

    <script>
        var map; // Define the map variable
        var pickupMarker; // Define the pickup marker variable
        var dropoffMarker; // Define the drop-off marker variable
        var route; // Define the route layer variable
        var doubleClickEnabled = true; // Enable double-click by default
        var isPickupPointConfirmed = false; // Track if the pickup point is confirmed
        var isDropoffPointConfirmed = false; // Track if the drop-off point is confirmed
        var confirmedPickupLocation; // Store the confirmed pickup location
        var confirmedDropoffLocation; // Store the confirmed drop-off location

        // Initialize the Leaflet map centered on an initial location (e.g., a default location)
        function initializeMap() {
            map = L.map('map', {
                zoomControl: false, // Disable default zoom control buttons
                doubleClickZoom: false // Disable double-click zoom
            }).setView([51.505, -0.09], 13);

            // Add a tile layer (you can use different map providers)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Enable double-clicking on the map
            map.doubleClickZoom.disable();

            // Check if the browser supports geolocation
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var userLat = position.coords.latitude;
                    var userLng = position.coords.longitude;

                    // Define a custom icon for the user's location marker (blue)
                    var blueIcon = L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                    });

                    // Create a marker with the custom icon and label for the user's location
                    var marker = L.marker([userLat, userLng], { icon: blueIcon }).addTo(map);
                    marker.bindPopup("You are here").openPopup(); // Display a popup with the label

                    // Center the map on the user's location
                    map.setView([userLat, userLng], 15);

                    // Reverse geocode the user's location using Nominatim
                    var xhr = new XMLHttpRequest();
                    xhr.open("GET", `https://nominatim.openstreetmap.org/reverse?format=json&lat=${userLat}&lon=${userLng}&zoom=18&addressdetails=1`, true);
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            var address = response.display_name;
                            // Display the reverse geocoded address in the <h4> element for the user's location
                            document.getElementById('userLocation').textContent = "Current location: " + address;
                        }
                    };
                    xhr.send();
                }, function (error) {
                    console.log("Error getting user's location: " + error.message);
                });
            } else {
                console.log("Geolocation is not supported by your browser.");
            }
        }

        // Function to add a pickup marker on double-click
        function addPickupMarker(e) {
            if (pickupMarker) {
                map.removeLayer(pickupMarker); // Remove existing pickup marker
            }

            pickupMarker = L.marker(e.latlng, { icon: greenIcon }).addTo(map);
            pickupMarker.bindPopup("Pick-up Point").openPopup();

            // Reverse geocode the pick-up point using Nominatim
            var xhr = new XMLHttpRequest();
            xhr.open("GET", `https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}&zoom=18&addressdetails=1`, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    var address = response.display_name;
                    // Display the reverse geocoded address in the <h4> element for the pick-up point
                    document.getElementById('pickupPoint').textContent = "Pick-up Point: " + address;
                    document.getElementById('pickupPoint').style.display = 'block'; // Show the element
                }
            };
            xhr.send();
        }

        // Function to add a drop-off marker on double-click
        function addDropoffMarker(e) {
            if (dropoffMarker) {
                map.removeLayer(dropoffMarker); // Remove existing drop-off marker
            }

            dropoffMarker = L.marker(e.latlng, { icon: redIcon }).addTo(map);
            dropoffMarker.bindPopup("Drop-off Point").openPopup();

            // Reverse geocode the drop-off point using Nominatim
            var xhr = new XMLHttpRequest();
            xhr.open("GET", `https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}&zoom=18&addressdetails=1`, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    var address = response.display_name;
                    // Display the reverse geocoded address in the <h4> element for the drop-off point
                    document.getElementById('dropoffPoint').textContent = "Drop-off Point: " + address;
                    document.getElementById('dropoffPoint').style.display = 'block'; // Show the element
                }
            };
            xhr.send();
        }

        // Function to confirm the addition of a pickup point
        function confirmAddPickupPoint() {
            if (!isPickupPointConfirmed) {
                var confirmation = confirm("Are you sure about the pick-up point?");
                if (confirmation) {
                    isPickupPointConfirmed = true;
                    confirmedPickupLocation = pickupMarker.getLatLng(); // Store the confirmed location
                    document.getElementById("addPickupPoint").textContent = "Undo";
                    document.getElementById("addDropoffPoint").disabled = false; // Enable drop-off button
                }
            } else {
                // Undo the action by removing the pickup marker and hiding the element
                map.removeLayer(pickupMarker);
                document.getElementById('pickupPoint').style.display = 'none';
                document.getElementById("addPickupPoint").textContent = "Add Pick-up Point";
                isPickupPointConfirmed = false;
                document.getElementById("addDropoffPoint").disabled = true; // Disable drop-off button
            }

            // Check if both markers are confirmed to show the undo button and remove the route
            if (isPickupPointConfirmed && isDropoffPointConfirmed) {
                document.getElementById("addPickupPoint").style.display = 'none';
                document.getElementById("addDropoffPoint").style.display = 'none';
                document.getElementById("undoButton").style.display = 'block';
                calculateRoute(); // Call calculateRoute when both points are confirmed
            }
        }

        // Function to confirm the addition of a drop-off point
        function confirmAddDropoffPoint() {
            if (!isDropoffPointConfirmed) {
                if (!isPickupPointConfirmed) {
                    alert("Please add a pickup point first."); // Display an alert if pickup point is not confirmed
                    return;
                }
                var confirmation = confirm("Are you sure about the drop-off point?");
                if (confirmation) {
                    isDropoffPointConfirmed = true;
                    confirmedDropoffLocation = dropoffMarker.getLatLng(); // Store the confirmed location
                    document.getElementById("addDropoffPoint").textContent = "Undo";
                    document.getElementById("pickupHeader").style.display = 'none'; // Hide pick-up header
                    document.getElementById("dropoffHeader").style.display = 'none'; // Hide drop-off header
                }
            } else {
                // Undo the action by removing the drop-off marker and hiding the element
                map.removeLayer(dropoffMarker);
                document.getElementById('dropoffPoint').style.display = 'none';
                document.getElementById("addDropoffPoint").textContent = "Add Drop-off Point";
                isDropoffPointConfirmed = false;
                document.getElementById("pickupHeader").style.display = 'block'; // Show pick-up header
                document.getElementById("dropoffHeader").style.display = 'block'; // Show drop-off header
            }

            // Check if both markers are confirmed to show the undo button and remove the route
            if (isPickupPointConfirmed && isDropoffPointConfirmed) {
                document.getElementById("addPickupPoint").style.display = 'none';
                document.getElementById("addDropoffPoint").style.display = 'none';
                document.getElementById("undoButton").style.display = 'block';
                calculateRoute(); // Call calculateRoute when both points are confirmed
            }
        }

        // Function to undo the addition of markers
        function undoMarkers() {
            if (pickupMarker) {
                map.removeLayer(pickupMarker);
                document.getElementById('pickupPoint').style.display = 'none';
                document.getElementById("addPickupPoint").textContent = "Add Pick-up Point";
                isPickupPointConfirmed = false;
            }
            if (dropoffMarker) {
                map.removeLayer(dropoffMarker);
                document.getElementById('dropoffPoint').style.display = 'none';
                document.getElementById("addDropoffPoint").textContent = "Add Drop-off Point";
                isDropoffPointConfirmed = false;
            }

            // Show the pick-up and drop-off buttons again and hide the undo button
            document.getElementById("addPickupPoint").style.display = 'block';
            document.getElementById("addDropoffPoint").style.display = 'block';
            document.getElementById("undoButton").style.display = 'none';
            document.getElementById("pickupHeader").style.display = 'block'; // Show pick-up header
            document.getElementById("dropoffHeader").style.display = 'block'; // Show drop-off header

            // Remove the route when undoing markers
            removeRoute();
        }

        // Function to remove the route from the map
        function removeRoute() {
            if (route) {
                map.removeLayer(route);
            }
        }

        // Function to calculate and display the route between pick-up and drop-off points using the OSRM API
        function calculateRoute() {
            if (confirmedPickupLocation && confirmedDropoffLocation) {
                var pickupCoordinates = `${confirmedPickupLocation.lng},${confirmedPickupLocation.lat}`;
                var dropoffCoordinates = `${confirmedDropoffLocation.lng},${confirmedDropoffLocation.lat}`;
                var waypoints = [pickupCoordinates, dropoffCoordinates];

                var url = 'https://router.project-osrm.org/route/v1/driving/' + waypoints.join(';') + '?geometries=geojson';

                // Make a GET request to the OSRM API
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        var routeGeometry = data.routes[0].geometry;
                        if (route) {
                            map.removeLayer(route); // Remove existing route if any
                        }
                        route = L.geoJSON(routeGeometry, {
                            style: {
                                color: 'blue'
                            }
                        }).addTo(map);

                        // Add the route to the map
                        route.addTo(map);
                    })
                    .catch(error => {
                        console.error('Error calculating route:', error);
                    });
            }
        }

        // Initialize the map
        initializeMap();

        // Add a double-click listener to the map for adding a pickup marker (if double-click is enabled)
        map.on('dblclick', function (e) {
            if (doubleClickEnabled && !isPickupPointConfirmed && !isDropoffPointConfirmed) {
                addPickupMarker(e);
            } else if (doubleClickEnabled && isPickupPointConfirmed && !isDropoffPointConfirmed) {
                addDropoffMarker(e);
            }
        });

        // Define custom icons for pickup (green) and drop-off (red) markers
        var greenIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
        });

        var redIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
        });
    </script>
</body>

</html>