<?php

require_once("../../includes/PHPMailer/class.phpmailer.php");

class Email{
			private $emailfrom;
			private $fromname;
			private $tlsencryption;
			
			function Email($emailfrom,$fromname,$encryption){
				$this->emailfrom = $emailfrom;
				$this->fromname = $fromname;
				$this->tlsencryption=$encryption;
			}
			
			function sendNotification($emailto,$toname,$subject,$body,$altbody,$ccrequestor){
				$mail = new PHPMailer;
				
				$mail->From = $this->emailfrom;
				$mail->FromName = $this->fromname;
				$mail->addAddress($emailto, $toname);     // Add a recipient
				for($i=0;$i<count($ccrequestor);$i++){
					$mail->addCC($ccrequestor[$i]);
				}
				
				$mail->addBCC('jgsulit@pricon.ph');
				$mail->isHTML(true);                                 

				$mail->Subject = $subject;
				$mail->Body    = $body;
				$mail->AltBody = $altbody;

				if(!$mail->send()) {
					/*
				    echo 'Message could not be sent.';
				    echo '<br>Mailer Error: ' . $mail->ErrorInfo;
				    */
				    return $mail->ErrorInfo;
				} else {
				    return true;
				}
			}
}

$query = "select email_sent_from, email_sender, encryption  from email_settings where id='1' LIMIT 0,1";
$rs = query($query);
$from = '';
$sender = '';
$encryption = '';
while($obj=fetch($rs)){
	$from = $obj->email_sent_from;
	$sender = $obj->email_sender;
	$encryption = $obj->encryption;
}
$emailClass = new Email($from,$sender,$encryption);


// $query = "select * from email_settings where id='1'";
// $rs = query($query);
// $SMTPAuth = true;
// $host = '';
// $port = '';
// $uname='';
// $pword='';
// $from = '';
// $sender = '';
// $encryption = '';
// while($obj=fetch($rs)){
// 	$host = $obj->host;
// 	$uname = $obj->username;
// 	$pword = $obj->password;
// 	$port = $obj->port;
// 	$from = $obj->email_sent_from;
// 	$sender = $obj->email_sender;
// 	$encryption = $obj->encryption;
// }
								
// $emailClass = new Email($host,$SMTPAuth,$uname,$pword,$port,$from,$sender,$encryption);





// $query = "select * from email_settings where id='1'";
// $rs = query($query);
// $SMTPAuth = true;
// $host = '';
// $port = '';
// $uname='';
// $pword='';
// $from = '';
// $sender = '';
// $encryption = '';
// while($obj=fetch($rs)){
	// $host = $obj->host;
	// $uname = $obj->username;
	// $pword = $obj->password;
	// $port = $obj->port;
	// $from = $obj->email_sent_from;
	// $sender = $obj->email_sender;
	// $encryption = $obj->encryption;
// }
								
// $emailClass = new Email($host,$SMTPAuth,$uname,$pword,$port,$from,$sender,$encryption);



// require '../resources/PHPMailer/PHPMailerAutoload.php';

// class Email{
			// private $host;
			// private $SMTPAuth;
			// private $username;
			// private $password;
			// private $port;
			// private $emailfrom;
			// private $fromname;
			// private $tlsencryption;

			// function Email($host,$SMTPAuth,$username,$password,$port,$emailfrom,$fromname,$encryption){
				// $this->host=$host;
				// $this->SMTPAuth = $SMTPAuth;
				// $this->username = $username;
				// $this->password = $password;
				// $this->port = $port;

				// $this->emailfrom = $emailfrom;
				// $this->fromname = $fromname;
				// $this->tlsencryption=$encryption;
			// }


			// function sendNotification($emailto,$toname,$subject,$body,$altbody,$ccrequestor){
				// $mail = new PHPMailer;
				// $mail->isSMTP();                                      // Set mailer to use SMTP
				// $mail->Host = $this->host;  // Specify main and backup SMTP servers
				// $mail->SMTPAuth = $this->SMTPAuth;                               // Enable SMTP authentication
				// $mail->Username = $this->username;                 // SMTP username
				// $mail->Password = $this->password;                           // SMTP password
				// $mail->SMTPSecure = $this->tlsencryption;                           // Enable TLS encryption, `ssl` also accepted
				// $mail->Port = $this->port;                                    // TCP port to connect to

				// $mail->From = $this->emailfrom;
				// $mail->FromName = $this->fromname;
				// $mail->addAddress($emailto, $toname);     // Add a recipient
				// for($i=0;$i<count($ccrequestor);$i++){
					// if($ccrequestor[$i] != 'gvelarde@pricon.ph') {
						// $mail->addCC($ccrequestor[$i]);
					// }
				// }
				
				// $mail->addBCC('ronfern@pricon.ph');
				// $mail->isHTML(true);                                  // Set email format to HTML

				// $mail->Subject = $subject;
				// $mail->Body    = $body;
				// $mail->AltBody = $altbody;

				// if(!$mail->send()) {
					// /*
				    // echo 'Message could not be sent.';
				    // echo '<br>Mailer Error: ' . $mail->ErrorInfo;
				    // */
				    // return $mail->ErrorInfo;
				// } else {
				    // return true;
				// }
			// }
// }



// $query = "select * from email_settings where id='1'";
// $rs = query($query);
// $SMTPAuth = true;
// $host = '';
// $port = '';
// $uname='';
// $pword='';
// $from = '';
// $sender = '';
// $encryption = '';
// while($obj=fetch($rs)){
	// $host = $obj->host;
	// $uname = $obj->username;
	// $pword = $obj->password;
	// $port = $obj->port;
	// $from = $obj->email_sent_from;
	// $sender = $obj->email_sender;
	// $encryption = $obj->encryption;
// }
								
// $emailClass = new Email($host,$SMTPAuth,$uname,$pword,$port,$from,$sender,$encryption);



?>