<?php
include("../config/connection.php");

    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");

    include("../config/db-function.php");

if(isset($_POST['action'])){
	$id = escapeString($_POST['id']);
	$code=escapeString($_POST['code']);
	$desc=escapeString($_POST['desc']);
	$prefix=escapeString($_POST['prefix']);
	$numseries=escapeString($_POST['numseries']);
	
	$loginuser =$userid;
	$date = date('Y-m-d H:i:s');

	$query = "update transaction_type set transaction_type_code='$code', transaction_description='$desc',prefix='$prefix', next_number_series='$numseries', updated_by_user_id='$loginuser',updated_date='$date' where id='$id'";
	$rs = query($query);
	$response=($rs==true)?"success":mysql_error();
	echo $response;
}



?>