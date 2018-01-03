<?php
	// If user is not logged in redirect user to sign in page.
	session_start();
	if (empty($_SESSION['user'])) {
		header('Location: sign-in.php');
		die();
	}
	require_once 'database.php';
	$sth = $dbh->prepare('SELECT * FROM category WHERE parent_id IS NULL ORDER BY id DESC');
	$sth->execute();
	$categories = $sth->fetchAll(PDO::FETCH_ASSOC);
	$subcategories = array();
	if (!empty($_POST['category_id'])) {
		$sth = $dbh->prepare('SELECT * FROM category WHERE parent_id = :parent_id ORDER BY id DESC');
		$sth->bindParam(':parent_id', $_POST['category_id']);
		$sth->execute();
		$subcategories = $sth->fetchAll(PDO::FETCH_ASSOC);
	}
	if (isset($_POST['submit'])) {
		// Check if empty name
		if (empty($_POST['name'])) {
			$errorMessages['name'] = 'Name is required and can\'t be empty.';
		}
		// Check if empty stock
		if (empty($_POST['stock'])) {
			$errorMessages['stock'] = 'Stock is required and can\'t be empty.';
		}
		// Check if empty price
		if (empty($_POST['price'])) {
			$errorMessages['price'] = 'Price is required and can\'t be empty.';
		}
		// Check if empty category
		if (empty($_POST['category_id'])) {
			$errorMessages['category_id'] = 'Category is required and can\'t be empty.';
		}
		// Check if empty sub category
		if (empty($_POST['subcategory_id'])) {
			$errorMessages['subcategory_id'] = 'Sub Category is required and can\'t be empty.';
		}
		if (empty($errorMessages)) {
			$sql = 'INSERT INTO item(name, stock, price, category_id) VALUES(:name, :stock, :price, :category_id)';
			$sth = $dbh->prepare($sql);
			$sth->bindParam(':name', $_POST['name']);
			$sth->bindParam(':stock', $_POST['stock']);
			$sth->bindParam(':price', $_POST['price']);
			$sth->bindParam(':category_id', $_POST['subcategory_id']);
			$sth->execute();
			// Reload current page to clear form submission
			header('Location: create-item.php');
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Create Item - Dashboard</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<a href="categories.php">Categories</a> |
		<a href="index.php">Items</a> |
		<a href="sign-out.php">Sign Out</a>
		<hr/>
		<h1>Create Item</h1>
		<hr/>
		<form action="create-item.php" method="post">

			<?php $nameVal = !empty($_POST['name']) ? $_POST['name'] : ''; ?>
			Name:
			<input type="text" name="name" placeholder="Enter item name" value="<?php echo $nameVal; ?>"><br/>
			<span class="error">
				<?php echo !empty($errorMessages['name']) ? $errorMessages['name'].'<br/>' : ''; ?>
			</span>

			<?php $stockVal = !empty($_POST['stock']) ? $_POST['stock'] : ''; ?>
			Stock:
			<input type="text" name="stock" placeholder="Enter stock in hand" value="<?php echo $stockVal; ?>"><br/>
			<span class="error">
				<?php echo !empty($errorMessages['stock']) ? $errorMessages['stock'].'<br/>' : ''; ?>
			</span>

			<?php $priceVal = !empty($_POST['price']) ? $_POST['price'] : ''; ?>
			Price:
			<input type="text" name="price" placeholder="Enter item price" value="<?php echo $priceVal; ?>"><br/>
			<span class="error">
				<?php echo !empty($errorMessages['price']) ? $errorMessages['price'].'<br/>' : ''; ?>
			</span>

			Category:
			<select name="category_id" id="category_id" onchange="fetchSubcategories(this)">
				<option></option>
				<?php foreach ($categories as $category): ?>
					<?php $categoryId = !empty($_POST['category_id']) ? $_POST['category_id'] : 0; ?>
					<?php $selected = $categoryId == $category['id'] ? 'selected="selected"' : ''; ?>
					<option value="<?php echo $category['id'] ?>" <?php echo $selected ?>>
						<?php echo $category['name']; ?>
					</option>
				<? endforeach; ?>
			</select><br/>
			<span class="error">
				<?php echo !empty($errorMessages['category_id']) ? $errorMessages['category_id'].'<br/>' : ''; ?>
			</span>

			Sub Category:
			<?php $subcategoryIsDisabled = empty($_POST['category_id']) ? 'disabled="disabled"' : ''; ?>
			<select name="subcategory_id" id="subcategory_id" <?php echo $subcategoryIsDisabled; ?>>
				<option></option>
				<?php foreach ($subcategories as $subcategory): ?>
					<?php $subcategoryId = !empty($_POST['subcategory_id']) ? $_POST['subcategory_id'] : 0; ?>
					<?php $selected = $subcategoryId == $subcategory['id'] ? 'selected="selected"' : ''; ?>
					<option value="<?php echo $subcategory['id'] ?>" <?php echo $selected ?>>
						<?php echo $subcategory['name']; ?>
					</option>
				<? endforeach; ?>
			</select><br/>
			<span class="error">
				<?php echo !empty($errorMessages['subcategory_id']) ? $errorMessages['subcategory_id'].'<br/>' : ''; ?>
			</span>
			<br/><input type="submit" name="submit" value="Save">
		</form>
	</body>
	<script type="text/javascript">
		function fetchSubcategories(element) {
			document.getElementById('subcategory_id').innerHTML = '';
			document.getElementById('subcategory_id').setAttribute('disabled', 'disabled');
			var selectedValue = (element.value || element.options[element.selectedIndex].value);
			var http = new XMLHttpRequest();
			var url = 'fetch-subcategories.php';
			var params = 'category_id='+selectedValue;
			http.open('POST', url, true);
			http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			http.onreadystatechange = function() {
			    if(http.readyState == 4 && http.status == 200) {
			    	var response = JSON.parse(http.responseText);
			    	var html = '<option></option>';
			        for (var i = 0; i < response.length; i++){
			        	var obj = response[i];
			        	html += '<option value="'+obj.id+'">'+obj.name+'</option>';
			        }
			        document.getElementById('subcategory_id').innerHTML = html;
			        if (response.length >= 1) {
			        	document.getElementById('subcategory_id').removeAttribute('disabled');
			        }
			    }
			}
			http.send(params);
		}
	</script>
</html>