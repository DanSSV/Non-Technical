
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TriSakay | Guest</title>
    <?php
    // Your other PHP file
    include '../php/dependencies.php';

    // Your PHP code continues here...
    ?>

    <!-- <link rel="stylesheet" href="../css/commuter.css"> -->
    <link rel="stylesheet" href="../css/commuter.css">
    <?php
    include('../php/icon.php');
    ?>

</head>

<body>
    <?php
    include('../php/navbar_guest.php');
    ?>
    <?php
    $imagePath = "../img/Logo_Nobg.png";
    ?>
    <div class="center-image">
        <img src="<?php echo $imagePath; ?>" alt="Image Description">
    </div>
    <?php
    // include('php/navbar_driver.php');
    

    $imagePath = "../img/Logo_Nobg.png";
    include('../db/dbconn.php'); // Include the database connection file
    

    ?>

<!-- <h5>We hope you enjoy your next ride with <strong>TriSakay</strong>, Guest.</h5> -->
<?php
session_start();

require_once '../db/dbconn.php';

$welcomeMessage = "";

if (isset($_SESSION['guest'])) {
    $welcomeMessage = "We hope you enjoy your next ride with <strong>TriSakay</strong>, " . $_SESSION['guest'];
} else {
    $welcomeMessage = "You are not logged in";
}

// if (isset($_SESSION['driver'])) {
//     $plateNumber = $_SESSION['driver'];

//     $query = "SELECT name FROM driver WHERE plate_number = ?";
//     $stmt = $conn->prepare($query);
//     $stmt->bind_param("s", $plateNumber);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $driverData = $result->fetch_assoc();

//     if ($driverData) {
//         $driverName = $driverData['name'];
//         $welcomeMessage .= " | Driver: " . $driverName;
//     } else {
//         $welcomeMessage .= " | Driver information not found";
//     }

//     $stmt->close();
// }

echo "<h5>" . $welcomeMessage . "</h5>";

$conn->close();
?>




    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-2">
                <button type="submit" class="btn btn-default custom-btn" onclick="redirectToMyLocationGuest()">
                    <i class="fa-solid fa-map-pin fa-lg" style="color: white"></i> My Location
                </button>
            </div>
            <div class="col-md-6 mb-2">
                <button type="submit" class="btn btn-default custom-btn" onclick="redirectToScanGuest()">
                    <i class="fa-solid fa-qrcode fa-lg" style="color: white"></i> Scan Code
                </button>
            </div>
        </div>
    </div>

    <script src="../js/commuter_hover.js"></script>
    <script src="../js/button.js"></script>
</body>

</html>