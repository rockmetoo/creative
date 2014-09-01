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
	
	if($CREATIVE_SYSTEM_DEF['userType'] == 1)
	{
		// XXX: IMPORTANT - get user profile information
		$CREATIVE_USER_DEF = CDBUser::getUserDetails($CREATIVE_SYSTEM_DEF['userId']);
		
		// XXX: IMPORTANT - get some question activities
		$questionStatus = CDBUser::getUserQuestionStatus();
	}
	else if($CREATIVE_SYSTEM_DEF['userType'] == 2)
	{
		header('location: expertDashboard.php');
		exit;
	}
	else if($CREATIVE_SYSTEM_DEF['userType'] == 3)
	{
		header('location: adminDashboard.php');
		exit;
	}
	else
	{
		header('location: index.php');
		exit;
	}
	
	include_once 'formValues' . $CREATIVE_SYSTEM_DEF['lang'] . '.php';
	include_once 'CLocalization.php';
	
	$lang = new CLocalization($CREATIVE_SYSTEM_DEF['lang'], 'userDashboard.php');
	
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
			<div style="padding-left:35%">
				<form name="searchQuestionForm" method="post" action="searchQuestion.php" target="_self">
					<input type="text" name="searchQuestion" class="searchQuestionTextBox" />&nbsp;&nbsp;
					<button name="submit" id="submit" type="submit" class="registerBlack" style="margin-top:3px">&nbsp;Search Question&nbsp;</button>
				</form>
			</div>
			<div class="formFullContent">
				<div class="formHolder">
					<div class="formLeftContent">
						<?php echo CUserMenu::userQuickLinkInLeftSide($lang); ?>
						<div class="clear5"></div>
					</div>
					<div class="formRightContent">
						<h2><span>&nbsp;</span></h2>
						<div class="formRightContentHolder">
							<?php
								if(!$questionStatus)
								{
									echo
										'<div class="success contentNotice transparent" style="width:90%;margin-left:4%;margin-top:10px;margin-bottom:10px;">
											You don\'t have enough activities to show. Please create some questions or answer some.
										</div>';
								}
								else
								{
									echo $questionStatus;
								}
							?>
						</div>
					</div>
				</div>
			</div>
			
<?php
	$template->getTemplate('layout/userFooter.php');
?>
			