<?php
	
	include_once 'CMail.php';

	class CLocalization
	{
		var $lang;
		var $pageName;
		var $translation	= array();
		var $common			= array();

		function __construct($lang, $pageName=false)
		{
			$this->lang 	= $lang;
			$this->pageName	= $pageName;
			$this->_init();
		}

		function _init()
		{
			if($this->pageName) include_once('localization/' . $this->lang . '/' . $this->pageName);
			
			include_once('localization/' . $this->lang . '/' . 'common.php');
			$this->translation = array_merge($this->translation, $this->common);
		}

		public function get($index, $val = false)
		{
			if(!$val && isset($this->translation[$index])) return $this->translation[$index];
			else if(is_array($val) && isset($this->translation[$index])) return vsprintf($this->translation[$index], $val);
			else if($val && isset($this->translation[$index])) return sprintf($this->translation[$index], $val);
			else
			{
				$message = "[ MISSING LOCALIZATION ] ".$this->lang. "\n";
				$message .= $_SERVER['HTTP_HOST'] . "/" . $_SERVER['REQUEST_URI'] . "\n";
				$message .= "--------------------------------------\nIndex : ".$index."\n";
				$trace = debug_backtrace();
				$message .= "File : ".$trace[0]['file']."\n";
				$message .= "Line : ".$trace[0]['line']."\n";
				CMail::send(
					array(CSettings::$SYSTEM_MAIL_VALUES['noreply'], 'CREATIVE @ Gateway', CSettings::$SYSTEM_MAIL_PASSWORD['noreply']),
					array(CSettings::$SYSTEM_MAIL_VALUES['tech'], 'IT Gods'), 'CREATIVE - Localization Error',
					$message, '', array(), array()
				);
				
				return $index;
			}
		}

		public function getLang()
		{
			return $this->lang;
		}
	}

?>