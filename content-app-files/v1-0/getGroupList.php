<?php

    require_once '../require-files/includeClass.php';
    $contentAppObj =  ContentApp::getInstance();
    
    // include encryption here
    require_once '../require-files/encryption.php';

        
    $result = $contentAppObj->getGroupList();
    $jsonEncodedValue = json_encode($result);
    
    $encryptedResult = NULL;
    require_once '../require-files/encryptionResult.php';
    $contentAppObj->closeConnection();
    //echo $jsonEncodedValue;
    echo $encryptedResult;
?>
