<?php

	include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/db-function.php");
	if(isset($_POST['action'])){
		$deptname = escapeString($_POST['deptname']);
		$code = escapeString(strtoupper($_POST['code']));
		$month = date('m');
		$loginuser = $userid;
		$date = date('Y-m-d H:i:s');
		$status = $_POST['status'];
		$query = "insert into section_department(id,section_department_code,section_department_name, month_flag, next_number_series, created_by_user_id, created_date,status) values(null,'$code','$deptname', '$month', '1', '$loginuser','$date','$status')";
		if($_POST['action']=='edit'){
			$id = mysql_real_escape_string($_POST['id']);
			$nextnum = escapeString($_POST['nextnum']);
			$query = "update section_department set section_department_code='$code', next_number_series='$nextnum', section_department_name='$deptname', updated_by_user_id='$loginuser', updated_date='$date',status='$status' where id='$id'";
		}
		$rs = query($query);
		$response=($rs==true)?"success":errornum();
		echo $response;



	}

	if(isset($_POST['deleteselected'])){
		$idarray = $_POST['id'];
		foreach ($idarray as $id) {
			$id = escapeString($id);
			$query = "delete from section_department where id='$id'";
			$rs = query($query);
			if(!$rs){
				if(mysql_errno()=='1451'){
					echo "MYSQL ERROR:1451	Unable to delete department with id $id.";
				}
			}
		}
		if(mysql_error()==''){
			echo "success";
		}

	}
?>