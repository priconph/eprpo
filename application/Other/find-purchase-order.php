<?php
	include("../../config/connection.php");
	include("../../config/checklogin.php");
	include("../../config/functions.php");
	$statusinput = isset($_GET['status'])?escapeString($_GET['status']):'';
	if($statusinput=='ALL'){
			$strstat =  "";
	}
	else if($statusinput=='VARIANCE'){
		$strstat =  "and discrepancy_flag is not null and discrepancy_flag!=''";
	}
	else{
	 	$strstat =  "and order_status='".$statusinput."'";
	}

													if($userid==getPoConfigData('purchasing_head')||USERID==1){
														$query = "select * from purchase_order_header, supplier where supplier.id=supplier_id $strstat order by date_flag desc";
													}
													else{
														$query = "select * from purchase_order_header, supplier where supplier.id=supplier_id and assigned_to='$userid' $strstat order by date_flag desc";
													}


													
													$rs = query($query);
													$rscount = getNumRows($rs);
													while ($obj=fetch($rs)) {
														$orderNum = $obj->order_number;
														$referenceprnum = $obj->reference_requisition_number;

														$orderStatus = $obj->order_status;
														//$reqdate = formatDate(getInfo('purchase_requisition_header, purchase_order_header ','requisition_date',"where reference_requisition_number=requisition_number and order_number='".$orderNum."'"));
														$reqdate = formatDate(getInfo('purchase_requisition_header','requisition_date',"where requisition_number='".$referenceprnum."'"));//formatDate($obj->expected_date);
														$supplier = strUtfEncode($obj->supplier_name);
														$printedflag = $obj->printed_flag==0?'NO':formatDate4($obj->printed_timestamp);
														$supplierAddr = strUtfEncode($obj->address);
														$remarks = strUtfEncode($obj->po_remarks);
														$expdelivery = formatDate($obj->delivery_date);
														$terms = getPaymentTermCode($obj->terms);
														$shipvia = getInfo('ship_via','ship_by',"where id='".$obj->ship_via."'");
														$mode =getModeOfShipment($obj->mode_of_shipment);
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
												        //$lastpoapprover = getLastPoApprover($orderNum);

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
															$enableedit = "<img ponum='$orderNum' class='nopadding editpurchaseorder hidden' src='../resources/img/File_Edit.png' title='Edit Purchase Order'>";
														}
														else if($orderStatus=='NEW'){
															$assignbtn = "<img ponum='$orderNum' data-toggle='modal' href='#assignmentmodal' class='nopadding assignpurchaseorder hidden' src='../resources/img/assign.png' title='Assign Purchase Order'>";
														}
														else if($orderStatus=='UNSERVED'||$orderStatus=='PARTIAL DELIVERY'||$orderStatus=='SERVED'){
															$revisebtn = "<img data-toggle='modal' href='#revisionmodal' ponum='$orderNum' reqnum='$referenceprnum' class='nopadding revisepurchaseorder hidden' src='../resources/img/File_List.png' title='Revise Purchase Order'>";
															$printbtn = "<img ponum='$orderNum' class='nopadding printpurchaseorder hidden' src='../resources/img/Printer.png' data-toggle='modal' href='#printpomodal' title='Print Purchase Order'>";
															if($printedflag=='NO'){
																$toggleprintflag = "<img ponum='$orderNum' class='nopadding tagpoasprinted' src='../resources/img/label_32.png' title='Tag as Printed'>";
															}
														}
														if($orderStatus=='REJECTED'){
															$reason = strUtfEncode(getStatusRemarks('purchase_order_approval_history',"order_number='".$orderNum."'",'po_approval_id',"reason_for_rejection"));
															$rejectedby = strUtfEncode(rejectedby('purchase_order_approval_history',"order_number='".$orderNum."'",'po_approval_id',"reason_for_rejection"));
														}


														$cancelpobtn = '';
														if($orderStatus!='CANCELLED'&&$orderStatus!='FOR REVISION'&&$orderStatus!='FOR CANCELLATION'){
															$cancelpobtn = "<img ponum='$orderNum' style='margin-left:3px' class='nopadding cancelpobtn' src='../resources/img/block_32.png' data-toggle='modal' href='#pocancellationmodal' title='Cancel PO'>";
														}

														$color='';
														$colortext ='';
														if($variance!=''){
															$color = "style='background-color:#ffeeee'";
															$colortext = "style='color:#a02020'";
														}


														echo "<tr class='tablerow pointer' rejectedby='$rejectedby' rejectionreason='$reason' lastapprovedby='$lastapprovedby' revnum='$revnum' forrevision='$forrevisionremarks' dateneeded='$podateneeded' withdiscrepancy='$withdiscrepancy' assignedto='$assignedto' postedby='$postedby' mode='$mode' attn='$attn' terms='$terms' currency='$currency' refpr='$referenceprnum' shipvia='$shipvia' ponumber='$orderNum' status='$orderStatus' pototal='$pototal' supplier='$supplier' supplierAddr='$supplierAddr' expdelivery='$expdelivery' orderdate='$orderdate' remarks='$remarks' firstapprover='$approverfirst' secondapprover='$approversecond' thirdapprover='$approverlast' dateassigned='$dateassigned' dateposted='$dateposted' cancelleddate='$cancelleddate' cancelledby='$cancelledby' cancelledreason='$cancelledreason' prsuffix='$prrevsuffix'>
																
																<td $color class='action text-center width-sm'>$enableedit $assignbtn $revisebtn $printbtn $toggleprintflag $cancelpobtn</td>
																<td $color class='clickable text-center text-nowrap'><b $colortext>$orderStatus</b><br>$variance</td>
																<td $color class='text-center text-nowrap clickable'>$orderdate</td>
																<td $color class='clickable text-center'>$orderNum</td>
																<td $color class='clickable text-center'>$revnum</td>
																<td $color class='clickable'>$supplier</td>
																<td $color class='clickable text-center text-nowrap'>$reqdate</td>
																<td $color class='clickable text-center text-nowrap'>$podateneeded</td>
																<td $color class='clickable text-center text-nowrap'>$printedflag</td>
																<td $color class='clickable text-center text-nowrap'>$datelastmod</td>
																
																<td $color class='clickable'>$reason</td>
																
															  </tr>";
													}


													if($rscount==0){
														echo "<td colspan='11' class='text-center'>No Results Found</td>";
													}
												?>



