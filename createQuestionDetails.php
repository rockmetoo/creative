<?php

	include_once 'bootstrap.php';
	include_once 'CDBSession.php';
	include_once 'CDBUser.php';
	include_once 'CUserMenu.php';
	include_once 'CDBQuestion.php';
	include_once 'CTemplate.php';
	
	$template = new CTemplate();
	
	global $CREATIVE_SYSTEM_DEF;
	
	// XXX: IMPORTANT - Only allow user who are logged in to view this page
	CDBSession::validateUser();
	
	// XXX: IMPORTANT - get user profile information
	$CREATIVE_USER_DEF = CDBUser::getUserDetails($CREATIVE_SYSTEM_DEF['userId']);
	
	// XXX: IMPORTANT - check this page only access by 'user' type
	CDBUser::onlyAccessByUser();
	
	include_once 'CFormValidator.php';
	include_once 'CHelperFunctions.php';
	include_once 'formValues' . $CREATIVE_SYSTEM_DEF['lang'] . '.php';
	include_once 'CLocalization.php';
	
	$lang = new CLocalization($CREATIVE_SYSTEM_DEF['lang'], 'createQuestionDetails.php');
	
	$title = 'Add question details';
	
	$numberOfQuestion	= $_REQUEST['question'];
	$subject			= $_REQUEST['subject'];
	$grade				= $_REQUEST['grade'];
	$chapter			= $_REQUEST['chapter'];
	
	// Include the form
	include('forms/createQuestionDetails.php');
	
	$JS_FILES		= array('jquery.multipage.js');
	$CSS_FILES		= array('style.css' => 'all', 'jquery.multipage.css' => 'all');
	$multipageForm	= '';
	
    // XXX: IMPORTANT - show page by page question form if form error or form not submit
	if((!isset($_POST['submit'])) || (count($processor->getErrors()) > 0))
	{
	    $multipageForm = '$("#createQuestionDetails").multipage({transitionFunction:transition, stateFunction: textpages});';
	}
	
	//var_dump($processor->getErrors());
	
	$defaultHide = '';
	$questionTypeOnClick = '';
	
	$questionTypeElemId = 7;
	
	for($i=1; $i<=$numberOfQuestion; $i++)
	{
	    // XXX: IMPORTANT - default hide
	    if(!isset($_POST['submit']))
	    {
	        $defaultHide .=
	        '
	            $(".descriptiveOrDeterministic' . $i . '").hide();
	            $(".selective' . $i . '").hide();
	            $("#createQuestionDetails-element-' . $questionTypeElemId . '-0").prop("checked", false);
	        ';
	    }
	    else if(isset($_POST['submit']))
	    {
	        if($_POST['questionType' . $i] == 1)
	        {
	            $defaultHide .=
	            '
	            $(".descriptiveOrDeterministic' . $i . '").show();
	            $(".selective' . $i . '").hide();
	            $("#createQuestionDetails-element-' . $questionTypeElemId . '-0").prop("checked", true);
	            ';
	        }
	        else if($_POST['questionType' . $i] == 2)
	        {
	            $defaultHide .=
	            '
	            $(".descriptiveOrDeterministic' . $i . '").show();
	            $(".selective' . $i . '").hide();
	            $("#createQuestionDetails-element-' . $questionTypeElemId . '-1").prop("checked", true);
	            ';
	        }
	        else if($_POST['questionType' . $i] == 3)
	        {
	            $defaultHide .=
	            '
	            $(".descriptiveOrDeterministic' . $i . '").hide();
	            $(".selective' . $i . '").show();
	            $("#createQuestionDetails-element-' . $questionTypeElemId . '-2").prop("checked", true);
	            ';
	        }
	        else
	        {
	            $defaultHide .=
	            '
	            $(".descriptiveOrDeterministic' . $i . '").hide();
	            $(".selective' . $i . '").hide();
	            $("#createQuestionDetails-element-' . $questionTypeElemId . '-0").prop("checked", false);
	            ';
	        }
	    }
	    
	    $questionTypeOnClick .=
	    '
	    $("#createQuestionDetails-element-' . $questionTypeElemId . '-0" ).live("click", function()
		{
		    $(".selective' . $i . '").hide();
            $(".descriptiveOrDeterministic' . $i . '").show();
        });
        
        $("#createQuestionDetails-element-' . $questionTypeElemId . '-1" ).live("click", function()
		{
		    $(".selective' . $i . '").hide();
            $(".descriptiveOrDeterministic' . $i . '").show();
        });
        
        $("#createQuestionDetails-element-' . $questionTypeElemId . '-2" ).live("click", function()
		{
            $(".descriptiveOrDeterministic' . $i . '").hide();
            $(".selective' . $i . '").show();
        });
	    ';
	    $questionTypeElemId += 8;
	}
	
	$JS_STRING	=
	'
		$(window).ready(function()
		{
			' . $multipageForm . '
			$(".addOneMore").live("click", function(e)
			{
				e.preventDefault();
				
				window.location = "createQuestion.php";
			});
			
			' . $defaultHide . $questionTypeOnClick . '
		});
		
		function transition(from,to)
		{
			$(from).fadeOut("fast", function(){$(to).fadeIn("fast");});
		}
		
		function textpages(obj,page,pages)
		{ 
			$(obj).html(page + " of " + pages);
		}
	';
	
	$username	= CDBUser::getSignInAs();
	$template->getTemplate('layout/userHeader.php');
?>
	<div id="mainContainer">
		<div id="content">
			<div id="headerForDashboard">
				<div class="headerLogo">
					<div style="float: right; padding-left: 10px; font-size: 25px; position: relative; margin-top: 0%; font-style: italic;">
						<a href="index.php" style="text-decoration: none;color: black">CREATIVE</a>
					</div>
					<ul class="headerList1">
						<li>
							<?php echo $lang->get('sign in as') . '<a href="profile.php" target="_self">' . $username . '</a>'; ?>
						</li>
						<li class="last">
							<a href="signout.php" style="font-size:12px;color:#FF4000;font-weight:bold;padding-left:10px">Sign Out</a>
						</li>
			    	</ul>
			    	<div class="clear"></div>
				</div>
			</div>
			<div class="formFullContent">
				<div class="formHolder">
					<div class="formLeftContent">
						<?php echo CUserMenu::userQuickLinkInLeftSide($lang, 3); ?>
						<div class="clear5"></div>
					</div>
					<div class="formRightContent">
						<h2><span>&nbsp;</span></h2>
						<div class="formRightContentHolder">
						<?php
							// After Form Submission if any error/success occured from Daemon
							if($processor->error_no == 1)
							{
								echo
								'
								<div class="success contentNotice transparent" style="width:90%;margin-left:4%;margin-top:10px;margin-bottom:10px;">
									<img src="../images/successful.png"></img>A question created successfully. Your question will soon publish in creative public area.
								</div><br/>
								<button class="registerBlack addOneMore" type="submit" name="backToCreateQuestion" style="margin-left:4%;">Add One More Question</button>
								<br/><br/>
								';
							}
							else
							{
								// Output form
								$processor->display();
							}
						?>
						</div>
					</div>
				</div>
			</div>
<?php
	$template->getTemplate('layout/userFooter.php');
?>