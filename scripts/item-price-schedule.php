<?php
include("../config/connection.php");
session_start();
if(isset($_POST['action'])){
	$transactionnum=$_POST['transactionnum'];
	$supplierid = $_POST['supplierid'];
	$referencenum=$_POST['refnum'];
	$datecreated=$_POST['datecreated'];
	$datestart=$_POST['datestart'];
	$dateend=$_POST['dateend'];
	$remarks=$_POST['remarks'];
	$items=$_POST['items'];
	$prices=$_POST['prices'];
	$itemremarks=$_POST['itemremarks'];
	$length = count($items);
	
	$loginuser = $_SESSION['eojsjfwoIjeKkdnlEOi'];
	$date = date('Y-m-d H:i:s');

	$query = "insert into  item_price_schedule_header values('$transactionnum','$datecreated','$datestart','$dateend','$supplierid','$referencenum','$remarks','$loginuser','$date',NULL,NULL)";
	$rs = mysql_query($query);
	if($rs){
		for($i=0;$i<$length;$i++){
			$query = "insert into item_price_schedule_details values('$transactionnum','".$items[$i]."','".$prices[$i]."','".$itemremarks[$i]."')";
			mysql_query($query);
		}
		echo mysql_error();
	}
	else{
		echo mysql_error();
	}
	echo "success";
	

}


?>