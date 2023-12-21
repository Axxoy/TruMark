<?php

// Set the default timezone to America/Chicago
date_default_timezone_set('America/Chicago');

session_start();
ob_start();

// Initialize operator
if (!(isset($_POST['operation'])))
	$_POST['operation'] = "";

// Check if logged in
if (isset($_SESSION['aldkldmsldmsks2029dkj29di'])) {
	header("Location: index.php");
}

// Switch that decides on which function is to be called depending on the parameter value it receives.
switch ($_POST['operation']) {
	case 'login':
		login($_POST['usernameOrEmail'], $_POST['password']);
		main_page();
		break;
	default:
		main_page();
}

function login($usernameOrEmail, $password)
{
	$db = new mysqli("127.0.0.1", "root", "1453", "signature") or die("could not connect to mysql"); // Open up the connection
	if ($db->connect_error)
		die("Connection failed: " . $db->connect_error);

	$usernameOrEmail = $db->real_escape_string($_POST['usernameOrEmail']); // Sanitize user input
	$password = $db->real_escape_string($_POST['password']); // Sanitize user input

	$result = $db->query("SELECT * FROM students WHERE username='$usernameOrEmail' OR email='$usernameOrEmail'");
	if ($result) {
		$row = $result->fetch_assoc();
		if ($row) {
			if ($row['password'] == $password) {
				$_SESSION['aldkldmsldmsks2029dkj29di'] = $row['uid'];
				$result->free(); // Free the result set
				header("Location: index.php");
				exit();
			} else {
				$result->free(); // Free the result set
				echo "<script type='text/javascript'>alert(\"Incorrect credentials!\")</script>";
			}
		} else {
			// echo "User not found.";
			echo "<script type='text/javascript'>alert(\"User not found!\")</script>";
		}
	} else {
		echo "Error: " . $db->error;
	}

	$db->close(); // Close the database connection
}

function main_page()
{
	echo "<!DOCTYPE HTML>";
	echo "<html>";
	echo "<head>";
	echo "<title>Login Page</title>";
	echo "<meta charset=\"utf-8\" />";
	echo "<link rel=\"stylesheet\" href=\"css/main.css\"/>";
	echo "</head>";
	echo "<body>";
	echo "<div id=\"container\">";
	echo "<div id=\"login-form\">";
	echo "<center>";
	echo "<img src='./images/logo.png' alt='Banner' style='width: 30em; background-image: linear-gradient(45deg, #e37682 15%, #5f4d93 85%);'>";
	echo "<br/>";
	echo "<br/>";
	echo "</center>";
	echo "<form method='post' name=loginForm action='login.php'>";
	echo "<input type=\"text\" name=\"usernameOrEmail\" placeholder=\"Username or Email\">";
	echo "<br/>";
	echo "<input type=\"password\" name=\"password\" placeholder=\"Password\">";
	echo "<br/>";
	echo "<input type=\"submit\" value=\"Login\" />";
	echo "<input type=hidden name=operation value='login'>";
	echo "</form>";
	echo "</div>";
	echo "</div>";
	echo "</body>";
	echo "</html>";
}
?>