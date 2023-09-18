<?php
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

    $userid = USERID;
    $where = array();
    if($userid==getPoConfigData('purchasing_head')||USERID==1){
        //do nothing
    }
    else{
        // array_push($where, "assigned_to='$userid'");
    }   

    $statusinput = isset($_GET['status'])?escapeString($_GET['status']):'';
    if($statusinput!=''){
        if($statusinput=='VARIANCE'){
            array_push($where, "purchase_order_header.discrepancy_flag is not null","purchase_order_header.discrepancy_flag!=''");
        }
        else if($statusinput=='FOR PRINT'){
            array_push($where, "purchase_order_header.printed_flag=0 AND (purchase_order_header.printed_timestamp='0000-00-00 00:00:00' OR purchase_order_header.printed_timestamp='') AND purchase_order_header.assigned_to='$userid' AND purchase_order_header.order_status NOT LIKE '%CANCEL%'");
        }
        else if($statusinput!='ALL'){
            array_push($where, "purchase_order_header.order_status='".$statusinput."'");
        }
    }

    $swhereinput = isset($_GET['swhere'])?escapeString($_GET['swhere']):'';
    if($swhereinput!=''){
        array_push($where, $swhereinput);
    }

    if(count($where)>0){
        $condition = "where ".implode(' and ', $where);
    }
    else{
        $condition = "";
    }



/* IF Query comes from DataTables do the following */
if (!empty($_POST) ) {
	if($userid==getPoConfigData('purchasing_head')||USERID==1||USERID==51||USERID==374){
		//do nothing
//            $condition = '';
	}
	else {
		if($condition != ""){
			$condition .= " AND purchase_order_header.assigned_to ='$userid' ";
		}else{
			$condition = " WHERE purchase_order_header.assigned_to ='$userid' ";
		}
	}  
	$qry = "select * 
			from (
					select 
							purchase_order_header.order_number,
							purchase_order_header.reference_requisition_number,
							purchase_order_header.order_status,
							purchase_requisition_header.requisition_date,
							supplier.supplier_name,
							purchase_order_header.printed_timestamp,
							purchase_order_header.supplier_address,
							purchase_order_header.po_remarks,
							purchase_order_header.delivery_date,
							purchase_order_header.printed_flag,
							payment_terms.payment_terms_code,
							purchase_order_header.ship_via,
							purchase_order_header.mode_of_shipment,
							mode_of_shipment.description,
							purchase_order_header.order_date,
							purchase_order_header.supplier_contact,
							purchase_order_header.datetime_assigned,
							purchase_order_header.date_flag,
							purchase_order_header.next_revision_series,
							purchase_order_header.assigned_to,
							purchase_order_header.posted_by,
							purchase_order_header.posted_date,
							purchase_order_header.expected_date,
							purchase_order_header.first_approver,
							purchase_order_header.second_approver,
							purchase_order_header.third_approver,
							purchase_order_header.currency,
							purchase_order_header.discrepancy_flag,
							ship_via.ship_by,
							(SELECT purchase_order_revision_history.remarks FROM purchase_order_revision_history WHERE purchase_order_revision_history.order_number=purchase_order_header.order_number LIMIT 0,1) as remarks
					from purchase_order_header
					left join supplier on supplier.id=supplier_id
					left join mode_of_shipment on mode_of_shipment.id=purchase_order_header.mode_of_shipment
					left join payment_terms on purchase_order_header.terms=payment_terms.id
					left join purchase_requisition_header on purchase_requisition_header.requisition_number=purchase_order_header.reference_requisition_number
					left join ship_via on ship_via.id=purchase_order_header.ship_via
					$condition
				) as a
		   ";	
	
	function getData2($sql){
        global $connection ;//we use connection already opened
        $query = mysql_query($sql) OR DIE ("Can't get Data from DB , check your SQL Query " );
        $data = array();

        $editprhidebtn = (userAccess(USERID,'.editpurchaserequisition')==false)?'hidden':'';
        while($obj=mysql_fetch_object($query)){
             $orderNum = $obj->order_number;
             $referenceprnum = $obj->reference_requisition_number;
             $orderStatus = $obj->order_status;
             $reqdate = formatDate($obj->requisition_date);
             $supplier = strUtfEncode($obj->supplier_name);
             $printedflag = $obj->printed_flag==0?'NO':formatDate4($obj->printed_timestamp);
             $supplierAddr = strUtfEncode($obj->supplier_address);
             $remarks = strUtfEncode($obj->po_remarks);
             $po_rev_remarks = strUtfEncode($obj->remarks);
             $expdelivery = formatDate($obj->delivery_date);
             $terms = strUtfEncode($obj->payment_terms_code);
             $shipvia = strUtfEncode($obj->ship_by);
             $mode =strUtfEncode($obj->description);
             $orderdate = formatDate2($obj->order_date);
             $pototal = getPoGrandTotal($orderNum);
             $attn = strUtfEncode($obj->supplier_contact);
             $dateassigned = formatDate4($obj->datetime_assigned);
             $datelastmod = formatDate4($obj->date_flag);
             $revnum = $obj->next_revision_series;
             $prrevsuffix = appendPrRevSuffix($referenceprnum);
             $assignedto = getUserName($obj->assigned_to);
             $postedby = getUserName($obj->posted_by);
             $dateposted = formatDate4($obj->posted_date);
             $podateneeded = formatDate($obj->expected_date);
             $lastapprovedby = getLastPoApprover($orderNum);
             $pocancellationinfo = getPoCancellationInfo($orderNum);
             $cancelleddate = $orderStatus=='CANCELLED'?$pocancellationinfo[1]:'';
             $cancelledby = $orderStatus=='FOR CANCELLATION'||$orderStatus=='CANCELLED'?$pocancellationinfo[2]:'';
             $cancelledreason = $orderStatus=='FOR CANCELLATION'||$orderStatus=='CANCELLED'?strUtfEncode($pocancellationinfo[0]):'';
             $approverfirst = getUserName($obj->first_approver).' '.getPoApprovalTimestamp($orderNum,$obj->first_approver,1);
             $approversecond = getUserName($obj->second_approver).' '.getPoApprovalTimestamp($orderNum,$obj->second_approver,2);
             $approverlast = $obj->third_approver==NULL?'Not Required':getUserName($obj->third_approver).' '.getPoApprovalTimestamp($orderNum,$obj->third_approver,3);
             $discrepancy = $obj->discrepancy_flag;
             $discrepancy = explode(',', $discrepancy);
             $variance='';
             for($i=0;$i<count($discrepancy);$i++){
                if($discrepancy[$i]=='PRICE VARIANCE'){
                    $variance = $variance."<span class='label label-primary'>".$discrepancy[$i]."</span><br>";
                }
                else if($discrepancy[$i]=='QUANTITY VARIANCE'){
                    $variance = $variance."<span class='label label-default'>".$discrepancy[$i]."</span><br>";
                }
                else if($discrepancy[$i]=='UNDERDELIVERY'){
                    $variance = $variance."<span class='label label-warning'>".$discrepancy[$i]."</span><br>";
                }
            }

            $withdiscrepancy = checkIfDiscrepancyExist($orderNum);
            $forrevisionremarks = getRevisionSources($orderNum, 'order_number');

            $dateneeded = getDateNeeded($orderNum);
            $enableedit = '';
            $revisebtn='';
            $printbtn='';
            $toggleprintflag = '';
            $reason = '';
            $rejectedby = '';
            $assignbtn='';
            $currency = getCurrencyCode($obj->currency);
            if($orderStatus=='DRAFT'||$orderStatus=='REJECTED'||$orderStatus=='REVISED'){
                $enableedit = "<img ponum='$orderNum' class='nopadding editpurchaseorder' src='../resources/img/File_Edit.png' title='Edit Purchase Order'>";
            }
            else if($orderStatus=='NEW'){
                if (USERID==51||USERID==374){
                    $assignbtn='';
                }else{
                    $assignbtn = "<img ponum='$orderNum' data-toggle='modal' href='#assignmentmodal' class='nopadding assignpurchaseorder' src='../resources/img/assign.png' title='Assign Purchase Order'>";
                }
            }
            else if($orderStatus=='UNSERVED'||$orderStatus=='PARTIAL DELIVERY'||$orderStatus=='SERVED'||$orderStatus=='HOLD'){
                
                if (USERID==51||USERID==374){
                   $revisebtn =''; 
                }else{
                    $revisebtn = "<img data-toggle='modal' href='#revisionmodal' ponum='$orderNum' reqnum='$referenceprnum' class='nopadding revisepurchaseorder' src='../resources/img/File_List.png' title='Revise Purchase Order'>";
                    if($printedflag=='NO'){
                        
                        if (USERID==51||USERID==374){
                            $printbtn =''; 
                        }else{
                            $printbtn = "<img ponum='$orderNum' class='nopadding printpurchaseorder' src='../resources/img/Printer.png' data-toggle='modal' href='#printpomodal' title='Print Purchase Order'>";
                            $toggleprintflag = "<img ponum='$orderNum' class='nopadding tagpoasprinted' src='../resources/img/label_32.png' title='Tag as Printed'>";
                        }
                    }
                }
            }
            if($orderStatus=='REJECTED'){
                $reason = strUtfEncode(getStatusRemarks('purchase_order_approval_history',"order_number='".$orderNum."'",'po_approval_id',"reason_for_rejection"));
                $rejectedby = strUtfEncode(rejectedby('purchase_order_approval_history',"order_number='".$orderNum."'",'po_approval_id',"reason_for_rejection"));
            }


            $cancelpobtn = '';
            if($orderStatus!='CANCELLED'&&$orderStatus!='FOR REVISION'&&$orderStatus!='FOR CANCELLATION'){
                
                if (USERID==51||USERID==374){
                   $cancelpobtn =''; 
                }else{
                    $cancelpobtn = "<img ponum='$orderNum' style='margin-left:3px' class='nopadding cancelpobtn' src='../resources/img/block_32.png' data-toggle='modal' href='#pocancellationmodal' title='Cancel PO'>";
                }
            
            }

            $color='';
            $colortext = '';
            if($variance!=''){
                $color = "style='background-color:#ffeeee'";
                $colortext = "style='color:#a02020'";
            }


            $arraydata =  array(
                                "DT_RowAttr"=>array(
                                                        "rejectedby"=>$rejectedby,
                                                        "rejectionreason"=>$reason,
                                                        "lastapprovedby"=>$lastapprovedby,
                                                        "revnum"=>$revnum,
                                                        "forrevision"=>$forrevisionremarks,
                                                        "dateneeded"=>$podateneeded,
                                                        "withdiscrepancy"=>$withdiscrepancy,
                                                        "assignedto"=>$assignedto,
                                                        "postedby"=>$postedby,
                                                        "mode"=>$mode,
                                                        "attn"=>$attn,
                                                        "terms"=>$terms,
                                                        "currency"=>$currency,
                                                        "refpr"=>$referenceprnum,
                                                        "shipvia"=>$shipvia,
                                                        "ponumber"=>$orderNum,
                                                        "status"=>$orderStatus,
                                                        "pototal"=>$pototal,
                                                        "supplier"=>$supplier,
                                                        "supplierAddr"=>$supplierAddr,
                                                        "expdelivery"=>$expdelivery,
                                                        "orderdate"=>$orderdate,
                                                        "remarks"=>$remarks.' - '.$po_rev_remarks,
                                                        // "remarks"=>$remarks,
                                                        "firstapprover"=>$approverfirst,
                                                        "secondapprover"=>$approversecond,
                                                        "thirdapprover"=>$approverlast,
                                                        "dateassigned"=>$dateassigned,
                                                        "dateposted"=>$dateposted,
                                                        "cancelleddate"=>$cancelleddate,
                                                        "cancelledby"=>$cancelledby,
                                                        "cancelledreason"=>$cancelledreason,
                                                        "prsuffix"=>$prrevsuffix
                                                    ),
                                "DT_RowClass"=>"tablerow pointer",
                                'ACTION'=>$enableedit.$assignbtn.$revisebtn.$printbtn.$toggleprintflag.$cancelpobtn,
                                'order_status'=>"<b $colortext>".$orderStatus."</b><br>".$variance, 
                                'order_date'=>$orderdate, 
                                'order_number'=>$orderNum, 
                                'next_revision_series'=>$revnum,
                                'supplier_name'=>$supplier,
                                'requisition_date'=>$reqdate,
                                'expected_date'=>$podateneeded,
                                'printed_timestamp'=>$printedflag,
                                'date_flag'=>$datelastmod,
                                'reason'=>$reason

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

    $columnSearch = array("order_status","supplier_name","requisition_date","expected_date","order_date");


    $recordsTotal = mysql_num_rows(mysql_query($qry));

    /* SEARCH CASE : Filtered data */
    if(!empty($_POST['search']['value'])){
        $columncount = count($_POST['columns'])-1;
        /* WHERE Clause for searching */
        for($i=1 ; $i<$columncount;$i++){
            $column = $_POST['columns'][$i]['data'];//we get the name of each column using its index from POST request
            if($column=='requisition_date'||$column=='last_made_changes'||$column=='expected_date'){
            	$where[]="$column like '%".date('Y-m-d', strtotime(escapeString($_POST['search']['value'])))."%'";
            }
            else{
            	$where[]="$column like '%".escapeString($_POST['search']['value'])."%'";
            }

        }

        /*for($i=0 ; $i<count($columnSearch);$i++){
            $column = $columnSearch[$i];//we get the name of each column using its index from POST request
            //if($column=='requisition_date'||$column=='expected_date'||$column=='order_date'){
            //   $where[]="$column like '%".date('Y-m-d', strtotime(utf8_decode($_POST['search']['value'])))."%'";
            //}
            //else{
                $where[]="$column like '%".utf8_decode($_POST['search']['value'])."%'";
            //}

        }*/


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
		if($orderBy == '') {
			$orderBy = 'order_number DESC, datetime_assigned DESC';
			$sql = sprintf("%s %s limit %d , %d ", $qry ,$orderBy , $start , $length);
		} else {
			$sql = sprintf("%s ORDER BY %s %s limit %d , %d ", $qry ,$orderBy,$orderType ,$start , $length);
		}
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