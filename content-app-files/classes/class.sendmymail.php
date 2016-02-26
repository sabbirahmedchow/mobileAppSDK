<?php
require_once('class.phpmailer.php');
class SendMyMail extends PHPMailer{
    public function send_my_mail($subject,$body,$sendTo = null){
        error_log("SendTo = $sendTo");

        $mail             = new PHPMailer();

        $mail->IsSMTP(); // telling the class to use SMTP
//        $mail->Host       = "mail.yourdomain.com"; // SMTP server
//        $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
//                                                   // 1 = errors and messages
//                                                   // 2 = messages only
//        $mail->SMTPAuth   = true;                  // enable SMTP authentication
//        $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
//        $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
//        $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
//        $mail->Username   = "mahbub.facebook@gmail.com";  // GMAIL username
//        $mail->Password   = "sOrinJK89Fahim";            // GMAIL password
        
        
        // Advanced setup with fall-back SMTP Server
        $mail->Host = "122.99.96.155";
        $mail->Port = 25;
        $mail->User = "rul";
        $mail->Password = "RUL";

        $mail->SetFrom('info@riseuplabs.com', 'Rise Up Labs');
        $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

        $address = "";
        
        if($sendTo != null){
            $address = $sendTo;
        } 
        
//        error_log("mail address is =$address");
        
        // $mail->AddAddress($address, "Mahbubur Rahman");
        $mail->AddAddress($address);
        
//        error_log("Subject is = $subject");
//        error_log("Body is = $body");
        try {
//            error_log("Subject is (2nd Time) = $subject");
            $mail->Subject = (string)$subject;
//            error_log("Subject is (3rd Time)= $subject");
            $mail->MsgHTML($body);
            $mail->Send();  
        } catch (phpmailerException $e) {
            echo $e->errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            echo $e->getMessage(); //Boring error messages from anything else!
        }
    }
}

//
////include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded
//
//$mail             = new PHPMailer();
//
////$body             = file_get_contents('contents.html');
////$body             = eregi_replace("[\]",'',$body);
//
////$body = "Problem Occured";
//
//$mail->IsSMTP(); // telling the class to use SMTP
//$mail->Host       = "mail.yourdomain.com"; // SMTP server
//$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
//                                           // 1 = errors and messages
//                                           // 2 = messages only
//$mail->SMTPAuth   = true;                  // enable SMTP authentication
//$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
//$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
//$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
//$mail->Username   = "mahbub.facebook@gmail.com";  // GMAIL username
//$mail->Password   = "sOrinJK89Fahim";            // GMAIL password
//
//$mail->SetFrom('mahbub.facebook@gmail.com', 'Mahbub Aaman');
//
////$mail->AddReplyTo("name@yourdomain.com","First Last");
//
////$mail->Subject    = "PHPMailer Test Subject via smtp (Gmail), basic";
//
//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
//
////$mail->MsgHTML($body);
//
//$address = "mahbubur@riseuplabs.com";
//$mail->AddAddress($address, "Mahbubur Rahman");
//
////$mail->AddAttachment("images/phpmailer.gif");      // attachment
////$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
//
////if(!$mail->Send()) {
////  echo "Mailer Error: " . $mail->ErrorInfo;
////} else {
////  echo "Message sent!";
////}
//
//function send_my_mail ($subject,$body){
//    error_log("Subject is = $subject");
//    error_log("Body is = $body");
//    try {
//        error_log("Subject is (2nd Time) = $subject");
//        $mail->Subject = (string)$subject;
//        error_log("Subject is (3rd Time)= $subject");
//        $mail->MsgHTML($body);
//        $mail->Send();  
//    } catch (phpmailerException $e) {
//        echo $e->errorMessage(); //Pretty error messages from PHPMailer
//    } catch (Exception $e) {
//        echo $e->getMessage(); //Boring error messages from anything else!
//    }
//
//}
////public function send_my_mail($body){
////    
////}
?>
