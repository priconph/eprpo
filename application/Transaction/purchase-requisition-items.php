<!-- THIS PAGE IS LOADED IN #CARTITEMS(PURCHASE REQUISITION VIEW) -->
<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/checklogin.php");
    include("../../config/functions.php");

    if(isset($_GET['LkNoNslOeiHdo%pdD'])){
    	$linenumber=1;
    	$prnumber = $_GET['LkNoNslOeiHdo%pdD'];
    	$query = "select item_code,
                         purchase_requisition_details.item_id,
    					 item.item_name,
                         long_description,
    					 purchase_requisition_details.quantity,
    					 unit_of_measure_code,
    					 purchase_requisition_details.unit_price,
    					 supplier_name, 
                         currency.currency_code as curr,
                         classification_code,
                         classification_code.acct_name
    		      from purchase_requisition_details
                  left join purchase_requisition_header on purchase_requisition_details.requisition_number = purchase_requisition_header.requisition_number
                  left join item on purchase_requisition_details.item_id=item.id
                  left join supplier on purchase_requisition_details.supplier_id=supplier.id
                  left join unit_of_measure on unit_of_measure_id=unit_of_measure.id
                  left join currency on purchase_requisition_header.currency=currency.id
                  left join classification_code on purchase_requisition_details.classification_code=classification_code.acct_code
    		      where purchase_requisition_details.requisition_number='$prnumber'";
    	$rs = query($query);
        if(getNumRows($rs)<1){
            echo "<tr><td class='noresult' colspan='20'>No Items Found</td></tr>";
        } 
    	while($obj=fetch($rs)){
            $itemid = $obj->item_id;
    		$itemcode = $obj->item_code;
    		$name=strUtfEncode($obj->item_name);
            $desc = strUtfEncode($obj->long_description);
    		$qty=$obj->quantity;
    		$uom=$obj->unit_of_measure_code;
            $classfn = strUtfEncode($obj->classification_code);
            $classfn_name = strUtfEncode($obj->acct_name);
    		$price=$obj->unit_price;
    		$amount = $qty*$price;
            $currency = $obj->curr;
            $qty=convertWithDecimal($obj->quantity,0);
    		$price = convertWithDecimal($price,5);
    		// $amount = convertWithDecimal($amount,2);
    		$supplier=strUtfEncode($obj->supplier_name);
    		$po=getPoNumber($prnumber,$itemid);
            $postat = getPoStatus($po);
			$poreason = getPoCancelReason($po);

			$classfn = $classfn." - ".$classfn_name;


			if($prnumber == 'PPSTS-PR2605-024'){
    			$amount = $amount - 0.02;
			}
			else{
    			$amount = convertWithDecimal($amount,2);
				
			}
			
    		echo "<tr title='Item Description: \n$desc'>
    				<td class='text-center'>$linenumber</td>
    				<td>$itemcode</td>
    				<td>$name</td>
                    <td>$desc</td>
                    <td>$supplier</td>
                    <td class='text-center'>$classfn </td>
    				<td class='text-right'>$qty</td>
    				<td class='text-center'>$uom</td>
                    <td class='text-center'>$currency</td>
    				<td class='text-right'>$price</td>
    				<td class='text-right'>$amount</td>
    				<td class='text-center text-nowrap'>$po</td>
                    <td class='text-center text-nowrap'>$postat</td>
                    <td class='text-center text-nowrap'>$poreason</td>
    			  </tr>";
    		$linenumber++;
    	}

    }
?>