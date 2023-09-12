<?php
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");



/* IF Query comes from DataTables do the following */
if (!empty($_POST) ) {
   
        // 09062023 add WHERE clause
        $qry = "select * 
                from(
                        select 
                                receiving_header.receiving_number,
                                receiving_header.receiving_status,
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
                                receiving_header.next_revision_series,
                                receiving_header.first_approver,
                                receiving_header.second_approver,
                                supplier.supplier_name,
                                currency.currency_code,
                                concat(user.first_name,' ',user.last_name) as rcvd_by,
                                (SELECT hold_invoice_remarks.`remarks` FROM `hold_invoice_remarks` WHERE hold_invoice_remarks.`reference_po_number` = receiving_header.reference_po_number LIMIT 0,1) as hold_remarks
                        from receiving_header 
                        inner join supplier on supplier.id=receiving_header.supplier_id
                        inner join user on user.id=receiving_header.received_by
                        inner join currency on currency.id=receiving_header.currency
                        
                        where receiving_header.received_date LIKE '%2022%' or receiving_header.received_date LIKE '%2023%'

                    ) as a
            ";

            //    where receiving_header.received_date LIKE '%2022%' or receiving_header.received_date LIKE '%2023%'

	function getData2($sql){
        global $connection ;//we use connection already opened
        $query = mysql_query($sql) OR DIE ("Can't get Data from DB , check your SQL Query " );
        $data = array();

        while($obj=mysql_fetch_object($query)){
                 $rcvnum = $obj->receiving_number;
                 $rcvstat = $obj->receiving_status == '' ? 'N/A' : $obj->receiving_status;
                 $revision = $obj->next_revision_series;
                 $poref = $obj->reference_po_number==''?'No PO Reference':$obj->reference_po_number;
                 $pr = getRefPrNumber($poref);
                 $itemcount = getNumOfItemsReceived($rcvnum);
                 $actualdate = formatDate($obj->actual_delivery_date);
                 $rcvdate = formatDate2($obj->received_date);
                 $rcvby = strUtfEncode($obj->rcvd_by);
                 $carrier = strUtfEncode($obj->carrier);
                 $remarks = strUtfEncode($obj->remarks);
                 $currency = $obj->currency_code;
                 $supplier = strUtfEncode($obj->supplier_name);
                 $otherref = strUtfEncode($obj->other_reference);
                 $prrevsuffix = appendPrRevSuffix($pr);
				 $asstmngr = getUserName($obj->first_approver).' '.getRcvApprovalHistoryTimestamp($obj->receiving_number,1,$revision);
				 $genmngr = getUserName($obj->second_approver).' '.getRcvApprovalHistoryTimestamp($obj->receiving_number,2,$revision);
                 $hold_remarks = $obj->hold_remarks == '' ? 'N/A' : $obj->hold_remarks;

            $arraydata =  array(
                                "DT_RowAttr"=>array(
                                                        "actualdate"=>$actualdate,
                                                        "prsuffix"=>$prrevsuffix,
                                                        "prref"=>$pr,
                                                        "currency"=>$currency,
                                                        "otherref"=>singleQuotesReverse($otherref),
                                                        "supplier"=>singleQuotesReverse($supplier),
                                                        "rcvnum"=>$rcvnum,
                                                        "remarks"=>singleQuotesReverse($remarks),
                                                        "po"=>singleQuotesReverse($poref),
                                                        "rcv_stat"=>singleQuotesReverse($rcvstat),
                                                        "date"=>$rcvdate,
                                                        "by"=>singleQuotesReverse($rcvby),
                                                        "carrier"=>singleQuotesReverse($carrier),
                                                        "asstmngr"=>singleQuotesReverse($asstmngr),
                                                        "genmngr"=>singleQuotesReverse($genmngr),
                                                        "hold_remarks"=>singleQuotesReverse($hold_remarks)
                                                    ),
                                "DT_RowClass"=>"tablerow rcvrow pointer",
                                'receiving_number'=>$rcvnum, 
                                'receiving_status'=>$rcvstat, 
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







    /* Useful $_POST Variables coming from the plugin */
    $draw = $_POST["draw"];//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
    $orderByColumnIndex  = $_POST['order'][0]['column'];// index of the sorting column (0 index based - i.e. 0 is the first record)
    $orderBy = $_POST['columns'][$orderByColumnIndex]['data'];//Get name of the sorting column from its index
    $orderType = $_POST['order'][0]['dir']; // ASC or DESC
    $start  = $_POST["start"];//Paging first record indicator.
    $length = $_POST['length'];//Number of records that the table can display in the current draw
    /* END of POST variables */

    
    $recordsTotal = mysql_num_rows(mysql_query($qry));

    /* SEARCH CASE : Filtered data */
    if(!empty($_POST['search']['value'])){
        $columncount = count($_POST['columns']);
        /* WHERE Clause for searching */
        for($i=0 ; $i<$columncount;$i++){
            $column = $_POST['columns'][$i]['data'];//we get the name of each column using its index from POST request
            // if($column=='actual_delivery_date'||$column=='received_date'){
            // 	//$where[]="$column like '%".date('Y-m-d', strtotime(escapeString($_POST['search']['value'])))."%'";
            // }
            // else{
            	$where[]="$column like '%".escapeString($_POST['search']['value'])."%'";
            // }

        }

        $where = "WHERE (".implode(" OR " , $where).")";// id like '%searchValue%' or name like '%searchValue%' ....
        /* End WHERE */

       
        $sql = sprintf("%s %s", $qry , $where);//Search query without limit clause (No pagination)
        $recordsFiltered = mysql_num_rows(mysql_query($sql));//Count of search result

        /* SQL Query for search with limit and orderBy clauses*/
        $sql = sprintf("%s %s ORDER BY %s %s limit %d , %d ", $qry , $where ,$orderBy, $orderType ,$start,$length  );
        $data = getData2($sql);
    }
    /* END SEARCH */
    else {
        $sql = sprintf("%s ORDER BY %s %s limit %d , %d ", $qry ,$orderBy,$orderType ,$start , $length);
        $data = getData2($sql);

        $recordsFiltered = $recordsTotal;
    }

    /* Response to client before JSON encoding */
    $response = array(
        "draw" => intval($draw),
        "recordsTotal" => $recordsTotal,
        "recordsFiltered" => $recordsFiltered,
        "data" => $data


    );

    echo json_encode($response);



} else {
    echo "NO POST Query from DataTable";
}
?>