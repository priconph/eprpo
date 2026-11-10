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
			
			// $query = "insert into purchase_order_appr
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

			// /* 04062025 by Nessa */
			// $getlastbirnumber = '';
			// // $getlastbirnumber = query("SELECT order_number, order_date, bir_po_number FROM `purchase_order_header` ORDER BY `order_date` DESC LIMIT 1");
			// // $getlastbirnumber = query("SELECT order_number, order_date, bir_po_number FROM `purchase_order_header` WHERE order_number='$ponumber' ORDER BY `order_date` DESC LIMIT 1");
			// $getlastbirnumber = query("SELECT order_number, order_date, bir_po_number FROM purchase_order_header 
			// WHERE order_date < (SELECT order_date FROM purchase_order_header WHERE order_number = '$ponumber')
			// AND bir_po_number != '' 
			// ORDER BY order_date 
			// DESC LIMIT 1"
			// );
			
			// // print_r(gettype($getlastbirnumber));
			// // exit();
			
			// while($obj=fetch($getlastbirnumber)){	
			// 	$bir_number 			= $obj->bir_po_number;
			// 	$previousOrderNumber 	= $obj->order_number;
		
			// 	$counter = 0000000000;
			// 	if($bir_number != null || $bir_number != ''){
			// 		$ctrl_no = $bir_number;
			// 		$counter = (intval($ctrl_no) + 1);
			// 	}else{
			// 		$counter = 1;
			// 	}

			// 	// print_r($counter);
			// 	// exit();

			// 	$bir_po_number = str_pad($counter, 10, "0", STR_PAD_LEFT);
			// 	// print_r ($bir_po_number);
			// 	// exit();
			// 	$updateQuery = "UPDATE purchase_order_header 
			// 						SET bir_po_number = '$bir_po_number' 
			// 						where order_number = '$ponumber'

			// 						";
			// 	$updateBirCounter = query($updateQuery);
			// 	if(!$updateBirCounter){
			// 		echo $updateBirCounter;
			// 	}

			// }



			
				// else{
				// 	echo "Update BIR Number Success!";
				// } 
			
			/* while($obj=fetch($getlastbirnumber)){
				$birLastCounter = $obj->bir_po_number;
				$ponumber = $obj->order_number;
				$orderdate = $obj->order_date;

				if($birLastCounter != '' ){
					$lastZeroPos = strrpos($birLastCounter, '0');
					if ($lastZeroPos !== false) {
						// Get the substring starting from the position after the last '0'
						$lastCounter = substr($birLastCounter, $lastZeroPos + 1);
						$nextCounter = $lastCounter + 1;
						
						// echo "The characters next to the last '0' are: " . $lastCounter;
					}
					
				}else{
					// $nextCounter = 1;
					$nextCounter = "PO".$birLastCounter . str_pad(1, 10, '0', STR_PAD_LEFT);
				}
			}

			$updateQuery = "UPDATE purchase_order_header 
								SET bir_po_number = '$nextCounter' where order_number = '$ponumber'

								";
				$updateBirCounter = query($updateQuery);
				if(!$updateBirCounter){
					echo $updateBirCounter;
				}
				else{
					// echo "Update BIR Number Success!";
				}  */
			
			/* 10012024 by Darren */
			// $checkApprovedCount = query("SELECT * FROM `purchase_order_approval_history` WHERE `order_number`='$ponumber'");
			// $approvedCount = getNumRows($checkApprovedCount);
			
			// $toApprovedCount = query("SELECT 
			// 						first_approver as first_approver,
			// 						second_approver as second_approver,
			// 						third_approver as third_approver
			// 						FROM purchase_order_header
			// 						WHERE order_number = '$ponumber'
			// 						");

			// $toApprovedCounter = 0;
			// while($obj=fetch($toApprovedCount)){
			// 	$f_approver = $obj->first_approver;
			// 	$s_approver = $obj->second_approver;
			// 	$t_approver = $obj->third_approver;
			// 	if($f_approver != '' ){
			// 		$toApprovedCounter+=1;
			// 	}
			// 	if($s_approver != '' ){
			// 		$toApprovedCounter+=1;
			// 	}
			// 	if($t_approver != '' ){
			// 		$toApprovedCounter+=1;
			// 	}
			// }

			// if($toApprovedCounter == $approvedCount){
				$previousBIR = query("SELECT *
                      FROM `purchase_order_header`
                      WHERE `bir_po_number` != ''
                      OR `bir_po_number` IS NOT NULL
                      ORDER BY `bir_po_number` DESC
                      LIMIT 1");

				// var_dump($previousBIR);
				// exit();

				if (!$previousBIR) {
					die("Query failed: " . mysqli_error($conn));
				}
				
				// Fetch and print the data
				$counter = "0000000000"; // default
				if ($previousBIR && $row = fetch($previousBIR)) {
					$previousCounter = $row->bir_po_number;
					$counter = intval($previousCounter) + 1;
				} else {
					$counter = 1; // no previous row
				}

				$nextBirCounter = str_pad($counter, 10, "0", STR_PAD_LEFT);

				$updateQuery = "UPDATE purchase_order_header 
								SET bir_po_number = '$nextBirCounter'
								WHERE order_number = '$ponumber'

								";
				$updateBirCounter = query($updateQuery);
				if(!$updateBirCounter){
					echo $updateBirCounter;
				}
				else{
					// echo "Update BIR Number Success!";
				} 
			// }
			
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

