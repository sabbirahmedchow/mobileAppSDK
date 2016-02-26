<?php
    // important for encryption      
    $keyVersion = NULL;
    
    if(isset($_REQUEST['keyVersion']) && $_REQUEST['keyVersion']){
        $keyVersion = $_REQUEST['keyVersion'];
    }
    
    if($keyVersion == NULL){
        echo json_encode(contentapp::ENCRYPTION_KEY_VERSION_NOT_DEFINED);
        exit;
    }
    
    // important for encryption 
    $publicKey = NULL;
    
    if(isset($_REQUEST['publicKey']) && $_REQUEST['publicKey']){
        $publicKey = $_REQUEST['publicKey'];
    }
    
    if($publicKey == NULL){
        echo json_encode(contentapp::ENCRYPTION_PUBLIC_KEY_NOT_DEFINED);
        exit;
    }
?>