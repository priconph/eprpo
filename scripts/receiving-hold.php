<?php
	include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include('../config/functions.php');
    include("email-class.php");

if(isset($_POST['HoldCancellation'])){
		if($_POST['HoldCancellation']=='Fkjhs4lkh4s!o3Iah$Uh#O@i2aoa4ho@'){

			$ponumber = escapeString($_POST['ponumber']);
			$reason =  escapeString($_POST['reason']);
			$now = date("Y-m-d H:i:s");
			$date = formatDate2($now);

			$hir = query("update hold_invoice_remarks set cancel_remarks ='$reason', cancelled_by = '".USERID."', lastupdate = '$now'
			 where reference_po_number ='$ponumber'");

			$rs = query("update purchase_order_header set order_status ='UNSERVED' where order_number='$ponumber'");


			$assigned_to = '';
			// $rs = query("select * from hold_invoice_remarks where reference_po_number='$ponumber'");
			$rs = query("select * from purchase_order_header where order_number='$ponumber'");
			while ($obj=fetch($rs)) {
				$assigned_to = $obj->assigned_to;
			}

			$holdby = getUserName($assigned_to);
			$holdbyemail = getUserEmail($assigned_to);

			$prevapprovers = getPoApprovers($ponumber);
			$prevapproversemail = array();
			$cancelledbyemail = getUserEmail(USERID);
			$cancelledbyname = getUserName(USERID);

			if(!in_array(USERID, $prevapprovers)&&USERID!=$holdby){
				array_push($prevapprovers, USERID);
			}
			for($i=0;$i<count($prevapprovers);$i++){
				array_push($prevapproversemail, getUserEmail($prevapprovers[$i]));
			}
			$emailsuccess = $emailClass->sendNotification($holdbyemail,
															$holdby,
															'EPRPO - Hold PO Cancellation',
														  	holdCancellationMessage($ponumber,$reason,$cancelledbyname,$date),							
														  	holdCancellationMessage($ponumber,$reason,$cancelledbyname,$date),
															array('rdvalenzuela@pricon.ph','mdgodoy@pricon.ph','amnueva@pricon.ph'));


			echo 'success';

		}
	}

?>