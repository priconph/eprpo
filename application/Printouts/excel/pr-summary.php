<?php

include("../../../config/connection.php");
include("../../../config/checklogin.php");
include("../../../config/functions.php");
require('../../../resources/htmltopdf/fpdf.php');


if(isset($_GET['prSummaryExcelFormat01128167839'])){

	$department = escapeString($_GET['department']);
	$requestedby = escapeString($_GET['requester']);
	$supplier = escapeString($_GET['supplier']);
	$reqfrom = escapeString($_GET['reqfrom']);
	$reqto = escapeString($_GET['reqto']);
	$neededfrom = escapeString($_GET['neededfrom']);
	$neededto = escapeString($_GET['neededto']);
	$qtymin = escapeString($_GET['qtymin']);
	$qtymax = escapeString($_GET['qtymax']);
	$pricemin = escapeString($_GET['pricemin']);
	$pricemax = escapeString($_GET['pricemax']);
	$prnumber = escapeString($_GET['prnumber']);
	$prstatus = escapeString($_GET['prstatus']);

	
	$query=  "select purchase_requisition_header.requisition_number,
					 item.item_code,
					 item.item_name,
					 item.long_description,
					 requisition_type,
					 first_name,
					 middle_name,
					 last_name,
					 requisition_date,
					 requisition_status,
					 date_needed,
					 justification,
					 supplier_name,
					 purchase_requisition_details.item_id,
					 purchase_requisition_details.unit_price,
					 purchase_requisition_details.quantity,
					 currency_code,
					 unit_of_measure_code,
					 classification_code,
					 pr_notes
			  from purchase_requisition_header, 
			  	   purchase_requisition_details,
			  	   user,
			  	   supplier,
			  	   currency,
			  	   unit_of_measure,
			  	   item
			  where purchase_requisition_header.requisition_number=purchase_requisition_details.requisition_number and
			        requisitioner=user.id and
			        purchase_requisition_details.supplier_id=.supplier.id and
			        purchase_requisition_details.item_id=item.id and
			        item.unit_of_measure_id=unit_of_measure.id and
			        purchase_requisition_header.currency=currency.id";
	
    if(strtoupper($prstatus)!='ANY'){
		$query = $query." and purchase_requisition_header.requisition_status='$prstatus'";
	}
	if($prnumber!=''){
		$query = $query." and purchase_requisition_header.requisition_number='$prnumber'";
	}
	if($department!='any'){
		$query = $query." and section_department_id='$department'";
	}
	if($requestedby!='any'&&$requestedby!=''){
		$query = $query." and requisitioner='$requestedby'";
	}
	if($supplier!='any'){
		$query = $query." and purchase_requisition_details.supplier_id='$supplier'";
	}

	if($reqfrom!=''&&$reqto!=''){
		$query = $query." and date(requisition_date) between '$reqfrom' and '$reqto'";
	}
	else if($reqfrom==''&&$reqto!=''){
		$query = $query." and date(requisition_date)<='$reqto'";
	}
	else if($reqfrom!=''&&$reqto==''){
		$query = $query." and date(requisition_date) between '$reqfrom' and curdate()";
	}

	if($neededfrom!=''&&$neededto!=''){
		$query = $query." and date(date_needed) between '$neededfrom' and '$neededto'";
	}
	else if($neededfrom==''&&$neededto!=''){
		$query = $query." and date(date_needed) <= '$neededto'";
	}
	else if($neededfrom!=''&&$neededto==''){
		$query = $query." and date(date_needed) between '$neededfrom' and curdate()";
	}

	if($qtymin!=''&&$qtymax!=''){
		$query = $query." and purchase_requisition_details.quantity between ".$qtymin." and ".$qtymax;
	}
	else if($qtymin==''&&$qtymax!=''){
		$query = $query." and purchase_requisition_details.quantity<=".$qtymax;
	}
	else if($qtymin!=''&&$qtymax==''){
		$query = $query." and purchase_requisition_details.quantity>=".$qtymin;
	}

	if($pricemin!=''&&$pricemax!=''){
		$query = $query." and purchase_requisition_details.unit_price between ".$pricemin." and ".$pricemax;
	}
	else if($pricemin==''&&$pricemax!=''){
		$query = $query." and purchase_requisition_details.unit_price<=".$pricemax;
	}
	else if($pricemin!=''&&$pricemax==''){
		$query = $query." and purchase_requisition_details.unit_price>=".$pricemin;
	}
	
	$rs = query($query);
	$count = getNumRows($rs);

	
    	$output = "<table border='1'>
    					<tr><th colspan='22'>PURCHASE REQUISITION SUMMARY</th></tr>
    					<tr></tr>
    					<tr>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>PR NO.</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>STATUS</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>TYPE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>REQUISITIONER</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>PR DATE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>DATE NEEDED</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>SUPPLIER</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>ITEM ID</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>ITEM CODE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>ITEM NAME</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>DESCRIPTION</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>CLASSF'N CODE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>CURRENCY</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>UNIT PRICE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>PO #</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>ORDERED QTY</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>RECEIVED PO QTY</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>BALANCE PO QTY</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>UOM</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>AMOUNT</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>REMARKS</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>JUSTIFICATION</th>
    					</tr>
    				";
    
    $prgrandtotal = 0;
    while($obj=fetch($rs)){
				$amount = $obj->unit_price*$obj->quantity;
				$prgrandtotal = $prgrandtotal + $amount;
				$amount = convertWithDecimal($amount,2);
				$po_number = getPoNumber($obj->requisition_number,$obj->item_id);
				// $rcvd = getQtyReceived_2($obj->order_number,$obj->item_id,$obj->unit_price,$obj->item_name,$obj->long_description);
				$rcvd = getQtyReceived_2($po_number,$obj->item_id,$obj->unit_price,$obj->item_name,$obj->long_description);
				$bal = $obj->quantity-$rcvd;
				
				$rcvd = convertWithDecimal($rcvd,0);
				$bal = convertWithDecimal($bal,0);


				$output .="<tr>
								<td>$obj->requisition_number</td>
								<td>".strtoupper($obj->requisition_status)."</td>
								<td>".strtoupper($obj->requisition_type)."</td>
								<td>$obj->first_name $obj->middle_name $obj->last_name</td>
								<td>".formatDate($obj->requisition_date)."</td>
								<td>".formatDate($obj->date_needed)."</td>
								<td>".$obj->supplier_name."</td>
								<td>".$obj->item_id."</td>
								<td>".$obj->item_code."</td>
								<td>".$obj->item_name."</td>
								<td>".$obj->long_description."</td>
								<td>".$obj->classification_code."</td>
								<td>$obj->currency_code</td>
								<td>".convertWithDecimal($obj->unit_price,5)."</td>
								<td>$po_number</td>
								<td>".convertWithDecimal($obj->quantity,0)."</td>
								<td>$rcvd</td>
								<td>$bal</td>
								<td>$obj->unit_of_measure_code</td>
								<td>$amount</td>
								<td>$obj->pr_notes</td>
								<td>$obj->justification</td>
							</tr>";

	}
	if($count>0){
			$output .="<tr>
							<td colspan='21'></td>
							<th>Total Amount</th>
							<td>".convertWithDecimal($prgrandtotal,2)."</td>
						</tr>";


	}

			if($count==0){
				$output .= "<tr><td colspan='21' align='center'>No Results Found</td></tr>";
			}
			$output .="</table>";
	header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=pr-summary.xls");
    echo $output;
}
?>