<?php
	ob_start();
	include('mail_body.php');
	$body = ob_get_clean();	
?>