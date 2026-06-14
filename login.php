<?php
session_start();
include './assets/inc/config.php';

if(isset($_REQUEST['email']) && isset($_REQUEST['password'])){

	$login_input=mysqli_real_escape_string($con,$_REQUEST['email']);
	$password=mysqli_real_escape_string($con,$_REQUEST['password']);
	$qr=mysqli_query($con,"SELECT u.*, b.name AS branch_name, b.code AS branch_code, b.prefix AS branch_prefix FROM users u LEFT JOIN branches b ON u.branch_id = b.id WHERE u.email='".$login_input."' AND u.password='".md5($password)."' LIMIT 1");
	if(mysqli_num_rows($qr)>0){
		$data=mysqli_fetch_assoc($qr);
		$_SESSION['user_data']=$data;
		$_SESSION['branch'] = array(
			'id' => (int)($data['branch_id'] ?? 1),
			'name' => $data['branch_name'] ?? 'Main Office',
			'code' => $data['branch_code'] ?? 'KMA',
			'prefix' => $data['branch_prefix'] ?? 'KMA'
		);
		if($data['usertype']==1){
			header("Location:backend/admin/index.php");	
		}
		else{
			header("Location:backend/records/index.php");
		}

	}
	else{
		header("Location:index.php?error=Invalid Login Details");		
	}
}
else{
	header("Location:index.php?error=Please Enter Email and Password");
}