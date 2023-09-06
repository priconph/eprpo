<style type="text/css">
/*
	* {
	    color: #2b2b2b;
	    font-family: "Roboto Condensed";
	}

	th {
	    text-align: left;
	    color: #4679bd;
	}

	tbody > tr:nth-of-type(even) {
	    background-color: #daeaff;
	}

	button {
	    cursor: pointer;
	    margin-top: 1rem;
	}
	*/
</style>



<?php

include("../../../config/connection.php");
// include("../../../config/checklogin.php");
include("../../../config/functions.php");
// require('../../../resources/htmltopdf/fpdf.php');


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
						 supplier.code AS supplier_code,
						 order_date,
						 order_status,
						 expected_date,
						 purchase_order_details.quantity,
						 purchase_order_header.posted_by,
						 purchase_order_header.next_revision_series,
						 purchase_order_header.updated_date,
						 unit_of_measure_id,
				 		 unit_of_measure_code,
						 purchase_order_details.`classification_code` as classification_code,
						 po_remarks
				  from purchase_order_header, 
				  		purchase_order_details, 
				  		item, 
				  		currency, 
				  		supplier, 
				  		unit_of_measure 
				  where purchase_order_header.order_number=purchase_order_details.order_number and 
				  		supplier.id=purchase_order_header.supplier_id and
				  		purchase_order_details.item_id=item.id and 
				  		purchase_order_header.currency=currency.id and
				  		item.unit_of_measure_id=unit_of_measure.id

				  		and date(order_date) > '2017-03-31' 
				  		and (
				  			date(order_date) = '".date('Y-m-d')."'
				  			or date(purchase_order_header.updated_date) = '".date('Y-m-d')."' 
				  			or date(purchase_order_header.posted_date) = '".date('Y-m-d')."' 
				  			or date(purchase_order_header.date_flag) = '".date('Y-m-d')."'
				  			or date(purchase_order_header.printed_timestamp) = '".date('Y-m-d')."'
				  			or date(purchase_order_header.datetime_assigned) = '".date('Y-m-d')."'
				  			)
				  		";
// echo $query;
$rs = query($query);
$count = getNumRows($rs);
	$output = "<table border='1' id='tbl_summary'>
					<tr>
						<th>PO DATE</th>
						<th>PO NUMBER</th>
						<th>REVISION NUMBER</th>
						<th>PO STATUS</th>
						<th>PERSON IN-CHARGE</th>
						<th>PR NUMBER</th>
						<th>DATE NEEDED</th>
						<th>CODE</th>
						<th>NAME</th>
						<th>DESCRIPTION</th>
						<th>SUPPLIER</th>
						<th>CURRENCY</th>
						<th>UOM</th>
						<th>UNIT PRICE</th>
						<th>PO QUANTITY</th>
						<th>RECEIVED PO QTY</th>
						<th>BALANCE PO QTY</th>
						<th>AMOUNT (RECEIVED QTY * UNIT PRICE)</th>
						<th>AMOUNT (PO QTY * UNIT PRICE)</th>
						<th>CLASSIFICATION CODE</th>
						<th>ALLOCATION (%)</th>
						<th>PO REMARKS</th>
						<th>SUPPLIER CODE</th>
					</tr>
				";

$pograndtotal = 0;
$porcvdtotal = 0;
$pobalance = 'ANY';


while($obj=fetch($rs)){

			$amount = $obj->unit_price*$obj->quantity;
			$pograndtotal = $pograndtotal + $amount;
			// $rcvd = getQtyReceived_2($obj->order_number,$obj->item_id,$obj->unit_price,$obj->item_name,$obj->long_description);

			//-11262019
			// $rcvd = getQtyReceived_2($obj->order_number,$obj->item_id,$obj->unit_price,$obj->item_name,$obj->long_description1);
			
			//12132019
			$rcvd = getQtyReceived_3($obj->order_number,$obj->item_id,$obj->unit_price,$obj->item_name,$obj->long_description1,$obj->qty);
			
			$bal = $obj->quantity-$rcvd;

			$rcvdamount = $obj->unit_price*$rcvd;
			$porcvdtotal = $porcvdtotal + $rcvdamount;
			$rcvdamount = convertWithDecimal($rcvdamount,2);

			$amount = convertWithDecimal($amount,2);
			$rcvd = convertWithDecimal($rcvd,0);
			$bal = convertWithDecimal($bal,0);
			$unitprice = convertWithDecimal($obj->unit_price,5);
			$qty = convertWithDecimal($obj->quantity,0);

			$orderdate = $obj->order_date;
			$dateneeded = $obj->expected_date;
			$postedby = getUserName($obj->posted_by);

			$prnumber = getRefPrNumber($obj->order_number);
			$allocation = getAllocation($prnumber); 		//add as of January 31, 2018 by Rona
			$staff = getPoAssignedTo($obj->order_number);
			
			$item_name = $obj->item_name == '' ? $obj->item_name1 : $obj->item_name;
			// $long_description = $obj->long_description == '' ? $obj->long_description1 : $obj->long_description;
			
			//-11262019
			$long_description = $obj->long_description1 == '' ? $obj->long_description1 : $obj->long_description1; 
			
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
								<td>$obj->supplier_code</td>
							</tr>";
			}
}
//check if rows  = ctr, then call function to csv
// if($count>0){
// 		$output .="<tr>
// 						<td colspan='15'></td>
// 						<th>Total</th>
// 						<td>".convertWithDecimal($porcvdtotal,2)."</td>
// 						<td>".convertWithDecimal($pograndtotal,2)."</td>
// 					</tr>";
// }
		// if($count==0){
		// 	$output .= "<tr><td colspan='16' align='center'>No Results Found</td></tr>";
		// }
		$output .="</table>";
// header("Content-Type: text/csv");
// header("Content-Disposition: attachment; filename=po-summary.csv");
// echo $output;


echo $output;



?>
<script type="text/javascript" src="../../../js/jquery-1.11.3.min.js"></script>
<script type="text/javascript">
function download_csv(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV FILE
    csvFile = new Blob([csv], {type: "text/csv"});

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // We have to create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Make sure that the link is not displayed
    downloadLink.style.display = "none";

    // Add the link to your DOM
    document.body.appendChild(downloadLink);

    // Lanzamos
    downloadLink.click();
}

function export_table_to_csv(html, filename) {
	var csv = [];
	var rows = document.querySelectorAll("table tr");
	
    for (var i = 0; i < rows.length; i++) {
		var row = [], cols = rows[i].querySelectorAll("td, th");
		
        for (var j = 0; j < cols.length; j++) 
			row.push("\""+cols[j].innerText+"\"");
            // row.push(cols[j].innerText);
        
		csv.push(row.join(","));		
	}

    // Download CSV
    download_csv(csv.join("\n"), filename);
}


setTimeout(function() { 
	var html = document.querySelector("table").outerHTML;
	export_table_to_csv(html, "eprpo"+"<?php echo date('Ymd'); ?>"+".csv");
	// setTimeout(function() { 
	// 	var data = {
	// 		'action' : 'action_copy',
	// 	}

	// 	$.ajax({
	// 		'data'      : data,
	// 		'type'      : 'post',
	// 		'dataType'  : 'json',
	// 		'url'       : 'po-summary-nav-functions.php',
	// 		success     : function(data){
	// 			console.log('Uploaded');
	// 		},
	// 	});
	// }, 5000);

}, 5000);
</script>

	<!-- export_table_to_csv(html, "eprpo"+"<?php //echo date('Ymd', strtotime( date('Y-m-d H:i:s') . ' +1 day')); ?>"+".csv"); -->
