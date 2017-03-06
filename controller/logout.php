<?php
	ob_start();
	session_start();
	require_once('../app/database/config.php');
	$data = json_decode(file_get_contents('php://input'), true);
	$sql = "update ch_member SET 	status_online = '0' WHERE uid =:uid  ";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':uid', $_SESSION['uid'], PDO::PARAM_STR);
	$stmt->execute();
	session_destroy();
	session_write_close();
?>
