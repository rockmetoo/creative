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
	
	include_once 'CFormValidator.php';
	include_once 'CHelperFunctions.php';
	include_once 'formValues' . $CREATIVE_SYSTEM_DEF['lang'] . '.php';
	include_once 'CLocalization.php';
	
	$lang = new CLocalization($CREATIVE_SYSTEM_DEF['lang'], 'profile.php');
	
	$title = 'Edit Profile';

	// Include the form
	include('forms/profile.php');
	
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
						<?php
						    if($CREATIVE_SYSTEM_DEF['userType'] == 1)
						    {
						        echo CUserMenu::userQuickLinkInLeftSide($lang, 1);
						    }
						    else if($CREATIVE_SYSTEM_DEF['userType'] == 2)
						    {
						        echo CUserMenu::expertQuickLinkInLeftSide($lang, 1);
						    }
						    else if($CREATIVE_SYSTEM_DEF['userType'] == 3)
						    {
						        echo CUserMenu::adminQuickLinkInLeftSide($lang, 1);
						    }
						?>
						<div class="clear5"></div>
					</div>
					<div class="formRightContent">
						<h2><span>&nbsp;</span></h2>
						<div class="formRightContentHolder">
							<?php
								// After Form Submission if any error/success occured
								if($processor->error_no === 1)
								{
									echo
										'<div class="success contentNotice transparent">
											<img src="../images/successful.png"></img>'. $processor->error_msg .'
										</div>
										';
								}
								else if($processor->error_no === 0)
								{
									echo
										'<div class="error contentNotice transparent">
											<img src="../images/unsuccessful.png"></img>'. $processor->error_msg .'
										</div>
										';
								}
							
								// Output form
								$processor->display();
							?>
						</div>
					</div>
				</div>
			</div>
<?php
	$template->getTemplate('layout/userFooter.php');
?>