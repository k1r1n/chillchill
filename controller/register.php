<?php
	ob_start();
	session_start();
	require_once('../app/database/config.php');
	$dateTime = date('Y-m-d H:i:s');
	$data  = json_decode(file_get_contents('php://input'), true);
	#variable
	$user  				= $data['username'];
	$pass  				= $data['password'];
	$email 				= $data['email'];
	$telephone 		= $data['telephone'];
	$firstname 		= $data['firstname'];
	$lastname 		= $data['lastname'];
	$address 			= $data['address'];
	$facebook_id  = $data['facebook_id'];
	$ip 	 				= $data['ip'];
	$hash 				= md5($pass);
	//$hash = password_hash($pass, PASSWORD_DEFAULT);
	#method
	if($_SERVER['REQUEST_METHOD'] == "GET"){

	}
	elseif($_SERVER['REQUEST_METHOD'] == "POST"){
		$sql 	= " insert ch_member set username =:user , password =:pass , email =:email , telephone =:telephone , firstname =:firstname , lastname =:lastname , address =:address , joined =:joined , lastlogin = null , facebook_id =:facebook_id , ip_address =:ip ";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':user', $user, PDO::PARAM_STR);
		$stmt->bindParam(':pass', $hash, PDO::PARAM_STR);
		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		$stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
		$stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
		$stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
		$stmt->bindParam(':address', $address, PDO::PARAM_STR);
		$stmt->bindParam(':joined', $dateTime, PDO::PARAM_STR);
		$stmt->bindParam(':facebook_id', $facebook_id, PDO::PARAM_STR);
		$stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
		$stmt->execute();
		echo "สมัครสมาชิกเรียบร้อยแล้ว";
		session_write_close();
	}
?>
