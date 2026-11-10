
<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include('../../config/functions.php');

    $po = isset($_GET['Oinsldfru'])?$_GET['Oinsldfru']:'';
    if($po==''){
        exit('DIRECT ACCESS NOT ALLOWED');
    }
    else{
        //11262019 -- new query
        //     $query = "select item_code,
        //                 item.item_name as item_name1,
        //                 purchase_order_details.item_name,
        //                 purchase_order_details.long_description,
        //                 item.long_description as long_description1,
        //                 item_id,
        //                 quantity,
        //                 purchase_order_details.unit_price,
        //                 unit_of_measure_code,
        //                 classification_code,
        //                 classification_code.acct_code,
        //                 classification_code.acct_name
        //         from purchase_order_details, item, unit_of_measure, purchase_order_header, classification_code
        //         where unit_of_measure_id=unit_of_measure.id and 
        //               purchase_order_details.order_number = purchase_order_header.order_number and
        //    item_id=item.id and  
        //    classification_code=classification_code.acct_code and  
        //     purchase_order_details.order_number='$po'
        //     group by purchase_order_details.item_name, 
        //     purchase_order_details.long_description, 
        //     purchase_order_details.unit_price,
        //     purchase_order_details.quantity
        //     order by purchase_order_details.quantity
        //     ";
                               
            /* 05202025 by Nessa */
           $query = "select b.item_code,
                        b.item_name as item_name1,
                        a.item_name,
                        a.long_description,
                        b.long_description as long_description1,
                        a.item_id,
                        a.quantity,
                        a.unit_price,
                        c.unit_of_measure_code,
                        a.classification_code,
                        e.acct_code,
                        e.acct_name
            FROM purchase_order_details a
                inner join item b ON b.id = a.item_id
                inner join unit_of_measure c ON c.id = b.unit_of_measure_id
                inner join purchase_order_header d ON d.order_number = a.order_number
                left join classification_code e ON e.acct_code = a.classification_code
            WHERE a.order_number='$po'
            GROUP by a.item_name, 
            a.long_description, 
            a.unit_price,
            a.quantity
            order by a.quantity";

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
        //    $classfn = $obj->classification_code;
            /* 05202025 by Nessa */
            if ($obj->acct_code == NULL){
                $classfn = $obj->classification_code;
            }else{
                $classfn = $obj->acct_code.' - '.$obj->acct_name;
            }
           
           $floatamount = $price*$qty;
           $amount = $price * $qty;
           $grandtotal = $grandtotal + round($amount,2);

           //12132019
           // $rcvdqty = getQtyReceived_2($po,$itemId,$price,$shortdesc,$longdesc);
           $rcvdqty = getQtyReceived_3($po,$itemId,$price,$shortdesc,$longdesc,$qty);
           $bal = $qty-$rcvdqty;
           $bal = convertWithDecimal($bal,0);
           $qty = convertWithDecimal($qty,0);
           $price = convertWithDecimal($price,6); //09072023

            //06172025 by Nessa
            //uncomment this to resolve the .1 issue/concern in total amount
            if ($po == 'PO-58402' || $po == 'PO-57456' || $po == 'PO-62496'){
                if ($itemcode == '66066'){
                    $amount = convertWithDecimal($amount); // orig data
                }else{
                    $amount = bcdiv($amount, 1, 2); 
                }
            }else if ($po == 'PO-62502'){ 
                $amount = '300.00'; // from 300.02 01222026 by Nessa
            }
            else if($po == 'PO-66208'){ //chris for YEC adjustment -0.02
                $amount = $amount - 0.02;
                $floatamount = $amount;
                $grandtotal = $amount;

            }
            else{
                $amount = convertWithDecimal($amount); // orig data
            }

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
