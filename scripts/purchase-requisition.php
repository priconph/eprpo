<?php
	include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include('../config/functions.php');
    include("email-class.php");
 
    /****** 
    	Added facshipto by Nessa 02212024
    ******/

	$allocdept =0;
	$allocbu = 0;
	$allocpl = 0;
	// echo json_encode($_POST);	
	// exit;

	if(isset($_POST['SdjfrlFBroigjRi'])){//save
		$facshipto = escapeString($_POST['facshipto']);

		$currency = escapeString($_POST['curr']);
		$status = "DRAFT";
		$requester = $userid;
		if(trim($_POST['dateneeded'])==''){
				$dateneeded = 'NULL';
		}
		else{
			$dateneeded = date('Y-m-d', strtotime($_POST['dateneeded']));
		}
		$reqdate = NULL;
		$date = date('Y-m-d H:i:s');
		$reqtype = escapeString($_POST['reqtype']);
		$remarks = escapeString($_POST['remarks']);
		$sh1 = escapeString($_POST['sh1']);
		$sh2 = escapeString($_POST['sh2']);
		// $gm = escapeString($_POST['gm']);
		$gm = 1;
		$avp = escapeString($_POST['avp']);
		$svp = escapeString($_POST['svp']);
		$pr = escapeString($_POST['pr']);
		$conf = escapeString($_POST['conf']);
		$flag = 0;
		$prnotes = escapeString($_POST['prnotes']);
		@$items = $_POST['items'];
		@$qtys = $_POST['qty'];
		@$prices = $_POST['prices'];
		@$classfns = $_POST['classfns'];
		@$suppliers = $_POST['suppliers'];
		$length = count($items);

		if($length>0){
			$prdesc = getItemInfo($items[0],"item_name");
			$prdesc = strUtfDecode($prdesc);
		}
		else{
			$prdesc = NULL;
		}

		//@$allocitem = $_POST['allocitem'];
		@$allocdept = $_POST['allocdept'];
		@$allocbu = $_POST['allocbu'];
		@$allocpl = $_POST['allocpl'];
		@$allocperc = $_POST['allocperc'];
		@$allocremarks = $_POST['allocremarks'];
		$alloclength = count($allocdept);

		$reqnum = getTransactionNumber(1);
		/*$checkreqrs = query("select * from purchase_requisition_header where requisition_number='$reqnum'");
		if($checkreqrs>0){
			$ex = true;
			while($ex==true){
				$reqnum = getTransactionNumber(1);
				$checkreqrs = query("select * from purchase_requisition_header where requisition_number='$reqnum'");
				if($checkreqrs<1){
					$ex = false;
				}
			}
		}*/

		$query = "insert into purchase_requisition_header(requisition_number,
								 						  requisition_status,
								 						  requisitioner,
								 						  requisition_date,
								 						  requisition_type,
								 						  date_needed,
								 						  justification,
								 						  section_head1,
								 						  section_head2,
								 						  conformance,
								 						  general_manager,
								 						  assistant_vice_president,
								 						  senior_vice_president,
								 						  president,
								 						  flag,
								 						  currency,
								 						  last_made_changes,
								 						  pr_notes,
								 						  pr_description,
								 						  facshipto
								 						  ) 
												   values('$reqnum',
												   	      '$status',
												   	      '$requester',
												   	      '$reqdate',
												   	      '$reqtype',
												   	      '$dateneeded',
												   	      '$remarks',
												   	      $sh1,
												   	      $sh2,
												   	      $conf,
												   	      $gm,
												   	      $avp,
												   	      $svp,
												   	      $pr,
												   	      $flag,
												   	      $currency,
												   	      '$date',
												   	      '$prnotes',
												   	      '$prdesc',
												   	      '$facshipto'
												   	  )";
		$rs = query($query);
		if($rs){

			for($i=0;$i<$length;$i++){
				$itemid = escapeString($items[$i]);
				$qty=escapeString($qtys[$i]);
				$price=escapeString($prices[$i]);
				$classfn = escapeString($classfns[$i]);
				$supplier=escapeString($suppliers[$i]);
				$query = "insert into purchase_requisition_details(requisition_number,item_id,quantity,unit_price,classification_code,supplier_id) values('$reqnum','$itemid','$qty','$price','$classfn','$supplier')";
				$rs = query($query);
				
			}

			for($i=0;$i<$alloclength;$i++){
					$aRem = escapeString($allocremarks[$i]);
					$aDept = escapeString($allocdept[$i]);
					$aBU = escapeString($allocbu[$i]);
					$aPL = escapeString($allocpl[$i]);
					$aPerc = escapeString($allocperc[$i]);
					// $query = "insert into purchase_requisition_allocation values('$reqnum','$aDept','$aPerc','$aRem')";
					$query = "insert into purchase_requisition_allocation values('$reqnum','$aDept','$aBU','$aPL','$aPerc','$aRem')";
					$rs = query($query);
					
			}

			echo $reqnum;
		}

	}

	if(isset($_POST['SKdHeKorUowAnPl'])){//submit
		$facshipto = escapeString($_POST['facshipto']);
		$currency = escapeString($_POST['curr']);
		$status = "IN REVIEW";
		$requester = $userid;
		$dateneeded = date('Y-m-d', strtotime($_POST['dateneeded']));
		$reqdate = date('Y-m-d H:i:s');
		$reqtype = escapeString($_POST['reqtype']);
		$remarks = escapeString($_POST['remarks']);
		$sh1 = escapeString($_POST['sh1']);
		$sh2 = escapeString($_POST['sh2']);
		// $gm = escapeString($_POST['gm']);
		$gm = 1;
		$avp = escapeString($_POST['avp']);
		$svp = escapeString($_POST['svp']);
		$pr = escapeString($_POST['pr']);
		$conf = escapeString($_POST['conf']);
		$flag = 0;
		$prnotes = escapeString($_POST['prnotes']); 
		$items = $_POST['items'];
		$qtys = $_POST['qty'];
		$prices = $_POST['prices'];
		$classfns = $_POST['classfns'];
		$length = count($items);
		$suppliers = $_POST['suppliers'];

		$suppliers = $_POST['suppliers']; 

		$prdesc = getItemInfo($items[0],"item_name");
		$prdesc = strUtfDecode($prdesc);

		@$allocremarks = $_POST['allocremarks'];
		$allocdept = $_POST['allocdept'];
		$allocbu = $_POST['allocbu'];
		$allocpl = $_POST['allocpl'];
		$allocperc = $_POST['allocperc'];
		// $alloclength = count($allocdept);
		$alloclength = count($allocbu);
		// print_r($allocdept);
		// print_r($allocbu);
		// print_r($allocpl);
		// print_r($alloclength);
		// exit;
		// echo $allocdept;
		// echo $allocbu;
		// echo $allocpl;
		// exit;
		if(array_sum($allocperc) != 100) {
			echo "Purchase Requisition is not properly allocated. Please check allocation summary.";
		}


		$reqnum = getTransactionNumber(1);
		/*$checkreqrs = query("select * from purchase_requisition_header where requisition_number='$reqnum'");
		if($checkreqrs>0){
			$ex = true;
			while($ex==true){
				$reqnum = getTransactionNumber(1);
				$checkreqrs = query("select * from purchase_requisition_header where requisition_number='$reqnum'");
				if($checkreqrs<1){
					$ex = false;
				}
			}
		}*/

		

			$query = "insert into purchase_requisition_header(requisition_number,
															  requisition_status,
															  requisitioner,
															  requisition_date,
															  requisition_type,
															  date_needed,
															  justification,
															  section_head1,
															  section_head2,
															  conformance,
															  general_manager,
															  assistant_vice_president,
															  senior_vice_president,
															  president,
															  flag,
															  currency,
															  last_made_changes,
															  pr_notes,
															  pr_description,
															  facshipto
															  ) 
													   values('$reqnum',
													   		  '$status',
													   		  '$requester',
													   		  '$reqdate',
													   		  '$reqtype',
													   		  '$dateneeded',
													   		  '$remarks',
													   		  $sh1,
													   		  $sh2,
													   		  $conf,
													   		  $gm,
													   		  $avp,
													   		  $svp,
													   		  $pr,
													   		  $flag,
													   		  '$currency',
													   		  '$reqdate',
													   		  '$prnotes',
													   		  '$prdesc',
													   		  '$facshipto'
													   		)";
			$rs = query($query);
			if($rs){
				$ok= true;
				for($i=0;$i<$length;$i++){
					$itemid = escapeString($items[$i]);
					$qty=escapeString($qtys[$i]);
					$price=escapeString($prices[$i]);
					$classfn = escapeString($classfns[$i]);
					$supplier = escapeString($suppliers[$i]);
					$query = "insert into purchase_requisition_details(requisition_number,item_id,quantity,unit_price,classification_code,supplier_id) values('$reqnum','$itemid','$qty','$price','$classfn','$supplier')";
					$rs = query($query);
					if(!$rs){
						echo $rs;
						$ok=false;
					}
				}
				if($ok){
					for($i=0;$i<$alloclength;$i++){
						$aRem = escapeString($allocremarks[$i]);
						$aDept = escapeString($allocdept[$i]);
						$aBU = escapeString($allocbu[$i]);
						$aPL = escapeString($allocpl[$i]);
						$aPerc = escapeString($allocperc[$i]);
						$query = "insert into purchase_requisition_allocation values('$reqnum','$aDept','$aBU','$aPL','$aPerc','$aRem')";
						$rs = query($query);
						if(!$rs){
							echo $rs;
							$ok = false;
						}
					}
					if($ok){
						$emailto = getUserEmail($sh1);
						$toname = getUserName($sh1);
						echo 'success@#$'.$reqnum.'@#$'.$emailto.'@#$'.$toname;
					}
				}
			}
			else{
				echo '<br>Error Occured: '.mysql_error().'<br>'.$query;
			}


	}

	if(isset($_POST['emailnotification'])&&$_POST['emailnotification']=='Nsl3Ep203N0amlOepan10@#0'){
		$reqnum = escapeString($_POST['reqnum']);
		$emailto = escapeString($_POST['emailto']);
		$toname = escapeString($_POST['emailtoname']);
		$date = $reqdate = date('Y-m-d H:i:s');
		$requestor = getUserName($userid);
		$rs = query("select * from purchase_requisition_header where requisition_number='$reqnum'");
		while($obj=fetch($rs)){
			$reqtype = $obj->requisition_type;
			$justification = $obj->justification;
			$ccrequestor = getUserEmail($obj->requisitioner);
			$rev_no = $obj->next_revision_series;
			
			$section_head1 = $obj->section_head1;
			$section_head2 = $obj->section_head2;
			$conformance = $obj->conformance;
			$general_manager = $obj->general_manager;
			$assistant_vice_president = $obj->assistant_vice_president;
			$senior_vice_president = $obj->senior_vice_president;
			$president = $obj->president;
		}
		if(!isset($ctr)) {
			$ctr = 0;
		}
		if($section_head1 != '' && $section_head1 != 1 && !is_null($section_head1)) {
			$ctr++;
		}
		if($section_head2 != '' && $section_head2 != 1 && !is_null($section_head2)) {
			$ctr++;
		}
		if($conformance != '' && $conformance != 1 && !is_null($conformance)) {
			$ctr++;
		}
		if($general_manager != '' && $general_manager != 1 && !is_null($general_manager)) {
			$ctr++;
		}
		if($assistant_vice_president != '' && $assistant_vice_president != 1 && !is_null($assistant_vice_president)) {
			$ctr++;
		}
		if($senior_vice_president != '' && $senior_vice_president != 1 && !is_null($senior_vice_president)) {
			$ctr++;
		}
		if($president != '' && $president != 1 && !is_null($president)) {
			$ctr++;
		}
		
		$approval_hierarchy = '1st Approver (from '.$ctr.' approvers)';
		$emailsuccess = $emailClass->sendNotification($emailto,$toname,'EPRPO - Purchase Requisition Approval',prMessage($reqnum,$requestor,$date,$reqtype,$justification,$approval_hierarchy),prMessage($reqnum,$requestor,$date,$reqtype,$justification,$approval_hierarchy),array($ccrequestor));
		if($emailsuccess==true){
			echo true;
		}
		else{
			echo "Unable to send email notification. [".$reqnum."]";
		}
	}

	if(isset($_POST['followupOnApproval'])){
		if($_POST['followupOnApproval']=='DHlei#N2ak2oiuha3b@K!JoEh'){
			ignore_user_abort(true); 
			set_time_limit(0); 
			ob_start();

			$prnumber = escapeString($_POST['prnum']);
			$msg = emailDecode($_POST['msg']);
			$emailto = getUserEmail(getNextApprover($prnumber));
			$toname =  getUserName(getNextApprover($prnumber));

			$rs = query("select * from purchase_requisition_header where requisition_number='$prnumber'");
			while($obj=fetch($rs)){
				$type = $obj->requisition_type;
				$justification = $obj->justification;
				$requestor = getUserName($obj->requisitioner);
				$reqdate = formatDate($obj->requisition_date);
				$ccrequestor = getUserEmail($obj->requisitioner);
			}

			echo "success";
			

			$size = ob_get_length()+1; 
			header("Content-Length: $size"); 
			header('Connection: close'); 
			ob_end_flush(); 
			ob_flush(); 
			flush();
			if (session_id()) session_write_close();

			$emailsuccess = $emailClass->sendNotification($emailto,$toname,'EPRPO - Purchase Requisition Follow-up',followupMessage($prnumber,$msg,$requestor,$reqdate,$type,$justification),followupMessage($prnumber,$msg,$requestor,$reqdate,$type,$justification),array($ccrequestor));

		}
	}




	if(isset($_FILES['file'])){
				$response = 'ok';
				$file = $_FILES['file'];
				$length = count($file['name']);
				$reqnum = escapeString($_POST['prnumber']);
				//
				$emailto = escapeString($_POST['email-to']);
				$emailtoname = escapeString($_POST['email-toname']);
				$source = escapeString($_POST['source-button']);
				//
				$x = 2;
				$flag = true;

				for($i=0;$i<$length;$i++){

					$tmp = $file['tmp_name'][$i];
					$filename = $file['name'][$i];
					$filetype = escapeString($_POST['filetype'][$i]);
					$otherfiletype = escapeString($_POST['otherfiletype'][$i]);

					//check if filename exist
					$fn = substr($filename, 0, strpos($filename, '.'));
					$ftype = substr($filename, strpos($filename, '.'));
					if(file_exists('../application/Attachments/'.$filename)==1){
						while($flag==true){
							if(file_exists('../application/Attachments/'.$fn.'('.$x.')'.$ftype)==1){
								$x++;
							}
							else{
								$flag = false;
								$filename = $fn.'('.$x.')'.$ftype;
								$x=2;
							}
						}
					}
					//

					move_uploaded_file($_FILES['file']['tmp_name'][$i],'../application/Attachments/'.$filename);
					$query = "insert into attachments values('$reqnum','$filename','$filetype','$otherfiletype')";
					$rs = query($query);
				
				}
				if($response=='ok'){
					$_SESSION['MdjKeoIdhk'] = 'purchase-requisition';
					//
					$_SESSION['reqnumXoslapEsnIE'] = $reqnum;
					$_SESSION['emailtoXoslapEsnIE'] = $emailto;
					$_SESSION['emailtonameXoslapEsnIE'] = $emailtoname;
					$_SESSION['sourcebtnPR']=$source;
					//
					header('location:../application/main.php');
				}
				
	}



	if(isset($_POST['prCancellationNoPo'])){
		if($_POST['prCancellationNoPo']=='Fkjhs4lkh4s!o3Iah$Uh#O@i2aoa4ho@'){

			$prnumber = escapeString($_POST['prnumber']);
			$reason =  escapeString($_POST['reason']);
			$now = date("Y-m-d H:i:s");
			$date = formatDate2($now);

			mysql_query("insert into purchase_requisition_cancellation_history(pr_number,reason,created_by,created_date) 
																		values('$prnumber','$reason','".USERID."','$now')");

			$prrequestor = '';
			$rs = query("select * from purchase_requisition_header where requisition_number='$prnumber'");
			while ($obj=fetch($rs)) {
				$prrequestor = $obj->requisitioner;
			}

			$requisitioner = getUserName($prrequestor);
			$requisitioneremail = getUserEmail($prrequestor);

			$prevapprovers = getPrApprovers($prnumber);
			$prevapproversemail = array();
			$cancelledbyemail = getUserEmail(USERID);
			$cancelledbyname = getUserName(USERID);

			if(!in_array(USERID, $prevapprovers)&&USERID!=$prrequestor){
				array_push($prevapprovers, USERID);
			}
			for($i=0;$i<count($prevapprovers);$i++){
				array_push($prevapproversemail, getUserEmail($prevapprovers[$i]));
			}
			$emailsuccess = $emailClass->sendNotification($requisitioneremail,
														  $requisitioner,'EPRPO - Purchase Requisition Cancellation',
														  prCancellationMessage($prnumber,$reason,$cancelledbyname,$requisitioner,$date),
														  prCancellationMessage($prnumber,$reason,$cancelledbyname,$requisitioner,$date),
														  $prevapproversemail);

			$rs = query("update purchase_requisition_header set requisition_status='CANCELLED', canceled_date='$now', last_made_changes='$now' where requisition_number='$prnumber'");

			$rs = query("select * from purchase_order_header where reference_requisition_number='$prnumber' and order_status='FOR CANCELLATION'");
			while($obj=fetch($rs)){
				$ponum = $obj->order_number;
				query("update purchase_order_header set order_status='CANCELLED', date_flag='$now' where order_number='$ponum'");
				query("update purchase_order_cancellation_history set created_date='$now' where po_number='$ponum' order by created_date desc");
			}

			echo 'success';

		}
	}

?>