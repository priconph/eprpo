<?php
	$loggedin = true;
	$maindir = str_replace(' ','%20',str_replace($_SERVER['DOCUMENT_ROOT'].'/', '', $_SERVER['SCRIPT_FILENAME']));
 	$maindir = substr($maindir, 0,strpos($maindir, '/')); 
 	$goto = 'http://'.$_SERVER['HTTP_HOST'].'/'.$main_dir.'/';
	@session_start();
	if(!isset($_SESSION['jhsgfhjsdgfhjnseuiyiu+-kjerowjn.oruewilsdkfjnojwoieriojsld'])||!isset($_SESSION['zfie@#MKLDFi934OPedieo'])){
		//header('location:'.$goto);
		$loggedin = false;

	}
	else{
		$n = $_SESSION['jhsgfhjsdgfhjnseuiyiu+-kjerowjn.oruewilsdkfjnojwoieriojsld'];
		$w = $_SESSION['zfie@#MKLDFi934OPedieo'];

		$querylog = "select * from user where username='$n' and password='$w'";
		$logrs = mysql_query($querylog);
		$logged = mysql_num_rows($logrs);
		if($logged!=1){
			
			$loggedin = false;
			unset($_SESSION['jhsgfhjsdgfhjnseuiyiu+-kjerowjn.oruewilsdkfjnojwoieriojsld']);
			unset($_SESSION['zfie@#MKLDFi934OPedieo']);
			//header('location:'.$goto);
		}
	}

?>
<script type="text/javascript">
				var loggedin = <?php echo json_encode($loggedin); ?>;
				if(loggedin=='false'||loggedin==false||loggedin==0){
					window.location.href = '../index.php?0jksleo03lDnlei=1&sessionExpired=true';
				}
</script>