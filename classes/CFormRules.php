<?php

	include_once 'CXML.php';

	class CFormRules
	{
		public static function isNumeric($value, $params=array())
		{
			if(is_numeric($value)) return true;
			else return false;
		}

		public static function isInt($value, $params=array())
		{
			return ((!$value && $params[0]) || (intval($value) == strval($value) && $value !== '') ) ? true : false;
		}

		public static function checkCEmail($value, $params=array())
		{
			$cc = explode(',', $value);
			
			foreach($cc as $email)
			{
				if(!self::checkEmail(trim($email), $params)) return false;
			}
			return true;
		}

		public static function isFloat($value, $params=array())
		{
			return ((!$value && $params[0]) || (floatval($value) == strval($value) && $value !== '') ) ? true : false;
		}

		public static function checkDate($date, $params=array())
		{
			if( $date == '' || $date == 'yyyy-mm-dd' || $date == '0000-00-00' )
			{
				// Empty or default field. Use mandatory="yes" to make it mandatory.
				return true;
			}
			//if mandatory=yes, check below!
			if(!preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $date, $matches))
			{
				//date not right format yyyy-mm-dd
				return false;
			}
			if(!checkdate($matches[2], $matches[3], $matches[1]))
			{
				//date not correct
				return false;
			}
			return true;
		}

		public static function checkEmail($email, $params = array())
		{
			if(filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				// valid
				return true;
			}
			else return false;
		}

		public static function checkCaptcha($value, $params)
		{
			if(!isset($_SESSION['captcha']))
			{
				session_start();
			}
			
			if(!empty($value))
			{
			    if(empty($_SESSION['captcha']) || trim(strtolower($value)) !== $_SESSION['captcha'])
			    {
			    	unset($_SESSION['captcha']);
			    	return false;
			    }
			    else
			    {
			    	unset($_SESSION['captcha']);
			        return true;
			    }
			}
			else
			{
				unset($_SESSION['captcha']);
				return false;
			}
		}
		
		public static function checkCellPhone($value, $params)
		{
			if(preg_match("/^[0-9]{3}[0-9]{4}[0-9]{4}$/", $value))
			{
				return true;
			}
			
			return false;
		}
		
		public static function checkAlphaAtDot($value, $params)
		{
			if(preg_match("'[^A-Za-z0-9@.]|\s{2}'", $value))
			{
				return false;
			}
			else{ return true; }
		}

		public static function checkUnixName($value, $params)
		{
			//UNIX style file/directory name only
			if(preg_match("'[^A-Za-z0-9_-]|\s{2}'", $value))
			{
				return false;
			}
			else{ return true; }
		}

		public static function validXML($value, $params=array())
		{
			return CXML::isValid($value);
		}

		public static function validXMLString($value, $params=array())
		{
			return CXML::isValid('<t>' . $value . '</t>');
		}

		/**
		 * Checks if value is in array
		 *
		 * Params values:
		 * 	[0] - array		- array to check
		 * 	[1] - boolean	- empty value resolves to true
		 *
		 * @param mixed $value
		 * @param array $params
		 * @return boolean
		 */
		public static function inArray($value, $params=array())
		{
			if(!isset($params[0]) || !is_array($params[0])) return false;
			if(!$value && $params[1]) return true;
			if(!is_array($value)) $value = array($value);
			foreach( $value as $v )
			{
				if(!in_array($v, $params[0])) return false;
			}
			return true;
		}

		public static function arraySmallerThan( $value, $params=array() )
		{
			if( isset($params[0]) && is_numeric($params[0]) && isset($value) && is_array($value) && count($value) <= $params[0] ) return true;
			return false;
		}

		public static function inBetween($value, $params=array())
		{
			if(!isset($params[0]) || !isset($params[1]) || !is_numeric($value) || !is_numeric($params[0]) || !is_numeric($params[1])) return false;
			return ($value >= $params[0] && $value <= $params[1]);
		}

		/**
		 * Checks if value is string of valid length
		 *
		 * Params values:
		 *  [0] - int		- min size
		 * 	[1] - int		- max size
		 * 	[2] - boolean	- on True use byte count instead of character count
		 *
		 * @param string $value
		 * @param array $params
		 * @return boolean
		 */
		public static function strLengthBetween($value, $params=array())
		{
			if(!is_string($value)) return false;
			$len = (isset($params[2]) && $params[2]) ? strlen($value) : mb_strlen($value,'UTF-8');
			return self::inBetween($len, $params);
		}

		/**
		 * Check if value is a valid URL
		 *
		 * Params values:
		 * 	[0] - array<string>	- List of accepted protocols. Defaults to http & https.
		 *  [1] - boolean		- Host section is optional. Default is false.
		 *
		 * @param string $value
		 * @param array $params
		 * @return boolean
		 */
		public static function isURL($value, $params=array())
		{
			if(!is_string($value)) return false;
			//get allowed protocols
			$protocol = ($params[0]) ? $params[0] : array('http', 'https');

			//based on regexp from RFC3986 Appendix B
			$matches = array();
			if(!preg_match('|^(?:([^:/?#]+):)?(?://([^/?#]*))?([^?#]*)(\?([^#]*))?(?:#(.*))?|',$value, $matches)) return false;

			//check protocol match
			if(!in_array($matches[1], $protocol)) return false;

			//check host is present
			if(!$params[1] && $matches[2] == '') return false;

			//everything passed ok
			return true;
		}
	}
