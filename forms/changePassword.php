<?php

	include_once 'form/Form.php';
	
	$formContents = new Form("set_password");
	$formContents->configure(array("action" => "changePassword.php", "method" => "post"));

	$formContents->addElement(
		new Element_HTMLExternal(
			'<div id="capsLockDiv"></div>
			<fieldset style="margin-left:22px;width:92%"><legend>Reset Your Password</legend><div class="form_row">
		')
	);
	
	$formContents->addElement(
		new Element_Password(
			'Old Password:', 'oldPassword', 'left',
			array('id' => 'oldPassword', 'mandatory' => 'yes', 'maxlength' => '32'),
			"<error for='oldPassword'>Old Password is Mandatory</error>"
		)
	);
	
	$formContents->addElement(
		new Element_Password(
			'New Password:', 'newPassword', 'left',
			array('id' => 'newPassword', 'mandatory' => 'yes', 'maxlength' => '32', 'validate' => 'checkAlphaNumeric', 'params' => '6,32'),
			"<error for='newPassword'>" . $lang->get('password error') . "</error>"
		)
	);
	
	$formContents->addElement(
		new Element_Password(
			'Retype New Password:', 'reNewPassword', 'left',
			array(
				'id' => 'reNewPassword', 'mandatory' => 'yes', 'maxlength' => '32',
				'validate' => 'checkAlphaNumeric', 'params' => '6,32'
		),
			"<error for='reNewPassword'>" . $lang->get('password error') . "</error>"
		)
	);
	
	$formContents->addElement(new Element_HTMLExternal("</div></fieldset>"));
	
	$formContents->addElement(
		new Element_HTMLExternal(
			'<div class="form_row_full" style="margin-top:12px;text-align:center;">
				<button class="registerBlack" type="submit" id="submit" name="submit" style="width:150px;">'
				. 'Reset New Password' . '</button>
			</div><br/><br/>'
		)
	);
	
	$form = '<div class="loginFormContainer">';
	$form .= $formContents->render(true);
	$form .= '</div>';
	
	if(isset($_POST['submit']))
	{
		// Form has been submitted, validate the form
		$processor = new CFormValidator($form);

		if($processor->validate())
		{
			list($ret, $message) = CDBUser::setPassword($_POST['oldPassword'], $_POST['newPassword'], $_POST['reNewPassword']);
			
			if($ret)
			{
				$processor->error_no	= 1;
				$processor->error_msg	= $message;
			}
			else
			{
				$processor->error_no	= 0;
				$processor->error_msg	= $message;
				$processor->validate(false);
			}
		}
	}
	else
	{
		//Form initially displayed, no need to validate it
		$processor = new CFormValidator($form, false);
	}

?>