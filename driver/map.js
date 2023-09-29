var map = L.map("map", {
  zoomControl: false,
}).setView([0, 0], 13);

L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  attribution:
    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
}).addTo(map);

var startingMarker, newMarker, trackMarker;
var routePolyline;
var trackMarkerIcon = L.icon({
  iconUrl:
    "https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png",
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
});

// document.getElementById("cancel").addEventListener("click", function () {
//   if (trackMarker) {
//     var lat = trackMarker.getLatLng().lat;
//     var lng = trackMarker.getLatLng().lng;
//     var plateNumber = "GHI-123";
//     var formData = new FormData();
//     formData.append("lat", lat);
//     formData.append("lng", lng);
//     formData.append("plate_number", plateNumber);

//     fetch("save_coordinates.php", {
//       method: "POST",
//       body: formData,
//     })
//       .then((response) => response.text())
//       .then((result) => {
//         console.log(result);
//       })
//       .catch((error) => {
//         console.error("Error saving coordinates to database:", error);
//       });
//   }
// });

document.addEventListener("DOMContentLoaded", function () {
  // Get a reference to the Cancel button
  var cancelButton = document.getElementById("cancel");

  // Add a click event listener to the Cancel button
  cancelButton.addEventListener("click", function () {
    // Display a confirmation dialog
    var isSure = confirm("Are you sure you want to cancel?");

    // If the user confirms, execute the code
    if (isSure) {
      if (trackMarker) {
        var lat = trackMarker.getLatLng().lat;
        var lng = trackMarker.getLatLng().lng;
        var plateNumber = "GHI-123";
        
        var formData = new FormData();
        formData.append("lat", lat);
        formData.append("lng", lng);
        formData.append("plate_number", plateNumber);

        fetch("save_coordinates.php", {
          method: "POST",
          body: formData,
        })
          .then((response) => response.text())
          .then((result) => {
            console.log(result);
            window.location.href = "receipt_cancel.php"; 
          })
          .catch((error) => {
            console.error("Error saving coordinates to database:", error);
          });
      }
    }
  });
});

function updateTrackingMarker() {
  navigator.geolocation.watchPosition(function (position) {
    var userLat = position.coords.latitude;
    var userLng = position.coords.longitude;

    if (trackMarker) {
      trackMarker.setLatLng([userLat, userLng]);
      map.setView([userLat, userLng]);
    } else {
      trackMarker = L.marker([userLat, userLng], {
        icon: trackMarkerIcon,
        zIndexOffset: 1000,
      }).addTo(map);

      map.setView([userLat, userLng]);
    }

    // Call the addMarkersAndRoute function and pass the position object
    addMarkersAndRoute(position);
  });
}

function addMarkersAndRoute(position) {
  // The position object is now accessible within this function
  var userLat = position.coords.latitude;
  var userLng = position.coords.longitude;

  fetch("get_marker_data.php")
    .then((response) => response.json())
    .then((data) => {
      var startingLat = parseFloat(data.starting_lat);
      var startingLng = parseFloat(data.starting_lng);
      var newLat = parseFloat(data.new_lat);
      var newLng = parseFloat(data.new_lng);

      startingMarker = L.marker([startingLat, startingLng]).addTo(map);
      newMarker = L.marker([newLat, newLng]).addTo(map);

      map.setView([startingLat, startingLng], 16);

      var waypoints = [
        [startingLng, startingLat],
        [newLng, newLat],
      ];

      var osrmURL =
        "https://router.project-osrm.org/route/v1/driving/" +
        waypoints.join(";") +
        "?geometries=geojson";

      fetch(osrmURL)
        .then((response) => response.json())
        .then((routeData) => {
          var routeGeometry = routeData.routes[0].geometry;

          routePolyline = L.geoJSON(routeGeometry, {
            style: {
              color: "blue",
            },
          }).addTo(map);

          var distance = routeData.routes[0].distance / 1000;
          var speed = 20;
          var eta = (distance / speed) * 60;
          var cost = distance <= 2 ? 30 : 30 + (distance - 2) * 10;
          cost = Math.round(cost);

          var popupContent =
            "Distance: " +
            distance.toFixed(2) +
            " km<br>Fare: ₱" +
            cost +
            "<br>ETA: " +
            eta.toFixed(0) +
            " minutes";

          var accuracy = position.coords.accuracy;
          var signalStrength;
          if (accuracy <= 30) {
            signalStrength = '<span style="color: green;">Good</span>';
          } else if (accuracy <= 50) {
            signalStrength = '<span style="color: yellow;">Fair</span>';
          } else {
            signalStrength = '<span style="color: red;">Bad</span>';
          }

          popupContent += "<br>Signal Strength: " + signalStrength;

          if (trackMarker) {
            trackMarker.bindPopup(popupContent).openPopup();

            // Check if the tracking marker is 10 meters or less from the new marker
            var distanceToNewMarker = trackMarker
              .getLatLng()
              .distanceTo(newMarker.getLatLng());
            if (distanceToNewMarker <= 10) {
              window.location.href = "receipt.php";
            }
          }
        })
        .catch((error) => console.error(error));
    })
    .catch((error) => console.error(error));
}

if ("geolocation" in navigator) {
  updateTrackingMarker();
}

