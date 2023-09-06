<!-- THIS PAGE IS LOADED IN #CARTITEMS(ITEMPRICE SCHEDULE VIEW) -->
<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/db-function.php");

    if(isset($_GET['KePfoeFkeu%lsfmWL$l2jqp'])){
    	$linenumber=1;
    	$id = $_GET['KePfoeFkeu%lsfmWL$l2jqp'];
    	$query = "select item_id,
    					 item_price_schedule_details.unit_price,
    					 item_price_schedule_details.remarks, 
    					 item_code,
    					 short_description
    			  from item_price_schedule_details, item 
    			  where item_id=item.id and transaction_number='$id'";
    	$rs=query($query);
    	while($obj=fetch($rs)){
    		$itemcode = $obj->item_code;
    		$desc = $obj->short_description;
    		$price = $obj->unit_price;
    		$itemremarks = $obj->remarks;
    		echo "
    			<tr>
    				<td></td>
    				<td class='text-center'>$linenumber</td>
    				<td>$itemcode</td>
    				<td>$desc</td>
    				<td class='text-center'>$price</td>
    				<td>$itemremarks</td>
    			</tr>
    		";
    		$linenumber++;
    	}
    }


?>