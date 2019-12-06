<?php
	require_once('session.php');
	require_once('DB.php');
	$user_logout = new DBHelper();
	
	if($user_logout->is_loggedin()!="")
	{
		$user_logout->redirect('index3.php');
	}
	if(isset($_GET['logout']) && $_GET['logout']=="true")
	{
		$user_logout->doLogout();
		$user_logout->redirect('index.php');
	}
