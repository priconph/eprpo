<?php

include("../../../config/connection.php");
include("../../../config/checklogin.php");
include("../../../config/functions.php");
require('../../../resources/htmltopdf/fpdf.php');


if(isset($_GET['poSummaryExcelFormat019827@839'])){


	$supplierId = escapeString($_GET['supplierId']);
	$stat = strtoupper(escapeString($_GET['status']));
	$from = escapeString($_GET['orderdatefrom']);
	$to = escapeString($_GET['orderdateto']);
	$dnfrom = escapeString($_GET['dateneededfrom']);
	$dnto = escapeString($_GET['dateneededto']);
	$staff = escapeString($_GET['staff']);
	$pobalance = escapeString(strtoupper($_GET['pobalance']));
	$query = "select purchase_order_details.item_id,
							 item_code,
							 item.item_name as item_name1,
							 item.long_description as long_description1,
							 purchase_order_details.item_name,
							 purchase_order_details.long_description,
							 purchase_order_details.order_number,
							 currency_code,
							 purchase_order_details.unit_price, 
							 supplier_name,
							 order_date,
							 order_status,
							 expected_date,
							 purchase_order_details.quantity,
							 purchase_order_header.posted_by,
							 purchase_order_header.next_revision_series,
							 unit_of_measure_id,
					 		 unit_of_measure_code,
							 purchase_order_details.`classification_code` as classification_code,
							 po_remarks
					  from purchase_order_header, purchase_order_details, item, currency, supplier, unit_of_measure 
					  where purchase_order_header.order_number=purchase_order_details.order_number and 
					  		supplier.id=purchase_order_header.supplier_id and
					  		purchase_order_details.item_id=item.id and 
					  		purchase_order_header.currency=currency.id and
					  		item.unit_of_measure_id=unit_of_measure.id";

	if($staff!='any'){
		$query = $query." and purchase_order_header.posted_by='$staff'";
	}
	if($supplierId=='any'){
		if($stat=='ANY'){
		}
		else if($stat=='OPEN'){
			$query = $query." and (order_status='UNSERVED' or order_status='PARTIAL DELIVERY')";
		}
		else{
			$query = $query." and order_status='$stat'";
		}
	}
	else{
		$query = $query." and purchase_order_header.supplier_id='$supplierId'";
		if($stat=='ANY'){
		}
		else if($stat=='OPEN'){
			$query = $query." and (order_status='UNSERVED' or order_status='PARTIAL DELIVERY')";
		}
		else{
			$query = $query." and order_status='$stat'";
		}
	}

	if($from!=''&&$to!=''){
		$query = $query." and date(order_date) between '$from' and '$to'";
	}
	else if($from==''&&$to!=''){
		$query = $query." and date(order_date) <= '$to'";
	}
	else if($from!=''&&$to==''){
		$query = $query." and date(order_date) between '$from' and curdate()";
	}

	if($dnfrom!=''&&$dnto!=''){
		$query = $query." and date(expected_date) between '$dnfrom' and '$dnto'";
	}
	else if($dnfrom==''&&$dnto!=''){
		$query = $query." and date(expected_date) <= '$dnto'";
	}
	else if($dnfrom!=''&&$dnto==''){
		$query = $query." and date(expected_date) between '$dnfrom' and curdate()";
	}


	$rs = query($query);
	$count = getNumRows($rs);

	
    	/*$output = "<table border='1'>
    					<tr><th colspan='16'>PURCHASE ORDER SUMMARY</th></tr>
    					<tr></tr>
    					<tr>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>POSTED BY</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>PO NO.</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>REVISION NO.</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>STATUS</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>ORDER DATE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>DATE NEEDED</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>SUPPLIER</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>ITEM CODE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>NAME</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>DESCRIPTION</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>CURRENCY</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>UNIT PRICE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>ORDERED QTY</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>RECEIVED</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>BALANCE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>AMOUNT</th>
    					</tr>
    				";*/
    	$output = "<table border='1'>
    					<tr><th colspan='2' align='left' style='font-size:25px'>Balance Order</th></tr>
    					<tr><th>Period Coverage</th><th></th></tr>
    					<tr><th align='right'>PO Date From:</th><th>$from</th></tr>
    					<tr><th align='right'>PO Date To:</th><th>$to</th></tr>
    					<tr></tr>
    					<tr>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>PO DATE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>PO NUMBER</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>REVISION NUMBER</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>PO STATUS</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>PERSON IN-CHARGE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>PR NUMBER</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>DATE NEEDED</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>CODE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>NAME</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>DESCRIPTION</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>SUPPLIER</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>CURRENCY</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>UOM</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>UNIT PRICE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>PO QUANTITY</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>RECEIVED PO QTY</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>BALANCE PO QTY</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>AMOUNT (RECEIVED QTY * UNIT PRICE)</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>AMOUNT (PO QTY * UNIT PRICE)</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>CLASSIFICATION CODE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>ALLOCATION (%)</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>PO REMARKS</th>
    						

    					</tr>
    				";
    
    $pograndtotal = 0;
    $porcvdtotal = 0;
	
    while($obj=fetch($rs)){

				$amount = $obj->unit_price*$obj->quantity;
				$pograndtotal = $pograndtotal + $amount;
				// $rcvd = getQtyReceived_2($obj->order_number,$obj->item_id,$obj->unit_price,$obj->item_name,$obj->long_description);

				//-11262019
				// $rcvd = getQtyReceived_2($obj->order_number,$obj->item_id,$obj->unit_price,$obj->item_name,$obj->long_description1);
				
				//12132019
				// $rcvd = getQtyReceived_3($obj->order_number,$obj->item_id,$obj->unit_price,$obj->item_name,$obj->long_description1,$obj->qty);
				$rcvd = getQtyReceived_3($obj->order_number,$obj->item_id,$obj->unit_price,$obj->item_name,$obj->long_description1,$obj->quantity);
				
				$bal = $obj->quantity-$rcvd;

				$rcvdamount = $obj->unit_price*$rcvd;
				$porcvdtotal = $porcvdtotal + $rcvdamount;
				$rcvdamount = convertWithDecimal($rcvdamount,2);

				$amount = convertWithDecimal($amount,2);
				$rcvd = convertWithDecimal($rcvd,0);
				$bal = convertWithDecimal($bal,0);
				$unitprice = convertWithDecimal($obj->unit_price,5);
				$qty = convertWithDecimal($obj->quantity,0);

				$orderdate = formatDate($obj->order_date);
				$dateneeded = formatDate($obj->expected_date);
				$postedby = getUserName($obj->posted_by);

				$prnumber = getRefPrNumber($obj->order_number);
				$allocation = getAllocation($prnumber); 		//add as of January 31, 2018 by Rona
				$staff = getPoAssignedTo($obj->order_number);
				
				$item_name = $obj->item_name == '' ? $obj->item_name1 : $obj->item_name;
				// $long_description = $obj->long_description == '' ? $obj->long_description1 : $obj->long_description;
				
				//-11262019
				$long_description = $obj->long_description1 == '' ? $obj->long_description1 : $obj->long_description1; 
				
				/*$output .="<tr>
								<td>$postedby</td>
								<td>$obj->order_number</td>
								<td>$obj->next_revision_series</td>
								<td>$obj->order_status</td>
								<td>$orderdate</td>
								<td>$dateneeded</td>
								<td>$obj->supplier_name</td>
								<td>$obj->item_code</td>
								<td>$obj->item_name</td>
								<td>$obj->long_description</td>
								<td>$obj->currency_code</td>
								<td>$unitprice</td>
								<td>$qty</td>
								<td>$rcvd</td>
								<td>$bal</td>
								<td>$amount</td>
							</tr>";*/
				if(($pobalance==1&&$bal>0)||$pobalance=='ANY'||($pobalance==0&&$bal==0)){
					$output .="<tr>
									<td>$orderdate</td>
									<td>$obj->order_number</td>
									<td>$obj->next_revision_series</td>
									<td>$obj->order_status</td>
									<td>$staff</td>
									<td>$prnumber</td>
									<td>$dateneeded</td>
									<td>$obj->item_code</td>
									<td>$item_name</td>
									<td>$long_description</td>
									<td>$obj->supplier_name</td>
									<td>$obj->currency_code</td>
									<td>$obj->unit_of_measure_code</td>
									<td>$unitprice</td>
									<td>$qty</td>
									<td>$rcvd</td>
									<td>$bal</td>
									<td>$rcvdamount</td>
									<td>$amount</td>
									<td>$obj->classification_code</td>
									<td>$allocation</td>
									<td>$obj->po_remarks</td>
								</tr>";
				}

	}
	if($count>0){
			$output .="<tr>
							<td colspan='15'></td>
							<th>Total</th>
							<td>".convertWithDecimal($porcvdtotal,2)."</td>
							<td>".convertWithDecimal($pograndtotal,2)."</td>
						</tr>";
	}
			if($count==0){
				$output .= "<tr><td colspan='16' align='center'>No Results Found</td></tr>";
			}
			$output .="</table>";
	header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=po-summary.xls");
    echo $output;
}
?>