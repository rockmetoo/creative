<?php 
	
	include_once 'bootstrap.php';
	include_once 'CDBSession.php';
	include_once 'CDBUser.php';
	include_once 'CTemplate.php';
	
	$template = new CTemplate();
	
	global $CREATIVE_SYSTEM_DEF;
	
	include_once 'formValues' . $CREATIVE_SYSTEM_DEF['lang'] . '.php';
	include_once 'CLocalization.php';
	
	$lang = new CLocalization($CREATIVE_SYSTEM_DEF['lang'], 'index.php');
	
	if(!$CREATIVE_SYSTEM_DEF['userId'])
	{
		$loginForm = '
		<ul class="indexLoginHeader">
			<form name="signin" method="post" id="signin" action="signin.php" target="_self">
				<label for="username">
					<span>User</span>&nbsp;<input id="username" type="text" name="username" />
				</label>
				<label for="password">
					<span>Password</span>&nbsp;<input id="password" type="password" name="password" />
				</label>
				<button name="submit" id="submit" type="submit" class="indexSignInButton">Sign In</button>
				<a href="signup.php" style="text-decoration:none;font-size:12px;color:#FF4000;font-weight:bold;padding-left:10px">Sign Up</a>
			</form>
		</ul>
		<div class="clear10"></div>';
	}
	
	$CSS_FILES = array('style.css' => 'all');
	$JS_STRING = '';
	
	if($CREATIVE_SYSTEM_DEF['userId'])
	{
		$template->getTemplate('layout/userHeader.php');
		
		CDBUser::getUserType($CREATIVE_SYSTEM_DEF['userId']);
		
		if($CREATIVE_SYSTEM_DEF['userType'] == 1)
		{
			$username = '<a href="userDashboard.php">' . CDBUser::getSignInAs() . '</a>';
		}
		else if($CREATIVE_SYSTEM_DEF['userType'] == 2)
		{
			$username = '<a href="expertDashboard.php">' . CDBUser::getSignInAs() . '</a>';
		}
		else if($CREATIVE_SYSTEM_DEF['userType'] == 3)
		{
			$username = '<a href="adminDashboard.php">' . CDBUser::getSignInAs() . '</a>';
		}
	}
	else
	{
		$template->getTemplate('layout/basicHeader.php');
	}
?>
	<div id="mainContainer">
		<div id="content">
<?php
	if(!$CREATIVE_SYSTEM_DEF['userId'])
	{
?>
		<div style="float: left; padding-left: 10px; font-size: 25px; position: relative; margin-top: 0%; font-style: italic;">
			<a href="index.php" style="text-decoration: none;color: black">CREATIVE</a>
		</div>
		<?php echo $loginForm; ?>
		<br/>
		<div class="indexSearchQuestion">
			<form name="searchQuestionForm" method="post" action="searchQuestion.php" target="_self">
				<input type="text" name="searchQuestion" class="searchQuestionTextBox" />&nbsp;&nbsp;
				<button name="submit" id="submit" type="submit" class="registerBlack" style="margin-top:3px">&nbsp;Search Question&nbsp;</button>
			</form>
		</div>
		<br/>
		<div class="indexHeader">
			<div class="indexBlock">
				<img src="../images/examCool.png" class="spermImage"></img>
				<br/><br/>Test Yourself Creatively
			</div>
		</div>
<?php
	}
	else
	{
?>
		<div id="header">
			<div class="headerLogo">
				<div style="float: right; padding-left: 10px; font-size: 25px; position: relative; margin-top: 0%; font-style: italic;">
					<a href="index.php" style="text-decoration: none;color: black">CREATIVE</a>
				</div>
				<ul class="headerList1">
					<li>
						<?php echo $lang->get('sign in as') . $username; ?>
					</li>
					<li class="last">
						<a href="signout.php" style="font-size:12px;color:#FF4000;font-weight:bold;padding-left:10px">Sign Out</a>
					</li>
		    	</ul>
		    	<div class="clear"></div>
			</div>
		</div>
		<br/>
		<div class="indexSearchQuestion">
			<form name="searchQuestionForm" method="post" action="searchQuestion.php" target="_self">
				<input type="text" name="searchQuestion" class="searchQuestionTextBox" />&nbsp;&nbsp;
				<button name="submit" id="submit" type="submit" class="registerBlack" style="margin-top:3px">&nbsp;Search Question&nbsp;</button>
			</form>
		</div>
		<br/>
		<div class="indexHeader">
			<div class="indexBlock">
				<img src="../images/examCool.png" class="spermImage"></img>
				<br/><br/>Test Yourself Creatively
			</div>
		</div>
<?php
	}
	
	$totalQuestionInTheDatabase = 2000;
?>
	<div class="indexPageShowWholeQuestion">
		<div class="counter">
			<h1 class="questionHasAtThisMoment"><?php echo $totalQuestionInTheDatabase; ?></h1>
		</div>
	</div>
<?php
	if($CREATIVE_SYSTEM_DEF['userId'])
	{
		$template->getTemplate('layout/userFooter.php');
	}
	else
	{
		$template->getTemplate('layout/basicFooter.php');
	}
?>