<?php
    include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include('../config/functions.php');
    include('email-class.php');
	
	if(isset($_POST['KhrOIrlsk34o2hlK'])){
		if($_POST['KhrOIrlsk34o2hlK']=='URhksoi3Hsri'){
			$transaction_type = 'PO';
			$completedelivery = escapeString($_POST['ifcomplete']);
			$po = escapeString($_POST['ponumber']);
			$carrier = escapeString($_POST['carrier']);
			$otherref = escapeString($_POST['otherref']);
			$actualdate = date('Y-m-d', strtotime($_POST['actualdate']));
			$remarks = escapeString($_POST['remarks']);
			$supplier = escapeString($_POST['supplier']);
			$currency = escapeString($_POST['currency']);
			$prices = $_POST['prices'];
			$items = $_POST['items'];
			$qtys = $_POST['qtys'];
			$tempqtys = $_POST['tempqtys'];
			$justis = $_POST['justifications'];
			$cas = $_POST['corrective_actions'];
			$rcvnum = getTransactionNumber(3);
			$length = count($items);
			$date=date('Y-m-d H:i:s');

			@$qtyvariance = escapeString($_POST['qtyvariance']);
			@$pricevariance = escapeString($_POST['pricevariance']);
			@$underdelivery = escapeString($_POST['underdelivery']);

			@$qtyvarnotes = escapeString(rtrim($_POST['qtyvarnotes'],'@&'));
			@$pricevarnotes = escapeString(rtrim($_POST['pricevarnotes'],'@&'));
			@$underdelnotes = escapeString(rtrim($_POST['underdelnotes'],'@&'));
			
			$hasoldpo = escapeString($_POST['hasoldpo']);
			$firstapprover=getPoConfigData('first_approver');
			$secondapprover=getPoConfigData('second_approver');
			
			if($hasoldpo == 'false') {
				$receiving_status = 'N/A';
			} else {
				$receiving_status = 'IN REVIEW';
			}
			
			/* Check if receiving record already exist */
			$res = query("select receiving_number from receiving_header where reference_po_number='$po' AND received_date='$date' AND received_by='$userid' AND carrier='$carrier' AND transaction_type='$transaction_type' AND supplier_id='$supplier' AND remarks='$remarks' AND currency='$currency' AND other_reference='$otherref' AND actual_delivery_date='$actualdate' AND receiving_status in ('ACCEPTED','N/A')");
			$count = getNumRows($res);
			if($count == 0) {
				$discrepancy = array();
				if($qtyvariance=='true'){
					array_push($discrepancy, 'QUANTITY VARIANCE');
					$qtyvar = explode('@&', $qtyvarnotes);
					for($i=0;$i<count($qtyvar);$i++){
						$varrem = $qtyvar[$i];
						query("insert into purchase_order_variance_details values('$po','$varrem','1')");
					}
					
				}
				if($pricevariance=='true'){
					array_push($discrepancy, 'PRICE VARIANCE');
					$qtyvar = explode('@&', $pricevarnotes);
					for($i=0;$i<count($qtyvar);$i++){
						$varrem = $qtyvar[$i];
						query("insert into purchase_order_variance_details values('$po','$varrem','1')");
					}
				}
				if($underdelivery=='true'){
					array_push($discrepancy, 'UNDERDELIVERY');
					$qtyvar = explode('@&', $underdelnotes);
					for($i=0;$i<count($qtyvar);$i++){
						$varrem = $qtyvar[$i];
						query("insert into purchase_order_variance_details values('$po','$varrem','1')");
					}
				}
				//
				$rs = query("select * from purchase_order_header where order_number='$po'");
				while($obj=fetch($rs)){
					$existing = $obj->discrepancy_flag;
				}
				$existing = explode(',', $existing);

				for($i=0;$i<count($existing);$i++){
					$var = $existing[$i];
					if (($key = array_search($var, $discrepancy))!==false) {
						unset($discrepancy[$key]);
					}
				}
				$variance = array_merge($existing,$discrepancy);
				$variance = implode(',', $variance);
				//
				$now = date('Y-m-d H:i:s');
				query("update purchase_order_header set discrepancy_flag='$variance', date_flag='$now' where order_number='$po'");


				$query = "insert into receiving_header(receiving_number,reference_po_number,received_date,received_by,carrier,transaction_type,supplier_id,remarks,currency,other_reference,actual_delivery_date,receiving_status,first_approver,second_approver,flag,date_flag)
												values('$rcvnum','$po','$date','$userid','$carrier','$transaction_type','$supplier','$remarks','$currency','$otherref','$actualdate','$receiving_status','$firstapprover','$secondapprover','1','$date')";
				$rs = query($query);
				for($i=0;$i<$length;$i++){
					$item = escapeString($items[$i]);
					$qty = escapeString($qtys[$i]);
					$tempqty = escapeString($tempqtys[$i]);
					$justification = escapeString($justis[$i]);
					$corrective_action = escapeString($cas[$i]);
					$price = escapeString($prices[$i]);
					
					//select item details from PO table and save to receiving table
					$item_details = query("select item_name,long_description from purchase_order_details where item_id='$item' and order_number='$po'");
					if($id_po=fetch($item_details)){
						$item_name = escapeString($id_po->item_name);
						$long_description = escapeString($id_po->long_description);
					} else {
						$item_name = '';
						$long_description = '';
					}
					
					//If item has open PO, save the received qty to temp field (qty_received_for_approval), then move onced ACCEPTED by Approvers
					$query = "insert into receiving_details(receiving_number,item_id,item_name,long_description,quantity_received, qty_received_for_approval,unit_price, justification, corrective_action) 
					values('$rcvnum','$item','$item_name','$long_description','$qty','$tempqty','$price','$justification','$corrective_action')";
					$rs=query($query);

				}
				if($completedelivery=='true'){
					updatePoStatus($po,'SERVED');
				}
				else{
					updatePoStatus($po,'PARTIAL DELIVERY');
				}
				


				$r = query("select * from purchase_order_header where order_number='$po'");
				while ($obj=fetch($r)){
						$assignedto = $obj->assigned_to;
						$assignedby = $obj->assigned_by;
						$ccprchhead = getUserEmail(getInfo("purchase_order_configuration","purchasing_head","where id='1'"));
						$prnumber = getInfo("purchase_order_header","reference_requisition_number","where order_number='".$po."'");
						$requestor = getUserEmail(getInfo("purchase_requisition_header","requisitioner","where requisition_number='".$prnumber."'"));
				}
				
				
				if($qtyvariance=='true'||$pricevariance=='true'||$underdelivery=='true'){//email notification
					$emailClass->sendNotification(getUserEmail($assignedto),getUserName($assignedto),'EPRPO - Discrepancy in '.$po,poDiscrepancyMessage($po),poDiscrepancyMessage($po),array(getUserEmail($assignedby)));
				}
				else{
					$emailClass->sendNotification(getUserEmail($assignedto),getUserName($assignedto),'EPRPO - Items Received'.' ['.$po.']',receivingMessage($po),receivingMessage($po),array($requestor,$ccprchhead));
				}

				if($r){
					if($receiving_status == 'IN REVIEW') {
						echo 'success2';
					} else {
						echo 'success';
					}
				}
			}
		}
	}
?>