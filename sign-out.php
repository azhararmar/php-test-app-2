<?php

session_start();
session_destroy();
session_unset();
unset($_SESSION['user']);
$_SESSION = array();
header('Location: sign-in.php');