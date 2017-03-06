<?php
	ini_set('display_errors', 1);
	error_reporting(~0);
	date_default_timezone_set("Asia/Bangkok");
	define("HOST", "");
	define("USER", "");
	define("PASSWORD", "");
	define("DB", "");
	$pdo = new PDO("mysql:host=".HOST.";dbname=".DB.";charset=utf8", USER, PASSWORD);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
