<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include('../../config/functions.php');

  /*   $ponumber = (isset($_GET['KJeslkrioj43lk']))?$_GET['KJeslkrioj43lk']:'';
    if($ponumber==''){
        exit('DIRECT ACCESS NOT ALLOWED');
    }
    else{
        $query = "select item.item_code,
                         item.item_name as item_name1,
                         purchase_order_details.item_name,
                         purchase_order_details.long_description,
                         item.long_description as long_description1,
                         receiving_details.unit_price,
                         quantity_received,
                         received_by,
                         received_date,
                         unit_of_measure_code
                  from receiving_details, receiving_header, item, unit_of_measure 
                  where receiving_details.receiving_number=receiving_header.receiving_number and
                        item.id=item_id and 
                        reference_po_number='$ponumber' and
                        unit_of_measure_id=unit_of_measure.id";
        $rs = query($query);
        $line=1;
        if(getNumRows($rs)<1){
          echo "<tr><td class='noresult' colspan='20'>No Items Received</td></tr>";
        }
        while($obj=fetch($rs)){
            $code = $obj->item_code;
			$name = strUtfEncode($obj->item_name) == '' ? '*'.strUtfEncode($obj->item_name1) : strUtfEncode($obj->item_name);
            $description = strUtfEncode($obj->long_description) == '' ? '*'.strUtfEncode($obj->long_description1) : strUtfEncode($obj->long_description);
            $uom = $obj->unit_of_measure_code;
            $price=convertWithDecimal($obj->unit_price,5);
            $qty = convertWithDecimal($obj->quantity_received,0);
            $receiver = strUtfEncode(getUserName($obj->received_by));
            $datereceived = formatDate4($obj->received_date);

            echo "<tr title='Item Description: \n$description'>
                    <td></td>
                    <td class='text-center'>$line</td>
                    <td>$code</td>
                    <td>$name</td>
                    <td>$description</td>
                    <td class='text-right'>$price</td>
                    <td class='text-center'>$uom</td>
                    <td class='text-right'>$qty</td>
                    <td>$receiver</td>
                    <td class='text-center'>$datereceived</td>
                  </tr>";
            $line++;
        }

       
    
    } */
    
	$ponumber = (isset($_GET['KJeslkrioj43lk']))?$_GET['KJeslkrioj43lk']:'';
    if($ponumber==''){
        exit('DIRECT ACCESS NOT ALLOWED');
    }    
	else{
        $query = "SELECT a.receiving_number, b.receiving_number as receiving_number_rd, a.reference_po_number, c.order_number as order_number_pod, a.received_date, a.other_reference, a.received_by, b.item_id, c.item_id as item_id_pod, c.quantity, b.quantity_received, d.item_code, d.item_name, d.long_description, d.unit_price, d.unit_of_measure_id, e.unit_of_measure_code
			FROM `receiving_header` a 
			LEFT JOIN receiving_details b on b.receiving_number = a.receiving_number
			LEFT JOIN purchase_order_details c on c.order_number = a.reference_po_number and b.item_id = c.item_id
		  LEFT JOIN item d on d.id = b.item_id
			LEFT JOIN unit_of_measure e on e.id = d.unit_of_measure_id
			WHERE a.reference_po_number = '$ponumber' 
			GROUP BY d.item_name, d.long_description, d.unit_price, a.received_date
			ORDER BY d.item_name, d.long_description, d.unit_price, a.received_date ASC";
      
       // LEFT JOIN item d on d.id = b.item_id and d.id = c.item_id

        $rs = query($query);
        $line = 1;
        $eoh = 0;
		    $prevItemId = '';
        while($obj=fetch($rs)){
		
			$invoice = $obj->other_reference;
            $itemId = $obj->item_id;
			$shortdesc = strUtfEncode($obj->item_name) == '' ? ''.strUtfEncode($obj->item_name) : strUtfEncode($obj->item_name);
			$longdesc = strUtfEncode($obj->long_description) == '' ? ''.strUtfEncode($obj->long_description) : strUtfEncode($obj->long_description);
			$itemcode = $obj->item_code;
			$price = $obj->unit_price;
			$uom = $obj->unit_of_measure_code;
			$qty = $obj->quantity;
			$rcvdqty = $obj->quantity_received;
            $price = convertWithDecimal($price,6);
			$receiver = strUtfEncode(getUserName($obj->received_by));
            $datereceived = formatDate4($obj->received_date);
			
			if ( $line == 1){
				$eoh = $qty;
				$prevItemId = $itemId;
			}
			
			if ($prevItemId == $itemId && $eoh <= 0){
				$eoh = $qty;				
			}
			if($prevItemId == $itemId) {
				$balx = $eoh - $rcvdqty; 
                $bal = number_format((float)$balx, 2, '.', ''); //09132024 by Nessa
			} else {
				$balx = $qty - $rcvdqty;
				$bal = number_format((float)$balx, 2, '.', ''); //09132024 by Nessa
			}
			
			echo "<tr title='Item Description: \n$longdesc'>
			<td class='text-center'>$line</td>
			<td>$invoice</td>
			<td>$itemcode</td>
			<td>$shortdesc</td>
			<td>$longdesc</td>
			<td class='text-right rowprice'>$price</td>
			<td class='text-center'>$uom</td>
			<td class='text-center'>$qty</td>
			<td class='text-center'>$rcvdqty</td>
			<td class='text-center'>$bal</td>
			<td class='text-left'>$receiver</td>
			<td class='text-center'>$datereceived</td>
		   
		   </tr>";
		   
			$line++;    
			
			if($itemId != $prevItemId) {
				$prevItemId = $itemId;
			}
			$eoh = $bal;		   
        }
		
		
    }

?>