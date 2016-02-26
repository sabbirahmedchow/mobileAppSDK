<?php

    require_once '../require-files/includeClass.php';
    $contentAppObj =  ContentApp::getInstance();
    
    // 
    $categoryId = NULL;
    if(isset($_REQUEST['categoryId']) && $_REQUEST['categoryId']){
        $categoryId = $_REQUEST['categoryId'];
    }
    
    // include encryption here
    require_once '../require-files/encryption.php';

    // checking if the categoryId has been passed from the app. If not, an error has been generated from the device end, calling the instance of the app class.    
    if($categoryId == NULL ){
        echo json_encode(wallpaper :: PARAMETER_NOT_DEFINED);
        exit;
    }

    $result = $contentAppObj->getCategoryWiseContentAll($categoryId);
    $jsonEncodedValue = json_encode($result);
    
    $encryptedResult = NULL;
    require_once '../require-files/encryptionResult.php';
    $contentAppObj->closeConnection();
    //echo $jsonEncodedValue;
    echo $encryptedResult;
?>
