<?php
	ini_set("max_execution_time", 0);
?>
<tr class='noresult hidden'><td colspan='13'>No Results Found</td></tr>
												<?php
													$query = "select * from purchase_requisition_header where requisitioner='$userid' order by last_made_changes desc";
													$rs = query($query);
													$rscount = getNumRows($rs);
													while($obj=fetch($rs)){
														$prnumber = $obj->requisition_number;
														$stat = strtoupper($obj->requisition_status);

														if($stat=='APPROVED'){
															$label = 'label-success';
														}
														else if($stat=='REJECTED'){
															$label = 'label-danger';
														}
														else{
															$label ='label-default';
														}
														$currency = getCurrencyCode($obj->currency);
														$requester = $obj->requisitioner;
														$dept = getDeptName($requester);
														$requester = getUserName($requester);
														$prgrandtotal = getPrGrandTotal($prnumber);
														$reqtype = strtoupper($obj->requisition_type);

														$reqdate = formatDate($obj->requisition_date);
														$dateneeded = formatDate($obj->date_needed);
														$canceleddate = formatDate($obj->canceled_date);
														$remarks = $obj->justification;
														$lastmodified = formatDate($obj->last_modified);
														$conf = getUserName($obj->conformance);
														$sh =getUserName( $obj->section_head);
														$gm = getUserName($obj->general_manager);
														$avp = getUserName($obj->assistant_vice_president);
														$svp = getUserName($obj->senior_vice_president);
														$pr = getUserName($obj->president);
														

														$prdesc = getPrDescription($prnumber);
														$lastapprovedby = getLastPrApprover($prnumber);

														$withdiscrepancy = checkIfDiscrepancyExistPR($prnumber);
														$forrevisionremarks = getRevisionSources($prnumber, 'requisition_number');

														$reason = '';
														$enableedit = '';
														if($stat=='REJECTED'||$stat=='DRAFT'||$stat=='FOR REVISION'){
															$enableedit = "<img prnum='$prnumber' class='nopadding editpurchaserequisition hidden' src='../resources/img/File_Edit.png' title='Edit Purchase Requisition'";	
														}
														if($stat=='REJECTED'){
															$reason = getStatusRemarks('purchase_requisition_approval_history',"requisition_number='".$prnumber."'",'pr_approval_id',"reason_for_rejection");
														}
														else if($stat=='FOR REVISION'){
															//$reason = getStatusRemarks('purchase_order_revision_history, purchase_order_header',"purchase_order_header.order_number=purchase_order_revision_history.order_number and reference_requisition_number='".$prnumber."'",'po_revision_id',"remarks");
															$reason = '<span style="font-style:italic; font-size:10px ">Click to see details on revision</span>';
														}
														echo "<tr class='tablerow pointer' withdiscrepancy='$withdiscrepancy' forrevision='$forrevisionremarks' lastapprovedby='$lastapprovedby' id='$prnumber' dept='$dept' canceleddate='$canceleddate' prtotal='$prgrandtotal' requisitioner='$requester' currency='$currency' remarks='$remarks' stat='$stat' type='$reqtype' reqdate='$reqdate' dateneeded='$dateneeded' lastmodified='$lastmodified' sh='$sh' conf='$conf' gm='$gm' avp='$avp' svp='$svp' pr='$pr'>
																<td></td>
																<td class='text-center text-nowrap'>$enableedit</td>
																
																<td class='clickable text-nowrap'>$prnumber</td>
																<td class='clickable text-center'><span class='label $label'>$stat</span></td>
																<td class='clickable text-center text-nowrap'>$reqdate</td>
																<td class='clickable'>$prdesc</td>

																<td class='clickable'>$reason</td>
														</tr>";
													}
												?>