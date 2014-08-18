<?php

	session_start();
	
	include_once 'form/Form.php';
	
	$form_contents = new Form("signup");
	$form_contents->configure(array("action" => "signup.php", "method" => "post"));
	
	$form_contents->addElement(
		new Element_HTMLExternal(
			'
			<errormsg for="captchaText" name="captchaText">' . $lang->get('captcha not match') . '</errormsg>
			<errormsg for="captchaText" name="default">' . $lang->get('write captcha') . '</errormsg>
			<fieldset style="margin-top:0px;width:350px"><legend>Registration Information</legend>
			<div class="form_row">
			'
		)
	);
	
	$form_contents->addElement(
		new Element_Textbox(
			 'Email:', 'primaryEmail', 'full'
			, array(
				'id' => 'primaryEmail', 'mandatory' => 'yes', 'maxlength' => '255',
				'validate' => 'checkEmail',
				'params' => '6,255'
			)
			, "<error for='primaryEmail'>" . $lang->get('primary email error') . "</error>"
		)
	);
	
	$form_contents->addElement(
		new Element_Password(
			'Password:', 'password', 'full'
			, array(
				'id' => 'password', 'mandatory' => 'yes', 'maxlength' => '32',
				'validate' => 'checkAlphaNumeric', 'params' => '6,32'
			)
			, "<error for='password'>" . $lang->get('password error') . "</error>"
		)
	);
	
	$form_contents->addElement(
		new Element_Password(
			"Re-enter Password:", "repeatPassword", "full"
			, array(
				"id"=>"repeatPassword", "mandatory"=>"yes", "maxlength"=>"32",
				"mustmatch"=>"password"
			)
			, "<error for='repeatPassword'>" . $lang->get('re-enter password error') . "</error>"
		)
	);
	
	$form_contents->addElement(
		new Element_HTMLExternal(
			'<div class="form_row_left">
        		<label for="captcha" class="form_col_label required"></label>
        		<div class="form_col_group">
					<img src="captcha.php" id="captcha" alt="captcha" class="captcha_img"></img>
					<a href="#"
						onclick="document.getElementById(\'captcha\').src=\'captcha.php?\'+Math.random();document.getElementById(\'captchaText\').focus();"
						class="change_captcha"
	    			>
    				' . $lang->get('captcha not readable') . '
    				</a>
				</div>
        	</div>' .
		'	<div class="formRowFull">
        		<label for="captchaText">
        			<span>
        		' . $lang->get('write captcha') . ':
        			</span>
        		</label>
        		<input type="text" name="captchaText" id="captchaText" mandatory="yes"
        			validate=\'{"captchaText":"checkCaptcha"}\' class="field"
                />
                <error for="captchaText"></error>
        	</div>
			<div class="formRowFull">
				<input id="terms" name="terms" value="1" mandatory="yes" type="checkbox" style="width:auto;margin:0;float:left"/>
				<label for="terms">' . $lang->get('registration agree') . '</label>
				<error for="terms">' . $lang->get('required') . '</error>
			</div>' . 
		'
		    <div class="clear10"></div>
			<div class="form_row_left">
		        <button name="submit" id="submit" type="submit" class="registerBlack">Sign Up</button>
		    </div>'
		)
	);
	
	$form_contents->addElement(new Element_HTMLExternal("</div></fieldset>"));
	$form = '<div class="loginFormContainer">';
	$form .= $form_contents->render(true);
	$form .= '</div>';

	if(isset($_POST['submit']))
	{
		//Form has been submitted, validate the form
		$processor = new CFormValidator($form);
		
		if($processor->validate())
		{
			$_POST['primaryEmail']	= trim($_POST['primaryEmail']);
			$_POST['password']		= trim($_POST['password']);
			
			// Generate random 32 character hash and assign it to a local variable
			$hash		= CHelperFunctions::createPseudoSecretHash($_POST['primaryEmail'], rand(0, 1000));
			$userId		= CDBSignIn::createTempUser($_POST['primaryEmail'], $_POST['password'], 1, $hash);

			$userValues = array(
				'hashlink'	=> CSettings::$HTTP_PROTOCOL . CSettings::$HOST . '/verify.php?hash=' . $hash,
				'username'	=> $_POST['primaryEmail']
			);

			$htmlContent = CMail::prepareBody('user_temp_reg_' . $CREATIVE_SYSTEM_DEF['lang'], $userValues, 1);

			CMail::send(
				array(CSettings::$SYSTEM_MAIL_VALUES['noreply'], 'No Reply', CSettings::$SYSTEM_MAIL_PASSWORD['noreply']),
				array($_POST['primaryEmail']),
				$lang->get('subject creative reg verify email'), '', $htmlContent
			);
			
			$systemValues = array(
				'username'			=> $_POST['primaryEmail'],
				'hash'				=> $hash,
				'HTTP_REFERER'		=> $_SERVER['HTTP_REFERER'],
				'REMOTE_ADDR'		=> $_SERVER['REMOTE_ADDR'],
				'HTTP_USER_AGENT'	=> $_SERVER['HTTP_USER_AGENT']
			);

			// system copy of registration mail
			$plain = CMail::prepareBody('system_user_temp_reg', $systemValues, 0);
			CMail::send(
				array(CSettings::$SYSTEM_MAIL_VALUES['noreply'], 'No Reply', CSettings::$SYSTEM_MAIL_PASSWORD['noreply'])
				, CSettings::$SYSTEM_MAIL_VALUES['tech']
				, $lang->get('subject creative reg verify email') . '-' . $_POST['primaryEmail'], $plain
			);
			
			header('location: registrationThanks.php?congrats=1');
		}
	}
	else{
		//Form initially displayed, no need to validate it
		$processor = new CFormValidator($form, false);
	}

?>