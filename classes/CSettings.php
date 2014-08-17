<?php
 
	class CSettings
	{
		static $BASE_DIRECTORY;
		
		static $HTTP_PROTOCOL;
		
		static $SYSTEM_DOMAIN_VALUES = array(
			'primaryDomain'			=> "creative.me",
			'pregPrimaryDomain' 	=> "creative\.me",
			'pregGmail'				=> "gmail\.com",
			'smtpServer'			=> "smtp.gmail.com",
			'googleSmtpServer'		=> "smtp.gmail.com",
			'smtpPort'				=> 465,
			'googleSmtpPort'		=> 465
		);

		static $SYSTEM_MAIL_VALUES = array(
			'support'		=> "srijonshil.proshno@gmail.com",
			'info'			=> "srijonshil.proshno@gmail.com",
			'tech'			=> "srijonshil.proshno@gmail.com",
			'noreply'		=> "srijonshil.proshno@gmail.com"
		);

		static $SYSTEM_MAIL_PASSWORD = array(
			'support'		=> 'kara1kuri2',
			'info'			=> 'kara1kuri2',
			'tech'			=> 'kara1kuri2',
			'noreply'		=> 'kara1kuri2',
		);

		// XXX: IMPORTANT - we are using mysqlnd_ms php plugins which is actually load balance
		// mysql connection. Initialize it in bootstrap.php
		static $MYSQL_CONNECTION_POOL			= array();
		
		// XXX: 1 for google, 2 for local mail server. Initialize it in bootstrap.php
		static $MAILER_SELECT;
		
		static $ALLOWED_DOC_TYPES = array(
			'pdf'	=> 'PDF file',
			'jpg'	=> 'Jpeg file',
			'jpeg'	=> 'Jpeg file',
			'png'	=> 'PNG file'
		);
		
		// XXX: IMPORTANT - Fixes MAGIC_QUOTES. call from bootstrap.php
	    static function fixSlashes($foo = '')
	    {
	        if(is_null($foo) || $foo == '') return null;
	        if(!get_magic_quotes_gpc()) return $foo;
	        return is_array($foo) ? array_map('self::fixSlashes', $foo) : stripslashes($foo);
	    }
	}
