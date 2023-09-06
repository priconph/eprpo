<?php

	include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include('../config/functions.php');
    include('email-class.php');
	$loginuser = $userid;
	$date = date('y-m-d H:i:s');


	if(isset($_POST['sdurJdSamsn'])){
		$rcvnumber = escapeString($_POST['sdurJdSamsn']);
		$loginuser = escapeString($userid);
		$rcvapprovallevel = getInfo("receiving_header","flag","where receiving_number='".$rcvnumber."'");
		$rs = approveReceiving($rcvnumber,$loginuser,$emailClass);
		$nextrevflagofrcv = getInfo("receiving_header","next_revision_series","where receiving_number='".$rcvnumber."'");
		//$purch_revision_no = getInfo("receiving_header","purch_revision_no","where receiving_number='".$rcvnumber."'");

		$remarks = escapeString($_POST['remarks']);
		$revision = getInfo("receiving_header","next_revision_series","where receiving_number='".$rcvnumber."'");

		if($rs=='true'){
			$query = "insert into receiving_approval_history(receiving_number,
																  action,
																  date,
																  user_id,
																  rcv_flag,
																  rcv_approval_flag,
																  rcv_approval_remarks,
																  revision_flag
																 ) 
														   values('$rcvnumber',
														   	      'ACCEPTED',
														   	      '$date',
														   	      '$loginuser',
														   	      '$nextrevflagofrcv',
														   	      '$rcvapprovallevel',
														   	      '$remarks',
														   	      '$revision'
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

	if(isset($_POST['adIssacluck'])){
		ignore_user_abort(true); 
		set_time_limit(0); 
		ob_start();

		$rcvnumber = $_POST['adIssacluck'];
		$reason = escapeString($_POST['rejectreason']);
		$reasonemailmsg = emailDecode($_POST['rejectreason']);
		$nextrevflagofrcv = getInfo("receiving_header","next_revision_series","where receiving_number='".$rcvnumber."'");
		$rev_ponum = $rcvnumber.'-R'.$nextrevflagofrcv;

		// $prnumber = getInfo("receiving_header","reference_requisition_number","where receiving_number='".$rcvnumber."'");
		// $prrev = getInfo("purchase_requisition_header","next_revision_series","where requisition_number='".$prnumber."'");
		// $rev_prnum = $prnumber.'-R'.$prrev;
		$now = date('Y-m-d H:i:s');

		$revision = getInfo("receiving_header","next_revision_series","where receiving_number='".$rcvnumber."'");

		$rs = query("select * from receiving_header where receiving_number='$rcvnumber'");
		while($obj=fetch($rs)){
			$emailto = getUserEmail($obj->received_by);
			$emailtoname = getUserName($obj->received_by);
		}
		$rejectedby = getUserName(USERID);

		/////////////////////////////////////////////////////
		query("insert into receiving_header_revision select * from receiving_header where receiving_header.receiving_number='$rcvnumber'");
		query("insert into receiving_details_revision select * from receiving_details where receiving_details.receiving_number='$rcvnumber'");
		query("update receiving_header_revision set receiving_number='$rev_ponum' where receiving_number='$rcvnumber'");
		query("update receiving_details_revision set receiving_number='$rev_ponum' where receiving_number='$rcvnumber'");
		query("update receiving_header set next_revision_series=next_revision_series+1 where receiving_number='$rcvnumber'");
	
		$rcvapprovallevel = getInfo("receiving_header","flag","where receiving_number='".$rcvnumber."'");
		$query = "insert into receiving_approval_history(receiving_number,
														 	  action,
														 	  reason_for_rejection,
														 	  date,
														 	  user_id,
														 	  rcv_flag,
														 	  rcv_approval_flag,
														 	  rcv_approval_remarks,
															  revision_flag
														 	  ) 
													   values('$rcvnumber',
													   		  'REJECTED',
													   		  '$reason',
													   		  '$date',
													   		  '$loginuser',
													   		  '$nextrevflagofrcv', 
													   		  '$rcvapprovallevel',
													   		  '$reason',
													   		  '$revision'
													   		 )";
		$rs = query($query);
		$query = "update receiving_header set receiving_status='REJECTED', date_flag='$now' where receiving_number='$rcvnumber'";
		$rs = query($query);
		echo "success";


		$size = ob_get_length()+1; 
		header("Content-Length: $size"); 
		header('Connection: close'); 
		ob_end_flush(); 
		ob_flush(); 
		flush();
		if (session_id()) session_write_close();
		
		$prevapprovers = getRcvApprovers($rcvnumber);
		$prevapproversemail = array();
		if(!in_array(USERID, $prevapprovers)){
			array_push($prevapprovers, USERID);
		}
		for($i=0;$i<count($prevapprovers);$i++){
			array_push($prevapproversemail, getUserEmail($prevapprovers[$i]));
		}

		if($rs){
				 $emailsuccess = $emailClass->sendNotification($emailto,
				 											   $emailtoname,
				 											   'EPRPO Receiving - Rejected',
				 											   rcvRejectMessage($rcvnumber,$rejectedby,$reasonemailmsg),
				 											   rcvRejectMessage($rcvnumber,$rejectedby,$reasonemailmsg),
				 											   $prevapproversemail
				 											   ); 
		}
	}

?>

