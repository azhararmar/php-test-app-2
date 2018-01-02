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
		<title>Create Category - Dashboard</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<a href="index.php">Home</a> |
		<a href="categories.php">Categories</a> |
		<a href="sign-out.php">Sign Out</a>
		<hr/>
		<h1>Create Category or Sub Category</h1>
		<hr/>
		<form action="create-category.php" method="post">
			<label>Type: </label>
			<input type="radio" name="type" value="category" id="radio-category" checked="checked">
			<label for="radio-category">Category</label>
			<input class="input-radio" type="radio" name="type" value="subcategory" id="radio-subcategory">
			<label for="radio-subcategory">Sub Category</label><br/>
			<div class="hide">
				<label>Parent Category: </label>
				<select name="parent_id" id="category">
					<option></option>
				</select><br/>
			</div>
			<label for="name">Name:</label>
			<input type="text" id="name" name="name"><br/>
			<label for="image">Image</label>
			<input type="file" id="image" name="image" accept="image/*"><br/><br/>
			<input type="submit" name="submit" value="Save">
		</form>
		<script type="text/javascript">
			
		</script>
	</body>
</html>