<?php
    include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include('../config/functions.php');
    //include('../config/db-function.php');
    include('email-class.php');	

    if(isset($_POST['emailnotification'])&&$_POST['emailnotification']=='Nsl3Ep203N0amlOepan10@#0'){//EMAIL NOTIFICATION
    	$stat = $_POST['status'];
    	$nextflag = $_POST['nextflag'];
		$reqnum = $_POST['reqnum'];
		$emailto = $_POST['emailto'];
		$toname = $_POST['emailtoname'];
		// $user_id = $_POST['user_id'];
		$purchhead = getInfo("purchase_order_configuration","purchasing_head","where id='1'");
		$date = $reqdate = date('Y-m-d H:i:s');
		$ctr = 0;
		$order = 0;
		$approval_hierarchy = '';
		$rs = query("select * from purchase_requisition_header where requisition_number='$reqnum' LIMIT 0,1");
		if($obj=fetch($rs)){
			$reqtype = $obj->requisition_type;
			$rev_no = $obj->next_revision_series;
			$justification = $obj->justification;
			$requestor = getUserName($obj->requisitioner);
			$ccrequestor = getUserEmail($obj->requisitioner);
			
			$section_head1 = $obj->section_head1;
			$section_head2 = $obj->section_head2;
			$conformance = $obj->conformance;
			$general_manager = $obj->general_manager;
			$assistant_vice_president = $obj->assistant_vice_president;
			$senior_vice_president = $obj->senior_vice_president;
			$president = $obj->president;			
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
		
		$rs2 = query("select * from purchase_requisition_approval_history where requisition_number='$reqnum' AND revision_flag='$rev_no'");
		$ctr_approved = returnNumRows($rs2) + 1;
		$approval_hierarchy = $ctr == $ctr_approved ? 'Final Approver (from '.$ctr.' approvers)' : return_ordinal($ctr_approved).' Approver (from '.$ctr.' approvers)';
		
		if($nextflag!=7){
			$emailsuccess = $emailClass->sendNotification($emailto,
														  $toname,'EPRPO - Purchase Requisition Approval',
														  prMessage($reqnum,$requestor,$date,$reqtype,$justification,$approval_hierarchy),
														  prMessage($reqnum,$requestor,$date,$reqtype,$justification,$approval_hierarchy),
														  array($ccrequestor));
			if($emailsuccess==true){
				echo true;
			}
			else{
				echo "Unable to send email notification. [".$reqnum."]";
			}
			// echo $emailsuccess;
		}
		else{
			$forrev = checkIfForRevision($reqnum);

			if($forrev=='NOT NULL'){
				$tobeccd = array($ccrequestor);
				$rs = query("select * 
					         from purchase_requisition_revision_sources
					         left join purchase_order_header on purchase_order_header.order_number=purchase_requisition_revision_sources.order_number 
					         where purchase_requisition_revision_sources.requisition_number='$reqnum'");
		        while($obj=fetch($rs)){
		        	$assignedto = getUserEmail($obj->assigned_to);
		            array_push($tobeccd, $assignedto);
		        }
				$emailsuccess = $emailClass->sendNotification(getUserEmail($purchhead),
														      getUserName($purchhead),
															  'EPRPO Purchase Order Revised',
															   poRevisedMessage($reqnum),
															   poRevisedMessage($reqnum),
															   $tobeccd
															);

			}else{
				$emailsuccess = $emailClass->sendNotification(getUserEmail($purchhead),
														      getUserName($purchhead),
															  'EPRPO Purchase Order Generation',
															   poGenMessage($reqnum),
															   poGenMessage($reqnum),
																array($ccrequestor)
															);
			}
			echo true;
			// echo $emailsuccess;
		}
	}

	if(isset($_POST['approveKUrywBoedlkn'])){//PR APPROVAL
		if(isset($_POST['approveKUrywBoedlkn'])=='fOskenDLfhir'){
			$pr = $_POST['prnumber'];

			$remarks = escapeString($_POST['remarks']);
			$revision = getInfo("purchase_requisition_header","next_revision_series","where requisition_number='".$pr."'");

			$loginuser = $userid;
			$date = date('Y-m-d H:i:s');
			$prapprovallevel = getInfo("purchase_requisition_header","flag","where requisition_number='".$pr."'");
			$rsp = approvePr($pr,$loginuser,$emailClass); //config/functions.php
			$stat = explode('@#$', $rsp);
			$stat = $stat[0];
			$nextrevflagofpr = getInfo("purchase_requisition_header","next_revision_series","where requisition_number='".$pr."'");
			$query = "insert into purchase_requisition_approval_history(requisition_number, 
																		action, 
																		user_id, 
																		reason_for_rejection, 
																		date,
																		pr_flag,
																		pr_approval_flag,
																		pr_approval_remarks,
																		revision_flag
																		) 
																 values('$pr',
																 	    'APPROVED',
																 	    '$loginuser',
																 	    NULL,
																 	    '$date',
																 	    '$nextrevflagofpr',
																 	    '$prapprovallevel',
																 	    '$remarks',
																 	    '$revision'
																 	    )";
			$rs = query($query);
			
			if($stat=='APPROVED'&&checkIfForRevision($pr)==NULL){
				createPurchaseOrder($pr);

			}
			else if($stat=='APPROVED'){
				updatePurchaseOrder($pr);
				//setRevisionStatOfPR($pr); //deletes ponuber sources from purchase_requisition_revision_sources(temp table)
			}

			echo "success".'@#$'.$rsp;
		}
	}

	if(isset($_POST['rKdneJcTlsj'])){//PR REJECTION
		if($_POST['rKdneJcTlsj']=='KneLdkeoirR'){
			ignore_user_abort(true); 
			set_time_limit(0); 
			ob_start();
			
			$loginuser = $userid;
			$pr = $_POST['prnum'];
			$now = date('Y-m-d H:i:s');
			$reason = escapeString($_POST['rejectreason']);
			$reasonemailmsg = emailDecode($_POST['rejectreason']);

			$revision = getInfo("purchase_requisition_header","next_revision_series","where requisition_number='".$pr."'");

			$prevapprovers = getPrApprovers($pr);
			$prevapproversemail = array();
			if(!in_array(USERID, $prevapprovers)){
				array_push($prevapprovers, USERID);
			}
			for($i=0;$i<count($prevapprovers);$i++){
				array_push($prevapproversemail, getUserEmail($prevapprovers[$i]));
			}


			///////////////////
			$rs = query("select * from purchase_requisition_header where requisition_number='$pr'");
			while($obj=fetch($rs)){
				$requestor = getUserName($obj->requisitioner);
				$requestoremail = getUserEmail($obj->requisitioner);
			}
			$rejectedby = getUserName(USERID);
			//////////////////

			query("update purchase_requisition_header set last_made_changes='$now' where requisition_number='$pr'");
			$date = date('Y-m-d H:i:s');
			///////
			$nextrevflagofpr = getInfo("purchase_requisition_header","next_revision_series","where requisition_number='".$pr."'");
			$rev_reqnum = $pr.'-R'.$nextrevflagofpr;
			///////

			//////////////////////////////
			query("insert into purchase_requisition_header_revision select * from purchase_requisition_header where purchase_requisition_header.requisition_number='$pr'");	
			query("insert into purchase_requisition_details_revision select * from purchase_requisition_details where purchase_requisition_details.requisition_number='$pr'");
					//query("insert into purchase_requisition_item_allocation_revision select * from purchase_requisition_item_allocation where purchase_requisition_item_allocation.requisition_number='$reqnumber'");
			query("insert into purchase_requisition_allocation_revision select * from purchase_requisition_allocation where purchase_requisition_allocation.requisition_number='$pr'");
			


			//query("insert into attachments_revision select * from attachments where attachments.requisition_number='$pr'");
			query("update purchase_requisition_header_revision set requisition_number='$rev_reqnum', sh_approved='NULL', sh2_approved='NULL', conf_approved='NULL', gm_approved='NULL', avp_approved='NULL', svp_approved='NULL' where requisition_number='$pr'");

			$rsattachments = query("select * from attachments where requisition_number='$pr'");
			while($o=fetch($rsattachments)){
				$oldfilename = escapeString(strUtfEncode($o->file_name));
				$newfilename = escapeString(strUtfEncode($rev_reqnum.'-'.$o->file_name));
				$filedesc = escapeString(strUtfEncode($o->file_description));
				$otherdesc = escapeString(strUtfEncode($o->other_description));

				query("insert into attachments_revision(requisition_number,file_name,file_description,other_description) 
					   values('$rev_reqnum','$newfilename','$filedesc','$otherdesc')");
				copy('../application/Attachments/'.$oldfilename, '../application/Attachments_Revision/'.$newfilename);
			}

			


			query("update purchase_requisition_header set next_revision_series=next_revision_series+1 where requisition_number='$pr'");
			///////////////////////
			$prapprovallevel = getInfo("purchase_requisition_header","flag","where requisition_number='".$pr."'");
			$query = "insert into purchase_requisition_approval_history(requisition_number, 
				 														action, 
				 														user_id, 
				 														reason_for_rejection, 
				 														date,pr_flag,
				 														pr_approval_flag,
				 														pr_approval_remarks,
				 														revision_flag
				 														)
																 values('$pr',
																 	    'REJECTED',
																 	    '$loginuser',
																 	    '$reason',
																 	    '$now',
																 	    '$nextrevflagofpr',
																 	    '$prapprovallevel',
																 	    '$reason',
																 	    '$revision'
																 	    )";
			$rs = query($query);
			if($rs){
				$response = rejectPr($pr);
				if($response=='ok'){
					echo 'success';
				}
				else{
					echo $response;
				}
			}else{
				echo $rs;
			}

			$size = ob_get_length()+1; 
			header("Content-Length: $size"); 
			header('Connection: close'); 
			ob_end_flush(); 
			ob_flush(); 
			flush();
			if (session_id()) session_write_close();
				if($response=='ok'){
					$emailsuccess = $emailClass->sendNotification($requestoremail,
																	  $requestor,
																	  'EPRPO Purchase Requisition - Rejected',
																	  prRejectMessage($pr,$rejectedby,$reasonemailmsg),
																	  prRejectMessage($pr,$rejectedby,$reasonemailmsg),
																	  $prevapproversemail
																	  );
				}
			}
	}

?>