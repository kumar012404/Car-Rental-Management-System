<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Contact Us | CaRs Luxury Rentals</title>
	<link rel="stylesheet" href="feedback.css">
</head>

<body>

	<?php
	require_once('../connection.php');
	session_start();

	if (!isset($_SESSION['email'])) {
		header("Location: ../index.php");
		exit();
	}

	$email = $_SESSION['email'];

	if (isset($_POST['submit'])) {
		$comment = mysqli_real_escape_string($con, $_POST['comment']);
		$sql = "INSERT INTO feedback (EMAIL,COMMENT) VALUES ('$email','$comment')";
		$result = mysqli_query($con, $sql);
		if ($result) {
			echo '<script>alert("Thank you for contacting us! We appreciate your message.")</script>';
			echo '<script> window.location.href = "../cardetails.php";</script>';
		} else {
			echo '<script>alert("Service error, please try again later.")</script>';
		}
	}

	// Get User Name for personalization
	$user_query = "SELECT FNAME, LNAME FROM users WHERE EMAIL='$email'";
	$user_res = mysqli_query($con, $user_query);
	$user_row = mysqli_fetch_assoc($user_res);
	$user_name = $user_row['FNAME'] . " " . $user_row['LNAME'];
	?>

	<a href="../cardetails.php" class="back-btn">BACK TO HOME</a>

	<div class="feedback-container">
		<div class="feedback-card">
			<h2>GET IN TOUCH WITH US</h2>
			<p class="subtitle">We'd love to hear from you</p>

			<form method="POST">
				<div class="form-group">
					<label>Registered Name</label>
					<input type="text" value="<?php echo $user_name; ?>" readonly>
				</div>

				<div class="form-group">
					<label>Email Address</label>
					<input type="email" value="<?php echo $email; ?>" readonly>
				</div>

				<div class="form-group">
					<label>Your Message</label>
					<textarea name="comment" rows="6" placeholder="How was your ride? Share your thoughts with us..."
						required autofocus></textarea>
				</div>

				<button type="submit" class="submit-btn" name="submit">SEND MESSAGE</button>
			</form>
		</div>
	</div>

</body>

</html>