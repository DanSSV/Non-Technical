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

    <!-- <link rel="stylesheet" href="../css/commuter.css"> -->
    <link rel="stylesheet" href="../css/manage.css">
    <?php
    include('../php/icon.php');
    ?>
</head>

<body>
    <?php
    include('../php/navbar_commuter.php');
    ?>
    <h3>Name:</h3>
    <h3>Emergency Contacts:</h3>
    <button type="submit" class="btn btn-default custom-btn" onclick="redirectToContacts()">
        <i class="fa-solid fa-address-book fa-sm" style="color: #000000;"></i> Change Contacts
    </button>
    <button type="submit" class="btn btn-default custom-btn" onclick="redirectToChangePass()">
        <i class="fa-solid fa-key fa-sm" style="color: #000000;"></i> Change Password
    </button>
    <script src="../js/change_hover.js"></script>
    <script src="../js/button.js"></script>
</body>

</html>