<?php
	// If user is not logged in redirect user to sign in page.
	session_start();
	ob_start();
	if (empty($_SESSION['user'])) {
		header('Location: sign-in.php');
		die();
	}
	require_once 'database.php';
	$sth = $dbh->prepare('SELECT id, name FROM category WHERE parent_id IS NULL');
	$sth->execute();
	$categories = $sth->fetchAll(PDO::FETCH_ASSOC);
	$errorMessages = array();
	if (isset($_POST['submit'])) {
		// Check if empty email address
		if (empty($_POST['name'])) {
			$errorMessages['name'] = 'Name is required and can\'t be empty.';
		}
		if ('subcategory' == $_POST['type'] && empty($_POST['parent_id'])) {
			$errorMessages['parent_id'] = 'Parent category is required and can\'t be empty.';
		}
		if (!empty($_FILES)) {
			$tmpImageName = $_FILES['image']['tmp_name'];
			$imageName = $_FILES['image']['name'];
			if (!getimagesize($tmpImageName)) {
				$errorMessages['image'] = 'Invalid image file.';
			}
			// Upload image only if validation passed for image type
			if (empty($errorMessages['image'])) {
				$newFileName = substr(sha1(mt_rand()), 0, 20).'.'.pathinfo($imageName, PATHINFO_EXTENSION);
				if (move_uploaded_file($tmpImageName, __DIR__.'/uploads/'.$newFileName)) {
					$_POST['image'] = $newFileName;
				}
			}
		}
		if (empty($errorMessages)) {
			$fields = array();
			$fields['name'] = ':name';
			if ('subcategory' == $_POST['type']) {
				$fields['parent_id'] = ':parent_id';
			}
			if (!empty($_POST['image'])) {
				$fields['image'] = ':image';
			}
			$sql = 'INSERT INTO category('.implode(', ', array_keys($fields)).') VALUES('.implode(', ', array_values($fields)).')';
			$sth = $dbh->prepare($sql);
			foreach ($fields as $key => $value) {
				$sth->bindParam($value, $_POST[$key]);
			}
			$sth->execute();
			// Reload current page to clear form submission
			header('Location: create-category.php');
		}
	}
	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Create Category - Dashboard</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<a href="categories.php">Categories</a> |
		<a href="index.php">Items</a> |
		<a href="sign-out.php">Sign Out</a>
		<hr/>
		<h1>Create Category or Sub Category</h1>
		<hr/>
		<a href="categories.php">Back</a><br/><br/>
		<form action="create-category.php" method="post" enctype="multipart/form-data">
			<label>Type: </label>
			<input type="radio" name="type" value="category" id="radio-category" checked="checked">
			<label for="radio-category">Category</label>
			<input class="input-radio" type="radio" name="type" value="subcategory" id="radio-subcategory">
			<label for="radio-subcategory">Sub Category</label><br/>
				<label>Parent Category: </label>
				<select name="parent_id" id="category">
					<option></option>
					<?php foreach ($categories as $category): ?>
						<option value="<?php echo $category['id'] ?>">
							<?php echo $category['name']; ?>
						</option>
					<? endforeach; ?>
				</select>
				<span>(Select this only if you want to create subcategory, else leave this empty)</span>
				<br/>
				<span class="error">
					<?php echo !empty($errorMessages['parent_id']) ? $errorMessages['parent_id'].'<br/>' : ''; ?>
				</span>
			<label for="name">Name:</label>
			<input type="text" id="name" name="name"><br/>
			<span class="error">
				<?php echo !empty($errorMessages['name']) ? $errorMessages['name'].'<br/>' : ''; ?>
			</span>
			<label for="image">Image</label>
			<input type="file" id="image" name="image" accept="image/*"><br/>
			<?php echo !empty($errorMessages['image']) ? $errorMessages['image'].'<br/>' : ''; ?>
			<br/><input type="submit" name="submit" value="Save">
		</form>
		<script type="text/javascript">

		</script>
	</body>
</html>