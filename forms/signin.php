<?php

	include_once 'form/Form.php';
	
	$form_contents = new Form("signin");
	
	$form_contents->configure(array("action" => "signin.php", "method" => "post"));

	$form_contents->addElement(
		new Element_HTMLExternal(
			'
			<fieldset style="margin-top:0px;width:350px"><legend>Login Credentials</legend>
			<div class="form_row">
			'
		)
	);
	
	$form_contents->addElement(
		new Element_Hidden(
			'signinto', (isset($_GET['signinto'])) ? htmlspecialchars($_GET['signinto']) : "",
			array('id' => 'signinto')
		)
	);
	
	$form_contents->addElement(
		new Element_Textbox(
			$lang->get('username'), 'username', 'full',
			array('id' => 'username', 'mandatory' => 'yes', 'maxlength' => '255'),
			"<error for='username'>" . $lang->get('username mandatory error') . "</error>"
		)
	);
	
	$form_contents->addElement(
		new Element_Password(
			$lang->get('password'), 'password', 'full',
			array(
				'id' => 'password', 'mandatory' => 'yes', 'maxlength' => '32',
				'validate' => 'checkAlphaNumeric', 'params' => '6,32'
			),
			"<error for='password'>" . $lang->get('password error') . "</error>
			<div id='capsLockDiv'></div>"
		)
	);
	
	$form_contents->addElement(
		new Element_Checkbox(
			'', 'keepSignIn', 'left', array('1' => $lang->get('keep me signed in info')),
			array('id' => 'keepSignIn', 'value' => '1'), ''
		)
	);
	
	$form_contents->addElement(
		new Element_HTMLExternal(
			'<br/><br/><div class="formRowFull">
				<button class="registerBlack" type="submit" id="submit" name="submit">'
				. $lang->get('sign in to creative') . '</button>
			</div>'
		)
	);
	
	$form_contents->addElement(new Element_HTMLExternal("</div></fieldset>"));
	$form = '<div class="loginFormContainer">';
	$form .= $form_contents->render(true);
	$form .= '</div>';
	
	if(isset($_POST['submit']))
	{
		// Form has been submitted, validate the form
		$processor = new CFormValidator($form);
		
		if($processor->validate())
		{
			$foo = CDBSignIn::login($_POST['username'], $_POST['password']);
			
			if(isset($foo['userId']))
			{
				CDBSession::sessionLinkUserId($foo['userId'], $foo['userStatus'], $_POST['keepSignIn']);
				
				if(!empty($_POST['signinto']) && ($_POST['signinto'] != '/' || $_POST['signinto'] != '/index.php'))
				{
					header('Location: '. CSettings::$HTTP_PROTOCOL . CSettings::$HOST . $_POST['signinto']);
					exit;
				}
				else
				{
					if($foo['userType'] == 1)
					{
						header('Location: ' . CSettings::$HTTP_PROTOCOL . CSettings::$HOST . '/' . 'userDashboard.php');
						exit;
					}
					else if($foo['userType'] == 2)
					{
						header('Location: ' . CSettings::$HTTP_PROTOCOL . CSettings::$HOST . '/' . 'expertDashboard.php');
						exit;
					}
					else if($foo['userType'] == 3)
					{
						header('Location: ' . CSettings::$HTTP_PROTOCOL . CSettings::$HOST . '/' . 'adminDashboard.php');
						exit;
					}
				}
			}
			else
			{
				if(isset($foo['failedLoginCount']) && $foo['failedLoginCount'] >= 5)
				{
					$processor->error_no	= 0;
					$processor->error_msg	= $lang->get('username blocked');
					
					$processor->validate(false);
				}
				else
				{
					// Username or password are incorrect redisplay form
					$processor->error_no	= 0;
					$processor->error_msg	= $lang->get('username or password error');
					
					$processor->validate(false);
				}
			}
		}
	}
	else
	{
		// Form initially displayed, no need to validate it
		$processor = new CFormValidator($form, false);
	}
?>
