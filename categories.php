<?php
	// If user is not logged in redirect user to sign in page.
	session_start();
	if (empty($_SESSION['user'])) {
		header('Location: sign-in.php');
		die();
	}
	
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
		<h1>Categories</h1>
		<hr/>
		<a href="create-category.php">Create Category</a>
	</body>
</html>