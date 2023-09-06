<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include('../../config/functions.php');

    $rcvnum = (isset($_GET['KJeslkrioj43lk']))?$_GET['KJeslkrioj43lk']:'';
    if($rcvnum==''){
    	exit('DIRECT ACCESS NOT ALLOWED');
    }
    else{
    	$query = "select item.item_code,
						item.item_name as item_name1,
						receiving_details.item_name,
						receiving_details.long_description,
						item.long_description as long_description1,
    					 receiving_details.unit_price,
    					 receiving_details.qty_received_for_approval,
    					 quantity_received,
    					 unit_of_measure_code
    			  from receiving_details, item, unit_of_measure 
    			  where item.id=item_id and 
    			        receiving_number='$rcvnum' and
    			        unit_of_measure_id=unit_of_measure.id";
    	$rs = query($query);
    	$line=1;
        while($obj=fetch($rs)){
    		$code = $obj->item_code;
			$name = strUtfEncode($obj->item_name) == '' ? ''.strUtfEncode($obj->item_name1) : strUtfEncode($obj->item_name);
			$description = strUtfEncode($obj->long_description) == '' ? ''.strUtfEncode($obj->long_description1) : strUtfEncode($obj->long_description);
    		$uom = $obj->unit_of_measure_code;
    		$price=convertWithDecimal($obj->unit_price,5);
    		$qty = convertWithDecimal($obj->quantity_received,0);
    		$qtyapp = $obj->qty_received_for_approval == 0 ? '-' : convertWithDecimal($obj->qty_received_for_approval,0);

    		echo "<tr title='Item Description: \n$description'>
    				<td class='text-center'>$line</td>
    				<td>$code</td>
    				<td>$name</td>
                    <td>$description</td>
    				<td class='text-right'>$price</td>
    				<td class='text-center'>$uom</td>
    				<td class='text-right'>$qty</td>
    				<td class='text-right'>$qtyapp</td>
    			  </tr>";
    		$line++;
    	}

    	$query = "select * from receiving_details_non_catalog, unit_of_measure where unit_of_measure.id=unit_of_measure and receiving_number='$rcvnum'";
    	$rs = query($query);
    	while($obj=fetch($rs)){
    		$code = $obj->item_code;
    		$name=$obj->item_name;
    		$description = $obj->item_description;
    		$price=convertWithDecimal($obj->unit_price,2);
    		$uom = $obj->unit_of_measure_code;
    		$qty = convertWithDecimal($obj->quantity_received,0);

    		echo "<tr title='Item Description: \n$description'>
    				<td class='text-center'>$line</td>
    				<td>$code</td>
    				<td>$name</td>
                    <td>$description</td>
    				<td class='text-right'>$price</td>
    				<td class='text-center'>$uom</td>
    				<td class='text-right'>$qty</td>
    			  </tr>";
    		$line++;
    	}

    }
    

?>