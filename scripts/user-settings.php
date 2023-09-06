<?php

	include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include('../config/functions.php');


	if(isset($_POST['jdsfhBfroi'])){
		if($_POST['jdsfhBfroi']=='dsfer43@3'){
			$current = md5(escapeString($_POST['current']));
			$new = md5(escapeString($_POST['newpass']));
			$retype = md5(escapeString($_POST['retype']));

			$query = "select * from user where id='$userid' and password='$current'";
			$rs = query($query);
			if(getNumRows($rs)!=1){
				echo "<b>Current Password</b> is incorrect";
			}
			else{
				$query = "update user set password='$new' where id='$userid'";
				$rs = query($query);
				if($rs){
					$_SESSION['zfie@#MKLDFi934OPedieo'] = $new;
					echo "success";
				}
			}
		}
	}

?>

