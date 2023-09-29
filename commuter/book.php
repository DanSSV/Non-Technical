<?php
include('../php/session_commuter.php');
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
        <input id="search-input" type="text" placeholder="Search for a location">
        <button id="search-button"><i class="fa-solid fa-magnifying-glass-location fa-lg" style="color: #ffffff;"></i>
            Search</button>
    </div>

    <div class="loc">
        <div id="map"></div>
    </div>

    <div class="info">
        <h1>Double click to select a destination</h1>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-2">
                <button type="submit" class="btn btn-default back" onclick="redirectToCommuter()">
                    <i class="fa-solid fa-rotate-left fa-lg" style="color: #00000;"></i> Back
                </button>
            </div>
            <div class="col-md-6 mb-2">
                <button type="submit" class="btn btn-default confirm">
                    <i class="fa-solid fa-check fa-lg" style="color: #000000;"></i> Confirm
                </button>
            </div>
        </div>
    </div>

    <script src="../js/button.js"></script>

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
        var currentLocationMarker;

        map.on("locationfound", function (e) {
            userLocation = e.latlng;
            startingMarker = L.marker(userLocation).addTo(map);
            startingMarker.bindPopup("Starting point").openPopup();
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
                var markerLocation = newMarker.getLatLng();
                var distance = userLocation.distanceTo(markerLocation) / 1000;
                var time = distance / 0.23233333;
                var cost = distance <= 2 ? 30 : 30 + (distance - 2) * 10;
                cost = Math.round(cost);

                newMarker
                    .bindPopup("Distance: " + distance.toFixed(2) + " km" + "<br>Time: " + time.toFixed(0) + " min" + "<br>Fare: ₱" + cost.toFixed(2))
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

        // function showConfirmDialog() {
        //     if (window.confirm('Are you sure about your destination? You cannot change this action after clicking Confirm.')) {
        //         console.log('Confirmed!');
        //         confirmationMade = true;
        //         document.querySelector('.search').style.display = 'none';
        //         document.querySelector('.back').style.display = 'none';
        //         document.querySelector('.confirm').style.display = 'none';
        //         document.getElementById('map').style.height = '600px';
        //         document.querySelector('.loc').style.height = '600px';
        //         var markerLocation = newMarker.getLatLng();
        //         var bounds = L.latLngBounds(userLocation, markerLocation);
        //         map.fitBounds(bounds);
        //     } else {
        //         console.log('Cancelled!');
        //     }
        // }
        function showConfirmDialog() {
    if (window.confirm('Are you sure about your destination? You cannot change this action after clicking Confirm.')) {
        console.log('Confirmed!');
        // Redirect to the scan_confirm.php page
        window.location.href = 'scan_confirm.php';
    } else {
        console.log('Cancelled!');
    }
}


        setInterval(trackUserLocation, 1000);

        function trackUserLocation() {
            if (userLocation && confirmationMade) {
                if (currentLocationMarker) {
                    map.removeLayer(currentLocationMarker);
                }
                currentLocationMarker = L.marker(userLocation, {
                    icon: L.icon({
                        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        tooltipAnchor: [16, -28],
                        shadowSize: [41, 41]
                    })
                }).addTo(map);
            }
        }

        document.getElementById("search-button").addEventListener("click", function () {
            // Get the value of the search input
            var searchValue = document.getElementById("search-input").value;

            // Use the Nominatim API to geocode the search value into a latitude and longitude
            axios.get("https://nominatim.openstreetmap.org/search?q=" + searchValue + "&format=json&limit=1")
                .then(function (response) {
                    var result = response.data[0];

                    // Center the map on the geocoded location
                    map.setView([result.lat, result.lon], 18);
                })
                .catch(function (error) {
                    console.log(error);
                });
        });
        function updateHeader() {
            var markerLocation = newMarker.getLatLng();
            var url = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=" + markerLocation.lat + "&lon=" + markerLocation.lng;
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