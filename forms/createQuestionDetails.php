<?php

	include_once 'form/Form.php';
	
	$formContents = new Form("createQuestionDetails");
	$formContents->configure(array("action" => "createQuestionDetails.php", "method" => "post"));
	
	$formContents->addElement(new Element_Hidden('question', $numberOfQuestion, array('id' => 'question')));
	$formContents->addElement(new Element_Hidden('subject', $subject, array('id' => 'subject')));
	$formContents->addElement(new Element_Hidden('grade', $grade, array('id' => 'grade')));
	$formContents->addElement(new Element_Hidden('chapter', $chapter, array('id' => 'chapter')));

	for($i=1; $i<=$numberOfQuestion; $i++)
	{
		$formContents->addElement(
			new Element_HTMLExternal(
				'<fieldset id="page'. $i .'" style="margin-left:22px;width:92%"><legend>Add question details</legend>
					<div class="form_row">
				'
			)
		);
		
		$formContents->addElement(
			new Element_Textbox(
				'Question:', 'questionText' . $i, 'full',
				array(
					'id' => 'questionText' . $i, 'mandatory' => 'yes', 'maxlength' => '2048',
					'value' => $_POST['questionText' . $i]
				),
				"<error for='questionText" . $i . "'>Please write question</error>"
			)
		);

		$formContents->addElement(
	        new Element_File(
                'Picture or Video:', 'additionalFile' . $i, 'left',
                array('validate' => 'checkAdditionalFileForQuestion'),
                "<error for='additionalFile$i'>Please upload a valid picture or video file of Max. 10 MB</error>"
	        )
		);
		
		$formContents->addElement(
	        new Element_Radio(
                'Question Type:', 'questionType' . $i, 'left', $QUESTION_TYPE,
	            array(
	                'validate' => 'checkFormTypeYes', 'value' => '1', 'mandatory' => 'yes',
	                'style' => "width: auto; margin: 0px; float: left;"
	            ),
	            "<error for=\"questionType$i\">Please select a valid question type</error>"
	        )
		);
		
		$formContents->addElement(new Element_HTMLExternal('<br/><br/><br/><br/><br/>'));
		
		$formContents->addElement(
		    new Element_HTMLExternal(
		        '
		        <div class="form_row_full descriptiveOrDeterministic' . $i . '">
		            <label for="answer' . $i . '"><span>Answer:</span>
		                <textarea name="answer' . $i . '" id="answer' . $i . '" rows="5" eitheror="selectiveRightAnswer' . $i . '">' . $_POST['answer' . $i] . '</textarea>
		            </label>
		            <ul class="list_of_error hidden" id="list_of_error_answer' . $i . '">
                        <li id="error_item_answer' . $i . '_default" class="hidden">Please write an answer properly</li>
                    </ul>
		        </div>
		        '
		    )
		);
		
		$formContents->addElement(
	        new Element_HTMLExternal(
                '
	            <div class="selective' . $i . '">
                    <div class="form_row_left">
                        <label for="selectiveRightAnswer' . $i . '"><span>Right Answer:</span>
    	                    <input type="text" name="selectiveRightAnswer' . $i . '" id="selectiveRightAnswer' . $i . '" value="" maxlength="256" eitheror="answer' . $i . '" />
                        </label>
                        <ul class="list_of_error hidden" id="list_of_error_selectiveRightAnswer' . $i . '">
                            <li id="error_item_selectiveRightAnswer' . $i . '_default" class="hidden">Please write an answer properly</li>
                        </ul>
                    </div>
	                <div class="form_row_left">
                        <label for="selectiveChoice1-' . $i . '"><span>Choice 1 (required):</span>
    	                    <input type="text" name="selectiveChoice1-' . $i . '" id="selectiveChoice1-' . $i . '" value="" maxlength="256"/>
                        </label>
                        <ul class="list_of_error hidden" id="list_of_error_selectiveChoice1-' . $i . '">
                            <li id="error_item_selectiveChoice1-' . $i . '_default" class="hidden">Please write a choice</li>
                        </ul>
                    </div>
	                <div class="form_row_left">
                        <label for="selectiveChoice2-' . $i . '"><span>Choice 2 (required):</span>
    	                    <input type="text" name="selectiveChoice2-' . $i . '" id="selectiveChoice2-' . $i . '" value="" maxlength="256"/>
                        </label>
                        <ul class="list_of_error hidden" id="list_of_error_selectiveChoice2-' . $i . '">
                            <li id="error_item_selectiveChoice2-' . $i . '_default" class="hidden">Please write a choice</li>
                        </ul>
                    </div>
	                <div class="form_row_left">
                        <label for="selectiveChoice3-' . $i . '"><span>Choice 3 (required):</span>
    	                    <input type="text" name="selectiveChoice3-' . $i . '" id="selectiveChoice3-' . $i . '" value="" maxlength="256"/>
                        </label>
                        <ul class="list_of_error hidden" id="list_of_error_selectiveChoice3-' . $i . '">
                            <li id="error_item_selectiveChoice3-' . $i . '_default" class="hidden">Please write a choice</li>
                        </ul>
                    </div>
	                <div class="form_row_left">
                        <label for="selectiveChoice4-' . $i . '"><span>Choice 4:</span>
    	                    <input type="text" name="selectiveChoice4-' . $i . '" id="selectiveChoice4-' . $i . '" value="" maxlength="256"/>
                        </label>
                    </div>
	                <div class="form_row_left">
                        <label for="selectiveChoice5-' . $i . '"><span>Choice 5:</span>
    	                    <input type="text" name="selectiveChoice5-' . $i . '" id="selectiveChoice5-' . $i . '" value="" maxlength="256"/>
                        </label>
                    </div>
	            </div>
                '
	        )
		);		
		
		if($numberOfQuestion == $i)
		{
			$formContents->addElement(
				new Element_HTMLExternal(
					'
					<div class="form_row_left" style="margin-top:12px;text-align:center;">
						<button class="registerBlack" id="preview" name="preview" style="width:150px;">Preview Question</button>
					</div>
					<div class="form_row_right" style="margin-top:12px;text-align:center;">
						<button class="registerBlack" type="submit" id="submit" name="submit" style="width:150px;">Submit</button>
					</div><br/>
					'
				)
			);	
		}
		
		$formContents->addElement(new Element_HTMLExternal('</div></fieldset>'));
	}
	
	$form = '<div class="loginFormContainer">';
	$form .= $formContents->render(true);
	$form .= '</div>';
	
	if(isset($_POST['submit']))
	{
		// Form has been submitted, validate the form
		$processor = new CFormValidator($form);

		if($processor->validate())
		{
			$id = CDBQuestion::setQuestionOnHold($_POST);
			$processor->error_no = 1;
		}
	}
	else
	{
		//Form initially displayed, no need to validate it
		$processor = new CFormValidator($form, false);
	}

?>