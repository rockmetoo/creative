<?php

    include_once 'bootstrap.php';
	include_once 'CDBSession.php';
	include_once 'CSettings.php';
	include_once 'CTemplate.php';
	
	global $CREATIVE_SYSTEM_DEF;
	
	CDBSession::ifLoggedInThenGoToDashBoarad();
	
	include_once 'CLocalization.php';
	$lang = new CLocalization($CREATIVE_SYSTEM_DEF['lang'], 'registrationThanks.php');
	
	$title = 'Thank you for your registration';
	
	$JS_STRING = '';
	$CSS_FILES = array('style.css' => 'all');
	
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
				<div class="formHolder">
					<div class="loginFormContainer">
						<?php
							$congrats = trim($_REQUEST['congrats']);
							
							if(isset($congrats) && is_numeric($congrats))
							{
						?>
								<h2 class="h2Title">
									<?php echo $lang->get('congretulation title'); ?>
								</h2>
								<div class="contentNotice">
									<?php echo $lang->get('complete reg process'); ?>
								</div>
						<?php
							}
							else
							{
								// Invalid request
						?>
								<h2 class="h2Title">
									<?php echo $lang->get('user registration invalid'); ?>
								</h2>
								<div class="contentNotice">
									<?php
										echo $lang->get('try user reg in creative', array(CSettings::$HOST));
									?>
								</div>
						<?php
							}
						?>
			    	</div>
				</div>
			</div>
<?php
	$template->getTemplate('layout/basicFooter.php');
?>