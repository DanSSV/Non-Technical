<?php
include('../php/session_commuter.php');

// Initialize a variable to track the submission status
$submissionStatus = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection
    include('../db/dbconn.php');

    // Get the user_id from the session
    $user_id = $_SESSION["user_id"];

    // Get the form data
    $subject = $_POST["subject"];
    $plateNumber = $_POST["plateNumber"];
    $concern = $_POST["concern"];

    // Insert data into the report_recover table
    $sql = "INSERT INTO report_recover (user_id, subject, plate_number, concern, date_time) VALUES ('$user_id', '$subject', '$plateNumber', '$concern', NOW())";

    if (mysqli_query($conn, $sql)) {
        $submissionStatus = 'Submitted';
    } else {
        $submissionStatus = 'Error: ' . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TriSakay | Item Recovery</title>
    <?php
    include '../php/dependencies.php';
    include('../php/icon.php');
    ?>
    <link rel="stylesheet" href="../css/lost.css">
</head>

<body>
    <?php include('../php/navbar_commuter.php'); ?>
    <div class="items">
        <h4>Report Driver or Recover Item</h4>
        <?php
        if ($submissionStatus === 'Submitted') {
            echo '<h4 style="color: green;">Submitted</h4>';
        }
        ?>
        <div class="container d-flex justify-content-center">
            <form method="post" action="">
                <div class="input-box">
                    <div class="input-field">
                        <input type="text" class="input" id="subject" name="subject" required autocomplete="off" />
                        <label for="subject">Subject</label>
                    </div>
                    <div class="input-field">
                        <input type="text" class="input" id="plateNumber" name="plateNumber" required />
                        <label for="plateNumber">Plate Number</label>
                    </div>
                    <div class="input-field">
                        <input type="text" class="input" id="concern" name="concern" required />
                        <label for="concern">Concern</label>
                    </div>
                    <div class="container d-flex justify-content-center mt-3">
                        <button type="submit" class="btn btn-default custom-btn">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>