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
	
	$lang = new CLocalization($CREATIVE_SYSTEM_DEF['lang'], 'changePassword.php');
	
	$title = 'Update password';

	// Include the form
	include('forms/changePassword.php');
	
	$JS_FILES	= array('capslock.jquery.js');
	$CSS_FILES	= array('style.css' => 'all');
	$JS_STRING	=
		'
		$(document).ready(function()
		{
			var options = {
				caps_lock_on: function()
				{
					var alertMessage = "<span id=\'capslock\'>CapsLock key pressed</span>";
					$("#capsLockDiv").text("CapsLock Key Pressed");
					$("#capsLockDiv").fadeIn("slow");
				},
				caps_lock_off: function()
				{
					$("#capsLockDiv").text("");
					$("#capsLockDiv").fadeOut("slow");
				},
				caps_lock_undetermined: function()
				{
					$("#capsLockDiv").text("");
					$("#capsLockDiv").fadeOut("slow");
				},
				debug: true
			};
			$("#oldPassword").capslock(options);
			$("#newPassword").capslock(options);
			$("#reNewPassword").capslock(options);
		});
		';
	
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
					<ul class="headerList1">
						<li>
							<?php echo $lang->get('sign in as') . '<a href="profile.php" target="_self">' . $username . '</a>'; ?>
						</li>
						<li class="last">
							<a href="signout.php" style="font-size:12px;color:#FF4000;font-weight:bold;padding-left:10px">Sign Out</a>
						</li>
			    	</ul>
			    	<div class="clear"></div>
				</div>
			</div>
			<div class="formFullContent">
				<div class="formHolder">
					<div class="formLeftContent">
						<?php echo CUserMenu::userQuickLinkInLeftSide($lang, 2); ?>
						<div class="clear5"></div>
					</div>
					<div class="formRightContent">
						<h2><span>&nbsp;</span></h2>
						<div class="formRightContentHolder">
							<?php
								//After Form Submission if any error/success occured from Daemon
								if($processor->error_no === 1)
								{
									echo
										'<div class="success contentNotice transparent">
											<img src="../images/successful.png"></img>'. $processor->error_msg .'
										';
									echo '</div>';
								}
								else if($processor->error_no === 0)
								{
									echo
										'<div class="error contentNotice transparent">
											<img src="../images/unsuccessful.png"></img>'. $processor->error_msg .'
										';
									echo '</div>';
								}
								
								//Output form
								$processor->display();
							?>
						</div>
					</div>
				</div>
			</div>
<?php
	$template->getTemplate('layout/userFooter.php');
?>