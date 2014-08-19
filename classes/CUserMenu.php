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
						<li><a href="profile.php">Profile</a></li>
						<li><a href="createQuestion.php">Create Question</a></li>
						<li><a href="advanceQuestionSearch.php">Advance Question Search</a></li>
						<li><a href="myQuestion.php">My Question</a></li>
						<li><a href="myReport.php">My Report</a></li>
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
						<li><a href="profile.php">Profile</a></li>
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
						<li><a href="profile.php">Profile</a></li>
						<li><a>Search User</a></li>
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
	}