<?php
	ob_start();
	session_start();
	require_once('../app/database/config.php');
	$dateTime = date('Y-m-d H:i:s');
	$method   = $_SERVER['REQUEST_METHOD'];
	$data     = json_decode(file_get_contents('php://input'), true);
	#variable
	$pid  		= $data['pid'];
	if(!empty($_SESSION['uid'])){
		$uid  	= $_SESSION['uid'];
	}
	//$hash = password_hash($pass, PASSWORD_DEFAULT);
	#method
	if($method == "GET"){
		$sql 	= " select * from ch_cart
						JOIN ch_product
						JOIN ch_member
						JOIN ch_product_category
						WHERE ch_product.pid = ch_cart.pid and ch_member.uid = ch_cart.uid and ch_product_category.product_category_id = ch_product.category and ch_cart.uid =:uid";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':uid', $uid, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$j = json_encode($result);
		echo $j;
	}
	elseif($method == "POST"){
		$sql 	= " insert ch_cart set pid=:pid, uid=:uid, count =1 ,create_at =:datetime  ";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
		$stmt->bindParam(':datetime', $dateTime, PDO::PARAM_STR);
		$stmt->bindParam(':uid', $uid, PDO::PARAM_STR);
		$stmt->execute();
		echo "สินค้าเข้าสู่ตะกร้าเรียบร้อยแล้ว";
		session_write_close();
	}
	elseif($method == "DELETE"){
		$sql 	= " delete from ch_cart where cart_id=:cart_id  ";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':cart_id', $_GET['cart_id'], PDO::PARAM_STR);
		$stmt->execute();
		echo "ลบเรียบร้อยแล้ว";
		session_write_close();
	}
?>
