<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");

/* IF Query comes from DataTables do the following */

    $qry = "select * 
                from(
                        select 
                                receiving_header.receiving_number,
                                receiving_header.reference_po_number,
                                receiving_header.received_date,
                                receiving_header.received_by,
                                receiving_header.carrier,
                                receiving_header.transaction_type,
                                receiving_header.supplier_id,
                                receiving_header.remarks,
                                receiving_header.other_reference,
                                receiving_header.currency,
                                receiving_header.actual_delivery_date,
                                supplier.supplier_name,
                                currency.currency_code,
                                concat(user.first_name,' ',user.last_name) as rcvd_by
                        from receiving_header 
                        left join supplier on supplier.id=receiving_header.supplier_id
                        left join user on user.id=receiving_header.received_by
                        left join currency on currency.id=receiving_header.currency
                    ) as a
               ";

    function getData2($sql){
        global $connection ;//we use connection already opened
        $query = mysql_query($sql) OR DIE ("Can't get Data from DB , check your SQL Query " );
        $data = array();

        while($obj=mysql_fetch_object($query)){
                 $rcvnum = $obj->receiving_number;
                 $poref = $obj->reference_po_number==''?'No PO Reference':$obj->reference_po_number;
                 $pr = getRefPrNumber($poref);
                 $itemcount = getNumOfItemsReceived($rcvnum);
                 $actualdate = formatDate($obj->actual_delivery_date);
                 $rcvdate = formatDate2($obj->received_date);
                 $rcvby = strUtfEncode($obj->received_by);
                 $carrier = strUtfEncode($obj->carrier);
                 $remarks = strUtfEncode($obj->remarks);
                 $currency = $obj->currency_code;
                 $supplier = strUtfEncode($obj->supplier_name);
                 $otherref = strUtfEncode($obj->other_reference);
                 $prrevsuffix = appendPrRevSuffix($pr);

               


            $arraydata =  array(
                                "DT_RowAttr"=>array(
                                                        "actualdate"=>$actualdate,
                                                        "prsuffix"=>$prrevsuffix,
                                                        "prref"=>$pr,
                                                        "currency"=>$currency,
                                                        "otherref"=>$otherref,
                                                        "supplier"=>$supplier,
                                                        "rcvnum"=>$rcvnum,
                                                        "remarks"=>$remarks,
                                                        "po"=>$poref,
                                                        "date"=>$rcvdate,
                                                        "by"=>$rcvby,
                                                        "carrier"=>$carrier
                                                    ),
                                "DT_RowClass"=>"tablerow rcvrow pointer",
                                'receiving_number'=>$rcvnum, 
                                'reference_po_number'=>$poref, 
                                'other_reference'=>$otherref, 
                                'supplier_name'=>$supplier,
                                'actual_delivery_date'=>$actualdate,
                                'received_date'=>$rcvdate,
                                'rcvd_by'=>$rcvby

                            );
            array_push($data, $arraydata);
        }

        return $data;
        
    }

   $data = getData2($qry);


    echo json_encode($data);



?>