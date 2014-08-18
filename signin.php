<?php

    include_once 'bootstrap.php';
	include_once 'CDBSession.php';
	include_once 'CSettings.php';
	include_once 'CTemplate.php';
	
	global $CREATIVE_SYSTEM_DEF;
	
	CDBSession::ifLoggedInThenGoToDashBoarad();

	include_once 'CDBSignIn.php';
	include_once 'CFormValidator.php';
	include_once 'CHelperFunctions.php';
	include_once 'formValues' . $CREATIVE_SYSTEM_DEF['lang'] . '.php';
	include_once 'CLocalization.php';
	
	$lang = new CLocalization($CREATIVE_SYSTEM_DEF['lang'], 'signin.php');

	// get the path for redirecting after login is successful
	if(!isset($_GET['path']) || !$_GET['path'])
	{
		$_GET['path'] = $_SERVER['SCRIPT_NAME'] . "?" . $_SERVER['QUERY_STRING'];
	}
	
	//Include the form
	include('forms/signin.php');
	
	$JS_FILES = array('capslock.jquery.js');
	
	$JS_STRING = '
		$(document).ready(function()
		{
			var options = {
				capsLockDiv_on: function(){
					var alertMessage = "<span id=\'capslock\'>' . $lang->get('capslock on') . '</span>";
					$("#capsLockDiv").html(alertMessage);
					$("#capsLockDiv").fadeIn("slow");
				},
				capsLockDiv_off: function(){
					$("#capsLockDiv").text("");
					$("#capsLockDiv").fadeOut("slow");
				},
				capsLockDiv_undetermined: function(){
					$("#capsLockDiv").text("");
					$("#capsLockDiv").fadeOut("slow");
				},
				debug: true
			};
			$("#password").capslock(options);
		});
	';
	$CSS_FILES = array('style.css' => 'all');

	// XXX: IMPORTANT - Add page title, meta description and meta tags
	$title				= $lang->get('sign in to creative');
	$metaDescription	= $lang->get('sign in to creative');
	$metaKeywords		= $lang->get('sign in to creative');
	
	$template = new CTemplate();
	
	$template->getTemplate('layout/basicHeader.php');	
?>
	<div id="mainContainer">
		<div id="content">
			<div id="header">
				<div class="headerLogo">
					<div style="float: right; padding-left: 10px; font-size: 25px; position: relative; margin-top: 0%; font-style: italic;">
						<a href="index.php" style="text-decoration: none;color: black">CREATIVE</a>
					</div>
					<ul class="headerList1">
						<li class="last">
							<a href="signup.php" style="font-size:12px;color:#FF4000;font-weight:bold;padding-left:10px">Sign Up</a>
						</li>
			    	</ul>
			    	<div class="clear"></div>
				</div>
			</div>
			<div class="clear10"></div>
			<div class="formFullContent">
				<div class="signinFormHolder">
				<?php
					if($processor->error_no === 0)
					{
						echo
						'
							<div class="error contentNotice transparent">
								<img src="../images/unsuccessful.png"></img>'. $processor->error_msg .'
							</div>
						';
					}
					
					$processor->display();
				?>
				</div>
			</div>
<?php
	$template->getTemplate('layout/basicFooter.php');
?>