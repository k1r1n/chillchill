<?php
	require_once('../app/database/config.php');
	$data = json_decode(file_get_contents('php://input'), true);
	if($_SERVER['REQUEST_METHOD'] == "GET"){
		$stmt = $pdo->prepare("select * from ch_truemoney");
		$stmt->execute();
	  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$j = json_encode($result);
	  echo $j;
	}
?>
