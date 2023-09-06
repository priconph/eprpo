<?php
	if(isset($_POST['downloadbtn'])){
		$file = $_POST['file'];
		//$filename = "../application/Attachments/$file";
		header('Content-type: application/pdf');
		header('Content-disposition:attachment; filename="'.$file.'"');
		readfile($file);

	}
?>