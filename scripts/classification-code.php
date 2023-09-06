<?php

	include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/db-function.php");
	if(isset($_POST['action'])){
		$acct_name = escapeString($_POST['acct_name']);
		$code = escapeString(strtoupper($_POST['code']));
		$month = date('m');
		$loginuser = $userid;
		$date = date('Y-m-d H:i:s');
		$status = $_POST['status'];
		$query = "insert into classification_code(id,acct_code,acct_name, created_by_user_id, created_date,status) values(null,'$code','$acct_name', '$loginuser','$date','$status')";
		if($_POST['action']=='edit'){
			$id = mysql_real_escape_string($_POST['id']);
			$query = "update classification_code set acct_code='$code', acct_name='$acct_name', updated_by_user_id='$loginuser', updated_date='$date',status='$status' where id='$id'";
		}
		$rs = query($query);
		$response=($rs==true)?"success":errornum();
		echo $response;
	}

	if(isset($_POST['deleteselected'])){
		$idarray = $_POST['id'];
		foreach ($idarray as $id) {
			$id = escapeString($id);
			$query = "delete from classification_code where id='$id'";
			$rs = query($query);
			if(!$rs){
				if(mysql_errno()=='1451'){
					echo "MYSQL ERROR:1451	Unable to delete classification code with id $id.";
				}
			}
		}
		if(mysql_error()==''){
			echo "success";
		}

	}
?>