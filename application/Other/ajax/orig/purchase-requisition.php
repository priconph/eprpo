<?php
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

/* IF Query comes from DataTables do the following */
if (!empty($_POST) ) {

    $userid = USERID;
	if(USERID==1){
		$qry = "select * from purchase_requisition_header";
	}
	else{
		$qry = "select * from purchase_requisition_header";
	}


	
	
	function getData2($sql){
    	global $connection ;//we use connection already opened
        $query = mysql_query($sql) OR DIE ("Can't get Data from DB , check your SQL Query " );
        $data = array();

        $editprhidebtn = (userAccess(USERID,'.editpurchaserequisition')==false)?'hidden':'';
		while($obj=mysql_fetch_object($query)){
            $prnumber = $obj->requisition_number;
            $nextrev = $obj->next_revision_series;
            $revnumber = $nextrev;
            $stat = strtoupper($obj->requisition_status);
            $reasonforcancellation = strUtfEncode(getPrCancelReason($prnumber));
            $forcancellation = $obj->cancellation_flag;
            $currency = getCurrencyCode($obj->currency);
            $requester = $obj->requisitioner;
            $dept = getDeptName($requester);
            $requester = getUserName($requester);
            $prgrandtotal = getPrGrandTotal($prnumber);
            $reqtype = strtoupper($obj->requisition_type);
            $reqdate = formatDate($obj->requisition_date);
            $dateneeded = formatDate($obj->date_needed);
            $canceleddate = formatDate2($obj->canceled_date);
            $remarks = strUtfEncode($obj->justification);
            $lastupdate =  formatDate2($obj->last_made_changes);
            $prnotes = strUtfEncode($obj->pr_notes);
            $lastmodified = formatDate2($obj->last_modified);
            // $conf = ($obj->conformance=='1')?'Not Applicable':(($obj->conformance==NULL&&$stat!='DRAFT')?'Not Required':getUserName($obj->conformance)).' '.getPrApprovalTimestampWithRev($prnumber,$obj->conformance,2,$revnumber);
            $conf = ($obj->conformance=='1')?'Not Applicable':(($obj->conformance==NULL&&$stat!='DRAFT')?'Not Required':getUserName($obj->conformance)).' '.getPrApprovalTimestampWithRev($prnumber,$obj->conformance,2,$revnumber);
            $sh1 =($obj->section_head1=='1')?'Not Applicable':(($obj->section_head1==NULL&&$stat!='DRAFT')?'Not Required':getUserName($obj->section_head1)).' '.getPrApprovalTimestampWithRev($prnumber,$obj->section_head1,0,$revnumber);
            $sh2 =($obj->section_head2=='1')?'Not Applicable':(($obj->section_head2==NULL&&$stat!='DRAFT')?'Not Required':getUserName($obj->section_head2)).' '.getPrApprovalTimestampWithRev($prnumber,$obj->section_head2,1,$revnumber);
            $gm = ($obj->general_manager=='1')?'Not Applicable':(($obj->general_manager==NULL&&$stat!='DRAFT')?'Not Required':getUserName($obj->general_manager)).' '.getPrApprovalTimestampWithRev($prnumber,$obj->general_manager,3,$revnumber);
            $avp = ($obj->assistant_vice_president=='1')?'Not Applicable':(($obj->assistant_vice_president==NULL&&$stat!='DRAFT')?'Not Required':getUserName($obj->assistant_vice_president)).' '.getPrApprovalTimestampWithRev($prnumber,$obj->assistant_vice_president,4,$revnumber);
            $svp = ($obj->senior_vice_president=='1')?'Not Applicable':(($obj->senior_vice_president==NULL&&$stat!='DRAFT')?'Not Required':getUserName($obj->senior_vice_president)).' '.getPrApprovalTimestampWithRev($prnumber,$obj->senior_vice_president,5,$revnumber);
            $pr = ($obj->president=='1')?'Not Applicable':(($obj->president==NULL&&$stat!='DRAFT')?'Not Required':getUserName($obj->president)).' '.getPrApprovalTimestampWithRev($prnumber,$obj->president,6,$revnumber);


														//$prdesc = getPrDescription($prnumber);
            $prdesc = strUtfEncode($obj->pr_description);
            $lastapprovedby = getLastPrApprover($prnumber,$revnumber);

            $withdiscrepancy = checkIfDiscrepancyExistPR($prnumber);
            $forrevisionremarks = getRevisionSources($prnumber, 'requisition_number');

            $reason = '';
            $rejectedby = '';
            $enableedit = '';
            $viewremarks = '';
            $notifyapprover = '';
            $cancelpr = '';

            $cancelpr = checkIfPrHasPo($prnumber);


            if($stat=='REJECTED'||$stat=='DRAFT'||$stat=='FOR REVISION'){
            	$enableedit = "<img prnum='$prnumber' class='nopadding editpurchaserequisition $editprhidebtn' src='../resources/img/File_Edit.png' title='Edit Purchase Requisition'>";	
            }
            if($stat=='REJECTED'){
            	$reason = strUtfEncode(getStatusRemarks('purchase_requisition_approval_history',"requisition_number='".$prnumber."'",'pr_approval_id',"reason_for_rejection"));
            	$rejectedby = rejectedby('purchase_requisition_approval_history',"requisition_number='".$prnumber."'",'pr_approval_id',"reason_for_rejection");
            	$viewremarks = '<span style="font-style:italic; font-size:10px ">Click to see details on rejection</span>';
            }
            if($stat=='IN REVIEW'){
            	$notifyapprover = "<img prnum='$prnumber' class='nopadding notifyapproverbtn' data-toggle='modal' href='#followupmodal' src='../resources/img/flag_32.png' title='Follow-up Approval'>";	

            }
            else if($stat=='FOR REVISION'){
															//$reason = getStatusRemarks('purchase_order_revision_history, purchase_order_header',"purchase_order_header.order_number=purchase_order_revision_history.order_number and reference_requisition_number='".$prnumber."'",'po_revision_id',"remarks");
            	$viewremarks = '<span style="font-style:italic; font-size:10px ">Click to see details on revision</span>';
            }
            // if($stat!='FOR REVISION'&&$stat!='CANCELLED'&&$stat!='APPROVED'){
            if($stat!='CANCELLED'&&$stat!='APPROVED'){ // commented out FOR REVISION condition since PR with FOR REVISION status can also be cancelled and we want to show cancel button for those PRs as well
            	if(checkIfPrHasPo($prnumber)==0||$forcancellation==1){
            		$notifyapprover = $notifyapprover."<img prnum='$prnumber' style='margin-left:3px' class='nopadding cancelprbtn' src='../resources/img/block_32.png' data-toggle='modal' href='#prcancellationmodal' title='Cancel PR'>";	
            	}
            }

           /* echo "<tr class='tablerow pointer' revnumber='$revnumber' prnotes='$prnotes' rejectedby='$rejectedby' rejectionreason = '$reason' withdiscrepancy='$withdiscrepancy' forrevision='$forrevisionremarks' lastapprovedby='$lastapprovedby' id='$prnumber' dept='$dept' canceleddate='$canceleddate' prtotal='$prgrandtotal' requisitioner='$requester' currency='$currency' remarks='$remarks' stat='$stat' type='$reqtype' reqdate='$reqdate' dateneeded='$dateneeded' lastmodified='$lastmodified' sh1='$sh1' sh2='$sh2' conf='$conf' gm='$gm' avp='$avp' svp='$svp' pr='$pr' reasonforcancellation='$reasonforcancellation'>
														
																<td class='text-center text-nowrap'>$enableedit $notifyapprover</td>
																<td class='clickable text-center'>$stat</td>
																<td class='clickable text-nowrap'>$prnumber</td>
																<td class='clickable text-center'>$nextrev</td>
																<td class='clickable text-center text-nowrap'>$reqdate</td>
																<td class='clickable'>$prdesc</td>

																<td class='clickable'>$viewremarks</td>
														</tr>";*/

			$arraydata =  array(
								//"DT_RowAttr"=>array(),	//to add attributes in tr e.g. "DT_RowAttr"=>array("attr1name"=>'attrvalue',"attr2name"=>'attrvalue')
								"DT_RowAttr"=>array(
														"revnumber"=>$revnumber,
														"prnotes"=>$prnotes,
														"id"=>$prnumber,
														"rejectedby"=>$rejectedby,
														"rejectionreason"=>$reason,
														"withdiscrepancy"=>$withdiscrepancy,
														"forrevision"=>$forrevisionremarks,
														"lastapprovedby"=>$lastapprovedby,
														"dept"=>$dept,
														"canceleddate"=>$canceleddate,
														"prtotal"=>$prgrandtotal,
														"requisitioner"=>$requester,
														"currency"=>$currency,
														"remarks"=>$remarks,
														"stat"=>$stat,
														"type"=>$reqtype,
														"reqdate"=>$reqdate,
														"dateneeded"=>$dateneeded,
														"lastmodified"=>$lastmodified,
														"sh1"=>$sh1,
														"sh2"=>$sh2,
														"conf"=>$conf,
														"gm"=>$gm,
														"avp"=>$avp,
														"svp"=>$svp,
														"pr"=>$pr,
														"reasonforcancellation"=>$reasonforcancellation
													),
								"DT_RowClass"=>"tablerow pointer",
								'ACTION'=>$enableedit.$notifyapprover,
								'requisition_status'=>"<b>".$stat."</b>", 
								'requisition_number'=>$prnumber, 
								'next_revision_series'=>$nextrev, 
								'requisition_date'=>$reqdate,
								'pr_description'=>$prdesc,
								'pr_notes'=>$viewremarks,
								'last_made_changes'=>$lastupdate,
                                'forcancellation'=>$forcancellation

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

        /* WHERE Clause for searching */
        for($i=1 ; $i<count($_POST['columns']);$i++){
            $column = $_POST['columns'][$i]['data'];//we get the name of each column using its index from POST request
            if($column=='requisition_date'||$column=='last_made_changes'){
            	$where[]="$column like '%".date('Y-m-d', strtotime(escapeString($_POST['search']['value'])))."%'";
            }
            else{
            	$where[]="$column like '%".escapeString($_POST['search']['value'])."%'";
            }

        }
        if(USERID!=1){
        	$accessstr=" and requisitioner='".$userid."'";
        }
        $where = "WHERE (".implode(" OR " , $where).")".$accessstr;// id like '%searchValue%' or name like '%searchValue%' ....
        /* End WHERE */

       
        $sql = sprintf("%s %s", $qry , $where);//Search query without limit clause (No pagination)

        $recordsFiltered = mysql_num_rows(mysql_query($sql));//Count of search result

        /* SQL Query for search with limit and orderBy clauses*/
        $sql = sprintf("%s %s ORDER BY %s %s limit %d , %d ", $qry , $where ,$orderBy, $orderType ,$start,$length  );
        $data = getData2($sql);
    }
    /* END SEARCH */
    else {
    	if(USERID!=1){
        	$qry=$qry." where requisitioner='".$userid."'";
        }
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