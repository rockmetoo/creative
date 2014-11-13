<?php

	class CustomHelpers
	{
		/**
		 * generate random digits for SMS authentication
		 * @param $digits
		 * @return number
		 */
		public static function nDigitRandom($digits)
		{
			return rand(pow(10, $digits - 1) - 1, pow(10, $digits) - 1);
		}
		
		/**
		 * Quote into query string
		 *
		 * @param string $format
		 * @param mixed $vars
		 * @return string
		 */
		public static function quoteInto($format, $vars)
		{
			if (!is_array($vars)) $vars = array($vars);
				
			foreach ($vars as $k=>$var) {
				if (is_int($var))
					$vars[$k] = $var;
				else
					$vars[$k] = '\'' . self::quote($var) . '\'';
			}
			
			return vsprintf($format, $vars);
		}
		
		/**
		 * Quote variable if needed
		 *
		 * @param mixed $var
		 * @return string
		 */
		public static function quote($var)
		{
			switch(gettype($var))
			{
				case 'string':
				case 'double':
					return addslashes($var);
				case 'integer':
					return $var;
				case 'boolean':
					return ($var) ? 1 : 0;
				default:
					return '';
			}
		}
		
		public static function makeUserId($count)
		{
			$hashArray = array(
				array('1', '9', '8', '5', '3', '6', '0', '2', '4', '7'),
				array('6', '5', '9', '4', '3', '2', '1', '7', '8', '0'),
				array('2', '9', '7', '3', '1', '4', '5', '8', '6', '0'),
				array('1', '4', '0', '5', '7', '6', '2', '9', '3', '8'),
				array('9', '4', '1', '0', '8', '5', '6', '7', '3', '2'),
				array('6', '8', '0', '2', '1', '4', '5', '7', '3', '9'),
				array('4', '3', '5', '0', '6', '8', '7', '9', '2', '1'),
				array('5', '0', '2', '1', '7', '9', '4', '3', '6', '8'),
				array('5', '1', '6', '3', '9', '8', '2', '0', '7', '4')
			);
			
			$value	= ~~($count);
			$digits	= array();
			$base	= sizeof($hashArray);
		
			for($i=sizeof($hashArray)-1; $i>=0; $i--)
			{
				$digits[] = $hashArray[sizeof($hashArray)-1-$i][~~($value / pow($base, $i) % $base)];
			}
		
			$valueString = implode('', $digits);
		
			return (+$valueString);
		}
	}