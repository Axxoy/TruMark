<?php

// Set the default timezone to America/Chicago
date_default_timezone_set('America/Chicago');

session_start();
ob_start();

// Initialize operator
if (!(isset($_POST['operation'])))
	$_POST['operation'] = "";

// Check if logged in
if (!(isset($_SESSION['aldkldmsldmsks2029dkj29di'])))
	header("Location: ../login.php");

// If the 'logout' post is provided, then log out of the system
if (isset($_POST['logout'])) {
	unset($_SESSION['aldkldmsldmsks2029dkj29di']);
	unset($_POST['logout']);
	header("Location: ../login.php");
}

switch ($_POST['operation']) {
	default:
		main_page();
}

function main_page()
{
	$uid = $_SESSION['aldkldmsldmsks2029dkj29di'];

	$db = new mysqli("127.0.0.1", "root", "1453", "signature") or die("could not connect to mysql"); // Open up the connection
	if ($db->connect_error)
		die("Connection failed: " . $db->connect_error);

	echo "<!DOCTYPE HTML>";
	echo "<html>";
	echo "<head>";
	echo "<title>Home</title>";
	echo "<link rel=\"stylesheet\" href=\"css/main.css\" />";
	echo "</head>";
	echo "<body>";
	echo "<div>";
	echo "<div id=\"intro\" style=\"position:relative; bottom:-100px;\">";
	$sql = "SELECT * FROM enrolled JOIN courses JOIN student_attendance ON enrolled.cid = courses.cid = student_attendance.cid WHERE enrolled.uid = '{$uid}'";
	$courses = mysqli_query($db, $sql);
	while ($course = mysqli_fetch_array($courses, MYSQLI_ASSOC)) {
		echo $course['course_name'];
		echo $course['status'];
	}

	echo "</br>";
	echo "<a href=\"index.php\">< Back</a>";
	echo "<form method='post' name=exitForm action='index.php'>";
	echo "<a class=\"skel-layers-ignoreHref\" onclick=\"document.forms['exitForm'].submit(); return false;\"><span class=\"icon fa-user\">Log out</span></a>";
	echo "<input type=hidden name=logout value='empty'>";
	echo "</form>";
	echo "</div>";

	echo "<div id=\"copyright\" style=\"position:relative; bottom:-200px;\">";
	echo "<ul><li>&copy; TruMark - Truman State University. All rights reserved.</li><li>Design: <a href=\"https://html5up.net\">HTML5 UP</a></li></ul>";
	echo "</div>";
	echo "</div>";
	echo "</body>";
	echo "</html>";

	$db->close();
}
?>