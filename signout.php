<?php

    include_once 'bootstrap.php';
	include_once 'CDBSession.php';
	
	global $CREATIVE_SYSTEM_DEF;

	CDBSession::sessionSetCookie();
	CDBSession::sessionUnlinkUserId($CREATIVE_SYSTEM_DEF["userId"]);

	header('Location: /');
?>