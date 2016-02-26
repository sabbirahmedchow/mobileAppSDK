<?php

    require_once '../require-files/includeClass.php';
    $contentAppObj =  ContentApp::getInstance();
    
    // 
    $groupId = NULL;
    if(isset($_REQUEST['groupId']) && $_REQUEST['groupId']){
        $groupId = $_REQUEST['groupId'];
    }
    
    // include encryption here
    require_once '../require-files/encryption.php';

     // checking if the groupId has been passed from the app. If not, an error has been generated from the device end, calling the instance of the app class.    
    if($groupId == NULL ){
        echo json_encode(wallpaper :: PARAMETER_NOT_DEFINED);
        exit;
    }

    $result = $contentAppObj->getCategoryListLimit30($groupId, 30);
    $jsonEncodedValue = json_encode($result);
    
    $encryptedResult = NULL;
    require_once '../require-files/encryptionResult.php';
    $contentAppObj->closeConnection();
    //echo $jsonEncodedValue;
    echo $encryptedResult; 
?>
