<?php

	include_once 'form/Form.php';
	
	global $CREATIVE_USER_DEF;
	global $CREATIVE_SYSTEM_DEF;

	$form_contents = new Form("profileEdit");
	$form_contents->configure(array("action" => "profile.php", "method" => "post"));
	
	$form_contents->addElement(
		new Element_HTMLExternal(
			'
			<fieldset style="margin-left:22px;width:92%"><legend>Personal Credentials</legend>
			<div class="form_row">
			'
		)
	);
	
	$form_contents->addElement(
		new Element_Textbox(
			$lang->get('firstName'), 'firstName', 'left'
			, array(
				'id' => 'firstName', 'value' => '' . CHelperFunctions::xmlEscape($CREATIVE_USER_DEF['firstName']) . '',
				'mandatory' => 'yes', 'maxlength' => '255'
			)
			, "<error for='firstName'>" . $lang->get('user first name') . "</error>"
		)
	);

	$form_contents->addElement(
		new Element_Textbox(
			$lang->get('lastName'), 'lastName', 'right'
			, array(
				'id' => 'lastName', 'value' => '' . CHelperFunctions::xmlEscape($CREATIVE_USER_DEF['lastName']) . '',
				'mandatory' => 'yes', 'maxlength' => '255'
			)
			, "<error for='lastName'>" . $lang->get('user last name') . "</error>"
		)
	);
	
	$form_contents->addElement(
		new Element_Textbox(
			'Post Code:', 'postCode', 'left'
			, array(
				'id' => 'postCode', 'value' => '' . CHelperFunctions::xmlEscape($CREATIVE_USER_DEF['postCode']) . '',
				'maxlength' => '16'
			)
		)
	);
	
	$form_contents->addElement(
		new Element_Textbox(
			'City:', 'cityOrDistrict', 'right'
			, array(
				'id' => 'cityOrDistrict', 'value' => '' . CHelperFunctions::xmlEscape($CREATIVE_USER_DEF['cityOrDistrict']) . '',
				'maxlength' => '64'
			)
		)
	);
	
	$form_contents->addElement(
		new Element_Textbox(
			'State:', 'stateOrDivision', 'left'
			, array(
				'id' => 'stateOrDivision', 'value' => '' . CHelperFunctions::xmlEscape($CREATIVE_USER_DEF['stateOrDivision']) . '',
				'maxlength' => '64'
			)
		)
	);
	
	$form_contents->addElement(
		new Element_Textbox(
			'Address:', 'address', 'right'
			, array(
				'id' => 'address', 'value' => '' . CHelperFunctions::xmlEscape($CREATIVE_USER_DEF['address']) . '',
				'mandatory' => 'yes', 'maxlength' => '128'
			)
			, "<error for='address'>Please write your address</error>"
		)
	);
	
	$form_contents->addElement(new Element_HTMLExternal("</div></fieldset>"));
	
	$form_contents->addElement(
		new Element_HTMLExternal(
			'<div class="form_row_full" style="margin-top:12px;text-align:center;">
				<button class="registerBlack" type="submit" id="submit" name="submit" style="width:150px;">Update</button>
			</div><br/><br/>'
		)
	);
?>

<?php
	$form = '<div class="loginFormContainer">';
	$form .= $form_contents->render(true);
	$form .= '</div>';
	if(isset($_POST['submit']))
	{
		// Form has been submitted, validate the form
		$processor = new CFormValidator($form);
		
		if($processor->validate())
		{
			// TODO: working in progress
			$_POST['firstName']			= trim($_POST['firstName']);
			$_POST['lastName']			= trim($_POST['lastName']);
			$_POST['postCode']			= trim($_POST['postCode']);
			$_POST['cityOrDistrict']	= trim($_POST['cityOrDistrict']);
			$_POST['stateOrDivision']	= trim($_POST['stateOrDivision']);
			$_POST['address']			= trim($_POST['address']);
			
			CDBUser::setUserBasicProfile($CREATIVE_SYSTEM_DEF['userId'], $_POST, true);
			$processor->error_no = 1;
			$processor->error_msg = $lang->get('profile save success');
		}
	}
	else
	{
		//Form initially displayed, no need to validate it
		$processor = new CFormValidator($form, false);
	}
?>