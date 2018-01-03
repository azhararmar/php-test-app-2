<?php
require_once 'database.php';
$sth = $dbh->prepare('SELECT * FROM category WHERE parent_id = :parent_id ORDER BY id DESC');
$sth->bindParam(':parent_id', $_POST['category_id']);
$sth->execute();
$categories = $sth->fetchAll(PDO::FETCH_ASSOC);
header('Content-Type: application/json');
echo json_encode($categories);