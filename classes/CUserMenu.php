<?php

	class CUserMenu
	{
		public function __construct(){}

		public static function userQuickLinkInLeftSide($lang, $selected = 0)
		{
			ob_start();
?>
			<div class="anyPageCol1Container">
				<h2>
					<span><?php echo $lang->get('user left side link'); ?></span>
				</h2>
				<div class="anyPageCol1ContainerTopBorder"></div>
				<div class="content">
					<ul>
						<li><a href="userDashboard.php" style="<?php echo ($selected==0) ? "font-weight:bold;text-decoration:underline;color:#FF5500;": ""; ?>">My Dashboard</a></li>
						<li><a href="profile.php" style="<?php echo ($selected==1) ? "font-weight:bold;text-decoration:underline;color:#FF5500;": ""; ?>">Profile</a></li>
						<li><a href="changePassword.php" style="<?php echo ($selected==2) ? "font-weight:bold;text-decoration:underline;color:#FF5500;": ""; ?>">Change Password</a></li>
						<li><a href="createQuestion.php" style="<?php echo ($selected==3) ? "font-weight:bold;text-decoration:underline;color:#FF5500;": ""; ?>">Create Question</a></li>
						<li><a href="advanceQuestionSearch.php" style="<?php echo ($selected==4) ? "font-weight:bold;text-decoration:underline;color:#FF5500;": ""; ?>">Advance Question Search</a></li>
						<li><a href="myQuestion.php" style="<?php echo ($selected==5) ? "font-weight:bold;text-decoration:underline;color:#FF5500;": ""; ?>">My Question</a></li>
						<li><a href="myReport.php" style="<?php echo ($selected==6) ? "font-weight:bold;text-decoration:underline;color:#FF5500;": ""; ?>">My Report</a></li>
					</ul>
				</div>
				<div class="clear"></div>
			</div>
<?php
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}
		
		public static function expertQuickLinkInLeftSide($lang, $selected = 0)
		{
			ob_start();
?>
			<div class="anyPageCol1Container">
				<h2>
					<span><?php echo $lang->get('user left side link'); ?></span>
				</h2>
				<div class="anyPageCol1ContainerTopBorder"></div>
				<div class="content">
					<ul>
						<li><a href="expertDashboard.php" style="<?php echo ($selected==0) ? "font-weight:bold;text-decoration:underline;color:#FF5500;": ""; ?>">My Dashboard</a></li>
						<li><a href="profile.php" style="<?php echo ($selected==1) ? "font-weight:bold;text-decoration:underline;color:#FF5500;": ""; ?>">Profile</a></li>
						<li><a href="changePassword.php" style="<?php echo ($selected==2) ? "font-weight:bold;text-decoration:underline;color:#FF5500;": ""; ?>">Change Password</a></li>
						<li><a>Advance Search</a></li>
						<li><a>My Report</a></li>
					</ul>
				</div>
				<div class="clear"></div>
			</div>
<?php
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}
		
		public static function adminQuickLinkInLeftSide($lang, $selected = 0)
		{
			ob_start();
?>
			<div class="anyPageCol1Container">
				<h2>
					<span><?php echo $lang->get('user left side link'); ?></span>
				</h2>
				<div class="anyPageCol1ContainerTopBorder"></div>
				<div class="content">
					<ul>
						<li><a href="adminDashboard.php" style="<?php echo ($selected==0) ? "font-weight:bold;text-decoration:underline;color:#FF5500;": ""; ?>">My Dashboard</a></li>
						<li><a href="profile.php" style="<?php echo ($selected==1) ? "font-weight:bold;text-decoration:underline;color:#FF5500;": ""; ?>">Profile</a></li>
						<li><a href="changePassword.php" style="<?php echo ($selected==2) ? "font-weight:bold;text-decoration:underline;color:#FF5500;": ""; ?>">Change Password</a></li>
						<li><a href="searchUser.php" style="<?php echo ($selected==3) ? "font-weight:bold;text-decoration:underline;color:#FF5500;": ""; ?>">Search User</a></li>
						<li><a>Search Question</a></li>
					</ul>
				</div>
				<div class="clear"></div>
			</div>
<?php
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}

		public static function loginAsPanel($lang, $username)
		{
		    global $CREATIVE_USER_DEF;
		    
		    ob_start();
?>
            <ul class="headerList1">
				<li>
					<?php echo $lang->get('sign in as') . '<a href="profile.php" target="_self">' . $username . '</a>'; ?>
				</li>
				<li style="margin-top:0px;margin-right:0px;">
				    <div class="imageMultipleBorders">
				        <?php
    				        if($CREATIVE_USER_DEF['profilePicture']) echo '<img src="profilePictureReader.php"></img>';
    				        else echo '<img src="../images/userUnknown.png"></img>';
				        ?>
				    </div>
				</li>
				<li class="last">
					<a href="signout.php" style="font-size:12px;color:#FF4000;font-weight:bold;padding-left:10px">Sign Out</a>
				</li>
	    	</ul>
	    	<div class="clear"></div>
<?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
		}
	}