<?php

	include_once 'CSettings.php';
	require_once('CMail.php');
	require_once('CDBQuery.php');
	require_once('Net/UserAgent/Mobile.php');

	class CDBSession extends CDBQuery
	{
		public static function getUserPassword($userId)
		{
			global $db;
			
			$userId	= intval($userId);
			$query	= "SELECT password FROM user WHERE userId = '$userId'";
			$res	= $db->queryOther('creativeUser', $query);
			
			if(!$res->num_rows) return 0;
			
			$foo = $res->fetch_assoc();
			
			return $foo['password'];
		}

		public static function getUserName($userId)
		{
			global $db;
			
			$userId = intval($userId);
			
			if($userId)
			{
				$query	= "SELECT username FROM user WHERE userId = '$userId'";
				$res	= $db->queryOther('creativeUser', $query);
				
				if(!$res->num_rows) return 0;
				
				$foo = $res->fetch_assoc();
				
				return $foo;
			}
		}

		public static function sessionGetId()
		{
			global $db;
			global $CREATIVE;
			
			$query	= "SELECT sessionId FROM systemSession WHERE cookie='$CREATIVE'";
			$res	= $db->queryOther('creativeUser', $query);
			
			if(!$res->num_rows)
			{
				return 0;
			}
			
			$foo = $res->fetch_assoc();
			
			return $foo["sessionId"];
		}

		public static function sessionGetStatus()
		{
			global $db;
			global $CREATIVE;
			
			$query	= "SELECT userStatus FROM systemSession WHERE cookie='$CREATIVE'";
			$res	= $db->queryOther('creativeUser', $query);
			
			if(!$res->num_rows)
			{
				return 0;
			}
			
			$foo = $res->fetch_assoc();
			
			return $foo["userStatus"];
		}

		public static function sessionGetUserId()
		{
			global $db;
			global $CREATIVE;
			global $CREATIVE_SESSION;
			global $CREATIVE_SYSTEM_DEF;
			global $mobile_agent;
			
			if(!$CREATIVE || ($CREATIVE != $CREATIVE_SESSION && $mobile_agent->isNonMobile()))
			{
				return 0;
				exit;
			}
			
			$query	= "SELECT userId FROM systemSession WHERE sessionId='" . $CREATIVE_SYSTEM_DEF["sessionId"] . "'";
			$res	= $db->queryOther('creativeUser', $query);
			
			if(!$res->num_rows)
			{
				return 0;
			}
			
			$foo = $res->fetch_assoc();
			return $foo["userId"];
		}

		public static function sessionLinkUserId($userId = 0, $user_status = 0, $permanent = 0)
		{
			global $CREATIVE;
			global $db;
			global $mobile_agent;
			
			$db->updateOther(
				'creativeUser', 'systemSession', 'cookie', $CREATIVE, array('userId'=>$userId, 'userStatus'=>$user_status)
			);
			
			if($mobile_agent->isNonMobile())
			{
				//90 days cookie
				if($permanent) setcookie("CREATIVE_SESSION", $CREATIVE, time() + 7776000, '/');
				//session cookie
				else setcookie("CREATIVE_SESSION", $CREATIVE, 0, '/');
			}
		}

		public static function sessionSetCookie()
		{
			global $db;
			global $CREATIVE;
			global $mobile_agent;
			
			if(!$mobile_agent->isNonMobile() && $mobile_agent->getUID())
			{
				$fulldomain = @gethostbyaddr($_SERVER['REMOTE_ADDR']);
   				$domainlist = array_reverse(explode('.', $fulldomain));
   				
   				if(preg_match("/\.(or[g]?|co[m]?|ne[t]?|)/i", $domainlist[1])){
       				$domain = $domainlist[2].'.'.$domainlist[1].'.'.$domainlist[0];
   				}
   				else
   				{
       				$domain = $domainlist[1].'.'.$domainlist[0];
   				}
   				
   				if(preg_match("/[0-9]{1,3}\.[0-9]{1,3}/", $domain))
   				{
					$domain = $_SERVER['REMOTE_ADDR'];
				}
				
				$CREATIVE = sprintf('%s %s', $mobile_agent->getUID(), $domain);
			}
			
			if($CREATIVE && CDBSession::sessionCheckCookie($CREATIVE))
			{
				$db->updateOther(
					'creativeUser', 'systemSession', 'cookie', $CREATIVE, array('lastSeen'=>date("Y-m-d H:i:s"))
				);

				return true;
			}
			
			if(!$CREATIVE)
			{
				srand((double)microtime() * 1000000);
				$loop = true;
				while($loop == true)
				{
					$CREATIVE = md5(uniqid(rand()));
					$query = "SELECT sessionId FROM systemSession WHERE cookie='$CREATIVE'";
					$res = $db->queryOther('creativeUser', $query);
					if(!$res->num_rows) $loop=false;
				}
				
				setcookie("CREATIVE", $CREATIVE, (time() + 31536000), '/');
			}
			
			$db->insertOther(
				'creativeUser', 'systemSession'
				, array(
					'cookie' => $CREATIVE, 'ip' => $_SERVER["REMOTE_ADDR"], 'lastSeen' => date("Y-m-d H:i:s")
				)
			);
		}

		public static function sessionUnlinkUserId($userId)
		{
			global $db;
			global $CREATIVE;
			
			$db->updateOther('creativeUser',  'systemSession', 'cookie', $CREATIVE, array('userId' => 0));
			setcookie("CREATIVE_SESSION", "", time() - 3600);
			setcookie("CREATIVE_SESSION", "", time() - 3600, "/");
		}

		/**
		 * Validate registered user
		 *
		 * @param boolean $redirect If true, will redirect browser without returning.
		 * @return boolean true on success, false if validation failed.
		 */
		public static function validateUser($redirect = true)
		{
			global $CREATIVE_SYSTEM_DEF;
			if(!isset($CREATIVE_SYSTEM_DEF["userId"]) || $CREATIVE_SYSTEM_DEF["userId"] == 0)
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
			
			$query	= "SELECT lastSeen FROM systemSession WHERE cookie='$cookie'";
			$res	= $db->queryOther('creativeUser', $query);
			
			return $res->num_rows;
		}
	}

	if(isset($_COOKIE["CREATIVE"])) $CREATIVE = $_COOKIE["CREATIVE"];
	if(isset($_COOKIE["CREATIVE_SESSION"])) $CREATIVE_SESSION = $_COOKIE["CREATIVE_SESSION"];
	$mobile_agent = Net_UserAgent_Mobile::factory();
	CDBSession::sessionSetCookie();
	$CREATIVE_SYSTEM_DEF = array();
	$CREATIVE_SYSTEM_DEF["sessionId"] = CDBSession::sessionGetId();
	$CREATIVE_SYSTEM_DEF["userStatus"] = CDBSession::sessionGetStatus();
	$CREATIVE_SYSTEM_DEF["cookie"] = $CREATIVE;
	$CREATIVE_SYSTEM_DEF["userId"] = (int)CDBSession::sessionGetUserId();
	$foo = CDBSession::getUserName($CREATIVE_SYSTEM_DEF['userId']);
	$CREATIVE_SYSTEM_DEF["username"] = $foo['username'];

	// XXX: IMPORTANT - check bootstrap.php
	$CREATIVE_SYSTEM_DEF["protocol"] = CSettings::$HTTP_PROTOCOL;

	if(substr($_SERVER['REQUEST_URI'], 0, 4) == '/en/') $CREATIVE_SYSTEM_DEF["lang"] = 'en';
	else if(substr($_SERVER['REQUEST_URI'], 0, 4) == '/ja/') $CREATIVE_SYSTEM_DEF["lang"] = 'ja';
	else if(substr($_SERVER['REQUEST_URI'], 0, 4) == '/bn/') $CREATIVE_SYSTEM_DEF["lang"] = 'bn';
	else $CREATIVE_SYSTEM_DEF["lang"] = 'en';
	
?>