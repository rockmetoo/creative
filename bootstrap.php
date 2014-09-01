<?php
	
	/**
	 * IMPORTANT: make this file unable to execute via WEB Server
	 */

	include_once 'classes/CSettings.php';
	
	define('ALLOWED_REFERRER', CSettings::$SYSTEM_DOMAIN_VALUES['primaryDomain']);
	
	global $CREATIVE_SYSTEM_DEF;
	
	// set language settings
	if(isset($_REQUEST['lang'])) $CREATIVE_SYSTEM_DEF['lang']				= $_REQUEST['lang'];
	if(!isset($CREATIVE_SYSTEM_DEF['lang'])) $CREATIVE_SYSTEM_DEF['lang']	= 'en';
	
	// XXX: IMPORTANT: 3 kinds of environment we have [local, staging, production]
	// Set this in your web server virtual host config as: setEnv APPLICATION_ENV local
	define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
	
	if(APPLICATION_ENV === 'local')
	{
		CSettings::$MYSQL_CONNECTION_POOL = array(
			'creative'		=> array('creative', 'root', '123456', 'creative', '3306', NULL),
			'creativeUser'	=> array('creativeUser', 'root', '123456', 'creativeUser', '3306', NULL),
		);
		
		// host, port, user, password, db, persistence, connectionObject
		CSettings::$MONGO_CONNECTION_POOL = array(
			'creative' => array('127.0.0.1', '27017', '', '', 'creative', false, NULL),
		);
		
		CSettings::$HOST = 'local.creative.com';
	}
	else if(APPLICATION_ENV === 'staging')
	{
		CSettings::$MYSQL_CONNECTION_POOL = array(
			'creative'		=> array('creative', 'root', '123456', 'creative', '3306', NULL),
			'creativeUser'	=> array('creativeUser', 'root', '123456', 'creativeUser', '3306', NULL),
		);
		
		// host, port, user, password, db, persistence, connectionObject
		CSettings::$MONGO_CONNECTION_POOL = array(
			'creative' => array('127.0.0.1', '27017', '', '', 'creative', false, NULL),
		);
		
		CSettings::$HOST = 'staging.creative.com';
	}
	else if(APPLICATION_ENV === 'production')
	{
		CSettings::$MYSQL_CONNECTION_POOL = array(
			'creative'		=> array('creative', 'root', '123456', 'creative', '3306', NULL),
			'creativeUser'	=> array('creativeUser', 'root', '123456', 'creativeUser', '3306', NULL),
		);
		
		// host, port, user, password, db, persistence, connectionObject
		CSettings::$MONGO_CONNECTION_POOL = array(
			'creative' => array('127.0.0.1', '27017', '', '', 'creative', false, NULL),
		);
		
		CSettings::$HOST = 'creative.com';
	}
	else
	{
		CSettings::$MYSQL_CONNECTION_POOL = array(
			'creative'		=> array('creative', 'root', '123456', 'creative', '3306', NULL),
			'creativeUser'	=> array('creativeUser', 'root', '123456', 'creativeUser', '3306', NULL),
		);
		
		// host, port, user, password, db, persistence, connectionObject
		CSettings::$MONGO_CONNECTION_POOL = array(
			'creative' => array('127.0.0.1', '27017', '', '', 'creative', false, NULL),
		);
		
		CSettings::$HOST = 'local.creative.com';
	}
	
	// XXX: base directory config
	CSettings::$BASE_DIRECTORY = dirname(__FILE__);

	set_include_path(
	    '.' . PATH_SEPARATOR . CSettings::$BASE_DIRECTORY . PATH_SEPARATOR
		. CSettings::$BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'ajax' . PATH_SEPARATOR
	    . CSettings::$BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'classes' . PATH_SEPARATOR
	    . CSettings::$BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'forms' . PATH_SEPARATOR
	    . CSettings::$BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'includes' . PATH_SEPARATOR
	    . CSettings::$BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'localization' . PATH_SEPARATOR
	    . CSettings::$BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'library' . PATH_SEPARATOR
	    . CSettings::$BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'templates' . PATH_SEPARATOR
	    . CSettings::$BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'pear' . PATH_SEPARATOR
		. CSettings::$BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'resources' . PATH_SEPARATOR
	    . get_include_path()
	);

	// XXX: set timezone
	date_default_timezone_set('UTC');
	
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) CSettings::$HTTP_PROTOCOL = 'https://';
	else CSettings::$HTTP_PROTOCOL = 'http://';
	
	// XXX: IMPORTANT - set mailer as google
	CSettings::$MAILER_SELECT = 1;

	// XXX: Fix magic quotes
    if(get_magic_quotes_gpc())
    {
        $_POST		= CSettings::fixSlashes($_POST);
        $_GET		= CSettings::fixSlashes($_GET);
        $_REQUEST	= CSettings::fixSlashes($_REQUEST);
        $_COOKIE	= CSettings::fixSlashes($_COOKIE);
    }
?>