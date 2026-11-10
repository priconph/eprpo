<?php

include("../../../config/connection.php");
include("../../../config/checklogin.php");
include("../../../config/functions.php");
require('../../../resources/htmltopdf/fpdf.php');


if(isset($_GET['ItemSummaryExcelFormat'])){

	// $from = escapeString($_GET['orderdatefrom']);
	// $to = escapeString($_GET['orderdateto']);

	$query=  "SELECT a.id, 
                a.item_code, 
                a.item_name, 
                a.long_description, 
                b.unit_of_measure_code, 
                a.unit_price, 
                CASE
                WHEN a.active_flag = 1 THEN 'Active'
                WHEN a.active_flag = 0 THEN 'Inactive'
                END AS active_status, 
                a.created_by_user_id, 
                a.created_date,
                a.updated_by_user_id, 
                a.updated_date,
                e.currency_code,
                g.supplier_name,
                h.quantity as po_qty,
                h.unit_price as po_unit_price,
                h.classification_code as po_classification_code

                FROM item a 
                LEFT JOIN unit_of_measure b ON b.id = a.unit_of_measure_id
                LEFT JOIN currency e ON e.id = a.default_currency
                LEFT JOIN item_supplier f ON f.item_id = a.id
                LEFT JOIN supplier g ON g.id = f.supplier_id
                LEFT JOIN purchase_order_details h ON h.item_id = a.id
                GROUP BY a.item_code

            ";
            // SELECT a.id, 
            //     a.item_code, 
            //     a.item_name, 
            //     a.long_description, 
            //     b.unit_of_measure_code, 
            //     a.unit_price, 
            //     CASE
            //     WHEN a.active_flag = 1 THEN 'Active'
            //     WHEN a.active_flag = 0 THEN 'Inactive'
            //     END AS active_status, 
            //     a.created_by_user_id, 
            //     CONCAT(c.first_name, ' ', c.last_name) as created_by, 
            //     a.created_date,
            //     a.updated_by_user_id, 
            //     CONCAT(d.first_name, ' ', d.last_name) as updated_by,
            //     a.updated_date,
            //     e.currency_code,
            //     g.supplier_name,
            //     h.quantity as po_qty,
            //     h.unit_price as po_unit_price,
            //     h.classification_code as po_classification_code

            //     FROM item a 

            //     LEFT JOIN unit_of_measure b ON b.id = a.unit_of_measure_id
            //     LEFT JOIN user c ON c.id = a.created_by_user_id
            //     LEFT JOIN user d ON d.id = a.updated_by_user_id
            //     LEFT JOIN currency e ON e.id = a.default_currency
            //     LEFT JOIN item_supplier f ON f.item_id = a.id
            //     LEFT JOIN supplier g ON g.id = f.supplier_id
            //     LEFT JOIN purchase_order_details h ON h.item_id = a.id
            //     GROUP BY a.item_code

                // WHERE created_date between '$from' and '$to'
				  		
	
	// if($from!=''&&$to!=''){
	// 	$query = $query." and date(received_date) between '$from' and '$to'";
	// }
	
	$rs = query($query);
	$count = getNumRows($rs);

    	$output = "
					<style>
						.num {
						  mso-number-format:General;
						}
						.text{
						  mso-number-format:'\@';/*force text*/
						}
					</style>

    				<table border='1'>
    					<tr><th colspan='13' align='left' style='font-size:25px'>Item Summary</th></tr>
    					<tr>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>ITEM CODE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>ITEM NAME</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>ITEM DESC</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>UOM</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>CURRENCY</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>UNIT PRICE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>SUPPLIER</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>CLASS CODE</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>STATUS</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>CREATED BY</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>DATE CREATED</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>UPDATED BY</th>
    						<th class='text-left' style='background-color:#0F3057; color:#fff'>DATE UPDATED</th>
    					</tr>
    				";

   		while($obj=fetch($rs)){
			
                $output .="<tr>	
                    <td>".$obj->item_code."</td>
                    <td>".$obj->item_name."</td>
                    <td>".$obj->long_description."</td>
                    <td>".$obj->unit_of_measure_code."</td>
                    <td>".$obj->currency_code."</td>
                    <td>".convertWithDecimal($obj->unit_price,6)."</td>
                    <td>".$obj->supplier_name."</td>
                    <td>".$obj->po_classification_code."</td>
                    <td>".$obj->active_status."</td>
                    <td>".getUserName($obj->created_by_user_id)."</td>
                    <td>".formatDate($obj->created_date)."</td>
                    <td>".getUserName($obj->updated_by_user_id)."</td>
                    <td>".formatDate($obj->updated_date)."</td>
                    </tr>";

		}
		
		//NON CATALOG ITEMS - END
	    if($count==0){
			$output .= "<tr><td colspan='13' align='center'>No Results Found</td></tr>";
		}
		$output .="</table>";
	
	header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=item-summary.xls");
    echo $output;
}

?>