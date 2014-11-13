<?php

	/**
	 * 	// send SMS
	 * 	ClickaTellSMS::publish('+819060271977', '+81XXXXXXXXXX', 'Hello World!');
	 * 
	 */
	class ClickaTellSMS
	{
		static		$instance;
		static		$twilioClient;
		// 1 for Twilio, 2 for clickatell
		static		$smsProvider = 1;
		
		static		$clickatellUser;
		static		$clickatellPassword;
		static		$clickatellApiId;
		static		$clickatellBaseurl;
		
		protected	$accountSid;
		protected	$authToken;
		
		static function singleton()
		{
			self::$clickatellUser		= "AmazingLife";
			self::$clickatellPassword	= "BZKeTCbVcXXJMK";
			self::$clickatellApiId		= "3480685";
			self::$clickatellBaseurl	= "http://api.clickatell.com";
			
			self::$instance || self::$instance = new ClickaTellSMS();
			return self::$instance;
		}
		
		protected function __construct(){}
		
		/**
		 * send SMS via Clickatell
		 */
		static function publish($fromNo, $toNo, $msg)
		{
			$singleton = self::singleton();
			
			$url = self::$clickatellBaseurl . "/http/sendmsg?user=" . self::$clickatellUser .
			"&password=" . self::$clickatellPassword . "&api_id=" . self::$clickatellApiId .
			"&to=" . $toNo . "&from=$fromNo" . "&text=" . $msg . "&unicode=1";
				
			// do sendmsg call
			$ret	= file($url);
			$send	= explode(":", $ret[0]);
			
			// XXX: IMPORTANT - successful SMS will return a string id rather numeric value
			if($send[0] == "ID")
			{
				// success SMS ID string
				return $ret[0];
			}
			else
			{
				// send message failed
				return 201;
			}
		}
		
		public static function convertStringToUnicode($string)
		{
			return bin2hex(iconv('UTF-8', 'UTF-16BE', $string));
		}
	}