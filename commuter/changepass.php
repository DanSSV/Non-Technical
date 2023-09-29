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
    <link rel="stylesheet" href="../css/changepass.css">
    <?php
    include('../php/icon.php');
    ?>
</head>

<body>
    <?php
    include('../php/navbar_commuter.php');
    ?>
    <h5>*The password should be at least 8 characters long.</h5>
    <h5>*The password should contain a mix of uppercase and lowercase letters, numbers, and special characters.</h5>
    <h5>*The password should not be a dictionary word or a common phrase.</h5>
    <h5>*The password should not be based on personal information, such as your name, birthday, or address.</h5>
    <h3>Emergency Contacts:</h3>
    <button type="submit" class="btn btn-default custom-btn" onclick="redirectToChangePass()">
        <i class="fa-solid fa-key fa-sm" style="color: #000000;"></i> Confirm Change
    </button>
    <button type="submit" class="btn btn-default custom-btn1" onclick="redirectToManage()">
        <i class="fa-solid fa-rotate-left fa-lg" style="color: #00000;"></i> Back?
    </button>
    <script src="../js/change_hover.js"></script>
    <script src="../js/button.js"></script>
</body>

</html>