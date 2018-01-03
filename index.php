<?php
	// If user is not logged in redirect user to sign in page.
	session_start();
	if (empty($_SESSION['user'])) {
		header('Location: sign-in.php');
		die();
	}
	require_once 'database.php';
	$sth = $dbh->prepare('
		SELECT
			i.id,
			i.name,
			i.stock,
			i.price,
			c.name as category_name
		FROM
			item i
		LEFT JOIN
			category c ON i.category_id = c.id
		ORDER BY 
			i.id DESC
	');
	$sth->execute();
	$items = $sth->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Dashboard - Home</title>
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
		<h1>Items</h1>
		<hr/>
		<a href="create-item.php">Create Item</a><br/><br/>
		<table>
	        <thead>
	            <tr>
	                <th style="width:50px;">ID</th>
	                <th style="width:150px;">Name</th>
	                <th style="width: 50px;">Stock</th>
	                <th style="width: 50px;">Price</th>
	                <th style="width:150px;">Category Name</th>
	            </tr>
	        </thead>
	        <tbody>
	        	<?php foreach ($items as $item): ?>
		        	<tr>
		                <td><?php echo $item['id']; ?></td>
		                <td><?php echo $item['name']; ?></td>
		                <td><?php echo $item['stock']; ?></td>
		                <td><?php echo $item['price']; ?></td>
		                <td><?php echo $item['category_name']; ?></td>
		            </tr>
	        	<?php endforeach; ?>
	        </tbody>
    	</table>
	</body>
</html>