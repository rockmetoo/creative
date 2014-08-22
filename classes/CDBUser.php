<?php

	include_once 'CDBQuery.php';
	include_once 'CSettings.php';
	include_once 'CHelperFunctions.php';

	class CDBUser extends CDBQuery
	{
		public static function getUserDetails($userId)
		{
			global $db;
			
			$userId	= intval($userId);
			$query	= $db->quoteInto("SELECT * FROM userProfile WHERE userId=%s", $userId);
			$res	= $db->query($query);
			
			if(!$res->num_rows)
			{
				return array();
			}
			
			return $res->fetch_assoc();
		}
		
		public static function getUserType($userId)
		{
			global $CREATIVE_SYSTEM_DEF;
			global $db;
			
			$query	= "SELECT userType FROM user WHERE userId = '$userId'";
			$res	= $db->queryOther('creativeUser', $query);
				
			$foo = $res->fetch_assoc();
			
			$CREATIVE_SYSTEM_DEF['userType'] = $foo['userType'];
			
			return $foo['userType'];
		}
		
		public static function getUserQuestionStatus()
		{
			global $db;
			
			return false;
		}
		
		/**
		 * Sets employer details
		 *
		 * @param int $employer_id
		 * @param array $changes
		 * @param bool $updateChangesOnly
		 * @return int $employer_id
		 */
		public static function setUserBasicProfile($userId, $changes, $updateChangesOnly = false)
		{
			global $db;
			
			$userId = intval($userId);
			
			if(empty($changes['firstName']) || empty($changes['lastName']) || empty($changes['country']))
			{
				$changes['isProfileUpdate'] = 0;
			}
			else
			{
				$changes['isProfileUpdate'] = 1;
			}
			
			// clean the array (so no other post values accidently slip through)
			$changes = array_intersect_key($changes, array_flip(array(
				'firstName',
				'lastName',
				'postCode',
				'cityOrDistrict',
				'stateOrDivision',
				'address',
				'isProfileUpdate',
				'dateCreated',
				'dateUpdated'
			)));
			
			if($userId && $updateChangesOnly)
			{
				// remove changes that do not need updating.
				$user = CDBUser::getUserDetails($userId);
				
				if(count($user))
				{
					foreach($changes as $k=>$v)
					{
						if($user[$k] == $v) unset($changes[$k]);
					}
				}
			}

			if(count($changes))
			{
				$changes['dateUpdated'] = $changes['dateCreated'] = date('Y-m-d H:i:s');
				if($userId)
				{
					$changes['userId'] = $userId;
					$db->duplicateRemovePrimary('userProfile', $changes, array('userId', 'dateCreated'), false);
				}
				else
				{
					$userId = $db->insert('userProfile', $changes, false);
				}
			}

			return $userId;
		}
		
		public static function setPassword($oldPassword, $newPassword, $reNewPassword)
		{
			global $db;
			global $CREATIVE_SYSTEM_DEF;
			
			if($newPassword === $reNewPassword)
			{
				include_once 'CDBSignIn.php';
				
				$password = CDBSignIn::getPassword($CREATIVE_SYSTEM_DEF['userId']);
				
				if(md5($oldPassword) === $password)
				{
					$db->updateOther('creativeUser', 'user', 'userId', $CREATIVE_SYSTEM_DEF['userId'],
						array(
							'password' => md5($newPassword),
							'lastChangedPassword' => date('Y-m-d H:i:s')
						)
					);
					
					return array(1, "New password successfully changed. Hurray...");
				}
				else
				{
					return array(0, "Old password not matched!");
				}
			}
			else
			{
				return array(0, "New password and Re-type new password are not same!");
			}
		}
		
		public static function setSpecificUserSettings($userid, $user_status)
		{
			global $db;
			global $CREATIVE_SYSTEM_DEF;
			
			if($user_status === 1)
			{
				$db->updateOther('creativeUser', 'user', 'userId', $userid,
					array(
						'userStatus' => $user_status,
						'failedLoginCount' => 0,
						'deactivatedDate' => date('Y-m-d H:i:s'),
						'deactivatedBy' => $CREATIVE_SYSTEM_DEF['userId'],
						'userStatusChangeDate' => date('Y-m-d H:i:s'),
						'dateUpdated' => date('Y-m-d H:i:s')
					)
				);
			}
			
			// If inactive then also remove session data immidiately
			if($user_status === 3)
			{
				$db->updateOther('creativeUser', 'user', 'userId', $userid,
					array(
						'userStatus' => $user_status,
						'deactivatedDate' => date('Y-m-d H:i:s'),
						'deactivatedBy' => $CREATIVE_SYSTEM_DEF['userId'],
						'userStatusChangeDate' => date('Y-m-d H:i:s'),
						'dateUpdated' => date('Y-m-d H:i:s')
					)
				);
				
				$db->updateOther('creativeUser', 'systemSession', 'userId', $userid,
					array(
						'userStatus'	=> $user_status,
						'userId'		=> 0
					)
				);
			}
		}
		
		/**
		 * Get company name
		 *
		 * @param int $employer_id
		 * @param string $lang
		 * @return string
		 */
		public static function getUserName($userId)
		{
			global $db;
			$userId = intval($userId);
			$query = $db->quoteInto("SELECT firstName, lastName FROM userProfile WHERE userId = %s", $userId);
			$res = $db->query($query);
			
			if($res->num_rows)
			{
				$foo = $res->fetch_assoc();
				return $foo['firstName'] . ' ' . $foo['lastName'];
			}
			
			return ' ';
		}
		
		public static function getSignInAs()
		{
			global $CREATIVE_SYSTEM_DEF;
			global $db;
			
			$username = self::getUserName($CREATIVE_SYSTEM_DEF['userId']);
			
			return ($username == ' ') ? $CREATIVE_SYSTEM_DEF['username'] : $username;
		}
		
		public static function onlyAccessByUser()
		{
			global $CREATIVE_SYSTEM_DEF;
			
			if($CREATIVE_SYSTEM_DEF['userType'] == 1)
			{
				// Don't do anything
			}
			else if($CREATIVE_SYSTEM_DEF['userType'] == 2)
			{
				header('location: expertDashboard.php');
				exit;
			}
			else if($CREATIVE_SYSTEM_DEF['userType'] == 3)
			{
				header('location: adminDashboard.php');
				exit;
			}
		}
		
		public static function setUserProfile($userId)
		{
			global $db;
			
			$userId = intval($userId);
			
			$db->duplicateRemovePrimary('userProfile', array(
				'userId' => $userId,
				'isProfileUpdate' => 0,
				'dateCreated'=>date('Y-m-d H:i:s'),
				'dateUpdated'=>date('Y-m-d H:i:s')), 'userId'
			);
		}
	}
?>