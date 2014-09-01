<?php

	include_once 'form/Form.php';
	
	$formContents = new Form("searchUser");
	$formContents->configure(array("action" => "searchUser.php", "method" => "post"));

	$formContents->addElement(
		new Element_HTMLExternal('<fieldset style="margin-left:22px;width:92%"><legend>Search User</legend><div class="form_row">')
	);
	
	$formContents->addElement(
        new Element_Textbox(
            'First Name:', 'firstName', 'left'
            , array('id' => 'firstName', 'value' => '', 'maxlength' => '255')
        )
	);
	
	$formContents->addElement(
        new Element_Textbox(
            'Last Name:', 'lastName', 'right'
            , array('id' => 'lastName', 'value' => '', 'maxlength' => '255')
        )
	);
	
	$formContents->addElement(new Element_HTMLExternal("</div></fieldset>"));
	
	$formContents->addElement(
		new Element_HTMLExternal(
			'<div class="form_row_full" style="margin-top:12px;text-align:center;">
				<button class="registerBlack" type="submit" id="submit" name="submit" style="width:150px;">Search User</button>
			</div><br/>'
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
			$searchResult    = CDBUser::searchUserForAdmin($_POST);
			$processor->msg  = $searchResult;
		}
	}
	else
	{
		//Form initially displayed, no need to validate it
		$processor = new CFormValidator($form, false);
	}

?>