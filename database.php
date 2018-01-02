<?php

$host = 'localhost';
$dbName = 'php_test_app';
$dbUser = 'needle';
$dbPass = 'needle';

try {
	$dbh = new PDO('mysql:host='.$host.';dbname='.$dbName, $dbUser, $dbPass);
} catch(PDOException $exception) {
	echo $exception->getMessage();
}
