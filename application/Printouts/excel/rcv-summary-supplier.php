<?php

include("../../../config/connection.php");
include("../../../config/checklogin.php");
include("../../../config/functions.php");
require('../../../resources/htmltopdf/fpdf.php');


if(isset($_GET['rcvSummarySupplierExcelFormat1231003435'])){

	$supplierId = strtoupper(escapeString($_GET['suppId']));
	$supplier = strtoupper(escapeString($_GET['supplier']));
	$from = escapeString($_GET['orderdatefrom']);
	$to = escapeString($_GET['orderdateto']);
	$query=  "select item.item_name as item_name1,
					 item.long_description as long_description1,
					 receiving_details.item_name,
					 receiving_details.long_description,
					 receiving_details.receiving_number,
					 receiving_header.received_date,
					 receiving_header.received_by,
					 receiving_header.actual_delivery_date,
					 supplier_name,
					 item_code,
					 receiving_details.unit_price,
					 receiving_details.quantity_received,
					 currency_code,
					 reference_po_number,
					 other_reference,
					  (SELECT purchase_order_details.`classification_code` FROM `purchase_order_details` WHERE purchase_order_details.`order_number` = receiving_header.reference_po_number AND purchase_order_details.item_id= receiving_details.item_id LIMIT 0,1) as classification_code,
					 (SELECT purchase_order_header.`po_remarks` FROM `purchase_order_header` WHERE purchase_order_header.`order_number` = receiving_header.reference_po_number LIMIT 0,1) as po_remarks
				  from receiving_header, receiving_details, item, supplier, currency
				  where receiving_header.receiving_number=receiving_details.receiving_number and
				  		supplier.id=receiving_header.supplier_id and
				  		receiving_header.currency=currency.id and 
				  		item.id=receiving_details.item_id and
				  		receiving_header.supplier_id='$supplierId'";
	
	if($from!=''&&$to!=''){
		$query = $query." and date(received_date) between '$from' and '$to'";
	}
	else if($from==''&&$to!=''){
		$query = $query." and date(received_date) <= '$to'";
	}
	else if($from!=''&&$to==''){
		$query = $query." and date(received_date) between '$from' and curdate()";
	}
	
	$rs = query($query);
	$count = getNumRows($rs);

	
    	$output = "<table border='1'>
    					<tr><th colspan='10'>RECEIVING SUMMARY BY SUPPLIER</th></tr>
    					<tr><th>SUPPLIER:</th><td>$supplier</td></tr>
    					<tr></tr>
    					<tr>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>DATE RCVD</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>RCV NUMBER</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>REFERENCE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>CODE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>ITEM NAME</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>DESCRIPTION</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>CURRENCY</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>UNIT PRICE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>QTY RCVD</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>RCVD BY</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>CLASSIFICATION CODE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>ALLOCATION (%)</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>PO REMARKS</th>
    					</tr>
    				";
    

    while($obj=fetch($rs)){
    			$ref = ($obj->reference_po_number==NULL)?$obj->other_reference:$obj->reference_po_number;
				$prnumber = getRefPrNumber($obj->reference_po_number);
				$item_name = $obj->item_name == '' ? $obj->item_name1 : $obj->item_name;
				$long_description = $obj->long_description == '' ? $obj->long_description1 : $obj->long_description;
    			$allocation = getAllocation($prnumber); 		//add as of January 31, 2018 by Rona
				$output .="<tr>
								<td>".formatDate($obj->received_date)."</td>
								<td>".$obj->receiving_number."</td>
								<td>$ref</td>
								<td>".$obj->item_code."</td>
								<td>".$item_name."</td>
								<td>".$long_description."</td>
								<td>".$obj->currency_code."</td>
								<td>".convertWithDecimal($obj->unit_price,6)."</td>
								<td>".convertWithDecimal($obj->quantity_received,0)."</td>
								<td>".getUserName($obj->received_by)."</td>
								<td>".$obj->classification_code."</td>
								<td>".$allocation."</td>
								<td>".$obj->po_remarks."</td>
							</tr>";

	}


			if($count==0){
				$output .= "<tr><td colspan='10' align='center'>No Results Found</td></tr>";
			}
			$output .="</table>";
	
	header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=rcv-summary-supplier.xls");
    echo $output;
}
?>