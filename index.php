<?php 
	
	include_once 'bootstrap.php';
	include_once 'CDBSession.php';
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
				<a href="signup.php" style="font-size:12px;color:#FF4000;font-weight:bold;padding-left:10px">Sign Up</a>
			</form>
		</ul>
		<div class="clear10"></div>';
	}
	
	$CSS_FILES = array('style.css' => 'all');
	$JS_STRING = "";
	
	if($CREATIVE_SYSTEM_DEF['userId'])
	{
		$template->getTemplate('layout/userHeader.php');
		$template->getTemplate('layout/indexHeader.php');
	}
	else
	{
		$template->getTemplate('layout/basicHeader.php');
	}
	
	if(!$CREATIVE_SYSTEM_DEF['userId'])
	{
?>
	<div id="main_container">
		<div id="content">
			<?php echo $loginForm; ?>
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
		</div>
		<div class="indexHeader">
			<div class="indexBlock">
				<img src="../images/sperm.gif" class="spermImage"></img>
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