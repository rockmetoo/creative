<?php 
	
	include_once 'bootstrap.php';
	include_once 'CDBSession.php';
	include_once 'CTemplate.php';
	
	global $CREATIVE_SYSTEM_DEF;
	
	// Redirect if already logged in
	if(
		(isset($CREATIVE_SYSTEM_DEF["userId"]) && $CREATIVE_SYSTEM_DEF["userType"] == 1) &&
		$CREATIVE_SYSTEM_DEF["userId"] > 0
	){
		header('location: userDashboard.php');
		exit;
	
	}
	else if(
			(isset($CREATIVE_SYSTEM_DEF["userId"]) && $CREATIVE_SYSTEM_DEF["userType"] == 2)
			&& $CREATIVE_SYSTEM_DEF["userId"] > 0
	){
		header('location: expertDashboard.php');
		exit;
	
	}
	else if(
			(isset($CREATIVE_SYSTEM_DEF["userId"]) && $CREATIVE_SYSTEM_DEF["userType"] == 3)
			&& $CREATIVE_SYSTEM_DEF["userId"] > 0
	){
		header('location: adminDashboard.php');
		exit;
	
	}
	
	include_once 'CDBSignIn.php';
	include_once 'CFormValidator.php';
	include_once 'CHelperFunctions.php';
	include_once 'formValues' . $CREATIVE_SYSTEM_DEF['lang'] . '.php';
	include_once 'CLocalization.php';
	
	$lang = new CLocalization($CREATIVE_SYSTEM_DEF['lang'], 'signup.php');
	
	// Include the form
	include('forms/signup.php');
?>