<?php

    require_once '../require-files/includeClass.php';
    $contentAppObj =  ContentApp::getInstance();
    
    // 
    $textId = NULL;
    if(isset($_REQUEST['textId']) && $_REQUEST['textId']){
        $textId = $_REQUEST['textId'];
    }
    
    // include encryption here
    require_once '../require-files/encryption.php';

    // checking if the textId has been passed from the app. If not, an error has been generated from the device end, calling the instance of the app class.        
    if($textId == NULL ){
        echo json_encode(wallpaper :: PARAMETER_NOT_DEFINED);
        exit;
    }

    $result = $contentAppObj->increaseTextLike($textId);
    $jsonEncodedValue = json_encode($result);
    
    $encryptedResult = NULL;
    require_once '../require-files/encryptionResult.php';
    $contentAppObj->closeConnection();
    //echo $jsonEncodedValue;
    echo $encryptedResult;
?>
