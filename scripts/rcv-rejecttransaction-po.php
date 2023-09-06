<?php
    include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include('../config/functions.php');
    include('email-class.php');
	
	if(isset($_POST['Mskdhebc211s3'])){
		$rcvnum = $_POST['Mskdhebc211s3'];
		$transaction_type = 'PO';
		$po = escapeString($_POST['ponumber']);
		$prices = $_POST['prices'];
		$items = $_POST['items'];
		$qtys = $_POST['qtys'];
		$tempqtys = $_POST['tempqtys'];
		$completedelivery = $tempqtys != '' ? 'false' : escapeString($_POST['ifcomplete']);
		$justis = $_POST['justifications'];
		$cas = $_POST['corrective_actions'];
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
		$receiving_status = $hasoldpo ? 'IN REVIEW' : 'N/A';
		
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
		
		

		$query = "update receiving_header set flag=1, receiving_status='$receiving_status' where receiving_number='$rcvnum'";
		$rs = query($query);
		for($i=0;$i<$length;$i++){
			$item = escapeString($items[$i]);
			$qty = escapeString($qtys[$i]);
			$tempqty = escapeString($tempqtys[$i]);
			$justification = escapeString($justis[$i]);
			$corrective_action = escapeString($cas[$i]);
			$price = escapeString($prices[$i]);
			
			$query = "update receiving_details set quantity_received='$qty', qty_received_for_approval='$tempqty', justification='$justification', corrective_action='$corrective_action'
				where item_id='$item' and receiving_number='$rcvnum'";
			
			$rs=query($query);

		}
		
		// if($completedelivery=='true'){
			// updatePoStatus($po,'SERVED');
		// }
		// else{
			// updatePoStatus($po,'PARTIAL DELIVERY');
		// }

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
			echo 'success';
		}
	}
?>