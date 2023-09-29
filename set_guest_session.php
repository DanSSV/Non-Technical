<?php
session_start();

// Set the "guest" session value if it's not already set
if (!isset($_SESSION['guest'])) {
    $_SESSION['guest'] = 'Guest';
    echo "Guest session value set.";
}

// Set the "driver" session value if it's not already set
if (!isset($_SESSION['driver'])) {
    $_SESSION['driver'] = 'juan';
    echo "Driver session value set.";
}
?>