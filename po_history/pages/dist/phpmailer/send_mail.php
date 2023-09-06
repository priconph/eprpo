<?php
require_once("../../../media/PHPMailer_SMTP/PHPMailerAutoload.php");

class SENDMAIL{
	function set_smtp_parameters(){
		$smtp_parameters				= array();
		$smtp_parameters['host'] 		= '192.168.3.254';
		$smtp_parameters['encryption'] 	= 'none';
		$smtp_parameters['username'] 	= 'issinfoservice';
		$smtp_parameters['password'] 	= 'pmi1234';
		$smtp_parameters['port'] 		= '25';
		return $smtp_parameters;
	}
	
	function email_with_no_attachment($from,$fromName,$recipient,$CCrecipient,$subject,$message){ //without attachement
		$recipientArray = explode(",",$recipient);
		$ccArray = explode(',',$CCrecipient);
		$attachmentArray = explode(',',$attachment);
		$mail = new PHPMailer();
		
		$smtp_parameters = $this->set_smtp_parameters();
		$mail->IsSMTP();
		// $mail->SMTPDebug = 1; 
		$mail->Host 		= $smtp_parameters['host'];
		$mail->SMTPAuth 	= true;
		$mail->SMTPSecure 	= $smtp_parameters['encryption'];
		$mail->Username 	= $smtp_parameters['username'];
		$mail->Password 	= $smtp_parameters['password'];
		$mail->Port 		= $smtp_parameters['port'];	
		
		$mail->From = $from;
		$mail->FromName = $fromName; //from name name that will displayed when sending mail
		
		//loop to all recipients
		$ctr = 0;
		while($ctr<count($recipientArray)){
			$mail->AddAddress($recipientArray[$ctr]);
			$ctr++;
		}
		//loop for all CC
		$ctr = 0;
		while($ctr<count($ccArray)){
			$mail->AddCC($ccArray[$ctr]);
			$ctr++;
		}
		$mail->AddBCC("ralatie@pricon.ph");
		//$mail->AddReplyTo("marlope@pricon.com", "Information"); //reply goes thru here
		$mail->WordWrap = 50;                             // set word wrap to 50 characters
		
		$mail->IsHTML(true);    // set email format to HTML
		$mail->Subject = $subject;
		$mail->Body    = $message;

		
		//validation if mail has been sent
		if(!$mail->Send())
		{
		   $msg = "Something went wrong. Your Email may not been sent."; // ../handler/handler.php is connected here
		}else{
			$msg = "Email sent!"; // ../handler/handler.php is connected here
		}	

		return $msg;	
	}
	
	function email_with_attachment($from,$fromName,$recipient,$CCrecipient,$subject,$message,$attachment){ //with attachement
		$recipientArray = explode(",",$recipient);
		$ccArray = explode(',',$CCrecipient);
		$attachmentArray = explode(',',$attachment);
		$mail = new PHPMailer();
		
		$smtp_parameters = $this->set_smtp_parameters();
		// $mail->IsSMTP();
		$mail->SMTPDebug = 1; 
		$mail->Host 		= $smtp_parameters['host'];
		$mail->SMTPAuth 	= true;
		$mail->SMTPSecure 	= $smtp_parameters['encryption'];
		$mail->Username 	= $smtp_parameters['username'];
		$mail->Password 	= $smtp_parameters['password'];
		$mail->Port 		= $smtp_parameters['port'];	
		
		$mail->From = $from;
		$mail->FromName = $fromName; //from name name that will displayed when sending mail
		
		//loop to all recipients
		$ctr = 0;
		while($ctr<count($recipientArray)){
			$mail->AddAddress($recipientArray[$ctr]);
			$ctr++;
		}
		//loop for all CC
		$ctr = 0;
		while($ctr<count($ccArray)){
			$mail->AddCC($ccArray[$ctr]);
			$ctr++;
		}
		//$mail->AddBCC("marlope@pricon.ph");
		//$mail->AddReplyTo("marlope@pricon.com", "Information"); //reply goes thru here
		$mail->WordWrap = 50;                             // set word wrap to 50 characters
		
		$ctr = 0;
		while($ctr<count($attachment)){	//reserved for attachments;
			$mail->AddAttachment($attachment[$ctr]);
			$ctr++;
		}
		
		/* example only */
		// $mail->AddAttachment('./PMI_CN_Inventory_'.date('Ymd').'.xls');        // add attachments
		
		$mail->IsHTML(true);    // set email format to HTML
		$mail->Subject = $subject;
		$mail->Body    = $message;
		
		//validation if mail has been sent
		if(!$mail->Send())
		{
		   echo "Message could not be sent. <p>";
		   echo "Mailer Error: " . $mail->ErrorInfo;
		   exit;
		}else{
			echo "Message Sent!. <p>";
		}
	}
}
?>
    