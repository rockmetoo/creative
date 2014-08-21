<?php

	require_once('CDBQuery.php');
	
	class CDBSignIn extends CDBQuery
	{
		
		public static function getCheckUsername($username)
		{
			global $db;
			
			$query = "SELECT userId FROM user WHERE username = %s";
			
			$res = $db->quotedQueryOther('creativeUser', $query, $username);
			
			if(!$res->num_rows)
			{
				return 0;
			}
			
			$foo = $res->fetch_assoc();
			
			return $foo["userId"];
		}

		public static function getLoginDetails($username, $password, $plainPassword)
		{
			global $db;
			
			$query = $db->quoteInto(
				'SELECT userId, username, userStatus, userType, loginCount, failedLoginCount, lastLogin
				, DATEDIFF(lastChangedPassword, now()) AS daysSincePasswordChange FROM
				user WHERE LOWER(username)=%s && (password=%s OR password=%s) && userStatus!=3'
				, array(mb_strtolower($username), $password, $plainPassword)
			);
			
			$res = $db->queryOther('creativeUser', $query);
			
			if(!$res->num_rows)
			{
				return 0;
			}
			
			return $res->fetch_assoc();
		}

		public static function getPassword($userId)
		{
			global $db;
			
			$userId	= intval($userId);
			$query	= $db->quoteInto("SELECT password FROM user WHERE userId = %s", $userId);
			$res	= $db->queryOther('creativeUser', $query);
			
			if(!$res->num_rows)
			{
				return 0;
			}
			
			$foo = $res->fetch_assoc();
			
			return $foo['password'];
		}

		public static function getStatus($userId)
		{
			global $db;
			
			$userId	= intval($userId);
			$query	= $db->quoteInto("SELECT userStatus FROM user WHERE userId=%s", $userId);
			$res	= $db->queryOther('creativeUser', $query);
			
			if(!$res->num_rows)
			{
				return 0;
			}
			
			$foo = $res->fetch_assoc();
			
			return $foo['userStatus'];
		}
		
		public static function getUserType($userId)
		{
			global $db;
			$userId=intval($userId);
			$query = $db->quoteInto("SELECT userType FROM user WHERE userId=%s", $userId);
			$res = $db->queryOther('creativeUser', $query);
			if(!$res->num_rows)
			{
				return 0;
			}
				
			$foo = $res->fetch_assoc();
			return $foo['userType'];
		}

		public static function getUsername($userId)
		{
			global $db;
			$userId = intval($userId);
			$query = $db->quoteInto("SELECT username FROM user WHERE userId=%s", $userId);
			$res = $db->queryOther('creativeUser', $query);
			if(!$res->num_rows)
			{
				return 0;
			}
			
			$foo = $res->fetch_assoc();
			return $foo['username'];
		}
		
		public static function getFailedLoginCount($username)
		{
			global $db;
			$query	= "SELECT failedLoginCount FROM user WHERE username = %s";
			$res	= $db->quotedQueryOther('creativeUser', $query, $username);
			
			if($res->num_rows)
			{
				$foo = $res->fetch_assoc();
				return $foo;
			}
		}
		
		public static function checkAnyTempReg($md5Hash)
		{
			global $db;
				
			$query = $db->quoteInto("
				SELECT * FROM userTemp WHERE hash = %s && active = '0' && dateCreated > DATE_SUB(NOW(), INTERVAL 2 DAY)",
				array($md5Hash)
			);
			
			$res = $db->queryOther('creativeUser', $query);
			
			if(!$res->num_rows)
			{
				return false;
			}
			else
			{
				return $res->fetch_assoc();
			}
		}

		public static function login($username, $password)
		{
			global $db;
			
			// check how many times user failed to login
			$fooFailedCount = CDBSignIn::getFailedLoginCount($username);
			
			if($fooFailedCount['failedLoginCount'] >= 5)
			{
				return $fooFailedCount;
			}
			
			$plainPassword = $password;
			
			// Generate a 32bit hash of $password
			$password	= md5($password);
			$foo		= CDBSignIn::getLoginDetails($username, $password, $plainPassword);
			
			if(isset($foo['userId']))
			{
				// Update Last Login
				CDBSignIn::setLastLogin($foo['userId'], $foo['loginCount'] + 1, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
				return $foo;
			}
			else
			{
				if($fooFailedCount['failedLoginCount'] < 5)
				{
					$query = "UPDATE user SET failedLoginCount = failedLoginCount + 1 WHERE username = %s";
					$db->quotedQueryOther('creativeUser', $query, $username);
				}

				return false;
			}
		}

		public static function setLastLogin($userId, $loginCount, $lastLoginIP, $lastLoginBrowser)
		{
			global $db;
			
			$userId = intval($userId);
			
			$db->updateOther('creativeUser', 'user', 'userId', $userId,
				array(
					'loginCount'		=> $loginCount,
					'lastLogin'			=> date('Y-m-d H:i:s'),
					'lastLoginIp'		=> $lastLoginIP,
					'lastLoginBrowser'	=> $lastLoginBrowser
				)
			);
		}

		public static function setPassword($userId, $password)
		{
			global $db;
			$userId = intval($userId);
			$db->updateOther('creativeUser', 'user', 'userId', $userId,
				array(
					'password' => md5($password),
					'lastChangedPassword'=>date('Y-m-d H:i:s')
				)
			);
		}
		
		public static function createTempUser($primaryEmail, $password, $userType, $hash)
		{
			global $db;

			$user_id = $db->insertOther('creativeUser',  'userTemp',
				array(
					'username'			=> $primaryEmail,
					'password'			=> md5($password),
					'userStatus'		=> 1,
					'userType'			=> $userType,
					'hash'				=> $hash,
					'registrationDate' => date('Y-m-d H:i:s'),
					'active'			=> 0,
					'dateCreated'		=> date("Y-m-d H:i:s"),
					'dateUpdated'		=> date("Y-m-d H:i:s")
				)
			);
			
			return $user_id;
		}
		
		public static function createUserLogin($username, $password, $userType, $loginCount, $lastLoginIP, $lastLoginBrowser)
		{
			global $db;
				
			$userId = $db->insertOther('creativeUser',  'user',
				array(
					'username'				=> $username,
					'password'				=> $password,
					'userStatus'			=> 1,
					'userType'				=> $userType,
					'registrationDate'		=> date('Y-m-d H:i:s'),
					'loginCount'			=> $loginCount,
					'lastLogin'				=> date('Y-m-d H:i:s'),
					'lastChangedPassword'	=> date('Y-m-d H:i:s'),
					'lastLoginIp'			=> $lastLoginIP,
					'lastLoginBrowser'		=> $lastLoginBrowser,
					'dateCreated'			=> date("Y-m-d H:i:s"),
					'dateUpdated'			=> date("Y-m-d H:i:s")
				)
			);
			
			return $userId;
		}
		
		public static function completedTempUserLogin($md5Hash)
		{
			global $db;

			$db->updateOther('creativeUser', 'userTemp', array('hash' => $md5Hash), 0, array('active' => 1));
		}
		
		public static function removeTempUserLogin($md5Hash)
		{
			global $db;
			
			$query = $db->quoteInto("DELETE FROM userTemp WHERE hash = %s", array($md5Hash));
			$db->queryOther('creativeUser', $query);
		}
	}
?>