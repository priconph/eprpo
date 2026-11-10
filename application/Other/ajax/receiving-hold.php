
<?php
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");



/* IF Query comes from DataTables do the following */
if (!empty($_POST) ) {
   
        
        $qry = "select * 
                from(
                        SELECT purchase_order_header.order_number,
                        purchase_order_header.order_status,
                        hold_invoice_remarks.pkid,
                        hold_invoice_remarks.reference_po_number, 
                        hold_invoice_remarks.remarks,
                        hold_invoice_remarks.user_id,
                        concat(user.first_name,' ',user.last_name) as hold_by,
                        hold_invoice_remarks.lastupdate,
                        supplier.id,
                        supplier.supplier_name
                        FROM purchase_order_header
                        inner join hold_invoice_remarks ON hold_invoice_remarks.reference_po_number = purchase_order_header.order_number
                        inner join user ON user.id = hold_invoice_remarks.user_id
                        inner join supplier ON supplier.id = purchase_order_header.supplier_id
                        WHERE hold_invoice_remarks.logdel ='0' AND purchase_order_header.order_status ='HOLD'
                    ) as a
               ";


            //    SELECT purchase_order_header.order_number,
            //             purchase_order_header.order_status,
            //             hold_invoice_remarks.pkid,
            //             hold_invoice_remarks.reference_po_number, 
            //             hold_invoice_remarks.remarks,
            //             hold_invoice_remarks.user_id,
            //             concat(user.first_name,' ',user.last_name) as hold_by,
            //             hold_invoice_remarks.lastupdate,
            //             supplier.id,
            //             supplier.supplier_name
            //             FROM purchase_order_header
            //             inner join hold_invoice_remarks ON hold_invoice_remarks.reference_po_number = purchase_order_header.order_number
            //             inner join user ON user.id = hold_invoice_remarks.user_id
            //             inner join supplier ON supplier.id = purchase_order_header.supplier_id
            //             WHERE hold_invoice_remarks.logdel ='0' AND purchase_order_header.order_status ='HOLD'

	function getData2($sql){
        global $connection ;//we use connection already opened
        $query = mysql_query($sql) OR DIE ("Can't get Data from DB , check your SQL Query " );
        $data = array();

        while($obj=mysql_fetch_object($query)){
                 $poref         = $obj->reference_po_number==''?'No PO Reference':$obj->reference_po_number;
                 $orderNum      = $obj->order_number;
                 $orderStatus   = $obj->order_status;
                 $supplier_name = strUtfEncode($obj->supplier_name);
                 $remarks       = strUtfEncode($obj->remarks);
                 $hold_by       = strUtfEncode($obj->hold_by);
                 $lastupdate    = formatDate2($obj->lastupdate);
                 $lastupdate2   = date('Y-m-d', strtotime($obj->lastupdate));
                 
                    $status = '';

                    if ( $lastupdate2 <= date('Y-m-d', strtotime(date('Y-m-d').' -5 days')) ){
                        $status          = '<font color="red">'.$status.'</font>';
                        $poref           = '<font color="red">'.$poref.'</font>';
                        $supplier_name   = '<font color="red">'.$supplier_name.'</font>';
                        $remarks         = '<font color="red">'.$remarks.'</font>';
                        $hold_by         = '<font color="red">'.$hold_by.'</font>';
                        $lastupdate      = '<font color="red">'.$lastupdate.'</font>';
                    }

            $cancelpobtn    = '';
            $viewrcvpo      = '';

            if($orderStatus =='HOLD'){
                $cancelpobtn = "<img ponum='$orderNum' style='margin-left:3px' class='nopadding cancelholdbtn' src='../resources/img/block_32.png' data-toggle='modal' href='#holdcancellationmodal' title='Cancel PO'>";
            }
        
            if($orderStatus=='HOLD'){
                $viewrcvpo = "<img ponum='$orderNum' class='nopadding viewreceivingpo $viewreceivingpobtn' src='../resources/img/File_Edit.png' title='Go to receiving'>";    
            }

            $arraydata =  array(
                                "DT_RowAttr"=>array(
                                                        "status"=>singleQuotesReverse($status),
                                                        "poref"=>singleQuotesReverse($poref),
                                                        "supplier_name"=>singleQuotesReverse($supplier_name),
                                                        "remarks"=>singleQuotesReverse($remarks),
                                                        "hold_by"=>singleQuotesReverse($hold_by),
                                                        "lastupdate"=>$lastupdate
                                                    ),
                                "DT_RowClass"=>"tablerow rcvrow pointer",
                                'ACTION'=>$viewrcvpo.$cancelpobtn,
                                'status'=>$status, 
                                'reference_po_number'=>$poref, 
                                'supplier_name'=>$supplier_name, 
                                'remarks'=>$remarks, 
                                'hold_by'=>$hold_by, 
                                'lastupdate'=>$lastupdate,

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
            if($column=='actual_delivery_date'||$column=='received_date'){
            	//$where[]="$column like '%".date('Y-m-d', strtotime(escapeString($_POST['search']['value'])))."%'";
            }
            else{
            	$where[]="$column like '%".escapeString($_POST['search']['value'])."%'";
            }

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