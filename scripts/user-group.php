<?php

include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include('../config/functions.php');
    //include("../config/db-function.php");
	if(isset($_POST['action'])){
		$group_name = escapeString(strtoupper($_POST['groupname']));
		$loginuser =$userid;
		$date = date('Y-m-d H:i:s');
		$query = "insert into user_group(id,user_group_name, created_by_user_id, created_date) values(null,'$group_name','$loginuser','$date')";
		if($_POST['action']=='edit'){
			$id = escapeString($_POST['id']);
			$query = "update user_group set user_group_name='$group_name', updated_by_user_id='$loginuser', updated_date='$date' where id='$id'";
		}
		$rs = query($query);
		$response=($rs==true)?"success":mysql_error();
		echo $response;
	}

	if(isset($_POST['deleteselected'])){
		$idarray = $_POST['id'];
		foreach ($idarray as $id) {
			$id = escapeString($id);
			$query = "delete from user_group where id='$id'";
			$rs = query($query);
		}
		echo "success";
	}


?>