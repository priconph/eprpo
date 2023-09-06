<?php
	include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include('../config/functions.php');
    include('email-class.php');

	if(isset($_POST['kerbfdsoirp'])){//save or submit

		ignore_user_abort(true); 
		set_time_limit(0); 
		ob_start();  

	
		$ponumber = explode("-R",escapeString($_POST['ponumber']));
		$ponumber = $ponumber[0];
		$supplieraddr = escapeString($_POST['supplieraddr']);
		$term = escapeString($_POST['term']);
		$shipvia = escapeString($_POST['shipvia']);
		//$deliverydate = escapeString($_POST['deliverydate']);
		$remarks = escapeString($_POST['remarks']);
		$mode = escapeString($_POST['mode']);
		$date=date('Y-m-d H:i:s');
		$attn=escapeString($_POST['attn']);
		$loginuser = $userid;
		//
		$dateneeded = date('Y-m-d', strtotime($_POST['dateneeded']));


		$prnumber = escapeString($_POST['prnumber']);
		//query("update purchase_requisition_header set date_needed='$dateneeded' where requisition_number='$prnumber'");
		//
		$currency = escapeString($_POST['currency']);
		$grandtotal = escapeString($_POST['grandtotal']);


		$firstapprover=getPoConfigData('first_approver');
		$secondapprover=getPoConfigData('second_approver');

		$thirdapprover = '';
		if($currency=='1'){
			if($grandtotal>=228153){
				$thirdapprover=getPoConfigData('third_approver');
			}
			else{
				$thirdapprover="NULL";
			}
		}
		else if($currency=='2'){
			if($grandtotal>=100000){
				$thirdapprover=getPoConfigData('third_approver');
			}
			else{
				$thirdapprover="NULL";
			}
		}
		else if($currency=='3'){
			if($grandtotal>=2500){
				$thirdapprover=getPoConfigData('third_approver');
			}
			else{
				$thirdapprover="NULL";
			}
		}


		if($_POST['kerbfdsoirp']=='SAVE'){
			$query = "update purchase_order_header 
					  set supplier_address='$supplieraddr',
					  	  supplier_contact='$attn', 
					  	  terms=$term, 
					  	  ship_via='$shipvia',
					  	  mode_of_shipment=$mode,
					  	  po_remarks='$remarks',
					  	  updated_date='$date',
					  	  updated_by='$loginuser', 
					  	  first_approver='$firstapprover', 
					  	  second_approver='$secondapprover', 
					  	  third_approver=$thirdapprover,
					  	  date_flag='$date',
					  	  expected_date='$dateneeded' 
					  where order_number='$ponumber'";
			$rs = query($query);
			if(!$rs){
				echo mysql_error();
			}
			else{
				echo "success";
			}
		}
		else if($_POST['kerbfdsoirp']=='SUBMIT'){
			

			$query = "update purchase_order_header 
					  set order_status='IN REVIEW', 
					  	  supplier_address='$supplieraddr',
					  	  supplier_contact = '$attn', 
					  	  terms=$term,
					  	  mode_of_shipment=$mode, 
					  	  posted_by='$loginuser', 
					  	  posted_date='$date',
					  	  updated_date='$date',
					  	  updated_by='$loginuser',
					  	  ship_via='$shipvia',
					  	  po_remarks='$remarks', 
					  	  first_approver='$firstapprover', 
					  	  second_approver='$secondapprover', 
					  	  third_approver=$thirdapprover,
					  	  expected_date='$dateneeded', 
					  	  flag='1',
					  	  date_flag='$date'  
					  where order_number='$ponumber'";
			$rs = query($query);
			if(!$rs){
				echo mysql_error();
			}
			else{
               
				echo "success#@$".$ponumber;
			}
			
		}

		$size = ob_get_length()+1; 
		header("Content-Length: $size"); 
		header('Connection: close'); 
		ob_end_flush(); 
		ob_flush(); 
		flush();
		if (session_id()) session_write_close(); 

		if($_POST['kerbfdsoirp']=='SUBMIT'){
			$crntdateneeded = escapeString($_POST['crntdateneeded']);
			$crntdateneeded = date('Y-m-d', strtotime($crntdateneeded));
			$qry = query("select * 
						  from purchase_order_header, 
						  	   purchase_requisition_header 
						  where requisition_number=reference_requisition_number and 
						        order_number='$ponumber'");
			while($obj=fetch($qry)){
				$currdateneeded = date('Y-m-d',strtotime($obj->date_needed)); 
				$requestoremail = getUserEmail($obj->requisitioner);
				$requestorname = getUserName($obj->requisitioner);
				$prreference = $obj->reference_requisition_number;
				$prchstaff = getUserEmail($obj->posted_by);
				$assignedby = getUserEmail($obj->assigned_by);
			}
			if(trim($crntdateneeded)!=trim($dateneeded)){
				$old = date('F j, Y',strtotime($currdateneeded)); 
				$new = date('F j, Y',strtotime($dateneeded)); 
				$emailsuccess = $emailClass->sendNotification($requestoremail,
					 										   $requestorname,
					 										   'EPRPO Purchase Requisition - Date Needed',
					 											dateneededUpdateNotification($prreference,$ponumber,$old,$new),
					 											dateneededUpdateNotification($prreference,$ponumber,$old,$new),
					 											array($assignedby)
					 											);
			}

			$emailsuccess = $emailClass->sendNotification(getUserEmail($firstapprover),
				                                          getUserName($firstapprover),
				                                          'EPRPO - Purchase Order Approval',
				                                          poMessage($ponumber),
				                                          poMessage($ponumber),
				                                          array($requestoremail,$prchstaff)
				                                          );
		}
	}


	if(isset($_POST['LKrnIRR4oRjiodb'])){
		$via = escapeString(strtoupper($_POST['LKrnIRR4oRjiodb']));
		$result = array();
		$query = "select * from mode_of_shipment where via='$via'";
		$rs = query($query);
		while($obj=fetch($rs)){
			array_push($result, $obj->id,$obj->description);
		}
		echo implode('%$@', $result);
	}


?>