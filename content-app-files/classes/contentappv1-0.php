<?php
   /*
    * This is the main class which has been used to work with all the pages in the app. 
    * You will see various functions that are called for the pages under V1-0 folder. 
    * All are listed in this class.
    */

    require_once('SimpleConfig.php');
    require_once('mysqldatabase.php');
    require_once('mysqlresultset.php');

    SimpleConfig::setFile('PATH_TO_YOUR_CONFIG_FILE');

    class ContentApp {

        // database connection resource
        public $connection;
        // database name
        public $db;
        // database configuration resource
        public $config;
        
        // static instance for singleton pattern
        private static $instance;
        
         /**
         * Operation/Execution SUCCESSFUL/FAILED
         */
        
        const QUERY_FAILED = 000;
        const QUERY_SUCCEEDED = 001; 
        
        /**
         * constant vairables # for # custom error response
         * 
         
         * in PHP const vairable don't start using $
         */
        // if parameter not passed
        const PARAMETER_NOT_DEFINED = 011;
        // if unique user id not passed
        const UID_NOT_DEFINED = 012; 
        // if unique user id is wrong
        const UID_NOT_VALID = 013; 
        // if not accessed through mobile
        const USER_AGENT_NOT_VALID = 014; 
        // if not accessed through mobile
        const APP_VERSION_NOT_DEFINED = 015;
        // if appId is not defined
        const APP_ID_NOT_DEFINED = 016;
        // if public key of encryption not defined
        const ENCRYPTION_PUBLIC_KEY_NOT_DEFINED = 017;
        // if public key of encryption not defined
        const ENCRYPTION_KEY_VERSION_NOT_DEFINED = 018;
        // not allowed to pass appId
        const NOT_ALLOWED_TO_PASS_APP_ID = 019;
        // not allowed to pass appId
        const KEY_VERSION_DOES_NOT_MATCH_WITH_PUBLIC_KEY = 020;

        private function __construct(){
            $this->config = SimpleConfig :: getInstance();
            $this->db = MySqlDatabase :: getInstance();
            $this->connection = $this->db->connect($this->config->host, $this->config->user, $this->config->password,$this->config->database,false);
        } // __construct

        public function closeConnection(){
            mysql_close($this->connection);
        }

        public static function getInstance(){
            if (!isset(self::$instance)) {
                self::$instance = new contentapp;
            }
            return self::$instance;
        } // getInstance
        
        public function getCategoryList($groupId){
            $sql = "SELECT * FROM tb_category where category_id IN (SELECT category_id FROM tb_category_group WHERE group_id='$groupId')";
            pc_debug("SQL = $sql",__FILE__,__LINE__);
            
            $result = $this->db->query($sql);
            $rows = array();

            while($row = mysql_fetch_array($result,MYSQLI_ASSOC)){
                array_push($rows,$row);
            }

            if(sizeof($rows) > 0){
                return $rows;
            }else {
                return false;
            }
        }
        
        
        public function getGroupList(){
            $sql = "SELECT * FROM tb_group";
            pc_debug("SQL = $sql",__FILE__,__LINE__);
            
            $result = $this->db->query($sql);
            $rows = array();

            while($row = mysql_fetch_array($result,MYSQLI_ASSOC)){
                array_push($rows,$row);
            }

            if(sizeof($rows) > 0){
                return $rows;
            }else {
                return false;
            }
        }
        
        
        public function getCategoryListLimit30($groupId, $limit)
        {
            $sql = "SELECT * FROM tb_category where category_id IN (SELECT category_id FROM tb_category_group WHERE group_id='$groupId') order by category_id desc limit $limit";
            pc_debug("SQL = $sql",__FILE__,__LINE__);
            
            $result = $this->db->query($sql);
            $rows = array();

            while($row = mysql_fetch_array($result,MYSQLI_ASSOC)){
                array_push($rows,$row);
            }

            if(sizeof($rows) > 0){
                return $rows;
            }else {
                return false;
            }  
        }
        
        public function getCategoryListLimit50($groupId, $limit)
        {
            $sql = "SELECT * FROM tb_category where category_id IN (SELECT category_id FROM tb_category_group WHERE group_id='$groupId') order by category_id desc limit $limit";
            pc_debug("SQL = $sql",__FILE__,__LINE__);
            
            $result = $this->db->query($sql);
            $rows = array();

            while($row = mysql_fetch_array($result,MYSQLI_ASSOC)){
                array_push($rows,$row);
            }

            if(sizeof($rows) > 0){
                return $rows;
            }else {
                return false;
            }  
        }
        
        public function getCategoryWiseContentAll($categoryId)
        {
            $sql = "SELECT * FROM tb_text t, tb_text_stats s where t.text_id=s.text_id AND t.text_id IN (SELECT text_id FROM tb_text_category WHERE category_id='$categoryId')";
            pc_debug("SQL = $sql",__FILE__,__LINE__);
            
            $result = $this->db->query($sql);
            $rows = array();

            while($row = mysql_fetch_array($result,MYSQLI_ASSOC)){
                array_push($rows,$row);
            }

            if(sizeof($rows) > 0){
                return $rows;
            }else {
                return false;
            }
        }     
        
        
        public function getCategoryWiseContentLimit30($categoryId, $limit)
        {
            $sql = "SELECT * FROM tb_text t, tb_text_stats s where t.text_id=s.text_id AND t.text_id IN (SELECT text_id FROM tb_text_category WHERE category_id='$categoryId') limit $limit";
            pc_debug("SQL = $sql",__FILE__,__LINE__);
            
            $result = $this->db->query($sql);
            $rows = array();

            while($row = mysql_fetch_array($result,MYSQLI_ASSOC)){
                array_push($rows,$row);
            }

            if(sizeof($rows) > 0){
                return $rows;
            }else {
                return false;
            }
        }     
        
        public function getCategoryWiseContentLimit50($categoryId, $limit)
        {
            $sql = "SELECT * FROM tb_text t, tb_text_stats s where t.text_id=s.text_id AND t.text_id IN (SELECT text_id FROM tb_text_category WHERE category_id='$categoryId') limit $limit";
            pc_debug("SQL = $sql",__FILE__,__LINE__);
            
            $result = $this->db->query($sql);
            $rows = array();

            while($row = mysql_fetch_array($result,MYSQLI_ASSOC)){
                array_push($rows,$row);
            }

            if(sizeof($rows) > 0){
                return $rows;
            }else {
                return false;
            }
        }
        
        public function increaseTextLike($text_id)
        {
           $sql = "select user_like from tb_text_stats where text_id='$text_id'";
            $numLike = $this->db->fetchOne($sql);
            
            pc_debug("Before update user_like = $numLike",__FILE__,__LINE__);
            
            if($numLike >= 0){
                $numLike++;
            }else {
                $numLike = 0;
            }
            
            $sql = "update tb_text_stats set user_like = '$numLike' where text_id='$text_id'";
            $result = $this->db->update($sql);
            
            if($result){ 
                return self::QUERY_SUCCEEDED;
            } else { 
                return self::QUERY_FAILED;
            } 
        }
        
        public function increaseTextDislike($text_id)
        {
           $sql = "select user_dislike from tb_text_stats where text_id='$text_id'";
            $numdisLike = $this->db->fetchOne($sql);
            
            pc_debug("Before update user_dislike = $numdisLike",__FILE__,__LINE__);
            
            if($numdisLike >= 0){
                $numdisLike++;
            }else {
                $numdisLike = 0;
            }
            
            $sql = "update tb_text_stats set user_dislike = '$numdisLike' where text_id='$text_id'";
            $result = $this->db->update($sql);
            
            if($result){ 
                return self::QUERY_SUCCEEDED;
            } else { 
                return self::QUERY_FAILED;
            } 
        }



        function getAppEncryptionPrivateKey ($publicKey,$keyVersion){
            
            $key = 'er7RdTPrtT80dos';
            $string = $publicKey;
            $privateKey = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
            
// $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
            
//            $sql = "select private_key from tb_app_secret_key where public_key='$publicKey' and key_version='$keyVersion'";
//            $privateKey = $this->db->fetchOne($sql);
            // pc_debug("Private Key # from DB # $privateKey",__FILE__,__LINE__);
            return $privateKey;
        }
        
//        function getAppEncryptionPrivateKey ($publicKey,$keyVersion){
//            $sql = "select private_key from tb_app_secret_key where public_key='$publicKey' and key_version='$keyVersion'";
//            $privateKey = $this->db->fetchOne($sql);
//            // pc_debug("Private Key # from DB # $privateKey",__FILE__,__LINE__);
//            return $privateKey;
//        }
        
        function getAppIdFromPublicKey ($publicKey){
            $sql = "select app_id from tb_app_secret_key where public_key='$publicKey'";
            $appId = $this->db->fetchOne($sql);
           // pc_debug("Private Key # from DB # $privateKey",__FILE__,__LINE__);
            return $appId;
        }
        
        function isKeyVersionValid($publicKey,$keyVersion){
            $privateKey = NULL;
            $sql = "select private_key from tb_app_secret_key where public_key='$publicKey' and key_version='$keyVersion'";
            $privateKey = $this->db->fetchOne($sql);
            if ($privateKey == NULL){
                return 0;
            } else {
                return 1;
            }
        }
        
                
        function EncryptRULSDK ($plainValue,$publicKey,$keyVersion){
                
                $key = $this->getAppEncryptionPrivateKey($publicKey,$keyVersion);
                $lengthOfKey = strlen($key);

                $sumKey = 0;
                
                for($countKey = 0; $countKey < $lengthOfKey; $countKey++){
                    $tmpChar = $key[$countKey];
                    $tmpAsciiValueKey = ord($tmpChar);
               //     pc_debug("AsciiValue of $tmpChar = $tmpAsciiValueKey",__FILE__,__LINE__);
                    $sumKey += ($tmpAsciiValueKey%23);
                }
               //     pc_debug("sumKey # $sumKey",__FILE__,__LINE__);
                
                $m = $sumKey%13;
                $n = ($sumKey+$m)%11;
                $z = ($sumKey+$m+$n)%7;
                $x = ($sumKey+$m+$n+$z)%5;
                $y = ($sumKey+$m+$n+$z+$x)%3;
                
               // pc_debug("value of m = $m, n = $n, z = $z, x = $x, y = $y",__FILE__,__LINE__);
                
                $tmpAsciiValue = 0;
                $tmpAsciiCharacter = "";
                $lengthOfPlainValue = strlen($plainValue);
                $encryptedValue = "";

                for($count = 0; $count < $lengthOfPlainValue; $count++){
                        $tmpAsciiValue = ord($plainValue[$count]); // ord method of PHP returns the ASCII value of a character
                 //       pc_debug("tmpAsciiValue = $tmpAsciiValue",__FILE__,__LINE__);
                        
                        $countMod = $count%5;
                        
                        if($countMod == 0){
                            $tmpAsciiValue = $tmpAsciiValue - $m;
                        } else if($countMod == 1){
                            $tmpAsciiValue -= $n;
                        } else if($countMod == 2){
                            $tmpAsciiValue -= $z; 
                        } else if($countMod == 3){
                            $tmpAsciiValue -= $x; 
                        } else {
                            $tmpAsciiValue -= $y; 
                        }
                 //       pc_debug("Changed tmpAsciiValue = $tmpAsciiValue",__FILE__,__LINE__);
                        $tmpAsciiCharacter = chr($tmpAsciiValue); // chr method of PHP returns the character of specified ASCII value
                        //$encryptedValue[$count] = $tmpAsciiCharacter;
                        $encryptedValue .= (string)$tmpAsciiCharacter;
                }
                return $encryptedValue;
        }

        /**
         * Decrypt Data of RUL SDK
         * @param string $encryptedValue
         * @return string 
         */
        function DecryptRULSDK ($encryptedValue,$publicKey,$keyVersion){    
            
                $key = $this->getAppEncryptionPrivateKey($publicKey,$keyVersion);
                $lengthOfKey = strlen($key);
                $sumKey = 0;
                
                for($countKey = 0; $countKey < $lengthOfKey; $countKey++){
                    $tmpChar = $key[$countKey];
                    $tmpAsciiValueKey = ord($tmpChar);
                    //pc_debug("AsciiValue of $tmpChar = $tmpAsciiValueKey",__FILE__,__LINE__);
                    $sumKey += ($tmpAsciiValueKey%23);
                }
                 //   pc_debug("sumKey # $sumKey",__FILE__,__LINE__);
                
                $m = $sumKey%13;
                $n = ($sumKey+$m)%11;
                $z = ($sumKey+$m+$n)%7;
                $x = ($sumKey+$m+$n+$z)%5;
                $y = ($sumKey+$m+$n+$z+$x)%3;
                
                // pc_debug("value of m = $m, n = $n, z = $z, x = $x, y = $y",__FILE__,__LINE__);
                
                $tmpAsciivalue = 0;
                $tmpAsciiCharacter = "";
                $lenghtOfEncryptedValue = strlen($encryptedValue);
                $decryptedValue = "";

                for($count = 0; $count < $lenghtOfEncryptedValue; $count++){
                        $tmpAsciiValue = ord($encryptedValue[$count]); 
                   //     pc_debug("tmpAsciiValue = $tmpAsciiValue",__FILE__,__LINE__);
                        
                        $countMod = $count%5;
                        if($countMod == 0){
                            $tmpAsciiValue += $m;
                        } else if($countMod == 1){
                            $tmpAsciiValue += $n;
                        } else if($countMod == 2){
                            $tmpAsciiValue += $z; 
                        } else if($countMod == 3){
                            $tmpAsciiValue += $x; 
                        } else {
                            $tmpAsciiValue += $y; 
                        }
                  //      pc_debug("Changed tmpAsciiValue = $tmpAsciiValue",__FILE__,__LINE__);
                        $tmpAsciiCharacter = chr($tmpAsciiValue);
                        //$decryptedValue[$count] = $tmpAsciiCharacter;
                        $decryptedValue .= (string)$tmpAsciiCharacter;
                }
                return $decryptedValue;
        }
       
        
    }  // APP Class Ends

?>
