<?php
	include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/db-function.php");

	if(isset($_POST['action'])){
		$code = escapeString(strtoupper($_POST['code']));
		$desc = escapeString(ucwords(strtolower($_POST['description'])));
		$days = escapeString(strtoupper($_POST['days']));
		
		$loginuser = $userid;
		$date = date('Y-m-d H:i:s');
		$query = "insert into payment_terms(payment_terms_code,description,days,created_by_user_id,created_date) values('$code','$desc','$days','$loginuser','$date')";
		if($_POST['action']=='edit'){
			$id = escapeString($_POST['id']);
			$query = "update payment_terms set payment_terms_code='$code', description='$desc', days='$days', updated_by_user_id='$loginuser', updated_date='$date' where id='$id'";
		}
		$rs = query($query);
		$response=($rs==true)?"success":mysql_error();
		echo $response;
	}

	if(isset($_POST['deleteselected'])){
		$idarray = $_POST['id'];
		foreach ($idarray as $id) {
			$id = escapeString($id);
			$query = "delete from payment_terms where id='$id'";
			$rs = query($query);
			if(!$rs){
				if(mysql_errno()=='1451'){
					echo "MYSQL ERROR:1451	Unable to delete terms with id $id.";
				}
			}
		}
		if(mysql_error()==''){
			echo "success";
		}

	}
?>