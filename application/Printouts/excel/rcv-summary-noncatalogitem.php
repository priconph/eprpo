<?php

include("../../../config/connection.php");
include("../../../config/checklogin.php");
include("../../../config/functions.php");
require('../../../resources/htmltopdf/fpdf.php');


if(isset($_GET['rcvSummaryNonCatalogItemExcelFormat1231003435'])){

	$item = strtoupper(escapeString($_GET['itemcode']));
	$from = escapeString($_GET['orderdatefrom']);
	$to = escapeString($_GET['orderdateto']);
	$query=  "select item_code,
					 item_name,
					 receiving_details_non_catalog.receiving_number,
					 receiving_details_non_catalog.item_description,
					 receiving_header.received_date,
					 receiving_header.received_by,
					 receiving_header.actual_delivery_date,
					 supplier_name,
					 item_code,
					 unit_price,
					 quantity_received,
					 currency_code,
					 reference_po_number,
					 other_reference,
					 unit_of_measure,
					 unit_of_measure_code,
					 (SELECT purchase_order_details.`classification_code` FROM `purchase_order_details` WHERE purchase_order_details.`order_number` = receiving_header.reference_po_number AND purchase_order_details.item_id= receiving_details_non_catalog.item_code LIMIT 0,1) as classification_code,
					 (SELECT purchase_order_header.`po_remarks` FROM `purchase_order_header` WHERE purchase_order_header.`order_number` = receiving_header.reference_po_number LIMIT 0,1) as po_remarks
				  from receiving_header, receiving_details_non_catalog, supplier, currency, unit_of_measure
				  where receiving_header.receiving_number=receiving_details_non_catalog.receiving_number and
				  		supplier.id=receiving_header.supplier_id and
				  		receiving_header.currency=currency.id and
				  		receiving_details_non_catalog.unit_of_measure=unit_of_measure.id
				  		";
	if($item!=''){
		$query = $query." and receiving_details_non_catalog.item_name like '%$item%'";

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
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>RECEIVED BY</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>INVOICE NO.</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>RCV NO.</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>CLASSIFICATION CODE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>ALLOCATION (%)</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>PO REMARKS</th>
    					</tr>
    				";
    

    while($obj=fetch($rs)){
    			$ref = ($obj->reference_po_number==NULL)?$obj->other_reference:$obj->reference_po_number;
				$output .="<tr>
								<td>NO PO REFERENCE</td>
								<td>NO PO REFERENCE</td>
								<td>NO PR REFERENCE</td>
								<td>".$obj->item_code."</td>
								<td>".$obj->item_name."</td>
								<td>".$obj->item_description."</td>
								<td>".$obj->supplier_name."</td>
								<td>".$obj->currency_code."</td>
								<td>".$obj->unit_of_measure_code."</td>
								<td>".convertWithDecimal($obj->unit_price,5)."</td>
								<td>".convertWithDecimal($obj->quantity_received,0)."</td>
								<td>NO PO REFERENCE</td>
								<td>".formatDate($obj->received_date)."</td>
								<td>".getUserName($obj->received_by)."</td>
								<td>".$obj->other_reference."</td>
								<td>".$obj->receiving_number."</td>
								<td>".$obj->classification_code."</td>
								<td>NO PR REFERENCE</td>
								<td>".$obj->po_remarks."</td>
							</tr>";

	}
			if($count==0){
				$output .= "<tr><td colspan='11' align='center'>No Results Found</td></tr>";
			}
			$output .="</table>";
	
	header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=rcv-summary-noncatalogitem.xls");
    echo $output;
}
?>