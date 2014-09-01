<?php

    include_once 'bootstrap.php';
    include_once 'CDBSession.php';
    include_once 'CDBUser.php';
    
    if(
         ALLOWED_REFERRER !== '' && (!isset($_SERVER['HTTP_REFERER']) || strpos(strtoupper($_SERVER['HTTP_REFERER']),
         strtoupper(ALLOWED_REFERRER)) === false)
    ){
        die("Internal server error. Please contact system administrator.");
    }
    
    global $CREATIVE_SYSTEM_DEF;
    
    // XXX: IMPORTANT - Only allow user who are logged in to view this page
    CDBSession::validateUser();
    
    $CREATIVE_USER_DEF    = CDBUser::getUserDetails($CREATIVE_SYSTEM_DEF['userId']);
    $profilePicture       = CSettings::$BASE_DIRECTORY . '/cdn/' . $CREATIVE_USER_DEF['profilePicture'];
    $typeConstant         = exif_imagetype($profilePicture);
    
    header('Content-Type:' . image_type_to_mime_type($typeConstant));
    header('Content-Length: ' . filesize($profilePicture));
    readfile($profilePicture);
    