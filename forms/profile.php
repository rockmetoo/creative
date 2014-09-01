<?php

	include_once 'form/Form.php';
	
	global $CREATIVE_USER_DEF;
	global $CREATIVE_SYSTEM_DEF;

	$formContents = new Form("profileEdit");
	$formContents->configure(array("action" => "profile.php", "method" => "post"));
	
	$formContents->addElement(
		new Element_HTMLExternal(
			'
			<fieldset style="margin-left:22px;width:92%"><legend>Personal Credentials</legend>
			<div class="form_row">
			'
		)
	);
	
	$formContents->addElement(
		new Element_Textbox(
			$lang->get('firstName'), 'firstName', 'left'
			, array(
				'id' => 'firstName', 'value' => CHelperFunctions::xmlEscape($CREATIVE_USER_DEF['firstName']),
				'mandatory' => 'yes', 'maxlength' => '255'
			)
			, "<error for='firstName'>" . $lang->get('user first name') . "</error>"
		)
	);

	$formContents->addElement(
		new Element_Textbox(
			$lang->get('lastName'), 'lastName', 'right'
			, array(
				'id' => 'lastName', 'value' => CHelperFunctions::xmlEscape($CREATIVE_USER_DEF['lastName']),
				'mandatory' => 'yes', 'maxlength' => '255'
			)
			, "<error for='lastName'>" . $lang->get('user last name') . "</error>"
		)
	);
	
	$formContents->addElement(
		new Element_Textbox(
			'Post Code:', 'postCode', 'left'
			, array(
				'id' => 'postCode', 'value' => CHelperFunctions::xmlEscape($CREATIVE_USER_DEF['postCode']),
				'maxlength' => '16'
			)
		)
	);
	
	$formContents->addElement(
		new Element_Textbox(
			'City:', 'cityOrDistrict', 'right'
			, array(
				'id' => 'cityOrDistrict', 'value' => CHelperFunctions::xmlEscape($CREATIVE_USER_DEF['cityOrDistrict']),
				'maxlength' => '64'
			)
		)
	);
	
	$formContents->addElement(
		new Element_Textbox(
			'State:', 'stateOrDivision', 'left'
			, array(
				'id' => 'stateOrDivision', 'value' => CHelperFunctions::xmlEscape($CREATIVE_USER_DEF['stateOrDivision']),
				'maxlength' => '64'
			)
		)
	);
	
	$formContents->addElement(
		new Element_Textbox(
			'Address:', 'address', 'right',
		    array(
				'id' => 'address', 'value' => CHelperFunctions::xmlEscape($CREATIVE_USER_DEF['address']),
				'mandatory' => 'yes', 'maxlength' => '128'
			),
		    "<error for='address'>Please write your address</error>"
		)
	);
	
	$formContents->addElement(
	    new Element_File(
	        'Profile Picture:', 'profilePicture', 'left',
	        array('validate' => 'checkProfilePicture'),
	        "<error for='address'>Please upload a valid picture file of Max. 8 MB</error>"
	    )
	);
	
	$formContents->addElement(new Element_HTMLExternal("</div></fieldset>"));
	
	$formContents->addElement(
		new Element_HTMLExternal(
			'<div class="form_row_full" style="margin-top:12px;text-align:center;">
				<button class="registerBlack" type="submit" id="submit" name="submit" style="width:150px;">Update</button>
			</div><br/><br/>'
		)
	);
?>

<?php
	$form = '<div class="loginFormContainer">';
	$form .= $formContents->render(true);
	$form .= '</div>';
	if(isset($_POST['submit']))
	{
		// Form has been submitted, validate the form
		$processor = new CFormValidator($form);
		
		if($processor->validate())
		{
		    include_once 'CImageResize.php';
		    
			// TODO: working in progress
			$_POST['firstName']			= trim($_POST['firstName']);
			$_POST['lastName']			= trim($_POST['lastName']);
			$_POST['postCode']			= trim($_POST['postCode']);
			$_POST['cityOrDistrict']	= trim($_POST['cityOrDistrict']);
			$_POST['stateOrDivision']	= trim($_POST['stateOrDivision']);
			$_POST['address']			= trim($_POST['address']);
			
			$profilePictureFileName     = $_FILES['profilePicture']['name'];
			$ext                        = pathinfo($profilePictureFileName, PATHINFO_EXTENSION);
			
			move_uploaded_file($_FILES["profilePicture"]["tmp_name"], CSettings::$BASE_DIRECTORY. "/cdn/" . $CREATIVE_SYSTEM_DEF['userId'] . '_original_profile_picture.' . $ext);
			
			$resizeObj = new CImageResize(CSettings::$BASE_DIRECTORY. "/cdn/" . $CREATIVE_SYSTEM_DEF['userId'] . '_original_profile_picture.' . $ext);
			$resizeObj -> resizeImage(24, 24);
			$resizeObj -> saveImage(CSettings::$BASE_DIRECTORY . '/cdn/' . $CREATIVE_SYSTEM_DEF['userId'] . '_profile_picture.' . $ext, 100);
			
			$_POST['profilePicture']    = $CREATIVE_SYSTEM_DEF['userId'] . '_profile_picture.' . $ext;
			
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