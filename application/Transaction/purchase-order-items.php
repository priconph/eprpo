
<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include('../../config/functions.php');

    $po = isset($_GET['Oinsldfru'])?$_GET['Oinsldfru']:'';
    if($po==''){
        exit('DIRECT ACCESS NOT ALLOWED');
    }
    else{
        // $query = "select item_code,
        //               item.item_name as item_name1,
        //               purchase_order_details.item_name,
        //               purchase_order_details.long_description,
        //               item.long_description as long_description1,
        //               item_id,
        //               quantity,
        //               purchase_order_details.unit_price,
        //               unit_of_measure_code,
        //               classification_code
        //       from purchase_order_details, item, unit_of_measure, purchase_order_header
        //       where unit_of_measure_id=unit_of_measure.id and 
        //             purchase_order_details.order_number = purchase_order_header.order_number and
        //            purchase_order_details.item_name=item.item_name and 
        //            purchase_order_details.long_description=TRIM(TRAILING '\n' FROM item.long_description) and 
        //  item_id=item.id and  
        //   purchase_order_details.order_number='$po'
        //   group by purchase_order_details.item_name, 
        //   purchase_order_details.long_description, 
        //   purchase_order_details.unit_price,
        //   purchase_order_details.quantity
        //   ";			

            //11262019 -- new query
                    $query = "select item_code,
                        item.item_name as item_name1,
                        purchase_order_details.item_name,
                        purchase_order_details.long_description,
                        item.long_description as long_description1,
                        item_id,
                        quantity,
                        purchase_order_details.unit_price,
                        unit_of_measure_code,
                        classification_code
                from purchase_order_details, item, unit_of_measure, purchase_order_header
                where unit_of_measure_id=unit_of_measure.id and 
                      purchase_order_details.order_number = purchase_order_header.order_number and
           item_id=item.id and  
            purchase_order_details.order_number='$po'
            group by purchase_order_details.item_name, 
            purchase_order_details.long_description, 
            purchase_order_details.unit_price,
            purchase_order_details.quantity
            order by purchase_order_details.quantity

            ";
             

           

           // purchase_order_details.quantity

            // purchase_order_details.item_name, 
            // purchase_order_details.long_description, 
            // purchase_order_details.unit_price,
            // purchase_order_details.quantity

        $rs = query($query);
        $line = 1;
        $grandtotal = 0;
        $ctrx = 0;
        while($obj=fetch($rs)){
           $itemId = $obj->item_id;
           $itemcode = $obj->item_code;
           $shortdesc = strUtfEncode($obj->item_name1) == '' ? ''.strUtfEncode($obj->item_name1) : strUtfEncode($obj->item_name1);
           $longdesc = strUtfEncode($obj->long_description1) == '' ? ''.strUtfEncode($obj->long_description1) : strUtfEncode($obj->long_description1);
           $qty = $obj->quantity;
           $price = $obj->unit_price;
           $uom = $obj->unit_of_measure_code;
           $classfn = $obj->classification_code;
           $floatamount = $price*$qty;
           $amount = $price * $qty;
           $grandtotal = $grandtotal + round($amount,2);

           //12132019
           // $rcvdqty = getQtyReceived_2($po,$itemId,$price,$shortdesc,$longdesc);
           $rcvdqty = getQtyReceived_3($po,$itemId,$price,$shortdesc,$longdesc,$qty);
           $bal = $qty-$rcvdqty;
           $bal = convertWithDecimal($bal,0);
           $qty = convertWithDecimal($qty,0);
           $price = convertWithDecimal($price,5);

           //10222021
           $amount = convertWithDecimal($amount); // orig data
           // $amount = bcdiv($amount, 1, 2); //uncomment this to resolve the .1 issue/concern in total amount

           $rcvdqty = convertWithDecimal($rcvdqty,0);
           $ctrx++;
           
		   echo "<tr prrowid='$itemId'>
           <td class='text-center'>$line</td>
           <td>$itemcode</td>
           <td>$shortdesc</td>
           <td>$longdesc</td>
           <td class='text-center'>$classfn</td>
           <td class='text-center'>$uom</td>
           <td class='text-right'>$qty</td>
            <td class='text-right'>$rcvdqty</td>
           <td class='text-right'>$bal</td>
           <td class='text-right rowprice'>$price</td>
           <td class='text-right amount' amount='$floatamount'>$amount</td>
          
           </tr>";
           $line++;      
        }
        $grandtotal = number_format($grandtotal,2,'.',','); 
    }
?>
