<?php
	require_once('../app/database/config.php');
	$data = json_decode(file_get_contents('php://input'), true);
	if($_SERVER['REQUEST_METHOD'] == "GET"){
		if(!empty($_GET['id'])){
			$sql 	= " select * from ch_product where pid = :pid ";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':pid', $_GET['id'], PDO::PARAM_INT);
			$stmt->execute();
		}
		else{
		  $stmt = $pdo->prepare("select * from ch_product");
		  $stmt->execute();
		}
	  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$j = json_encode($result);
	  echo $j;
	}
?>
