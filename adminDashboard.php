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
	
	if($CREATIVE_SYSTEM_DEF['userType'] == 3)
	{
		$CREATIVE_USER_DEF = CDBUser::getUserDetails($CREATIVE_SYSTEM_DEF['userId']);
	}
	else if($CREATIVE_SYSTEM_DEF['userType'] == 1)
	{
		header('location: userDashboard.php');
		exit;
	}
	else if($CREATIVE_SYSTEM_DEF['userType'] == 2)
	{
		header('location: expertDashboard.php');
		exit;
	}
	else
	{
		header('location: index.php');
		exit;
	}
	
	include_once 'formValues' . $CREATIVE_SYSTEM_DEF['lang'] . '.php';
	include_once 'CLocalization.php';
	
	$lang = new CLocalization($CREATIVE_SYSTEM_DEF['lang'], 'adminDashboard.php');
	
	$CSS_FILES	= array('style.css' => 'all');
	$JS_STRING	= '';
	
	$username	= CDBUser::getSignInAs();
	$template->getTemplate('layout/userHeader.php');
	
?>
	<div id="mainContainer">
		<div id="content">
			<div id="headerForDashboard">
				<div class="headerLogo">
					<div style="float: right; padding-left: 10px; font-size: 25px; position: relative; margin-top: 0%; font-style: italic;">
						<a href="index.php" style="text-decoration: none;color: black">CREATIVE</a>
					</div>
                    <?php echo CUserMenu::loginAsPanel($lang, $username); ?>
				</div>
			</div>
			<div class="formFullContent">
				<div class="formHolder">
					<div class="formLeftContent">
						<?php echo CUserMenu::adminQuickLinkInLeftSide($lang); ?>
						<div class="clear5"></div>
					</div>
					<div class="formRightContent">
						<h2><span>&nbsp;</span></h2>
						<div class="formRightContentHolder">
							Hurrah! I am logged in as ADMIN<br/>
							Hurrah! I am logged in as ADMIN<br/>
							Hurrah! I am logged in as ADMIN<br/>
							Hurrah! I am logged in as ADMIN<br/>
							Hurrah! I am logged in as ADMIN<br/>
							Hurrah! I am logged in as ADMIN<br/>
							Hurrah! I am logged in as ADMIN<br/>
							Hurrah! I am logged in as ADMIN<br/>
							Hurrah! I am logged in as ADMIN<br/>
							Hurrah! I am logged in as ADMIN<br/>
							Hurrah! I am logged in as ADMIN<br/>
							Hurrah! I am logged in as ADMIN<br/>
							Hurrah! I am logged in as ADMIN<br/>
							Hurrah! I am logged in as ADMIN<br/>
							Hurrah! I am logged in as ADMIN<br/>
							Hurrah! I am logged in as ADMIN<br/>
						</div>
					</div>
				</div>
			</div>
			
<?php
	$template->getTemplate('layout/userFooter.php');
?>