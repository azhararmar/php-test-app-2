<?php
	// If user is not logged in redirect user to sign in page.
	session_start();
	if (empty($_SESSION['user'])) {
		header('Location: sign-in.php');
		die();
	}
	// Fetch all items
	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Dashboard</title>
	</head>
	<body>
		<a href="index.php">Home</a> |
		<a href="categories.php">Categories</a> |
		<a href="sign-out.php">Sign Out</a>
		<hr/>
		<h1>Create Item</h1>
		<hr/>
		<form action="create-item.php" method="post">
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