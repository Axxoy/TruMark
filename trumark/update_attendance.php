<?php

// Set the default timezone to America/Chicago
date_default_timezone_set('America/Chicago');

session_start();  // Always start the session at the beginning

if (isset($_POST['message'])) {
    $id_from_server = htmlspecialchars($_POST['message']);
    $id_from_user = $_SESSION['aldkldmsldmsks2029dkj29di'];

    if ($id_from_server == $id_from_user) {
        $cid = $_POST['cid'];

        $db = new mysqli("127.0.0.1", "root", "1453", "signature");

        echo $cid;
        $stmt = $db->prepare("INSERT INTO student_attendance (uid, cid, attendance_date, status) VALUES (?, ?, CURDATE(), 'Present')");
        $stmt->bind_param("ii", $id_from_user, $cid);

        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $db->close();

        echo "<script type='text/javascript'>alert('Your signature is accepted!'); window.location.href = 'index.php#courses';</script>";
    } else {
        echo "<script type='text/javascript'>alert('Your signature is not accepted!'); window.location.href = 'index.php#courses';</script>";
    }
}
?>