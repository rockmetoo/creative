<?php

	include_once 'bootstrap.php';
	include_once 'CDBSession.php';
	include_once 'CDBUser.php';
	include_once 'CUserMenu.php';
	include_once 'CTemplate.php';
	
	$template = new CTemplate();
	
	global $CREATIVE_SYSTEM_DEF;
	
	// XXX: IMPORTANT - Only allow user who are logged in to view this page
	CDBSession::validateUser();
	
	// XXX: IMPORTANT - get user profile information
	$CREATIVE_USER_DEF = CDBUser::getUserDetails($CREATIVE_SYSTEM_DEF['userId']);
	
	// XXX: IMPORTANT - check this page only access by 'user' type
	CDBUser::onlyAccessByUser();
	
	include_once 'CFormValidator.php';
	include_once 'CHelperFunctions.php';
	include_once 'formValues' . $CREATIVE_SYSTEM_DEF['lang'] . '.php';
	include_once 'CLocalization.php';
	
	$lang = new CLocalization($CREATIVE_SYSTEM_DEF['lang'], 'createQuestionDetails.php');
	
	$title = 'Add question details';
	
	// Include the form
	include('forms/createQuestionDetails.php');
	
	$CSS_FILES	= array('style.css' => 'all');
	$JS_STRING	= '';
	
	$username	= CDBUser::getSignInAs();
	$template->getTemplate('layout/userHeader.php');
?>