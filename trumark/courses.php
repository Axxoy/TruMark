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
	case 'course_info':
		course_info($_POST['cid']);
		break;
}

function course_info($cid)
{
	?>

	<!DOCTYPE HTML>
	<html>

	<head>
		<title>Home</title>
		<link rel="stylesheet" href="css/main.css">
		<style>
			html,
			body {
				height: 100%;
				margin: 0;
				background-image: none !important;
				/* Disable background image */
				background-color: #361945 !important;
			}
		</style>
	</head>

	<body>
		<div>
			<div id="intro" style="position:relative; top:-100px;">
				<?php
				$db = new mysqli("127.0.0.1", "root", "1453", "signature") or die("could not connect to mysql"); // Open up the connection
				if ($db->connect_error)
					die("Connection failed: " . $db->connect_error);

				$result = $db->query("SELECT * FROM courses WHERE cid='{$cid}'");
				$course = $result->fetch_assoc();
				?>

				<h1 class="course-heading" style="font-size: 50px;">
					<?php echo htmlspecialchars($course['course_name']); ?>
				</h1>
				<br>
				<?php echo "Course Instructor: " . $course['instructor'] . "<br><br>"; ?>
				<h3> Course Description:</h3>
				<br>

				<?php
				// echo $course['course_desc'];
				echo '<p style="text-align: justify;">' . $course['course_desc'] . '</p>';
				$course_schedules = $db->query("SELECT * FROM course_schedule WHERE course_id='{$cid}'");


				echo "<h3> Course Schedule: </h3>";

				$course_schedule_array = [];

				while ($course_schedule = mysqli_fetch_array($course_schedules, MYSQLI_ASSOC)) {
					$dayOfWeek = $course_schedule['day_of_week'];
					$startTime = $course_schedule['start_time'];
					$endTime = $course_schedule['end_time'];

					// Convert the start time to a formatted 12-hour time with AM/PM
					$startTimeFormatted = date("h:i A", strtotime($startTime));

					// Convert the end time to a formatted 12-hour time with AM/PM
					$endTimeFormatted = date("h:i A", strtotime($endTime));

					$course_schedule_array[] = [
						'dayOfWeek' => $dayOfWeek,
						'startTime' => $startTimeFormatted,
						'endTime' => $endTimeFormatted
					];
				}

				usort($course_schedule_array, function ($a, $b) {
					$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
					$aIndex = array_search($a['dayOfWeek'], $days);
					$bIndex = array_search($b['dayOfWeek'], $days);
					return $aIndex - $bIndex;
				});

				foreach ($course_schedule_array as $course_schedule) {
					$dayOfWeek = $course_schedule['dayOfWeek'];
					$startTime = $course_schedule['startTime'];
					$endTime = $course_schedule['endTime'];

					echo '<span style="font-size: 16px;">' . "$dayOfWeek from $startTime to $endTime<br>" . '</span>';
				}
				?>

				<br>
				<form action='take_signature.php' method='post'>
					<input type='hidden' name='cid' value='<?php echo $cid; ?>'>
					<input type='submit' value='Take Attendance'>
				</form>
				<br>
				<a href="index.php#courses">&lt; Back</a>
				<form method='post' name='exitForm' action='index.php'>
					<input type='hidden' name='logout' value='empty'>
				</form>
			</div>
			<div id="copyright" style="position:relative; bottom:0px;">
				<ul>
					<li>&copy; TruMark</li>
					<li>Design: <a href="https://html5up.net">HTML5 UP</a></li>
				</ul>
			</div>
		</div>
	</body>

	</html>

<?php } ?>