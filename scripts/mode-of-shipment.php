<?php
	include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/db-function.php");

	if(isset($_POST['action'])){
		$shipvia = escapeString($_POST['shipvia']);
		$modeofshipment = escapeString($_POST['modeofshipment']);
		
		$loginuser = $userid;
		$date = date('Y-m-d H:i:s');
		$query = "insert into mode_of_shipment(description,via,created_by_user_id,created_date) values('$modeofshipment','$shipvia','$loginuser','$date')";
		if($_POST['action']=='edit'){
			$id = escapeString($_POST['id']);
			$query = "update mode_of_shipment set description='$modeofshipment', via='$shipvia', updated_by_user_id='$loginuser', updated_date='$date' where id='$id'";
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
			$query = "delete from mode_of_shipment where id='$id'";
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