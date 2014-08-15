<?php

	include_once 'CSettings.php';
	include_once 'CDBQuery.php';
	require_once('Net/UserAgent/Mobile.php');

	class CSession extends CDBQuery
	{
		public static function getUserPassword($userId)
		{
			global $db;
			$userId = intval($userId);
			$query = "SELECT password FROM user	WHERE userId = '$userId'";
			$res = $db->queryOther('siteUser', $query);
			if(!$res->num_rows) return 0;
			$foo = $res->fetch_assoc();
			return $foo['password'];
		}

		public static function getUserName($userId)
		{
			global $db;
			$userId = intval($userId);
			if($userId){
				$query = "SELECT username FROM user WHERE userId = '$userId'";
				$res = $db->queryOther('siteUser', $query);
				if(!$res->num_rows) return 0;
				$foo = $res->fetch_assoc();
				return $foo;
			}
		}

		public static function sessionGetId()
		{
			global $db;
			global $SMITH;
			$query = "SELECT sessionId FROM systemSession WHERE cookie='$SMITH'";
			$res = $db->queryOther('siteUser', $query);
			if(!$res->num_rows){
				return 0;
			}
			$foo = $res->fetch_assoc();
			return $foo["sessionId"];
		}

		public static function sessionGetStatus()
		{
			global $db;
			global $SMITH;
			$query = "SELECT userStatus FROM systemSession WHERE cookie='$SMITH'";
			$res = $db->queryOther('siteUser', $query);
			if(!$res->num_rows){
				return 0;
			}
			$foo = $res->fetch_assoc();
			return $foo["userStatus"];
		}

		public static function sessionGetUserId()
		{
			global $db;
			global $SMITH;
			global $SMITH_SESSION;
			global $SMITH_SYSTEM_DEF;
			global $mobile_agent;
			if(!$SMITH || ($SMITH != $SMITH_SESSION && $mobile_agent->isNonMobile())){
				return 0;
				exit;
			}
			$query = "SELECT userId FROM systemSession WHERE sessionId='" . $SMITH_SYSTEM_DEF["sessionId"] . "'";
			$res = $db->queryOther('siteUser', $query);
			if(!$res->num_rows){
				return 0;
			}
			$foo = $res->fetch_assoc();
			return $foo["userId"];
		}

		public static function sessionLinkUserId($userId = 0, $user_status = 0, $permanent = 0)
		{
			global $SMITH;
			global $db;
			global $mobile_agent;
			
			$db->updateOther(
				'siteUser', 'systemSession', 'cookie', $SMITH, array('userId'=>$userId, 'userStatus'=>$user_status)
			);
			
			if($mobile_agent->isNonMobile())
			{
				//90 days cookie
				if($permanent) setcookie("SMITH_SESSION", $SMITH, time() + 7776000, '/');
				//session cookie
				else setcookie("SMITH_SESSION", $SMITH, 0, '/');
			}
		}

		public static function sessionSetCookie()
		{
			global $db;
			global $SIMPLESO;
			global $mobile_agent;
			
			if(!$mobile_agent->isNonMobile() && $mobile_agent->getUID())
			{
				$fulldomain = @gethostbyaddr($_SERVER['REMOTE_ADDR']);
   				$domainlist = array_reverse(explode('.', $fulldomain));
   				
   				if(preg_match("/\.(or[g]?|co[m]?|ne[t]?|)/i", $domainlist[1]))
   				{
       				$domain = $domainlist[2] . '.' . $domainlist[1] . '.' . $domainlist[0];
   				}
   				else
   				{
       				$domain = $domainlist[1] . '.' . $domainlist[0];
   				}
   				
   				if(preg_match("/[0-9]{1,3}\.[0-9]{1,3}/", $domain))
   				{
					$domain = $_SERVER['REMOTE_ADDR'];
				}
				
				$SIMPLESO = sprintf('%s %s', $mobile_agent->getUID(), $domain);
			}
			
			if($SIMPLESO && CDBSession::sessionCheckCookie($SIMPLESO))
			{
				$db->updateOther(
					'simpleso', 'sessions', 'cookie', $SMITH, array('lastSeen' => date("Y-m-d H:i:s"))
				);
				return true;
			}
			
			if(!$SIMPLESO)
			{
				srand((double) microtime() * 1000000);
				$loop = true;
				
				while($loop == true)
				{
					$SIMPLESO	= md5(uniqid(rand()));
					$query		= "SELECT sessionId FROM sessions WHERE cookie='$SIMPLESO'";
					$res		= $db->queryOther('simpleso', $query);
					
					if(!$res->num_rows) $loop = false;
				}
				
				setcookie("SIMPLESO", $SIMPLESO, (time() + 31536000), '/');
			}
			
			$db->insertOther(
				'simpleso', 'sessions' , array('cookie' => $SMITH, 'lastSeen' => date("Y-m-d H:i:s"))
			);
		}

		public static function sessionUnlinkUserId($userId)
		{
			global $db;
			global $SMITH;
			$db->updateOther('siteUser',  'systemSession', 'cookie', $SMITH, array('userId' => 0));
			setcookie("SMITH_SESSION", "", time() - 3600);
			setcookie("SMITH_SESSION", "", time() - 3600, "/");
		}

		public static function validate()
		{
			if(!isset($SMITH_SYSTEM_DEF["userId"]))
			{
				header('location: /login.php?loginto=' . $_SERVER['REQUEST_URI']);
				exit;
			}
		}

		/**
		 * Validate Employer
		 *
		 * @param boolean $redirect If true, will redirect browser without returning.
		 * @return boolean true on success, false if validation failed.
		 */
		public static function validateUser($redirect = true)
		{
			global $SMITH_SYSTEM_DEF;
			if(!isset($SMITH_SYSTEM_DEF["userId"]) || $SMITH_SYSTEM_DEF["userId"] == 0)
			{
				if(!$redirect) return false;
				header('location: signin.php?signinto=' . $_SERVER['REQUEST_URI']);
				exit;
			}
			return true;
		}

		/* PRIVATE FUNCTIONS */
		private static function sessionCheckCookie($cookie)
		{
			global $db;
			$query = "SELECT lastSeen FROM systemSession WHERE cookie='$cookie'";
			$res = $db->queryOther('siteUser', $query);
			return $res->num_rows;
		}
	}

	if(isset($_COOKIE["SIMPLESO"]))			$SIMPLESO			= $_COOKIE["SIMPLESO"];
	if(isset($_COOKIE["SIMPLESO_SESSION"]))	$SIMPLESO_SESSION	= $_COOKIE["SIMPLESO_SESSION"];
	
	$mobile_agent = Net_UserAgent_Mobile::factory();
	CSession::sessionSetCookie();
	$SIMPLESO_SYSTEM_DEF = array();
	$SIMPLESO_SYSTEM_DEF["sessionId"]	= CSession::sessionGetId();
	$SIMPLESO_SYSTEM_DEF["userStatus"]	= CSession::sessionGetStatus();
	$SIMPLESO_SYSTEM_DEF["cookie"]		= $SIMPLESO;
	$SIMPLESO_SYSTEM_DEF["userId"]		= (int)CSession::sessionGetUserId();
	
	$foo								= CSession::getUserName($SIMPLESO_SYSTEM_DEF['userId']);
	$SIMPLESO_SYSTEM_DEF["username"]	= $foo['username'];

	// check bootstrap.php
	$SIMPLESO_SYSTEM_DEF["protocol"]	= CSettings::$HTTP_PROTOCOL;
	$SIMPLESO_SYSTEM_DEF["lang"]		= 'ja';
	
?>