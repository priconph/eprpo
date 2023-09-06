<?php
	include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/db-function.php");

	if(isset($_POST['action'])){
		$desc = escapeString(strtoupper($_POST['desc']));
		
		$loginuser = $userid;
		$date = date('Y-m-d H:i:s');
		$query = "insert into ship_via(ship_by,created_by_user_id,created_date) values('$desc','$loginuser','$date')";
		if($_POST['action']=='edit'){
			$id = escapeString($_POST['id']);
			$query = "update ship_via set ship_by='$desc', updated_by_user_id='$loginuser', updated_date='$date' where id='$id'";
		}
		$rs = query($query);
		if($rs){
			echo "success";
		}
		else{
			echo mysql_error();
		}
	}

	if(isset($_POST['deleteselected'])){
		$storage = array();
		$response = false;
		$idarray = $_POST['id'];
		foreach ($idarray as $id) {
			$id = escapeString($id);
			$query = "delete from ship_via where id='$id'";
			$rs = query($query);
			if(!$rs){
					$response = true;
			}
		}
		if($response==false){
			echo "success";
		}



	}


?>