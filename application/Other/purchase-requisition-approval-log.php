<?php
	include("../../config/connection.php");
	include("../../config/checklogin.php");
	include("../../config/functions.php");

	$pr = isset($_GET['ksjcno0923jsbkyiu94hnsk'])?$_GET['ksjcno0923jsbkyiu94hnsk']:'';
	$prnum = $pr;


	if($pr!=''){

		$fl = '';
		$revision = $_GET['rev'];
		$prrev = $pr.'-R'.$_GET['rev'];
		$rs = query("select * from purchase_requisition_header_revision where requisition_number='$prrev'");
		if(getNumRows($rs)==1){
			$fl = 'exist';
			$pr = $prrev;
		}

		if($fl!='exist'){
			$query = "select * from purchase_requisition_header where requisition_number='$pr'";
		}
		else{
			$query = "select * from purchase_requisition_header_revision where requisition_number like '$pr%' order by last_made_changes desc limit 1";
		}
		$rs = query($query);
		while($obj=fetch($rs)){
			$prnumber = $obj->requisition_number;
			$prsuffix = $fl!='exist'?appendPrRevSuffix($prnumber):'';
			//$stat = strtoupper($obj->requisition_status);
			$stat = 'IN REVIEW';
			$revflag = $obj->next_revision_series;

			$currency = getCurrencyCode($obj->currency);
			
			$requester = getUserName($obj->requisitioner);
			$dept = getDeptName($obj->requisitioner);
			$prgrandtotal = getPrGrandTotal($prnumber);
			$reqtype = strtoupper($obj->requisition_type);

			$reqdate = formatDate($obj->requisition_date);
			$dateneeded = formatDate($obj->date_needed);
			$canceleddate = formatDate($obj->canceled_date);
			$justification = strUtfEncode($obj->justification);
			$prnotes = strUtfEncode($obj->pr_notes);
            $reasonforcancellation = strUtfEncode(getPrCancelReason($prnumber));
			$lastmodified = formatDate($obj->last_modified);

			/*$conf = ($obj->conformance=='1')?'Not Applicable':(($obj->conformance==NULL)?'Not Required':getUserName($obj->conformance));
			$sh1 =($obj->section_head1=='1')?'Not Applicable':(($obj->section_head1==NULL)?'Not Required':getUserName($obj->section_head1));
			$sh2 =($obj->section_head2=='1')?'Not Applicable':(($obj->section_head2==NULL)?'Not Required':getUserName($obj->section_head2));
			$gm = ($obj->general_manager=='1')?'Not Applicable':(($obj->general_manager==NULL)?'Not Required':getUserName($obj->general_manager));
			$avp = ($obj->assistant_vice_president=='1')?'Not Applicable':(($obj->assistant_vice_president==NULL)?'Not Required':getUserName($obj->assistant_vice_president));
			$svp = ($obj->senior_vice_president=='1')?'Not Applicable':(($obj->senior_vice_president==NULL)?'Not Required':getUserName($obj->senior_vice_president));
			$prs = ($obj->president=='1')?'Not Applicable':(($obj->president==NULL)?'Not Required':getUserName($obj->president));*/
			$conf = ($obj->conformance=='1')?'Not Applicable':(($obj->conformance==NULL)?'Not Required':getUserName($obj->conformance)).' '.getPrApprovalHistoryTimestamp($prnum,2,$revision);
			$sh1 =($obj->section_head1=='1')?'Not Applicable':(($obj->section_head1==NULL)?'Not Required':getUserName($obj->section_head1)).' '.getPrApprovalHistoryTimestamp($prnum,0,$revision);
			$sh2 =($obj->section_head2=='1')?'Not Applicable':(($obj->section_head2==NULL)?'Not Required':getUserName($obj->section_head2)).' '.getPrApprovalHistoryTimestamp($prnum,1,$revision);
			$gm = ($obj->general_manager=='1')?'Not Applicable':(($obj->general_manager==NULL)?'Not Required':getUserName($obj->general_manager)).' '.getPrApprovalHistoryTimestamp($prnum,3,$revision);
			$avp = ($obj->assistant_vice_president=='1')?'Not Applicable':(($obj->assistant_vice_president==NULL)?'Not Required':getUserName($obj->assistant_vice_president)).' '.getPrApprovalHistoryTimestamp($prnum,4,$revision);
			$svp = ($obj->senior_vice_president=='1')?'Not Applicable':(($obj->senior_vice_president==NULL)?'Not Required':getUserName($obj->senior_vice_president)).' '.getPrApprovalHistoryTimestamp($prnum,5,$revision);
			$prs = ($obj->president=='1')?'Not Applicable':(($obj->president==NULL)?'Not Required':getUserName($obj->president)).' '.getPrApprovalHistoryTimestamp($prnum,6,$revision);

			if($fl!='exist'){
				$lastapprovedby = getLastPrApprover($prnumber,$revision);
			}
			else{
				$lastapprovedby = getLastPrApprover2($prnumber,$revision);
			}
		}
	}
	else{
		exit('Purchase Requisition does not exist.');
	}
?>

<!doctype html>
<html style='background-color:#fff'>
	<head>

		<title>EPRPO - Purchase Requisition</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="../../css/bootstrap.css">
		<link rel="stylesheet" href="../../css/main.css">
		<link rel="shortcut icon" href="../../resources/pricon.ico">
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>

		<link href="../../css/fontAwesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	</head>
	<body>
		<div class="container">
			<div class="col-md-12"><h2>Purchase Requisition</h2></div>

			<form class="form-horizontal">
				
								<div class='tabpane-white margin-top-20 margin-bottom-10' id='generalinfosection'>
										<ul class="nav nav-tabs">
										  <li role="presentation" class="active" data-pane='#pr-geninfo' id='pr-geninfotab'><a>General Information</a></li>
										  <li role="presentation" data-pane='#pr-approverspane'><a>Approvers</a></li>
										 
										</ul>
										<div class='tab-panes'>
											<div class='pane active' id='pr-geninfo'>
												<div class="pane-content">
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label col-sm-4">Requisition No.</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='prnumber'><?php echo $prnumber.$prsuffix; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Status</label>
															<div class="col-sm-8">
																<span class='form-control-static' id='reqstat'><?php echo $stat; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Requisitioner</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='requisitioner'><?php echo $requester; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Department</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='dept'><?php echo $dept; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Currency</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='reqcurrency'><?php echo $currency; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Justification</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='justification'><?php echo $justification; ?></span>
															</div>
														</div>
														<br>
													</div>
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label col-sm-4">Type</label>
															<div class="col-sm-8">
																 <span id='reqtype'><?php echo $reqtype; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Requisition Date</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='reqdate'><?php echo $reqdate; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Date Needed</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='reqdateneeded'><?php echo $dateneeded; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Last Modified</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='lastmodified'><?php echo $lastmodified; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Remarks</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='prnotes'><?php echo $prnotes; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Date Cancelled</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='datecanceled'><?php echo $canceleddate; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Reason for Cancellation</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='reasonforcancellation'><?php echo $reasonforcancellation; ?></span>
															</div>
														</div>
														<br>
													</div>

												</div>
											</div>
											<div class="pane" id='pr-approverspane'>
												<div class="pane-content">
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label col-sm-5">Section Head</label>
															<div class="col-sm-7">
																 <span class='form-control-static sectionhead'><?php echo $sh1; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-5">Section Head</label>
															<div class="col-sm-7">
																 <span class='form-control-static sectionhead'><?php echo $sh2; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-5">Conformance Head</label>
															<div class="col-sm-7">
																 <span class='form-control-static confhead'><?php echo $conf; ?></span>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-5">General Manager</label>
															<div class="col-sm-7">
																 <span class='form-control-static genman'><?php echo $gm; ?></span>
															</div>
														</div>
														
														
														<br>
													</div>
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label col-sm-5">Asst. Vice Pres./Vice President</label>
															<div class="col-sm-7">
																 <span class='form-control-static asstvp'><?php echo $avp; ?></span>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-5">Sr. Vice President</label>
															<div class="col-sm-7">
																 <span class='form-control-static srvp'><?php echo $svp; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-5">President</label>
															<div class="col-sm-7">
																 <span class='form-control-static president'><?php echo $prs; ?></span>
															</div>
														</div>
														<!--<div class="form-group">
															<label class="control-label col-sm-5">Last approved by</label>
															<div class="col-sm-7">
																 <span class='form-control-static lastapprovedby' id='prnumber'><?php echo $lastapprovedby; ?></span>
															</div>
														</div>-->
													</div>


													

												</div>
											</div>
										</div>
								</div>
								<br>
								<div class='tabpane-white margin-top-20 margin-bottom-10' id='prdetails'>
										<ul class="nav nav-tabs">
										  <li role="presentation" class="active" data-pane='#lineitemspane'><a>Line Items</a></li>
										  <li role="presentation" data-pane='#allocationsummarypane'><a>Allocation Summary</a></li>
										  <li role="presentation" data-pane='#attachmentspane'><a>Attachments</a></li>
										</ul>
										<div class='tab-panes'>
											<div class='pane active' id='lineitemspane'>
												<div class="pane-content">
													<div class='tablewithbuttons'>
														<div class='table-wrapper' style='height:200px'>
															<table class='table table-striped table-condensed mytable table-bordered no-side-borders table-hover'>
																<thead>
																	<tr>
																		<th class='text-center'>LINE</th>
																		<th>ITEM CODE</th>
																		<th>NAME</th>
																		<th>DESCRIPTION</th>
																		<th>SUPPLIER</th>
																		<th class='text-center'>CLASSF'N CODE</th>
																		<th class='text-right'>QTY</th>
																		<th class='text-center'>UOM</th>
																		<th class='text-center'>CURRENCY</th>
																		<th class='text-right'>UNIT PRICE</th>
																		<th class='text-right'>AMOUNT</th>
																		<!--<th class='text-center'>PO NUMBER</th>
																		<th class='text-center'>STATUS</th>-->
																	</tr>
																</thead>
																<tbody id='cartitems'>

																<?php
																	if($fl!='exist'){ 
																		$query = "select item_code,
																	                         purchase_requisition_details.item_id,
																	    					 item.item_name,
																	                         long_description,
																	    					 purchase_requisition_details.quantity,
																	    					 unit_of_measure_code,
																	    					 purchase_requisition_details.unit_price,
																	    					 supplier_name,
																	    					 classification_code
																	    		      from purchase_requisition_details, item, supplier, unit_of_measure, purchase_requisition_header
																	    		      where purchase_requisition_details.supplier_id=supplier.id and 
																	    		      		purchase_requisition_details.item_id=item.id and 
																	                        purchase_requisition_details.requisition_number = purchase_requisition_header.requisition_number and
																	    		      		unit_of_measure_id=unit_of_measure.id and
																	    		      		purchase_requisition_details.requisition_number='$prnumber'";
																	}
																	else{
																		$query = "select item_code,
																                         purchase_requisition_details_revision.item_id,
																    					 item.item_name,
																                         long_description,
																    					 purchase_requisition_details_revision.quantity,
																    					 unit_of_measure_code,
																    					 purchase_requisition_details_revision.unit_price,
																    					 supplier_name,
																    					 classification_code
																    		      from purchase_requisition_details_revision, item, supplier, unit_of_measure, purchase_requisition_header_revision
																    		      where purchase_requisition_details_revision.supplier_id=supplier.id and 
																    		      		purchase_requisition_details_revision.item_id=item.id and 
																                        purchase_requisition_details_revision.requisition_number = purchase_requisition_header_revision.requisition_number and
																    		      		unit_of_measure_id=unit_of_measure.id and
																    		      		purchase_requisition_details_revision.requisition_number='$prnumber'";
																	}
																    	$rs = query($query);
																    	$linenumber = 1; 
																    	$gtotal = 0;
																    	while($obj=fetch($rs)){
																            $itemid = $obj->item_id;
																    		$itemcode = $obj->item_code;
																    		$name=strUtfEncode($obj->item_name);
																            $desc = strUtfEncode($obj->long_description);
																            $classfn = $obj->classification_code;
																    		$qty=$obj->quantity;
																    		$uom=$obj->unit_of_measure_code;
																    		$price=$obj->unit_price;
																    		$amount = $qty*$price;
																    		$gtotal = $gtotal + round($amount,2);
																            $qty=convertWithDecimal($obj->quantity,0);
																    		$price = convertWithDecimal($price,5);
																    		$amount = convertWithDecimal($amount,2);
																    		$supplier=strUtfEncode($obj->supplier_name);
																    		$po=getPoNumber($pr,$itemid);
																            $postat = getPoStatus($po);
																    		echo "<tr title='Item Description: \n$desc'>
																    				<td class='text-center'>$linenumber</td>
																    				<td>$itemcode</td>
																    				<td>$name</td>
																    				<td>$desc</td>
																                    <td>$supplier</td>
																                    <td class='text-center text-nowrap'>$classfn</td>
																    				<td class='text-right'>$qty</td>
																    				<td class='text-center'>$uom</td>
																    				<td class='text-center'>$currency</td>
																    				<td class='text-right'>$price</td>
																    				<td class='text-right'>$amount</td>
																    				
																    			  </tr>";
																    			  //<td class='text-center'>$po</td>
																                   //<td class='text-center'>$postat</td>
																    		$linenumber++;
																    	}
																    	$gtotal = convertWithDecimal($gtotal,2);

																?>		
																</tbody>
															</table>

														</div>
														<div class="row">
															<div class="col-md-offset-7 col-md-5" style='margin-top: 10px;'>
																<form class='form-horizontal'>
																	<div class="form-group" >
																		<label class='control-label col-xs-5'>Total Amount</label> 
																		<div class="col-xs-7">
																			<div class="input-group">
																				<div class="input-group-addon"><b><?php echo $currency; ?></b>
																				</div>
																				<input type='text' class='form-input form-control text-right disabled' value='<?php echo $gtotal; ?>' id='grandtotal' readonly>
																			</div>
																			
																		</div>
																	</div>			
																</form>
															</div>
														</div>
													</div>
													
												</div>
											</div>
											<div class='pane' id='allocationsummarypane'>
												<div class="pane-content">
													<div class='table-wrapper' style='height:200px'>
			                                            <table class='table table-striped table-condensed mytable'>
			                                                <thead>
			                                                    <tr>
			                                                        <th class='text-center'>LINE</th>
			                                                        <th>DEPARTMENT</th>
			                                                        <th class='text-center'>PERCENTAGE %</th>
			                                                        <th>REMARKS</th>
			                                                    </tr>

			                                                </thead>
			                                                <tbody id='allocationsummary'>
			                                                	<?php
			                                                	 if($fl!='exist'){
				                                                	 $query="select section_department_name, 
				                                                	 				purchase_requisition_allocation.remarks, 
				                                                	 				percentage
																	        from purchase_requisition_allocation, 
																	        	 section_department 
																	        where requisition_number='$prnumber' and
																	        	  department_id=section_department.id";
																}
																else{
																	 $query="select section_department_name, 
			                                                	 				purchase_requisition_allocation_revision.remarks, 
			                                                	 				percentage
																        from purchase_requisition_allocation_revision, 
																        	 section_department 
																        where requisition_number='$prnumber' and
																        	  department_id=section_department.id";
																}
																        $rs = query($query);
																        $line = 1;
																        while($obj=fetch($rs)){;
																            $dept = $obj->section_department_name;
																            $perc = $obj->percentage;
																            $remarks = strUtfEncode($obj->remarks);
																            echo "<tr>
																		            <td class='text-center'>$line</td>
																		            <td>$dept</td>
																		            <td class='text-center'>$perc</td>
																		            <td>$remarks</td>
																		          </tr>";
																            $line++;
																        }
			                                                	?>
			                                                </tbody>
			                                            </table>
			                                        </div>
												</div>
											</div>
											<div class='pane' id='attachmentspane'>
												<div class="pane-content">
													<div class="table-wrapper" style='height:200px'>
			                                            <table class='table table-striped table-condensed table-hover mytable'>
			                                                <thead>
			                                                    <tr>
			                                                        <th style='width:100px;'></th>
			                                                        <th>FILE</th>
			                                                        <th>DESCRIPTION</th>
			                                                        <th>OTHER DESCRIPTION</th>
			                                                    </tr>
			                                                </thead>
			                                                <tbody id='attachmentstable'>
			                                                	<?php
			                                                		if($fl!='exist'){
			                                                			$foldername = 'Attachments';
			                                                			$query = "select * from attachments where requisition_number='$prnumber'";
			                                                		}
			                                                		else{
			                                                			$foldername = 'Attachments_Revision';
			                                                			$query = "select * from attachments_revision where requisition_number='$prnumber'";
			                                                		}
																	$rs = query($query);
																	if(getNumRows($rs)<1){
																		echo "<tr><td class='noresult' colspan='20' style='font-style:italic; color:#aaa'>No Attachments</td></tr>";
																	}
																	while($obj=fetch($rs)){
																		$filename = $obj->file_name;
																		$filedesc = $obj->file_description;
																		                                                        //temp
																		if($filedesc=='NULL'){
																			$filedesc = '';
																		}
																		                                                        //
																		$other = strUtfEncode($obj->other_description);
																		echo "<tr class='attachmentrow'>
																				<td class='text-center'>
																					<form action='../".$foldername."/download.php' method='post'>
																						<input type='hidden' name='file' value='$filename'>
																						<input class='btn btn-xs btn-default' type='submit' value='download' name='downloadbtn'>
																					</form>
																				</td>
																				<td>$filename</td>
																				<td>$filedesc</td>
																				<td>$other</td>
																			</tr>";
																	}

			                                                	?>
			                                                
			                                                </tbody>
			                                            </table>
			                                        </div> 
												</div>
											</div>
										</div>
								</div>

			</form>
			<?php include('pr-legend.php'); ?>

		</div>
		




		<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->

		<script src="../../js/jquery-1.11.3.min.js"></script>
		<script src="../../js/bootstrap.min.js"></script>
		<script src="../../js/jsfunctions.js"></script>
		<script src="../../js/custom.js"></script>
	</body>
</html>