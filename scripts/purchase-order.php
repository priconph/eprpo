<?php
    include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include('../config/functions.php');
    include('email-class.php');

	if(isset($_POST['KJlrkjosnRh2kjhl34k'])){//PO PR REVISION
		if(isset($_POST['KJlrkjosnRh2kjhl34k'])=='329&4kjhos909dfe'){
			$ponumber = escapeString($_POST['ponumber']);
			$reqnumber = escapeString($_POST['reqnumber']);
			$remarks = escapeString($_POST['remarks']);
			$date = date('Y-m-d H:i:s');
			//////////////////////////////////////////////////////////////////////////////

			$rs = query("select * from purchase_requisition_header where requisition_number='$reqnumber'");
			$obj=fetch($rs);
			$prrevnum = $obj->next_revision_series;
			$requestor = $obj->requisitioner;

			
			$rs = query("select * from purchase_order_header where order_number='$ponumber'");
			$obj=fetch($rs);
			$porevnum = $obj->next_revision_series;
			$order_status = $obj->order_status;
			$printed_flag = $obj->printed_flag;
			$printed_timestamp = $obj->printed_timestamp;
			$ccassignedto = getUserEmail($obj->assigned_to);
			$ccassignedby = getUserEmail($obj->assigned_by);

			$rev_reqnum = $reqnumber.'-R'.$prrevnum;
			// $rev_ponum = $ponumber.'-R'.$porevnum;
			$purch_revision_no = getInfo("purchase_order_header","purch_revision_no","where order_number='".$ponumber."'");
			$rev_ponum = $ponumber.'-R'.$porevnum.'.'.$purch_revision_no;
			query("update purchase_order_header_revision set reference_requisition_number='$rev_reqnum' where order_number like '$ponumber%' and reference_requisition_number='$reqnumber'");
			
			//create copy of pr and po
			query("insert into purchase_requisition_header_revision select * from purchase_requisition_header where purchase_requisition_header.requisition_number='$reqnumber'");	
			query("insert into purchase_requisition_details_revision select * from purchase_requisition_details where purchase_requisition_details.requisition_number='$reqnumber'");
			query("insert into purchase_requisition_allocation_revision select * from purchase_requisition_allocation where purchase_requisition_allocation.requisition_number='$reqnumber'");
			
			
			query("update purchase_requisition_header_revision set requisition_number='$rev_reqnum' where requisition_number='$reqnumber'");
			query("update purchase_requisition_header set next_revision_series=next_revision_series+1 where requisition_number='$reqnumber'");
			
			$now = date('Y-m-d H:i:s');	
			
			if($order_status == 'DRAFT') {
				$query = "update purchase_order_header set order_status='FOR REVISION', date_flag='$now', printed_timestamp=NULL, printed_flag=0 where order_number='$ponumber'";
				$rs = query($query);
			} else {
				query("insert into purchase_order_header_revision select * from purchase_order_header where purchase_order_header.order_number='$ponumber'");
				query("insert into purchase_order_details_revision select * from purchase_order_details where purchase_order_details.order_number='$ponumber'");
				query("update purchase_order_header_revision set order_number='$rev_ponum', reference_requisition_number='$rev_reqnum' where order_number='$ponumber'");
				
				//add revision if PO is printed already and done with all PO approval
				if($printed_flag == 0 && $printed_timestamp == '0000-00-00 00:00:00') {
					query("update purchase_order_header set purch_revision_no=purch_revision_no+1 where order_number='$ponumber'");
				} else {
					query("update purchase_order_header set next_revision_series=next_revision_series+1, purch_revision_no=0 where order_number='$ponumber'");
				}
				
				$query = "update purchase_order_header set order_status='FOR REVISION', date_flag='$now', printed_timestamp=NULL, printed_flag=0 where order_number='$ponumber'";
				$rs = query($query);
				
				$query = "insert into purchase_order_revision_history(order_number,user_id,remarks,revision_number,date) values('$ponumber','$userid','$remarks','$porevnum','$date')";
				$rs = query($query);
				
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
																  'FOR REVISION',
																  '$remarks',
																  '$now',
																  '$userid',
																  '$porevnum',
																   NULL,
																  '$remarks',
																  '$porevnum',
																  '$purch_revision_no'
																 )";
				query($query);
			}
			
			///////////////////////////////////////////////////////////////////////////////////
					
			
			$query = "update purchase_requisition_header 
					  set requisition_status='FOR REVISION',
					  	  flag=NULL,
					      sh_approved=NULL,
					      sh2_approved=NULL,
					      conf_approved=NULL,
					      gm_approved=NULL,
					      avp_approved=NULL,
					      svp_approved=NULL,
					      pr_approved=NULL,
					      last_made_changes='$now' 
					  where requisition_number='$reqnumber'";
			$rs = query($query);
			

			$exist = query("select * from purchase_requisition_revision_sources where requisition_number='$reqnumber' and order_number='$ponumber'");
			if(getNumRows($exist)<1){
				query("insert into purchase_requisition_revision_sources values('$reqnumber','$ponumber')");
			}
			
			$rsattachments = query("select * from attachments where requisition_number='$reqnumber'");
			while($o=fetch($rsattachments)){
				$oldfilename = escapeString(strUtfEncode($o->file_name));
				$newfilename = escapeString(strUtfEncode($rev_reqnum.'-'.$o->file_name));
				$filedesc = escapeString(strUtfEncode($o->file_description));
				$otherdesc = escapeString(strUtfEncode($o->other_description));

				query("insert into attachments_revision(requisition_number,file_name,file_description,other_description) 
					   values('$rev_reqnum','$newfilename','$filedesc','$otherdesc')");
				copy('../application/Attachments/'.$oldfilename, '../application/Attachments_Revision/'.$newfilename);
			}

			$purchhead = getInfo("purchase_order_configuration","purchasing_head","where id='1'");

			$tobeccd = array($ccassignedto,$ccassignedby);
			$emailsuccess = $emailClass->sendNotification(getUserEmail($requestor),
													      getUserName($requestor),
														  'EPRPO - Purchase Requisition for Revision',
														   prRevisionMessage($reqnumber),
														   prRevisionMessage($reqnumber),
														   $tobeccd
														);
			
		}
	}


	if(isset($_POST['Kj4lkd8Klei3nfkisdfk'])){//PO ASSIGNMENT
			if($_POST['Kj4lkd8Klei3nfkisdfk']=="ekhrPro4j2kHFlkef#lfk"){
				ignore_user_abort(true); 
				set_time_limit(0); 
				ob_start();  
					
					$date = date('Y-m-d H:i:s');
					$ponumber = escapeString($_POST['ponumber']);
					$assignedto = escapeString($_POST['assignedto']);

					$prnumber = getInfo("purchase_order_header","reference_requisition_number","where order_number='".$ponumber."'");
					$requestor = getUserEmail(getInfo("purchase_requisition_header","requisitioner","where requisition_number='".$prnumber."'"));
					
					$query = "update purchase_order_header set assigned_to='$assignedto', assigned_by='$userid', order_status='DRAFT', status_before_revision='DRAFT', date_flag='$date', datetime_assigned='$date' where order_number='$ponumber'";
					$rs = query($query);
					if($rs){
						echo 'success';
					}

				$size = ob_get_length()+1; 
				header("Content-Length: $size"); 
				header('Connection: close'); 
				ob_end_flush(); 
				ob_flush(); 
				flush();
				if (session_id()) session_write_close(); 

					$emailsuccess = $emailClass->sendNotification(getUserEmail($assignedto),
						                                          getUserName($assignedto),
						                                          'EPRPO - Purchase Order Assignment',
						                                          poAssgnMessage($ponumber),
						                                          poAssgnMessage($ponumber),
						                                          array($requestor));
			}
	}

	if(isset($_POST['MdlkeoI230Uj143dl'])){//retrive data discrepancy of given po number
		if($_POST['MdlkeoI230Uj143dl']=='NddJy@$l3*p&4kh2'){
			$orderNum=escapeString($_POST['ponumber']);
            $rs = query("select * from purchase_order_variance_details where order_number='$orderNum' and flag='1'");
            $count = getNumRows($rs);
            $data = array();
            while($obj=fetch($rs)){
            	array_push($data, $obj->variance_remarks);
            }
           
           	$data = implode('@&', $data);
            if(trim(str_replace('@&','',$data),' ')==''){
            	echo 'none';
            }
            else{
            	 echo $data;
            }
           

		}
	}


	if(isset($_POST['tagpoasprinted'])){//retrive data discrepancy of given po number
		if($_POST['tagpoasprinted']=='DKjhsi$OIhs$K#uh#i2#lfk'){
			$orderNum=escapeString($_POST['ponumber']);
			$now = date('Y-m-d H:i:s');
			query("update purchase_order_header set printed_flag=1, printed_timestamp='$now', date_flag='$now' where order_number='$orderNum'");
			echo "success";
		}
	}

	if(isset($_POST['poCancellation'])){//retrive data discrepancy of given po number
		if($_POST['poCancellation']=='Fkjhs4lkh4s!o3Iah$Uh#O@i2aoa4ho@'){
			$ponumbers = $_POST['ponumbers'];
			$poreasons = $_POST['poreasons'];
			$prnumber = escapeString($_POST['prnumber']);
			$len = count($ponumbers);
			$now = date('Y-m-d H:i:s');
			$transactedby = USERID;

			$poreasonmsgdetails = array();

			$checkpr = checkIfAllPoOfPrCancelled2($prnumber,$ponumbers);
			if($checkpr==1){
				$stat = 'FOR CANCELLATION';
			}else{
				$stat = 'CANCELLED';
			}

			for($i=0;$i<$len;$i++){
				$ponum = escapeString($ponumbers[$i]);
				$reason = escapeString($poreasons[$i]);
				$rs = query("update purchase_order_header set order_status='$stat', updated_date='$now', updated_by='$transactedby', date_flag='$now' where order_number='$ponum'");
				if($rs){
					query("insert into purchase_order_cancellation_history(po_number,reason,created_date,created_by) values('$ponum','$reason','$now','$transactedby')");
				}
				$podetail = "<b>".$ponum."</b> - ".$reason;
				array_push($poreasonmsgdetails, $podetail);
			}

			$prrequestor = '';
			$rs = query("select * from purchase_requisition_header where requisition_number='$prnumber'");
			while ($obj=fetch($rs)) {
				$prrequestor = $obj->requisitioner;
			}
			$requisitioner = getUserName($prrequestor);
			$requisitioneremail = getUserEmail($prrequestor);
			$cancelledby = getUserName($transactedby);

			//EMAIL NOTIFICATION
			$emailsuccess = $emailClass->sendNotification($requisitioneremail,
															  $requisitioner,'EPRPO - Purchase Orders for Cancellation',
															  poCancellationMessage($prnumber,$poreasonmsgdetails,$cancelledby),
															  poCancellationMessage($prnumber,$poreasonmsgdetails,$cancelledby),
															  array());
			//EMAIL NOTIFICATION


			$prtobecancelled = checkIfAllPoOfPrCancelled($prnumber);//boolean result
			if($prtobecancelled==1){
				query("update purchase_requisition_header set requisition_status='FOR CANCELLATION', cancellation_flag=1, last_made_changes='$now' where requisition_number='$prnumber'");
				//EMAIL NOTIFICATION
				$emailsuccess = $emailClass->sendNotification($requisitioneremail,
															  $requisitioner,'EPRPO - Purchase Requisition for Cancellation',
															  prForCancellationMessage($prnumber),
															  prForCancellationMessage($prnumber),
															  array());
				//EMAIL NOTIFICATION
			}

			echo "success";


		}
	}
?>