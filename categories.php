<?php
	// If user is not logged in redirect user to sign in page.
	session_start();
	if (empty($_SESSION['user'])) {
		header('Location: sign-in.php');
		die();
	}
	require_once 'database.php';
	$sth = $dbh->prepare('SELECT * FROM category ORDER BY id DESC');
	$sth->execute();
	$categories = $sth->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Dashboard</title>
		<style type="text/css">
			table thead tr th {
				text-align: left;
			}
		</style>
	</head>
	<body>
		<a href="categories.php">Categories</a> |
		<a href="index.php">Items</a> |
		<a href="sign-out.php">Sign Out</a>
		<hr/>
		<h1>Categories</h1>
		<hr/>
		<a href="create-category.php">Create Category</a><br/><br/>
		<table>
	        <thead>
	            <tr>
	                <th style="width:50px;">ID</th>
	                <th style="width:150px;">Category Name</th>
	                <th style="width: 50px;">Image</th>
	                <th style="width:150px;">Parent Category Id</th>
	            </tr>
	        </thead>
	        <tbody>
	        	<?php foreach ($categories as $category): ?>
		        	<tr>
		                <td><?php echo $category['id']; ?></td>
		                <td><?php echo $category['name']; ?></td>
		                <td style="padding: 0px !important;">
		                	<?php if (!empty($category['image'])): ?>
		                		<a href="uploads/<?php echo $category['image']; ?>" target="_blank">
		                			<img src="uploads/<?php echo $category['image']; ?>" style="height:35px;padding-top:5px;">
		                		</a>
		                	<?php endif; ?>
		                </td>
		                <td><?php echo $category['parent_id']; ?></td>
		            </tr>
	        	<?php endforeach; ?>
	        </tbody>
    	</table>
	</body>
</html>