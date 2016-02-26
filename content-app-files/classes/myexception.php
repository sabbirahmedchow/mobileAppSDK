<?php
// error_reporting(E_ALL | E_STRICT);  // From PHP 5.4.0 E_STRICT is a part of E_ALL
// error_reporting(E_ALL | ~E_NOTICE);
error_reporting(E_ALL);
set_error_handler("pc_error_handler");

require_once 'class.sendmymail.php';

class NoticeException extends Exception { 
    public function __toString() {
        return  "NOTICE :: $this->message";
    }
}
 
class WarningException extends Exception { 
    public function __toString() {
        return  "WARNING :: $this->message";
    }
}

class StrictException extends Exception {
    public function __toString() {
        return  "STRICT :: $this->message";
    }
}

class UserThrownException extends Exception {
    public function __toString() {
        return "USER ERROR :: $this->message";
    }
}

class UserDepricatedException extends Exception {
    public function __toString() {
        return "USER DEPRICATED ERROR :: $this->message";
    }
}
 
class OtherErrorException extends Exception {
    public function __toString() {
        return  "OTHER ERROR :: $this->message";
    }
}

function pc_error_handler($errno,$error,$file,$line,$context) {
    $message = "[$error][FILE : $file][LINE : $line]";
    
    if($errno == E_WARNING) {
        throw new WarningException($message);
    } else if($errno == E_NOTICE) {
        throw new NoticeException($message);
    } else if($errno == E_STRICT) {
        throw new StrictException($message);
    } else if($errno == E_USER_ERROR) {
        throw new UserThrownException($message);
    } else if($errno == E_USER_DEPRECATED) {
        throw new UserDepricatedException($message);
    } else {
        throw new OtherErrorException($message);
    }
}

// turn debuggin on
define('DEBUG',0);

// generic debugging function
function pc_debug ($message,$file,$line,$level=1){
    if(defined('DEBUG') && ($level > DEBUG)){
        $message = "DEBUG :: [$message][FILE : $file][LINE : $line]"; 
        error_log($message);
    }    
}

//    require 'Doctrine/Common/ClassLoader.php';
//    $classLoader = new Doctrine\Common\ClassLoader('Doctrine', '/var/www/vhosts/riseuptest.com/subdomains/ioswallpaper/httpdocs/classes/');
//    $classLoader->register(); 
//    
//    use Doctrine\DBAL\Configuration;
//    use Doctrine\DBAL\DriverManager;

?>