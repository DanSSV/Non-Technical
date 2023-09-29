<?php
include('../php/session_commuter.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ride | TriSakay</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <?php
    include('../php/dependencies.php');
    ?>
    <link rel="stylesheet" href="../css/search1.css">
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
    <div id="map" style="height: 60vh;"></div>
    <div class="address">
        <h5 id="loading" class="loading" style="text-align: center;">Enter the desired Destination.</h5>
    </div>


    <button type="submit" class="btn btn-default confirm" id="confirmButton" style="display: none;">
        <i class="fa-solid fa-check fa-lg" style="color: white"></i> Confirm
    </button>

    <script>
        const searchInput = document.getElementById("search-input");
        const searchButton = document.getElementById("search-button");

        searchInput.addEventListener("keyup", function (event) {
            if (event.keyCode === 13) {
                searchButton.click();
            }
        });

        searchButton.addEventListener("click", function () {
            console.log("Search button clicked or Enter key pressed");
        });
    </script>
    <script>
        var map = L.map('map', {
            doubleClickZoom: false
        }).setView([0, 0], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        map.zoomControl.remove();

        var startingLocationMarker = L.marker([0, 0]).addTo(map);
        var destinationMarker = L.marker([0, 0], { draggable: true, autoPan: true, opacity: 0 }).addTo(map);
        var currentLocationMarker;
        var watchID;

        var startingLat = 0;
        var startingLng = 0;

        function saveLocation(startLat, startLng, newLat, newLng) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "save_location.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText);
                }
            };
            xhr.send("starting_lat=" + startLat + "&starting_lng=" + startLng + "&new_lat=" + newLat + "&new_lng=" + newLng);
        }

        function getLocationAndSave() {
            navigator.geolocation.getCurrentPosition(function (position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                startingLocationMarker.setLatLng([lat, lng])
                // .bindPopup("Starting Location: Latitude: " + lat + "<br>Longitude: " + lng).openPopup();
                map.setView([lat, lng], 13);
                startingLat = lat;
                startingLng = lng;
            }, function (error) {
                console.error("Error getting location:", error);
            });
        }

        window.addEventListener("load", getLocationAndSave);

        var redIcon = new L.Icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        function trackCurrentLocation() {
            currentLocationMarker = L.marker([0, 0], { icon: redIcon }).addTo(map);
            watchID = navigator.geolocation.watchPosition(function (position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                var accuracy = position.coords.accuracy;
                var currentLatLng = currentLocationMarker.getLatLng();
                var destinationLatLng = destinationMarker.getLatLng();
                var distance = currentLatLng.distanceTo(destinationLatLng) / 1000;
                var cost = distance <= 2 ? 30 : 30 + (distance - 2) * 10;
                cost = Math.round(cost);

                var signalStrength;
                if (accuracy <= 30) {
                    signalStrength = '<span style="color: green;">Good</span>';
                } else if (accuracy <= 50) {
                    signalStrength = '<span style="color: yellow;">Fair</span>';
                } else {
                    signalStrength = '<span style="color: red;">Bad</span>';
                }

                var speed = 20; // Speed in km/h (assumed)
                var eta = distance / speed; // Calculate ETA in hours

                var popupContent = `
            
            Signal Strength: ${signalStrength}
        `;

                if (distance !== undefined && cost !== undefined) {
                    popupContent += `<br>Distance: ${distance.toFixed(2)} km<br>Cost: ₱${cost}`;
                }

                if (speed === 20) {
                    eta = eta * 60; // Convert ETA to minutes if speed is 20 km/h
                    popupContent += `<br>ETA: ${eta.toFixed(0)} minutes`;
                }

                currentLocationMarker.setLatLng([lat, lng]).bindPopup(popupContent).openPopup();
                map.setView([lat, lng], 16);
            }, function (error) {
                console.error("Error tracking location:", error);
            });
        }

        function stopTrackingCurrentLocation() {
            navigator.geolocation.clearWatch(watchID);
        }

        map.on('dblclick', function (e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;
            var confirmButton = document.getElementById('confirmButton');
            if (confirmButton.style.display === 'none') {
                confirmButton.style.display = 'block';
                destinationMarker.setLatLng([lat, lng]).setOpacity(1)
                // .bindPopup("Destination: Latitude: " + lat + "<br>Longitude: " + lng).openPopup();
            } else {
                confirmButton.style.display = 'none';
                destinationMarker.setOpacity(0);
            }
            confirmButton.addEventListener('click', function () {
                window.location.href = 'commuter_map.php';
                var newLat = lat;
                var newLng = lng;
                saveLocation(startingLat, startingLng, newLat, newLng);
                confirmButton.style.display = 'none';
                reverseGeocode(newLat, newLng, function (address) {
                    var loadingElement = document.getElementById("loading");
                    loadingElement.textContent = "Destination: " + address;
                    document.querySelector('.search').style.display = 'none';
                    document.getElementById('map').style.height = '75vh'; // Set the desired height here
                    var waypoints = [startingLng + "," + startingLat, newLng + "," + newLat];
                    var url = 'https://router.project-osrm.org/route/v1/driving/' + waypoints.join(';') + '?geometries=geojson';
                    fetch(url)
                        .then(function (response) {
                            return response.json();
                        })
                        .then(function (data) {
                            var geojson = data.routes[0].geometry;
                            routePolyline = L.geoJSON(geojson, {
                                style: { color: 'blue', weight: 4 }
                            }).addTo(map);
                        })
                        .catch(function (error) {
                            console.log(error);
                            location.reload();
                        });
                    map.off('dblclick');
                    trackCurrentLocation();
                });
            });
        });

        window.addEventListener("beforeunload", function () {
            stopTrackingCurrentLocation();
        });

        searchButton.addEventListener("click", function () {
            var searchValue = searchInput.value;
            axios.get("https://nominatim.openstreetmap.org/search?q=" + searchValue + "&format=json&limit=1")
                .then(function (response) {
                    var result = response.data[0];
                    map.setView([result.lat, result.lon], 16);
                })
                .catch(function (error) {
                    console.log(error);
                });
        });

        // Update the reverseGeocode function to parse and format the address
        function reverseGeocode(lat, lng, callback) {
            axios.get("https://nominatim.openstreetmap.org/reverse?lat=" + lat + "&lon=" + lng + "&format=json")
                .then(function (response) {
                    var addressData = response.data;
                    var address = addressData.display_name;

                    // Split the address into parts
                    var addressParts = address.split(', ');

                    // Define the parts you want to keep
                    var filteredAddress = addressParts.slice(0, 5).join(', '); // Keep the first 4 parts

                    callback(filteredAddress);
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    </script>
</body>

</html>