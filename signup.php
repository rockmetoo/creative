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
	
	$lang = new CLocalization($CREATIVE_SYSTEM_DEF['lang'], 'signup.php');
	
	// Include the form
	include('forms/signup.php');
	
	$JS_STRING	= '';
	$CSS_FILES	= array('style.css' => 'all');
	
	// set Add page title, meta description and meta tags
	$title = $lang->get('pageTitle');
	
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
							<a href="signin.php" style="font-size:12px;color:#FF4000;font-weight:bold;padding-left:10px">Sign In</a>
						</li>
			    	</ul>
			    	<div class="clear"></div>
				</div>
			</div>
			<div class="clear10"></div>
			<div class="formFullContent">
				<div class="signupFormHolder">
				<?php
					// Output form
					$processor->display();
				?>
				</div>
			</div>
<?php
	$template->getTemplate('layout/basicFooter.php');
?>