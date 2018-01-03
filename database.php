<?php

$host = 'localhost';
$dbName = 'php_test_app';
$dbUser = 'needle';
$dbPass = 'needle';

try {
	$dbh = new PDO('mysql:host='.$host.';dbname='.$dbName, $dbUser, $dbPass);
	$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
} catch(PDOException $exception) {
	echo $exception->getMessage();
}
