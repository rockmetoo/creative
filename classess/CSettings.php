<?php
 
	class CSettings
	{
		static $root;
		
		static $HTTP_PROTOCOL;
		
		static $SYSTEM_DOMAIN_VALUES = array(
			'primary_domain'		=> "simpleso.jp",
			'preg_primary_domain' 	=> "simpleso\.jp",
			'preg_gmail'			=> "gmail\.com",
			'smtp_server'			=> "smtp.gmail.com",
			'google_smtp_server'	=> "smtp.gmail.com",
			'smtp_port'				=> 465,
			'google_smtp_port'		=> 465
		);

		// http://mockup.simpleso.jp/contact.php need info@simpleso.jp
		// http://mockup.simpleso.jp/step4.php order@simpleso.jp
		static $SYSTEM_MAIL_VALUES = array(
			'support'		=> "support@simpleso.jp",
			'info'			=> "info@simpleso.jp",
			'order'			=> "order@simpleso.jp",
			'it'			=> "it@simpleso.jp",
			'faq'			=> "faq@simpleso.jp"
		);

		static $SYSTEM_MAIL_PASSWORD = array(
			'support'		=> '{3?:R/9Y)dWxXW&tqK$r(lp>DCVds>',
			'info'			=> '{3?:R/9Y)dWxXW&tqK$r(lp>DCVds>',
			'order'			=> '{3?:R/9Y)dWxXW&tqK$r(lp>DCVds>',
			'it'			=> '{3?:R/9Y)dWxXW&tqK$r(lp>DCVds>',
			'faq'			=> '{3?:R/9Y)dWxXW&tqK$r(lp>DCVds>'
		);

		static $SYSTEM_SMS_SENDER = array(
			'dev'			=> '+15005550006',
			'live'			=> '+17472332960',
			'clickatell'	=> 'Simpleso'
		);
		
		static $SYSTEM_ADMIN_MOBILE = array(
			'dev'			=> '+819080000505',
			'live'			=> '+819080000505'
		);
		
		static $SYSTEM_SMS_MESSAGE = array(
			'fourDigitConfirmation'		=> "シンプル葬をお申し込みいただくためのお客様携帯番号確認コードは{{fourDigitRandomNumber}}です。",
			'adminConfirmation'			=> "お客様からのお申し込みがありました。すぐに詳細を確認し、対応してください。{{protocol}}control.simpleso.jp/{{linkToAccess}}",
			'adminConfirmationNoLink'	=> "お客様からのお申し込みがありました。すぐに詳細を確認し、対応してください。"
		);
		
		static $SYSTEM_ADDITIONAL_COST = array(
			"additionalFlower1" => 32400,
			"additionalFlower2" => 54000,
			"monk"				=> 54000,
			"photoSession"		=> 16200,
			"posthumousName"	=> 54000,
			"東京都"				=> 246240,	/* 0.08 Tax included */
			"福岡県"				=> 181440	/* 0.08 Tax included */
		);
		
		static $SYSTEM_PAYPAL_SANDBOX = array(
			'webscr'			=> 'https://www.sandbox.paypal.com/cgi-bin/webscr',
			'business'			=> 'it@amazinglife.jp',
			'returnUrl'			=> 'http://devnew.simpleso.jp/paypalCallback.php',
			'cancelUrl'			=> 'http://devnew.simpleso.jp/paymentCancelled.php?res=1',
			'notifyUrl'			=> 'http://devnew.simpleso.jp/paypalIpn.php',
			'itemName'			=> 'シンプル火葬 サービス料金(税込)',
			'currencyCode'		=> 'JPY',
			'identity'			=> '9WZ9P7nlRTdxgWQp2Jq06-ybO8ri48nhAvIfWHqklPrA2-JQPUA-zM6Orem'
		);
		
		static $SYSTEM_PAYPAL_LIVE = array(
			'webscr'			=> 'https://www.paypal.com/cgi-bin/webscr',
			'business'			=> 'paypal@amazinglife.jp',
			'returnUrl'			=> 'https://simpleso.jp/paypalCallback.php',
			'cancelUrl'			=> 'https://simpleso.jp/paymentCancelled.php?res=1',
			'notifyUrl'			=> 'https://simpleso.jp/paypalIpn.php',
			'itemName'			=> 'シンプル火葬 サービス料金(税込)',
			'currencyCode'		=> 'JPY',
			'identity'			=> 'OqZ2Jc9I5-826oRQbxwFFncRt-daty1DFpLwoPTYn-AHB6h4BDAbeFFMAa0'
		);
		
		static $SYSTEM_WEBPAY_SANDBOX = array(
			'publicKey'			=> 'test_public_fxzaVLb0EgYv24L5RQ69w9nl',
			'privateKey'		=> 'test_secret_6tb5dU6Ip38TaPV4z10Kxctb'
		);
		
		static $SYSTEM_WEBPAY_LIVE = array(
			'publicKey'			=> 'live_public_ex72il6hR7vRg0h8CDgf86by',
			'privateKey'		=> 'live_secret_71y7f0aRi77d8Jm9BR5ma7Bv'
		);
		
		static $COMMAND_KEY = "40complex#$!11JY";
		
		static $CALLBACK_CMD_INDEX = array(
			'CALLBACK_CMD_INDEX_WEBPAY_SUCCESS'			=> 1,
			'CALLBACK_CMD_INDEX_WEBPAY_CANCELLED'		=> 2,
			'CALLBACK_CMD_INDEX_HALL_DATA_CACHE'		=> 3,
			'CALLBACK_CMD_INDEX_KEYWORD_RE_GENERATE'	=> 4,
			'CALLBACK_CMD_INDEX_KEYWORD_RE_CACHE'		=> 5,
			'CALLBACK_CMD_INDEX_PREORDER_SUCCESS'		=> 6
		);

		static $CALLBACK_SERVER_HOST = 'localhost';

		static $CALLBACK_SERVER_TIMEOUT = 5000;
		
		static $AMAZING_LIFE_ORDER = 'ao';
		
		static $USER_FROM = array(
			'direct'		=> 1,
			'google.co.jp'	=> 2,
			'a8.net'		=> 3
		);

		//Fixes MAGIC_QUOTES
	    static function fixSlashes($foo = '')
	    {
	        if(is_null($foo) || $foo == '') return null;
	        if(!get_magic_quotes_gpc()) return $foo;
	        return is_array($foo) ? array_map('self::fixSlashes', $foo) : stripslashes($foo);
	    }
	}
