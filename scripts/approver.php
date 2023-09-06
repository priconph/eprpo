<?php
    include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/db-function.php");
if(isset($_POST['action'])){
	$action = escapeString($_POST['action']);
	$user=escapeString($_POST['user']);
	$department=escapeString($_POST['department']);
	$approvaltype=escapeString($_POST['approvaltype']);
	$stat=escapeString($_POST['stat']);
	$loginuser =$userid;
	$date = date('Y-m-d H:i:s');
	$id = escapeString($_POST['id']);

	$query = "insert into approver(user_id,section_department_id,approval_type_id,created_by_user_id,created_date) values('$user','$department','$approvaltype','$loginuser','$date')";
	if($action=='edit'){
		
		$query = "update approver set user_id='$user', section_department_id='$department',approval_type_id='$approvaltype', active_flag='$stat', updated_by_user_id='$loginuser',updated_date='$date' where id='$id'";
	}
	$rs = query($query);
	$response=($rs==true)?"success":mysql_error();
	echo $response;
}

	if(isset($_POST['deleteselected'])){
		$idarray = $_POST['id'];
		foreach ($idarray as $id) {
			$id = escapeString($id);
			$query = "delete from approver where id='$id'";
			$rs = query($query);
			if(!$rs){
				if(mysql_errno()=='1451'){
					echo "MYSQL ERROR:1451	Unable to delete approver with id $id.";
				}
			}
		}
		if(mysql_error()==''){
			echo "success";
		}

	}

	if(isset($_POST['approverreactivation'])){
		if($_POST['approverreactivation']=='FKjshOs$ksnOO$93slamli#H@op!'){
			$approver = escapeString($_POST['approver']);
			query("update approver set active_flag=1 where user_id='$approver'");
			echo 'success';
		}
	}

?>