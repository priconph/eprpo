<?php

	include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include('../config/functions.php');
    include('email-class.php');
	$loginuser = $userid;
	$date = date('y-m-d H:i:s');


	if(isset($_POST['sdfnrworthdf'])){
		$ponumber = escapeString($_POST['sdfnrworthdf']);
		$loginuser = escapeString($userid);
		$poapprovallevel = getInfo("purchase_order_header","flag","where order_number='".$ponumber."'");
		$rs = approvePo($ponumber,$loginuser,$emailClass);
		
		$nextrevflagofpo = getInfo("purchase_order_header","next_revision_series","where order_number='".$ponumber."'");
		$purch_revision_no = getInfo("purchase_order_header","purch_revision_no","where order_number='".$ponumber."'");

		$remarks = escapeString($_POST['remarks']);
		$revision = getInfo("purchase_order_header","next_revision_series","where order_number='".$ponumber."'");

		if($rs=='true'){
			$query = "insert into purchase_order_approval_history(order_number,
																  action,
																  date,
																  user_id,
																  po_flag,
																  po_approval_flag,
																  po_approval_remarks,
																  revision_flag,
																  purch_revision_no
																 ) 
														   values('$ponumber',
														   	      'APPROVED',
														   	      '$date',
														   	      '$loginuser',
														   	      '$nextrevflagofpo',
														   	      '$poapprovallevel',
														   	      '$remarks',
														   	      '$revision',
														   	      '$purch_revision_no'
														   	     )";
			$rs = query($query);
			if(!$rs){
				echo $rs;
			}
			else{
				echo "success";
			}
		}
	}

	if(isset($_POST['rKdneJcTlsj'])){
		ignore_user_abort(true); 
		set_time_limit(0); 
		ob_start();

		$ponumber = $_POST['ponum'];
		$reason = escapeString($_POST['rejectreason']);
		$reasonemailmsg = emailDecode($_POST['rejectreason']);
		$nextrevflagofpo = getInfo("purchase_order_header","next_revision_series","where order_number='".$ponumber."'");
		$purch_revision_no = getInfo("purchase_order_header","purch_revision_no","where order_number='".$ponumber."'");
		$rev_ponum = $ponumber.'-R'.$nextrevflagofpo.'.'.$purch_revision_no;

		$prnumber = getInfo("purchase_order_header","reference_requisition_number","where order_number='".$ponumber."'");
		$prrev = getInfo("purchase_requisition_header","next_revision_series","where requisition_number='".$prnumber."'");
		$rev_prnum = $prnumber.'-R'.$prrev;
		$now = date('Y-m-d H:i:s');

		

		$revision = getInfo("purchase_order_header","next_revision_series","where order_number='".$ponumber."'");

		$rs = query("select * from purchase_order_header where order_number='$ponumber'");
		while($obj=fetch($rs)){
			$emailto = getUserEmail($obj->posted_by);
			$emailtoname = getUserName($obj->posted_by);
		}
		$rejectedby = getUserName(USERID);

		/////////////////////////////////////////////////////
		query("insert into purchase_order_header_revision select * from purchase_order_header where purchase_order_header.order_number='$ponumber'");
		query("insert into purchase_order_details_revision select * from purchase_order_details where purchase_order_details.order_number='$ponumber'");
		query("update purchase_order_header_revision set order_number='$rev_ponum', reference_requisition_number='$rev_prnum' where order_number='$ponumber'");
		query("update purchase_order_header set purch_revision_no=purch_revision_no+1 where order_number='$ponumber'");
	
		////////////////////////////////////////////////////////
		// $poapprovallevel = getInfo("purchase_order_header","flag","where order_number='".$ponumber."'");
		// $query = "insert into purchase_order_approval_history(order_number,
														 	  // action,
														 	  // reason_for_rejection,
														 	  // date,
														 	  // user_id,
														 	  // po_flag,
														 	  // po_approval_flag,
														 	  // po_approval_remarks,
															  // revision_flag
														 	  // ) 
													   // values('$ponumber',
													   		  // 'REJECTED',
													   		  // '$reason',
													   		  // '$date',
													   		  // '$loginuser',
													   		  // '$nextrevflagofpo', //
													   		  // '$poapprovallevel',
													   		  // '$reason',
													   		  // '$revision'
													   		 // )";
		$poapprovallevel = getInfo("purchase_order_header","flag","where order_number='".$ponumber."'");
		$query = "insert into purchase_order_approval_history(order_number,
														 	  action,
														 	  reason_for_rejection,
														 	  date,
														 	  user_id,
														 	  po_flag,
														 	  po_approval_flag,
														 	  po_approval_remarks,
															  revision_flag,
															  purch_revision_no
														 	  ) 
													   values('$ponumber',
													   		  'REJECTED',
													   		  '$reason',
													   		  '$date',
													   		  '$loginuser',
													   		  '$nextrevflagofpo', 
													   		  '$poapprovallevel',
													   		  '$reason',
													   		  '$revision',
													   		  '$purch_revision_no'
													   		 )";
		$rs = query($query);
		$query = "update purchase_order_header set order_status='REJECTED', date_flag='$now' where order_number='$ponumber'";
		$rs = query($query);
		echo "success";


		$size = ob_get_length()+1; 
		header("Content-Length: $size"); 
		header('Connection: close'); 
		ob_end_flush(); 
		ob_flush(); 
		flush();
		if (session_id()) session_write_close();
		 $purchasinghead = getInfo("purchase_order_configuration","purchasing_head","where id='1'");
		 $ccprchhead = getUserEmail(getInfo("purchase_order_configuration","purchasing_head","where id='1'"));

		$prevapprovers = getPoApprovers($ponumber);
		$prevapproversemail = array();
		if(!in_array(USERID, $prevapprovers)){
			array_push($prevapprovers, USERID);
		}
		if(!in_array($purchasinghead, $prevapprovers)){
			array_push($prevapprovers, $purchasinghead);
		}
		for($i=0;$i<count($prevapprovers);$i++){
			array_push($prevapproversemail, getUserEmail($prevapprovers[$i]));
		}

		if($rs){
				 $emailsuccess = $emailClass->sendNotification($emailto,
				 											   $emailtoname,
				 											   'EPRPO Purchase Order - Rejected',
				 											   poRejectMessage($ponumber,$rejectedby,$reasonemailmsg),
				 											   poRejectMessage($ponumber,$rejectedby,$reasonemailmsg),
				 											   $prevapproversemail
				 											   ); 
		}
	}

?>

