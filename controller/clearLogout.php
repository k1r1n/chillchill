<?php
	require_once('../app/database/config.php');
	$data = json_decode(file_get_contents('php://input'), true);
	//*** Reject user not online
	$dateTime = date('Y-m-d H:i:s');
	$intRejectTime = 1; // Minute
	$sql = "update ch_member SET status_online = '0', lastlogin = null  WHERE 1 AND DATE_ADD(lastlogin, INTERVAL $intRejectTime MINUTE) <=NOW() ";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
?>
