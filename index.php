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
		<title>Home - Dashboard</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<a href="index.php">Home</a> |
		<a href="categories.php">Categories</a> |
		<a href="sign-out.php">Sign Out</a>
		<hr/>
		<h1>Item List</h1>
		<hr/>
		<a href="create-item.php">Create Item</a>
	</body>
</html>