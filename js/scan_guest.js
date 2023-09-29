let scanner = new Instascan.Scanner({
  video: document.getElementById("preview"),
});

scanner.addListener("scan", function (content) {
  scanner.stop();
  // document.querySelector("h1").textContent = "Verifying...";

  // Use AJAX to send the scanned content to the server for verification
  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        let response = JSON.parse(xhr.responseText);
        if (response.exists) {
          window.location.href = "search.php"; // Redirect to done.html
        } else {
          showModalDialog(); // Show the modal dialog
        }
      } else {
        document.querySelector("h1").textContent = "Error verifying content.";
      }
    }
  };

  // ...

  function showModalDialog() {
    let modal = document.getElementById("myModal");
    modal.style.display = "block";

    let closeButton = document.querySelector(".close");
    closeButton.addEventListener("click", function () {
      modal.style.display = "none";
    });

    let retryButton = document.getElementById("retry-button");
    retryButton.addEventListener("click", function () {
      modal.style.display = "none";
      scanner.start(cameras[0]);
    });
  }
  let retryButton = document.getElementById("retry-button");
  retryButton.addEventListener("click", function () {
    modal.style.display = "none";
    window.location.href = "commuter.php"; // Redirect to scan.php
  });

  xhr.open("POST", "../verify_guest.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.send("plate_number=" + encodeURIComponent(content));
});

Instascan.Camera.getCameras()
  .then(function (cameras) {
    if (cameras.length > 0) {
      scanner.start(cameras[0]);

      let retryButton = document.getElementById("retry-button");
      retryButton.addEventListener("click", function () {
        let modal = document.getElementById("myModal");
        modal.style.display = "none";
        scanner.start(cameras[0]);
      });
    } else {
      console.error("No cameras found.");
    }
  })
  .catch(function (e) {
    console.error(e);
  });
