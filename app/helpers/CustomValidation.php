<?php

	class CustomValidation extends Illuminate\Validation\Validator
	{
		public function validateOnlyHiragana($attribute, $value, $parameters)
		{
			$pattern = "/^[\x{3041}-\x{3096}\s]*$/u";
			
			// return true or false
			return (bool) preg_match($pattern, $value);
		}
		
		public function validateCheckCellPhone($attribute, $value, $parameters)
		{
			if(preg_match("/^[0-9]{3}[0-9]{4}[0-9]{4}$/", $value))
			{
				return true;
			}
				
			return false;
		}
	}