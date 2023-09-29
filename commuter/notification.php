<?php
include('../php/session_commuter.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TriSakay | Commuter</title>
    <?php
    // Your other PHP file
    include '../php/dependencies.php';

    // Your PHP code continues here...
    ?>
    <link rel="stylesheet" href="../css/history.css">
    <?php
    include('../php/icon.php');
    ?>

</head>

<body>
    <?php
    include('../php/navbar_commuter.php');
    ?>

    <table class="table table-transparent table-hover">
        <thead class="table-light">
            <tr>
                <th><i class="fa-regular fa-id-card" style="color: #2c5746;"></i> Driver</th>
                <th><i class="fa-solid fa-location-pin" style="color: #2c5746;"></i> From</th>
                <th><i class="fa-solid fa-location-pin-lock" style="color: #2c5746;"></i> Destination</th>
                <th><i class="fa-regular fa-calendar" style="color: #2c5746;"></i> Date & Time</th>
                <th><i class="fa-solid fa-peso-sign" style="color: #2c5746;"></i> Fare</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <tr>
        <td>Jane Doe</td>
        <td>Baliuag Sentral Terminal</td>
        <td>Brgy. Tabang</td>
        <td>2023-08-16 10:00</td>
        <td>Php 100</td>
      </tr>
      <tr>
        <td>Maria dela Rosa</td>
        <td>Brgy. San Juan</td>
        <td>Baliuag Palengke</td>
        <td>2023-08-16 11:00</td>
        <td>Php 50</td>
      </tr>
      <tr>
        <td>Jose Rizal</td>
        <td>Baliuag Tren Station</td>
        <td>Brgy. Sta. Cruz</td>
        <td>2023-08-16 12:00</td>
        <td>Php 75</td>
      </tr>
      <tr>
        <td>Andres Bonifacio</td>
        <td>Baliuag Simbahan</td>
        <td>Brgy. Sta. Rita</td>
        <td>2023-08-16 01:00</td>
        <td>Php 80</td>
      </tr>
      <tr>
        <td>Apolinario Mabini</td>
        <td>Baliuag City Hall</td>
        <td>Brgy. San Rafael</td>
        <td>2023-08-16 02:00</td>
        <td>Php 100</td>
      </tr>
        </tbody>
    </table>
</body>

</html>