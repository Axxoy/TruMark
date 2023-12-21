<?php

// Set the default timezone to America/Chicago
date_default_timezone_set('America/Chicago');

session_start();
ob_start();

// Check if logged in
if (!isset($_SESSION['aldkldmsldmsks2029dkj29di'])) {
    header("Location: login.php");
    exit; // Add an exit statement to stop executing further code
}

// If the 'logout' post is provided, then log out of the system
if (isset($_POST['logout'])) {
    unset($_SESSION['aldkldmsldmsks2029dkj29di']);
    unset($_POST['logout']);
    header("Location: login.php");
    exit; // Add an exit statement to stop executing further code
}

// Initialize operator
if (!isset($_POST['operation'])) {
    $_POST['operation'] = "";
}

$db = new mysqli("127.0.0.1", "root", "1453", "signature") or die("could not connect to mysql"); // Open up the connection

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}


// Get the new password from the form
$newPassword = isset($_POST['newPassword']) ? $_POST['newPassword'] : '';


// Check if the new password is empty
if (empty($newPassword)) {
    echo "Error: New password cannot be empty.";
    exit; // Add an exit statement to stop executing further code
}

// Prepare the update query
$uid = $_SESSION['aldkldmsldmsks2029dkj29di'];
$updateQuery = "UPDATE students SET password = '$newPassword' WHERE uid = '$uid'";

// Execute the update query
if ($db->query($updateQuery) === TRUE) {
    echo "Password updated successfully.";
} else {
    echo "Error updating password: " . $db->error;
}

// Close the database connection
$db->close();
?>