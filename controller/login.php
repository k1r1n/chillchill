<?php
	ob_start();
	session_start();
	require_once('../app/database/config.php');
	$dateTime = date('Y-m-d H:i:s');
	$data  = json_decode(file_get_contents('php://input'), true);
	#variable
	$user  = $data['username'];
	$pass  = $data['password'];
	$ip 	 = $data['ip'];
	$hash  = md5($pass);
	//$hash = password_hash($pass, PASSWORD_DEFAULT);
	#method
	if($_SERVER['REQUEST_METHOD'] == "GET"){
		//print_r($_SESSION);
		if(!empty($_SESSION['uid'])){
			header("Content-type: application/json");
			$session[]= array(
				'uid' 				  => $_SESSION['uid'],
		    'firstname' 	  => $_SESSION['firstname'],
		    'lastname' 		  => $_SESSION['lastname'],
		    'email' 			  => $_SESSION['email'],
		    'joined' 			  => $_SESSION['joined'],
		    'lastlogin' 	  => $_SESSION['lastlogin'],
		    'privilege' 	  => $_SESSION['privilege'],
				'status_online' => $_SESSION['status_online'],
		    'credit' 			  => $_SESSION['credit'],
				'ip_address' 	  => $_SESSION['ip_address']
		  );
		  $jsonstring = json_encode($session);
			echo $jsonstring;
			//session_write_close();
		}

	}
	elseif($_SERVER['REQUEST_METHOD'] == "POST"){
		$sql 	= " select * from ch_member where username = :user and password = :pass ";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':user', $user, PDO::PARAM_STR);
		$stmt->bindParam(':pass', $hash, PDO::PARAM_STR);
		$stmt->execute();
		$result	= $stmt->fetch(PDO::FETCH_ASSOC);
    if($stmt->rowCount() > 0){
      #check LoginStatus
			if($result["status_online"] == "1")
				echo "มีผู้ใช้งานก่อนหน้านี้เมื่อเวลา : ".$result["lastlogin"];
			else{
				#update lastlogin
				$update = "update ch_member SET lastlogin =:currentTime , status_online = 0 , ip_address =:ip WHERE uid =:uid ";
				$stmts = $pdo->prepare($update);
				$stmts->bindParam(':currentTime', $dateTime, PDO::PARAM_STR);
				$stmts->bindParam(':uid', $result['uid'], PDO::PARAM_STR);
				$stmts->bindParam(':ip', $ip, PDO::PARAM_STR);
				$stmts->execute();

				$_SESSION['uid'] 	         = $result['uid'];
				$_SESSION['firstname'] 	   = $result['firstname'];
				$_SESSION['lastname'] 	   = $result['lastname'];
				$_SESSION['email'] 			   = $result['email'];
				$_SESSION['joined'] 		   = $result['joined'];
				$_SESSION['lastlogin'] 	   = $result['lastlogin'];
				$_SESSION['privilege'] 	   = $result['privilege'];
				$_SESSION['status_online'] = $result['status_online'];
				$_SESSION['credit'] 		   = $result['credit'];
				$_SESSION['ip_address']    = $result['ip_address'];
				echo "success";
			}
    }else
    	echo "โปรดตรวจสอบข้อมูลให้ถูกต้อง.";
	}
	session_write_close();
?>
