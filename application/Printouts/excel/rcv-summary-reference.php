<?php

include("../../../config/connection.php");
include("../../../config/checklogin.php");
include("../../../config/functions.php");
require('../../../resources/htmltopdf/fpdf.php');


if(isset($_GET['rcvSummaryReferenceExcelFormat1231003435'])){

	$by = strtoupper(escapeString($_GET['by']));
	$reference = strtoupper(escapeString($_GET['reference']));
	$ponumber = strtoupper(escapeString($_GET['ponumber']));
	$from = escapeString($_GET['orderdatefrom']);
	$to = escapeString($_GET['orderdateto']);
	$query=  "select item.item_name,
					 item.long_description,
					 receiving_details.receiving_number,
					 receiving_header.received_date,
					 receiving_header.actual_delivery_date,
					 receiving_header.received_by,
					 supplier_name,
					 other_reference,
					 reference_po_number,
					 item.item_code,
					 receiving_details.unit_price,
					 receiving_details.quantity_received,
					 currency_code,
					 unit_of_measure_id,
					 unit_of_measure_code,
					 (SELECT purchase_order_details.`classification_code` FROM `purchase_order_details` WHERE purchase_order_details.`order_number` = receiving_header.reference_po_number LIMIT 0,1) as classification_code,
					 (SELECT purchase_order_header.`reference_requisition_number` FROM `purchase_order_header` WHERE purchase_order_header.`order_number` = receiving_header.reference_po_number LIMIT 0,1) as reference_requisition_number,
					 (SELECT purchase_order_header.`po_remarks` FROM `purchase_order_header` WHERE purchase_order_header.`order_number` = receiving_header.reference_po_number LIMIT 0,1) as po_remarks,
 					 (SELECT hold_invoice_remarks.`remarks` FROM `hold_invoice_remarks` WHERE hold_invoice_remarks.`reference_po_number` = receiving_header.reference_po_number LIMIT 0,1) as hold_remarks

				  from receiving_header, receiving_details, item, supplier, currency, unit_of_measure
				  where receiving_header.receiving_number=receiving_details.receiving_number and 
				  		supplier.id=receiving_header.supplier_id and
				  		receiving_header.currency=currency.id and 
				  		item.id=receiving_details.item_id and
				  		item.unit_of_measure_id=unit_of_measure.id and
				  		reference_po_number is not null";
	if(strtoupper(trim($ponumber))!=''){
		$query = $query." and reference_po_number='$ponumber'";
	}
	if(strtoupper(trim($reference))!=''){
		$query = $query." and other_reference='$reference'";
	}
	if($from!=''&&$to!=''){
		$query = $query." and date(received_date) between '$from' and '$to'";
	}
	else if($from==''&&$to!=''){
		$query = $query." and date(received_date) <= '$to'";
	}
	else if($from!=''&&$to==''){
		$query = $query." and date(received_date) between '$from' and curdate()";
	}
	else if($from!=''&&$to==''){
		$query = $query." and date(actual_delivery_date) between '$from' and curdate()";
	}

	$rs = query($query);
	$count = getNumRows($rs);

	
    	$output = "<table border='1'>
    					<tr><th colspan='2' align='left' style='font-size:25px'>Delivery Report</th></tr>
    					<tr><th>Period Coverage</th><th></th></tr>
    					<tr><th align='right'>From:</th><th>$from</th></tr>
    					<tr><th align='right'>To:</th><th>$to</th></tr>
    					<tr></tr>
    					<tr>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>PO DATE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>PO NUMBER</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>PR NUMBER</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>CODE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>NAME</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>DESCRIPTION</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>SUPPLIER</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>CURRENCY</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>UOM</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>UNIT PRICE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>RECEIVED QUANTITY</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>PERSON IN-CHARGE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>RECEIVED DATE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>DELIVERY DATE</th> 
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>RECEIVED BY</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>INVOICE NO.</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>RCV NO.</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>CLASSIFICATION CODE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>ALLOCATION (%)</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>PO REMARKS</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>HOLD REMARKS</th>
    					</tr>
    				";
    

    while($obj=fetch($rs)){
				$prnumber = getRefPrNumber($obj->reference_po_number);
				$allocation = getAllocation($prnumber); 		//add as of January 31, 2018 by Rona
				$output .="<tr>
								<td>".getPoDate($obj->reference_po_number)."</td>
								<td>".$obj->reference_po_number."</td>
								<td>".$obj->reference_requisition_number."</td>
								<td>".$obj->item_code."</td>
								<td>".$obj->item_name."</td>
								<td>".$obj->long_description."</td>
								<td>".$obj->supplier_name."</td>
								<td>".$obj->currency_code."</td>
								<td>".$obj->unit_of_measure_code."</td>
								<td>".convertWithDecimal($obj->unit_price,5)."</td>
								<td>".convertWithDecimal($obj->quantity_received,0)."</td>
								<td>".getPoAssignedTo($obj->reference_po_number)."</td>
								<td>".formatDate($obj->received_date)."</td>
								<td>".formatDate($obj->actual_delivery_date)."</td>
								<td>".getUserName($obj->received_by)."</td>
								<td>".$obj->other_reference."</td>
								<td>".$obj->receiving_number."</td>
								<td>".$obj->classification_code."</td>
								<td>".$allocation."</td>
								<td>".$obj->po_remarks."</td>
								<td>".$obj->hold_remarks."</td>
							</tr>";

	}
			if($count==0){
				$output .= "<tr><td colspan='16' align='center'>No Results Found</td></tr>";
			}
			$output .="</table>";
	
	header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=rcv-summary-reference.xls");
    echo $output;
}
?>