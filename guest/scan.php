<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TriSakay | Scan Code</title>
    <?php
    include '../php/dependencies.php';
    include('../php/icon.php');
    ?>
    <link rel="stylesheet" href="../css/scan.css">
</head>

<body>
    <?php
    include('../php/navbar_guest.php');
    ?>
    <div class="vid">
        <video id="preview"></video>
    </div>
    <div class="container">
        <div class=" mb-2">
            <button type="submit" class="btn btn-default custom-btn" onclick="redirectToGuest()">
                <i class="fa-solid fa-rotate-left fa-lg" style="color: #00000;"></i> Back
            </button>
        </div>
    </div>
    <script src="../js/button.js"></script>
    <div id="result"></div>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Driver is not a registered driver.</p>
            <button id="retry-button">Scan Again</button>


        </div>
    </div>
    <script src="../js/scan_guest.js"></script>
    <script>
        let retryButton = document.getElementById("retry-button");
        retryButton.addEventListener("click", function () {
            modal.style.display = "none";
            window.location.href = "guest.php"; // Redirect to scan.php
        });
    </script>
</body>

</html>