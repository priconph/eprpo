<?php
	//$loggedin = true;
	@session_start();
	if(!isset($_SESSION['jhsgfhjsdgfhjnseuiyiu+-kjerowjn.oruewilsdkfjnojwoieriojsld'])||!isset($_SESSION['zfie@#MKLDFi934OPedieo'])){
		header('location:../index.php');
		//$loggedin = false;

	}
	else{
		$n = $_SESSION['jhsgfhjsdgfhjnseuiyiu+-kjerowjn.oruewilsdkfjnojwoieriojsld'];
		$w = $_SESSION['zfie@#MKLDFi934OPedieo'];

		$querylog = "select * from user where username='$n' and password='$w'";
		$logrs = mysql_query($querylog);
		$logged = mysql_num_rows($logrs);
		if($logged!=1){
			header('location:../index.php');
			//$loggedin = false;
			unset($_SESSION['jhsgfhjsdgfhjnseuiyiu+-kjerowjn.oruewilsdkfjnojwoieriojsld']);
			unset($_SESSION['zfie@#MKLDFi934OPedieo']);
		}
	}

?>
