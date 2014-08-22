<?php

	include_once 'form/Form.php';
	
	$formContents = new Form("createQuestion");
	$formContents->configure(array("action" => "createQuestion.php", "method" => "post"));

	$formContents->addElement(
		new Element_HTMLExternal(
			'
			<fieldset style="margin-left:22px;width:92%"><legend>Create a question</legend>
			<div class="form_row">
			'
		)
	);
	
	$formContents->addElement(
		new Element_HTMLExternal(
			'
			<div class="form_row_left">
				<label for="username"><span>Number of Question:</span></label>
			</div>
			'
		)
	);
	
	$formContents->addElement(
		new Element_Textbox(
		 '', 'numberOfQuestion', 'right'
			, array(
				'id' => 'numberOfQuestion', 'mandatory' => 'yes', 'maxlength' => '32',
				'validate' => 'checkNumberOfQuestion',
				'params' => '1,32'
			)
			, "<error for='numberOfQuestion'>Please enter a number between 1 to 32</error>"
		)
	);
	
	$formContents->addElement(new Element_HTMLExternal("</div></fieldset>"));
	
	$formContents->addElement(
		new Element_HTMLExternal(
			'<div class="form_row_full" style="margin-top:12px;text-align:center;">
				<button class="registerBlack" type="submit" id="submit" name="submit" style="width:150px;">
					Submit
				</button>
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
			header('Location: '. CSettings::$HTTP_PROTOCOL . CSettings::$HOST . 'createQuestonDetails.php');
			exit;
		}
	}
	else
	{
		//Form initially displayed, no need to validate it
		$processor = new CFormValidator($form, false);
	}

?>