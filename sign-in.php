<?php
	session_start();
	if (!empty($_SESSION['user'])) {
		header('Location: index.php');
		die();
	}
	require_once 'database.php';
	$errorMessages = array();
	if (isset($_POST['submit'])) {
		// Check if empty email address
		if (empty($_POST['email'])) {
			$errorMessages['email'] = 'Email address is required and can\'t be empty.';
		}
		// Check if empty password
		if (empty($_POST['password'])) {
			$errorMessages['password'] = 'Password is required and can\'t be empty.';
		}
		// Check if valid email address
		if (!empty($_POST['email']) && !filter_var($_POST['email'], \FILTER_VALIDATE_EMAIL)) {
			$errorMessages['email'] = 'Invalid email address';
		}
		// Fetch user with given email address
		$sth = $dbh->prepare('SELECT id, name, email, password FROM user WHERE user.email = :email');
		$sth->bindParam(':email', $_POST['email']);
		$sth->execute();
		$user = $sth->fetch(PDO::FETCH_ASSOC);
		// Check if user with given email address exist
		if (!empty($_POST['email']) && empty($user)) {
			$errorMessages['email'] = 'User with this email address not found.';
		}
		// Validate Password
		if (!empty($_POST['password']) && $user && !password_verify($_POST['password'], $user['password'])) {
			$errorMessages['password'] = 'Invalid password.';
		}
		// Validation passed, set authentication session and redirect user to index.php
		$_SESSION['user'] = $user;
		header('Location: index.php');
		die();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Sign In</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<form action="sign-in.php" method="post">
			<?php $emailVal = !empty($_POST['email']) ? $_POST['email'] : ''; ?>
			<input type="text" name="email" placeholder="Enter your email address" value="<?php echo $emailVal; ?>"><br/>
			<span class="error">
				<?php echo !empty($errorMessages['email']) ? $errorMessages['email'].'<br/>' : ''; ?>
			</span>
			<input type="password" name="password" placeholder="Enter your email Password"><br/>
			<span class="error">
				<?php echo !empty($errorMessages['password']) ? $errorMessages['password'].'<br/>' : ''; ?>
			</span>
			<input type="submit" name="submit" value="Sign In">
		</form>
	</body>
</html>