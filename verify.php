<?php

    include_once 'bootstrap.php';
	include_once 'CDBSession.php';
	include_once 'CSettings.php';
	include_once 'CTemplate.php';
	
	$template = new CTemplate();
	
	global $CREATIVE_SYSTEM_DEF;
	
	CDBSession::ifLoggedInThenGoToDashBoarad();
	
	include_once 'CDBSignIn.php';
	include_once 'CDBUser.php';
	include_once 'CHelperFunctions.php';
	include_once 'CLocalization.php';

	$lang	= new CLocalization($CREATIVE_SYSTEM_DEF['lang'], 'verify.php');
	$title	= 'verify your registration';
	
	$JS_STRING = '';
	$CSS_FILES = array('style.css' => 'all');

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
				<div class="formHolder">
					<div class="loginFormContainer">
					<?php
						$md5Hash = trim($_REQUEST['hash']);
						
						if(CHelperFunctions::isValidMd5($md5Hash))
						{
							$foo = CDBSignIn::checkAnyTempReg($md5Hash);
							
							if($foo)
							{
								if($foo['userType'] == 1 && CDBSignIn::getCheckUsername($foo['username']))
								{
					?>
									<h2 class="h2Title">
										<?php echo $lang->get('user already exist'); ?>
									</h2>
									<div class="contentNotice">
										<?php
											echo $lang->get('try another user name');
										?>
									</div>
					<?php
								}
								else
								{
									$userId = CDBSignIn::createUserLogin(
										$foo['username'], $foo['password'], $foo['userType'],
										0, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']
									);
									
									CDBUser::setUserProfile($userId);
									
									CDBSignIn::completedTempUserLogin($md5Hash);
									
									$userValues = array('username' => $foo['username']);
									
									
									$plain = CMail::prepareBody('user_complete_reg_' . $CREATIVE_SYSTEM_DEF['lang'], $userValues, 0);
									
									CMail::send(
										array(CSettings::$SYSTEM_MAIL_VALUES['noreply'], 'No Reply', CSettings::$SYSTEM_MAIL_PASSWORD['noreply']),
										array($foo['username']),
										$lang->get('subject creative reg completed'), $plain, ''
									);
									
									$systemValues = array(
										'username'			=> $foo['username'],
										'HTTP_REFERER'		=> isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
										'REMOTE_ADDR'		=> $_SERVER['REMOTE_ADDR'],
										'HTTP_USER_AGENT'	=> $_SERVER['HTTP_USER_AGENT']
									);
									
									// system copy of registration mail
									$plain = CMail::prepareBody('system_user_complete_reg', $systemValues, 0);
									
									CMail::send(
										array(CSettings::$SYSTEM_MAIL_VALUES['noreply'], 'No Reply', CSettings::$SYSTEM_MAIL_PASSWORD['noreply']),
										CSettings::$SYSTEM_MAIL_VALUES['tech'],
										$lang->get('subject creative reg completed') . '-' . $foo['username'], $plain
									);
									
									CDBSignIn::removeTempUserLogin($md5Hash);
					?>
									<h2 class="h2Title">
										<?php echo $lang->get('congretulation title'); ?>
									</h2>
									<div class="contentNotice">
										<?php echo $lang->get('click to sign in', array(CSettings::$HOST)); ?>
									</div>
					<?php
								}
							}
							else
							{
								// Invalid request
					?>
								<h2 class="h2Title">
									<?php echo $lang->get('registration invalid'); ?>
								</h2>
								<div class="contentNotice">
									<?php echo $lang->get('try reg in creative', array(CSettings::$HOST)); ?>
								</div>
					<?php
							}
						}
						else
						{
							// Invalid request
					?>
							<h2 class="h2Title">
								<?php echo $lang->get('registration invalid'); ?>
							</h2>
							<div class="contentNotice">
								<?php echo $lang->get('try reg in creative', array(CSettings::$HOST)); ?>
							</div>
					<?php
						}
					?>
		    		</div>
				</div>
			</div>
<?php
	$template->getTemplate('layout/basicFooter.php');
