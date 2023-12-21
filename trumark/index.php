<?php

// Set the default timezone to America/Chicago
date_default_timezone_set('America/Chicago');

session_start();
ob_start();

// Check if logged in
if (!(isset($_SESSION['aldkldmsldmsks2029dkj29di'])))
	header("Location: login.php");

// If the 'logout' post is provided, then log out of the system
if (isset($_POST['logout'])) {
	unset($_SESSION['aldkldmsldmsks2029dkj29di']);
	unset($_POST['logout']);
	header("Location: login.php");
}

// Initialize operator
if (!(isset($_POST['operation'])))
	$_POST['operation'] = "";
?>

<!DOCTYPE HTML>
<html>

<?php
$db = new mysqli("127.0.0.1", "root", "1453", "signature") or die("could not connect to mysql"); // Open up the connection
if ($db->connect_error)
	die("Connection failed: " . $db->connect_error);

$sql = "SELECT uid, username, first_name, last_name, email, major, avatar, banner
        FROM students 
        WHERE uid = '{$_SESSION['aldkldmsldmsks2029dkj29di']}'";

$result = mysqli_query($db, $sql);

if ($result) {
	$student = mysqli_fetch_assoc($result);

	// Display fetched student information
	if ($student) {
		$username = $student['username'];
		$first_name = $student['first_name'];
		$last_name = $student['last_name'];
		$email = $student['email'];
		$major = $student['major'];
		$avatar = $student['avatar'];
		$banner = $student['banner'];
	} else {
		echo "Student not found!";
	}
} else {
	echo "Error fetching student information: " . mysqli_error($db);
}
?>

<head>
	<title>TruMark</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel="stylesheet" href="assets-stellar/css/main.css" />

	<style>
		.popup {
			position: fixed;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			background-color: #fff;
			padding: 20px;
			border: 1px solid #ccc;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
			z-index: 9999;
			display: flex;
			flex-direction: column;
			align-items: center;
		}

		.popup input[type="password"],
		.popup button {
			margin-right: 10px;
			margin-left: 10px;
			margin-top: 10px;
			width: 100%;
		}

		.popup .button-container {
			display: flex;
			margin-top: 10px;

		}

		.profile-container {
			width: 100%;
			overflow-x: auto;
		}

		.profile-table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 20px;
			border-radius: 8px;
			overflow: hidden;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		}

		.profile-table th,
		.profile-table td {
			padding: 8px 12px;
			/* Reduced padding */
			text-align: left;
			border-bottom: 1px solid #eee;
			font-size: 0.9em;
			/* Reduced font size */
		}

		.profile-table th {
			background-color: #F2F2F2;
			font-weight: 600;
			color: #333;
			text-transform: uppercase;
		}

		.profile-table td {
			color: #555;
		}

		.profile-table tr:nth-child(even) {
			background-color: #E6E6FA;
			/* Light purple accent for even rows */
		}

		.profile-table tr:last-child td {
			border-bottom: none;
		}

		.error-message {
			color: red;
		}
	</style>
</head>

<body class="is-preload">

	<!-- Header or Side Navigation Bar-->
	<div id="header">

		<div class="top">

			<!-- Logo -->
			<div id="logo">
				<?php
				echo '<span class="image avatar48"><img src="images/avatars/' . htmlspecialchars($avatar) . '" alt="" /></span>';
				// Assuming $student['username'] contains the desired username
				echo '<h1 id="title">' . htmlspecialchars($first_name) . ' ' . htmlspecialchars($last_name) . '</h1>';
				echo '<p>' . htmlspecialchars($major) . '</p>';
				echo '<p>' . htmlspecialchars($email) . '</p>';
				echo '<p>' . htmlspecialchars($banner) . '</p>';
				?>

			</div>

			<!-- Nav -->
			<nav id="nav">
				<ul>
					<li><a href="#top" id="top-link"><span class="icon solid fa-home">Home</span></a></li>
					<li><a href="#my-profile" id="profile-link"><span class="icon solid fa-user">My Profile</span></a>
					</li>
					<li><a href="#courses" id="courses-link"><span class="icon solid fa-th">My Courses</span></a>
					</li>
					<!-- <li><a href="#report" id="report-link"><span class="icon solid fa-clipboard">Report</span></a> -->
					</li>
					<li><a href="#about" id="about-link"><span class="icon solid fa-info">About</span></a></li>
					<li><a href="#info" id="info-link"><span class="icon solid fa-tags">Info</span></a></li>

					<form method="post" name="exitForm" action="index.php">
						<li>
							<a onclick="confirmLogout();"><span class="icon solid fa-power-off"
									style="text-decoration: bold; cursor: pointer;">Log
									Out</span></a>
						</li>
						<input type="hidden" name="logout" value="empty">
					</form>
				</ul>
			</nav>

		</div>

		<div class="bottom">



			<!-- Social Icons -->
			<ul class="icons">
				<!-- <li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
				<li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
				<li><a href="#" class="icon brands fa-github"><span class="label">Github</span></a></li>
				<li><a href="#" class="icon brands fa-dribbble"><span class="label">Dribbble</span></a></li>
				<li><a href="#" class="icon solid fa-envelope"><span class="label">Email</span></a></li> -->
				<!-- <li>
					<a href="#" class="">
						<span class="label">
							<img src="images/bulldog.png" alt="" style="max-width: 100%; height: auto;">
						</span>
					</a>
				</li> -->
				<li style="list-style-type: none;">
					<span class="label">
						<img src="images/bulldog.png" alt="" style="max-width: 20%; height: auto;">
					</span>
				</li>

				<li style="list-style-type: none; font-size: 0.em;">&copy; TruMark - All
					rights reserved.</li>
				</li>

				</li>

			</ul>



		</div>

	</div>

	<!-- Main -->
	<div id="main">

		<!-- Intro Banner-->
		<section id="top" class="one dark cover">
			<div class="container" style="height: 15rem; width: 100%; padding-top: 13rem;">

				<header>
					<!-- <h2 class="alt"><strong>TruMark -</strong> a <a href="http://html5up.net/license">Truman State University</a> Project<br />
								site template designed by <a href="http://html5up.net">Cüneyt Aksoy</a>.</h2>
								<p>Attendance Re-Imagined</p> -->
				</header>

				<!-- <footer>
					<a href="#courses" class="button scrolly">My Courses 📘</a>
				</footer> -->

			</div>
		</section>

		<section id="welcome-page" class="" style="font-family: Arial, sans-serif; text-align: center;">
			<div class="container">
				<header>
					<?php $sql = "SELECT uid, username, first_name, last_name, email, major, avatar, banner, classification, GPA, `level`, degree, advisor FROM students WHERE uid = '{$_SESSION['aldkldmsldmsks2029dkj29di']}'";
					$result = mysqli_query($db, $sql);
					$student = mysqli_fetch_assoc($result);
					?>
					<h2>Hello,
						<?php echo htmlspecialchars($student['first_name']); ?> 👋
					</h2>
					<br>
					<div class="profile-welcome">
						<h4>Welcome to your profile page. Here you can view your information on the My Profile section,
							log
							attendance in the My Courses section, learn more about TruMark, access links, or change your
							password. 🎉
						</h4>
					</div>
				</header>
			</div>
		</section>

		<section id="my-profile" class="two light" style="font-family: Arial, sans-serif;">
			<div class="container">
				<header>
					<h2>My Profile 👤</h2>
				</header>
				<?php
				$sql = "SELECT uid, username, first_name, last_name, email, major, avatar, banner, classification, GPA, `level`, degree, advisor FROM students WHERE uid = '{$_SESSION['aldkldmsldmsks2029dkj29di']}'";
				$result = mysqli_query($db, $sql);
				if ($result) {
					$student = mysqli_fetch_assoc($result);
					// Display fetched student information
					if ($student) {
						?>
						<div class="profile-container">
							<table class="profile-table">
								<tr>
									<th>User ID</th>
									<td>
										<?php echo htmlspecialchars($student['uid']); ?>
									</td>
								</tr>
								<tr>
									<th>Username</th>
									<td>
										<?php echo htmlspecialchars($student['username']); ?>
									</td>
								</tr>
								<tr>
									<th>First Name</th>
									<td>
										<?php echo htmlspecialchars($student['first_name']); ?>
									</td>
								</tr>
								<tr>
									<th>Last Name</th>
									<td>
										<?php echo htmlspecialchars($student['last_name']); ?>
									</td>
								</tr>
								<tr>
									<th>Email</th>
									<td>
										<?php echo htmlspecialchars($student['email']); ?>
									</td>
								</tr>
								<tr>
									<th>Major</th>
									<td>
										<?php echo htmlspecialchars($student['major']); ?>
									</td>
								</tr>
								<tr>
									<th>Banner</th>
									<td>
										<?php echo htmlspecialchars($student['banner']); ?>
									</td>
								</tr>
								<tr>
									<th>Classification</th>
									<td>
										<?php echo htmlspecialchars($student['classification']); ?>
									</td>
								</tr>
								<tr>
									<th>GPA</th>
									<td>
										<?php echo htmlspecialchars($student['GPA']); ?>
									</td>
								</tr>
								<tr>
									<th>Level</th>
									<td>
										<?php echo htmlspecialchars($student['level']); ?>
									</td>
								</tr>
								<tr>
									<th>Degree</th>
									<td>
										<?php echo htmlspecialchars($student['degree']); ?>
									</td>
								</tr>
								<tr>
									<th>Advisor</th>
									<td>
										<?php echo htmlspecialchars($student['advisor']); ?>
									</td>
								</tr>

								<tr>
									<th>Password</th>
									<td>
										<center>
											<button onclick="openChangePasswordPopup()">Change Password</button>
										</center>
									</td>
								</tr>
							</table>
						</div>
						<?php
					} else {
						echo "<p class='error-message'>Student not found!</p>";
					}
				} else {
					echo "<p class='error-message'>Error fetching student information: " . mysqli_error($db) . "</p>";
				}
				?>
			</div>
		</section>



		<!-- Courses Section -->
		<section id="courses" class="three light">
			<!-- Bulldog Banner Image -->

			<div class="container"
				style="width: 100%; height: 16rem; background-image: url(./images/banner2.jpg); background-size: cover; background-position: center; background-repeat: no-repeat; margin-top: -6.5%;">
			</div>
			<div class="container">

				<!-- <p>Vitae natoque dictum etiam semper magnis enim feugiat convallis convallis
					egestas rhoncus ridiculus in quis risus amet curabitur tempor orci penatibus.
					Tellus erat mauris ipsum fermentum etiam vivamus eget. Nunc nibh morbi quis
					fusce hendrerit lacus ridiculus.</p> -->

				<?php

				$sql = "SELECT uid, username, first_name, last_name, email, major, avatar, banner, classification, GPA, `level`, degree, advisor FROM students WHERE uid = '{$_SESSION['aldkldmsldmsks2029dkj29di']}'";

				$result = mysqli_query($db, $sql);

				if ($result) {
					$student = mysqli_fetch_assoc($result);

					// Display fetched student information
					if ($student) {
						$username = $student['username'];
						$first_name = $student['first_name'];
						$last_name = $student['last_name'];
						$email = $student['email'];
						$major = $student['major'];
						$avatar = $student['avatar'];
						$banner = $student['banner'];
						$uid = $student['uid'];
						$classification = $student['classification'];
						$GPA = $student['GPA'];
						$level = $student['level'];
						$degree = $student['degree'];
						$advisor = $student['advisor'];
					} else {
						echo "Student not found!";
					}
				} else {
					echo "Error fetching student information: " . mysqli_error($db);
				}
				?>

				<!-- Intro -->

				<header>
					<br>
					<br>
					<h2>Courses 📚</h2>


					<!-- Display Date & Time -->
					<p id="date_time"></p>
					<script>
						function updateDateTime() {
							var now = new Date();
							var day = now.toLocaleDateString('en-US', { weekday: 'long' });
							var date = now.toLocaleDateString();
							var time = now.toLocaleTimeString();
							document.getElementById('date_time').innerHTML = day + ', ' + date + ' ' + time;
						}
						setInterval(updateDateTime, 1000);
					</script>

				</header>

				<p>Thank you for using TruMark. To log your attendance for today's classes:</p>
				<ol>
					<li>Select the course from the dropdown below</li>
					<li>Sign your name to confirm the record</li>
					<li>Click "Submit" to record your attendance</li>
				</ol>

				<?php
				$sql = "SELECT * FROM enrolled JOIN courses ON enrolled.cid = courses.cid WHERE enrolled.uid = '{$_SESSION['aldkldmsldmsks2029dkj29di']}'";
				$courses = mysqli_query($db, $sql);
				echo '<div class="row" style = "justify-content: center;">';

				// Start the while loop
				while ($course = mysqli_fetch_array($courses, MYSQLI_ASSOC)) {
					echo '<div class="col-4 col-12-mobile">';
					echo '<article class="item">';
					echo '<form action="courses.php" method="post">';
					echo '<input type="hidden" name="cid" value="' . $course['cid'] . '">';
					echo '<input type="hidden" name="operation" value="course_info">';
					$imagePath = $course['course_picture']; // $row should contain the fetched data
					echo '<a href="courses.php" onclick="this.closest(\'form\').submit(); return false;">'; // Submit form on image click
					echo '<img src="images/' . $imagePath . '" alt="Course Image" style="width: 293px; height: 195px;">';
					echo '</a>';
					echo '</form>';
					echo '<header>';
					echo "<h3>" . ucwords($course['course_name']) . "</h3>";
					echo '</header>';
					echo '</article>';
					echo '</div>';
				}

				echo '</div>'; // End of row
				
				?>
				<br>
				<br>
				<br>
				<a href="#top" class="button scrolly">Go back ✨</a>
			</div>
		</section>

		<!-- Report Section -->

		<!-- <section id="report" class="two light">
			<header>
				<h2>Student Report 📈</h2>

			</header>
		</section> -->


		<!-- About Section -->
		<section id="about" class="three light">
			<div class="container">

				<header>
					<h2>About TruMark ℹ️</h2>
				</header>

				<!-- <a href="#" class="image featured"><img src="images/pic08.jpg" alt="" /></a> -->
				<a href="#" class="image featured"><img src="./images/campus2.jpg" alt="" /></a>

				<p style="text-align: left; text-align: justify;">
					TruMark revolutionizes attendance tracking at <a href="https://www.truman.edu/">Truman State
						University</a>, re-imagining the traditional process to pave the way for future innovative
					systems.
					<br>
					<br>
					TruMark offers students a hassle-free way to log their attendance online, liberating them from the
					hassles of manual processes. Simultaneously, it empowers faculty with secure, centralized attendance
					records, ensuring ease of access anytime, anywhere.

					<br>
					<br>
					Students can effortlessly log their attendance for classes while the system securely stores records
					in a centralized database, granting faculty anytime access.
					<br>
					<br>
					Utilizing machine learning and signature analysis, TruMark ensures accurate attendance data by
					authenticating user logins. Moreover, its consolidated analytics unveil trends, empowering faculty
					to support student success effectively.
				</p>
			</div>
		</section>

		<!-- Contact -->
		<!-- <section id="contact" class="four">
			<div class="container">

				<header>
					<h2>Contact</h2>
				</header>

				<p>Elementum sem parturient nulla quam placerat viverra
					mauris non cum elit tempus ullamcorper dolor. Libero rutrum ut lacinia
					donec curae mus. Eleifend id porttitor ac ultricies lobortis sem nunc
					orci ridiculus faucibus a consectetur. Porttitor curae mauris urna mi dolor.</p>

				<form method="post" action="#">
					<div class="row">
						<div class="col-6 col-12-mobile"><input type="text" name="name" placeholder="Name" /></div>
						<div class="col-6 col-12-mobile"><input type="text" name="email" placeholder="Email" /></div>
						<div class="col-12">
							<textarea name="message" placeholder="Message"></textarea>
						</div>
						<div class="col-12">
							<input type="submit" value="Send Message" />
						</div>
					</div>
				</form>

			</div>
		</section> -->

	</div>


	<!-- <footer id="footer"> -->
	<div id="footer">
		<!-- <section>
			<h2>Aliquam sed mauris</h2>
			<p>Sed lorem ipsum dolor sit amet et nullam consequat feugiat consequat magna adipiscing tempus etiam dolore veroeros. eget dapibus mauris. Cras aliquet, nisl ut viverra sollicitudin, ligula erat egestas velit, vitae tincidunt odio.</p>
			<ul class="actions">
				<li><a href="#" class="button">Learn More</a></li>
			</ul>
		</section> -->
		<section id="info">
			<h2> - More Information - </h2>
			<br>
			<br>
			<dl class="alt">
				<dt>📍 Address</dt>
				<dd>100 E Normal Ave &bull; Kirksville, MO 63501 &bull; USA</dd>
				<dt>☎️ Phone</dt>
				<dd>(660) 785-4000</dd>
				<dt>📧 Email</dt>
				<dd><a href="#">admissions@truman.edu</a></dd>
			</dl>
			<ul class="icons">
				<li><a href="https://twitter.com/TrumanState" class="icon brands fa-twitter alt" target="_blank"><span
							class="label">Twitter</span></a></li>
				<li><a href="https://www.facebook.com/trumanstateuniversity/" class="icon brands fa-facebook-f alt"
						target="_blank"><span class="label">Facebook</span></a></li>
				<li><a href="https://www.instagram.com/trumanstate/" class="icon brands fa-instagram alt"
						target="_blank"><span class="label">Instagram</span></a></li>
				<li><a href="https://github.com/Axxoy" class="icon brands fa-github alt" target="_blank"><span
							class="label">GitHub</span></a></li>
				<li><a href="https://www.youtube.com/user/trumanuniversity" class="icon brands fa-youtube alt"
						target="_blank"><span class="label">YouTube</span></a></li>
				<li><a href="https://www.linkedin.com/in/cuneyt-aksoy" class="icon brands fa-linkedin alt"
						target="_blank"><span class="label">LinkedIn</span></a></li>
				<li><a href="https://www.truman.edu" class="icon brands fa-html5" target="_blank"><span
							class="label">Website</span></a></li>
			</ul>
		</section>
		<!-- </footer> -->
	</div>

	<!-- Footer -->
	<div id="footer">

		<!-- Copyright -->
		<ul class="copyright">
			<li>&copy; TruMark - Truman State University. All rights reserved.</li>
			<li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
		</ul>

	</div>
	<!-- ---- -->

	<!-- Scripts -->
	<script src="assets-stellar/js/jquery.min.js"></script>
	<script src="assets-stellar/js/jquery.scrolly.min.js"></script>
	<script src="assets-stellar/js/jquery.scrollex.min.js"></script>
	<script src="assets-stellar/js/browser.min.js"></script>
	<script src="assets-stellar/js/breakpoints.min.js"></script>
	<script src="assets-stellar/js/util.js"></script>
	<script src="assets-stellar/js/main.js"></script>


	<script>
		function openChangePasswordPopup() {
			// Create a popup dialog
			var popup = document.createElement("div");
			popup.className = "popup";

			// Create password input field
			var passwordInput = document.createElement("input");
			passwordInput.type = "password";
			passwordInput.placeholder = "Enter new password";
			popup.appendChild(passwordInput);

			// Create a container div for the buttons
			var buttonContainer = document.createElement("div");
			buttonContainer.style.display = "flex"; // Set the container to flex display
			buttonContainer.style.justifyContent = "space-between"; // Add space between the buttons

			// Create submit button
			var submitButton = document.createElement("button");
			submitButton.innerText = "Submit";

			// Create cancel button
			var cancelButton = document.createElement("button");
			cancelButton.innerText = "Cancel";
			cancelButton.style.backgroundColor = "red"; // Set the background color to red

			// Add event listeners to the buttons
			submitButton.addEventListener("click", function () {
				// Handle password change logic here
				var newPassword = passwordInput.value;

				// Send the new password to the server for database update
				var xhr = new XMLHttpRequest();
				xhr.open("POST", "update_password.php", true);
				xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xhr.onreadystatechange = function () {
					if (xhr.readyState === 4 && xhr.status === 200) {
						// Handle the server response here (e.g., show success message)
						alert(xhr.responseText);

						// Close the popup
						document.body.removeChild(popup);
					}
				};
				xhr.send("newPassword=" + newPassword);
			});

			cancelButton.addEventListener("click", function () {
				// Close the popup
				document.body.removeChild(popup);
			});

			// Append the buttons to the container
			buttonContainer.appendChild(submitButton);
			buttonContainer.appendChild(cancelButton);

			// Append the button container to the popup
			popup.appendChild(buttonContainer);

			// Append the popup to the body
			document.body.appendChild(popup);
		}	</script>



	<script>
		function confirmLogout() {
			if (confirm("Are you sure you want to log out?")) {
				document.forms['exitForm'].submit();
			}
		}	</script>

</body>

</html>