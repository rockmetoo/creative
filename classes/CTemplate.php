<?php

	include_once 'CSettings.php';
	
	class CTemplate
	{
		function __construct()
		{
		}
		
		public function getTemplate($templateFile)
		{
			include_once Csettings::$BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'templates' .
			DIRECTORY_SEPARATOR . $templateFile;
		}
		
		public function getContents($templateFile)
		{
			return file_get_contents(
				CSettings::$BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $templateFile
			);
		}
	}