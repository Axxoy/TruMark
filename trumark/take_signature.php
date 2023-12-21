<?php

// Set the default timezone to America/Chicago
date_default_timezone_set('America/Chicago');

error_reporting(E_ALL); // Enable error reporting for debugging
ini_set('display_errors', 1); // Display errors for debugging
session_start();  // Always start the session at the beginning

if (isset($_POST['cid'])) {
    $cid = $_POST['cid'];
    $uid = $_SESSION['aldkldmsldmsks2029dkj29di'];

    // Open up the connection
    $db = new mysqli("127.0.0.1", "root", "1453", "signature");

    // Check connection
    if ($db->connect_errno) {
        echo "<script type='text/javascript'>alert('Connection failed: " . $db->connect_error . "'); window.location.href = 'index.php#courses';</script>";
        exit();
    }



    // Assuming $uid and $cid are already set and sanitized properly
    $current_date = date('Y-m-d');  // Format the date as MySQL's date format

    $checkStmt = $db->prepare("SELECT * FROM student_attendance WHERE uid = ? AND cid = ? AND attendance_date = ?");
    $checkStmt->bind_param("iis", $uid, $cid, $current_date);
    $checkStmt->execute();
    $result = $checkStmt->get_result();


    if ($result->num_rows > 0) {
        echo "<script type='text/javascript'>alert('Your attendance has already been taken!'); window.location.href = 'index.php#courses';</script>";
        $checkStmt->close();
        $db->close();
        exit();
    }


    $checkStmt->close();

    // Get the current day and time
    $currentDay = date('l'); // returns the full textual representation of the day of the week
    $currentTime = date('H:i:s'); // returns the current time

    // Prepare the SQL statement to select the course schedule
    $stmt = $db->prepare("SELECT * FROM course_schedule WHERE course_id = ? AND day_of_week = ?");
    $stmt->bind_param("is", $cid, $currentDay); // Binding parameters

    // Execute the prepared statement
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $schedules = $result->fetch_all(MYSQLI_ASSOC);
            $canTakeAttendance = false;

            foreach ($schedules as $schedule) {
                // Check if the current time is within the start_time and end_time
                if ($currentTime >= $schedule['start_time'] && $currentTime <= $schedule['end_time']) {
                    $canTakeAttendance = true;
                    break;
                }
            }

            if (!$canTakeAttendance) {
                echo "<script type='text/javascript'>alert('You cannot take attendance now! It\'s not the correct time or day.'); window.location.href = 'index.php#courses';</script>";
                $stmt->close();
                $db->close();
                exit();
            }


            // Further code for when attendance can be taken
        } else {
            echo "<script type='text/javascript'>alert('You cannot take attendance now! It\'s not the correct time or day.'); window.location.href = 'index.php#courses';</script>";
            $stmt->close();
            $db->close();
            exit();
        }
    } else {
        // Handle query execution error
        echo "<script type='text/javascript'>alert('Query execution failed.'); window.location.href = 'index.php#courses';</script>";
        $stmt->close();
        $db->close();
        exit();
    }
} else {
    // Handle case where 'cid' POST variable is not set
    echo "<script type='text/javascript'>alert('Course ID is not provided.'); window.location.href = 'index.php#courses';</script>";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="view65" content="width=device-width, initial-scale=1.0">
    <title>Signature Pad</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/main.css">

    <?php
    echo '<style>
        ';
    echo 'html, body {';
    echo '    height: 100%;';
    echo '    margin: 0;';
    echo '    background-image: none !important; /* Disable background image */';
    echo '    background-color: #361945 !important; ';
    echo '}';
    echo '
    </style>';
    ?>

    <style>
        html,
        body {
            height: 100%;
            overflow: hidden;
        }

        #intro {
            height: 100%;
        }

        #signature-pad {
            max-height:
                /* Adjust as necessary to fit */
            ;
        }
    </style>
</head>

<body>
    <div id="intro" style="position:relative">
        <div class="container">
            <canvas id="signature-pad" width="876" height="543"></canvas>
            <a href=index.php#courses>
                < Back</a>
                    <button onclick="saveSignature()">Save Signature</button>
                    <button onclick="clearCanvas()">Clear</button>
                    <div id="server-response"></div>
        </div>

        <!-- Hidden form for redirection to the PHP page -->
        <form id="hiddenForm" action="update_attendance.php" method="post" style="display: none;">
            <input type="hidden" name="message" id="hiddenInput">
            <input type='hidden' name='cid' value='<?php echo isset($_POST['cid']) ? $_POST['cid'] : ''; ?>'>
            <input type="submit" id="hiddenSubmit">
        </form>

        <script src="scripts.js"></script>
    </div>
</body>

</html>