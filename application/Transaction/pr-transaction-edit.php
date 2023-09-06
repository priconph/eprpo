<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
    include('../../config/functions.php');
	$pr = isset($_GET['KdUeobMFHDpo'])?$_GET['KdUeobMFHDpo']:'';
	if($pr==''){
		exit('Direct Access Not Allowed');
	}
	else{
	    $loginuserdeptid = getUserDeptId($userid);
        $query = "select * from purchase_requisition_header where requisition_number='$pr'";
        $rs=query($query);
        while($obj=fetch($rs)){
            $status = $obj->requisition_status;
            $requisitioner = $obj->requisitioner;
            $reqdate = formatDate3($obj->requisition_date);
            $reqtype = $obj->requisition_type;
            $dateneeded = formatDate($obj->date_needed);
            $currency = $obj->currency;
            $remarks = strUtfEncode($obj->justification);
            $prnotes = strUtfEncode($obj->pr_notes);
            $sh1selected = $obj->section_head1;
            $sh2selected = $obj->section_head2;
            $cfselected = $obj->conformance;
            $gmselected = $obj->general_manager;
            $avpselected = $obj->assistant_vice_president;
            $svpselected = $obj->senior_vice_president;
            $prselected = $obj->president;
            //$forrevision = $obj->revision_flag;


        }
        $reasonforrejection = strUtfEncode(getStatusRemarks('purchase_requisition_approval_history',"requisition_number='".$pr."'",'pr_approval_id',"reason_for_rejection"));
        $rejectedby = strUtfEncode(rejectedBy('purchase_requisition_approval_history',"requisition_number='".$pr."'",'pr_approval_id',"reason_for_rejection"));
        $forrevision = getRevisionSources($pr,'requisition_number');
        $withdiscrepancy = checkIfDiscrepancyExistPR($pr);
    }
?>
<div class='header-page' >
	<div class='header-page-inner'>
		Edit Purchase Requisition
	</div>
</div>
<div class="container-fluid">
	<div class="pageContent">
		<div class="panel panel-primary mypanel">
    			<div class="panel-body">
    				<div class='tabpane-white margin-top-20 margin-bottom-10' id='generalinfosection'>
						<ul class="nav nav-tabs">
							  <li role="presentation" class="active" data-pane='#predit-geninfo' id='predit-geninfotab'><a>General Information</a></li>
							  <li role="presentation" class="hidden" data-pane='#predit-revisionremarks' id='predit-revisionremarkstab'><a>Revision Remarks</a></li>
							  <li role="presentation" class="hidden" data-pane='#predit-discrepancy' id='predit-discrepancytab'><a>Discrepancy Details</a></li>
							  <li role="presentation" class="hidden" data-pane='#predit-rejectionremarks' id='predit-rejectionremarkstab'><a>Rejection Remarks</a></li>
						</ul>
					</div>
					<div class='tab-panes'>
						<div class='pane active' id='predit-geninfo'>
							<div class="pane-content" style='padding-bottom:10px'>
								<form class="form-horizontal">
									<div class="col-md-12">
										<div class="errordiv"></div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-sm-4">Requisition No.</label>
											<div class="col-sm-8">
												<input type='text' name='prnumber' class='form-input form-control disabled reqnum' value="<?php echo $pr; ?>" readonly>
											</div>
										</div>
										
										<div class="form-group">
											<label class="control-label col-sm-4">Requisitioner</label>
											<div class="col-sm-8">
												<input type='text' name='prnumber' class='form-input form-control disabled' value="<?php echo strUtfEncode(getUserName($userid));?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Department</label>
											<div class="col-sm-8">
												<input type='text' name='sec-dept-div' class='form-input form-control disabled' value="<?php echo getDeptName($userid); ?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Type</label>
											<div class="col-sm-8">
												<select name='type' class='form-input form-control reqtype'>
														<?php
															if($reqtype=='regular'){
																echo "<option value='regular' selected>REGULAR</option>";
															}
															else{
																echo "<option value='regular'>REGULAR</option>";
															}
															if($reqtype=='urgent'){
																echo "<option value='urgent' selected>URGENT</option>";
															}
															else{
																echo "<option value='urgent'>URGENT</option>";
															}
														?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Currency</label>
											<div class="col-sm-8">
												<select name='type' class='form-input form-control currency'>
													<option value='NULL'></option>
													<?php 
														$query = "select * from currency";
														$rs = query($query);
														while($obj=fetch($rs)){
															$id = $obj->id;
															$code = $obj->currency_code;
															if($id==$currency){
																echo "<option value='$id' selected>$code</option>";
															}
															else{
																echo "<option value='$id'>$code</option>";
															}
														}
													?>
												</select>
											</div>
										</div>
										<?php 
											if($forrevision>0||$status=='REJECTED'){
                                                echo "<div class=\"form-group\">
                                                                <label class=\"control-label col-sm-4\">PR History</label>
                                                                <div class=\"col-sm-8\">
                                                                     <span class='form-control-static prtrans-prhistorytoggle' style='font-style:italic; color:blue; cursor:pointer;'>View Details</span>
                                                                </div>
                                                </div>";
											
											}
										?>
									</div>
									<div class="col-md-6">
										
										<div class="form-group">
											<label class="control-label col-sm-4">Requisition Date</label>
											<div class="col-sm-8">
												<input type='date' class='form-input form-control dateinput reqdate' value="<?php echo date('Y-m-d'); ?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Date Needed</label>
											<div class="col-sm-8">
												<input type='text' class='form-input form-control dateinput dateneeded' value="<?php echo $dateneeded; ?>">
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Justification</label>
											<div class="col-sm-8">
												<textarea rows='2' name='justification' class='form-input form-control justification'><?php echo $remarks; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Remarks</label>
											<div class="col-sm-8">
												<textarea rows='3' class='form-input form-control prnotes'><?php echo $prnotes; ?></textarea>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
						<div class='pane' id='predit-revisionremarks'>
						</div>
						<div class='pane' id='predit-discrepancy'>
						</div>
						<div class='pane' id='predit-rejectionremarks'>
							<form class='form-horizontal'>
								<div class="form-group">
									<label class="control-label col-sm-2">Approver</label>
									<div class="col-sm-10">
										<input class='form-control' id='rejectedby' value="<?php echo $rejectedby; ?>" readonly>
									</div>
								</div>
							</form>
							<form class='form-horizontal'>
								<div class="form-group">
									<label class="control-label col-sm-2">Reason for Rejection</label>
									<div class="col-sm-10">
										<textarea class='form-control' rows='4' id='reasonforrejection' readonly><?php echo $reasonforrejection; ?></textarea>
									</div>
								</div>
							</form>
						</div>
					</div>
    			</div>
    	</div>

    	<div class="panel panel-primary mypanel">
    			<div class="panel-body">
    				<div class='tabpane-white margin-top-20 margin-bottom-10' id='generalinfosection'>
						<ul class="nav nav-tabs">
							  <li role="presentation" class="active" data-pane='#lineitems'><a>Line Items</a></li>
							  <li role="presentation" data-pane='#allocation'><a>Allocation Summary</a></li>
							  <li role="presentation" data-pane='#attachments'><a>Attachments</a></li>
						</ul>
					</div>
					<div class='tab-panes'>
						<div class='pane active' id='lineitems'>

							<div class='tablewithbuttons'>
								<div class='table-wrapper' style='height:200px'>
									<div class="table-responsive">
										<table class='table table-striped table-condensed table-bordered no-side-borders table-hover mytable'>
											<thead>
												<tr>
													<th class='text-center'><input type='checkbox' class='selectall'></th>
													<th class='text-center text-nowrap'>LINE</th>
													<th class='text-nowrap'>ITEM CODE</th>
													<th class='text-nowrap'>ITEM NAME</th>
													<th class='text-nowrap'>DESCRIPTION</th>
													<th class='text-nowrap text-center'>CLASSF'N CODE</th>
													<th class='text-right text-nowrap'>QTY</th>
													<th class='text-nowrap text-center'>UOM</th>
													<th>SUPPLIER</th>
													<th class='text-right text-nowrap'>UNIT PRICE</th>
													<th class='text-right text-nowrap'>AMOUNT</th>
												</tr>
											</thead>
											<tbody id='pritems'>
												<?php
												$query = "select item_code,
															item_name,
															classification_code,
															item_id,
															quantity,
															purchase_requisition_details.unit_price,
															unit_of_measure_code,
															item.conformance_flag,
															purchase_requisition_details.supplier_id,
															supplier_name,
															long_description,
															supplier.active_flag as supp_status
															from purchase_requisition_details
															left join item on item_id=item.id
															left join supplier on purchase_requisition_details.supplier_id=supplier.id
															left join unit_of_measure on unit_of_measure_id=unit_of_measure.id
												where requisition_number='$pr'";
												$rs = query($query);
												$line = 1;
												$grandtotal = 0;
												$q = 'qty';
												$a = 'amount';
												$checkifconformance = false;
												while($obj=fetch($rs)){
													if($obj->supp_status == 1) {														
														$itemId = $obj->item_id;
														$itemcode = $obj->item_code;
														$shortdesc = strUtfEncode($obj->item_name);
														$longdesc = strUtfEncode($obj->long_description);
														$conf = $obj->conformance_flag;
														if($conf=='1'){
															$checkifconformance=true;
														}
														$classfn = $obj->classification_code;
														$qty = $obj->quantity;
														$price = $obj->unit_price;
														$uom = $obj->unit_of_measure_code;
														$suppid = $obj->supplier_id;
														$supplier = strUtfEncode($obj->supplier_name);
														$floatamount = $price*$qty;
														$amount = $price * $qty;
														$grandtotal = $grandtotal + $amount;
														$qty = convertWithDecimal($obj->quantity,0);
														$price = convertWithDecimal($price,5);
														$amount = convertWithDecimal($amount,2);
														echo "<tr prrowid='$itemId' conformance='$conf' class='pointer'>
														<td class='text-center'><input type='checkbox' class='itemcheckbox'></td>
														<td class='text-center rowclick rowlineitem'>$line</td>
														<td class='rowclick text-nowrap'>$itemcode</td>
														<td class='rowclick'>$shortdesc</td>
														<td class='rowclick'>$longdesc</td>
														<td class='rowclassfn text-center rowclick'>$classfn</td>
														<td class='text-right rowqty rowclick' id='$itemcode$q'>$qty</td>
														<td class='text-center rowclick'>$uom</td>
														<td class='rowsupplier rowclick' supplierid='$suppid'>$supplier</td>
														<td class='text-right rowprice rowclick'>$price</td>
														<td class='text-right amount rowclick' id='$itemcode$a' amount='$floatamount'>$amount</td>
														</tr>";
														$line++;      
													} else {
														echo "<script>$('#inactive_suppliers').modal('show');</script>";
													}
												}
												$grandtotal = convertWithDecimal($grandtotal,2);
												?>
											</tbody>
										</table>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6" style='margin-top:10px;'>
										<button class='btn btn-sm btn-blue2 mybtn' id='prcreate-additembtn'>Add an Item</button>
										<button class='btn btn-sm btn-blue2 mybtn' id='prcreate-addlotitembtn' data-toggle='modal' href='#addprlotitemmodal'>Add by Lot</button>
										<button class='btn btn-sm btn-blue2 mybtn' id='prcreate-removeitembtn'>Remove Item(s)</button>
									</div>
									<div class="col-sm-6" style='margin-top:10px;'>
										<form class='form-horizontal'>
											<div class="form-group" >
												<label class='control-label col-xs-5'>Total Amount</label> 
												<div class="col-xs-7">
													<div class="input-group">
														<div class="input-group-addon totalcurrencycode"></div>
														<input type='text' class='form-input form-control text-right' value='<?php echo $grandtotal; ?>' id='totalpramount' readonly>
													</div>
												</div>
											</div>			
										</form>
									</div>

								</div>

							</div>

						</div>
						<div class='pane' id='allocation'>
							<div class="pane-content">
											<div class='table-wrapper' style='height:200px'>

												<table class='table table-striped table-condensed table-hover mytable'>
													<thead>
														<tr>
															<th><input type='checkbox' class='selectall'></th>
															<th>DEPARTMENT</th>
															<th class='text-center'>PERCENTAGE %</th>
															<th>REMARKS</th>
														</tr>

													</thead>
													<tbody id='allocationsummary'>
														<?php
	                                             
	                                                        $query="select department_id, section_department_name, percentage, purchase_requisition_allocation.remarks 
	                                                                from purchase_requisition_allocation, section_department 
	                                                                where requisition_number='$pr' and
	                                                                      department_id=section_department.id";
	                                                        $rs = query($query);
	                                                        while($obj=fetch($rs)){
	                                                            $deptid = $obj->department_id;
	                                                            $dept = $obj->section_department_name;
	                                                            $perc = $obj->percentage;
	                                                            $notes = strUtfEncode($obj->remarks);
	                                                            echo "<tr>
	                                                                    <td><input type='checkbox' class='itemcheckbox'></td>
	                                                                    <td class='allocationdeptid' allocdeptid='$deptid'>$dept</td>
	                                                                    <td class='text-center allocationpercentage'>$perc</td>
	                                                                    <td class='allocationnotes'>$notes</td>
	                                                                  </tr>";
	                                                  
	                                                        }
	                                                    ?>

													</tbody>
												</table>
											</div>
											<div class="row">
												<div class="col-sm-12" style='margin-top:10px;'>
								    				<button class='btn btn-sm btn-blue2 mybtn' id='prcreate-addallocationbtn' data-toggle='modal' href='#allocationmodal'>Add Allocation</button>
													<button class='btn btn-sm btn-blue2 mybtn' id='prcreate-removeallocationbtn'>Remove Allocation</button>

								    			</div>
								    		</div>
											
							</div>
						</div>
						<div class='pane' id='attachments'>
							<div class="pane-content">
								<div class="table-wrapper" style='height:200px'>
													<form class='form-horizontal' action='../scripts/purchase-requisition.php' method='post' id='attachmentsform'  enctype='multipart/form-data'>
														<input type='hidden' value="<?php echo $pr; ?>" name='prnumber' id='prnum'>
														<input type='hidden' value='' name='email-to' id='email-to'>
														<input type='hidden' value='' name='email-toname' id='email-toname'>
														<input type='hidden' value='' name='source-button' id='source-button'>
														<input type='hidden' name='dsfkjoei' value="<?php echo 'JsoNelDNlsd'; ?>">
														<table class='table table-striped table-condensed table-hover mytable' cellspacing='0'>
															<thead>
																<tr>
																	<th><input type='checkbox' class='selectall'></th>
																	<th>FILE</th>
																	<th>DESCRIPTION</th>
																	<th>OTHER DESCRIPTION</th>
																</tr>
															</thead>
															<tbody id='attachmentstable'>
																	<?php
				                                                     $query = "select * from attachments where requisition_number='$pr'";
				                                                     $rs = query($query);
				                                                     while($obj=fetch($rs)){
				                                                        $filename = $obj->file_name;
				                                                        $filedesc = $obj->file_description;
				                                                        //temp
				                                                        if($filedesc=='NULL'){
				                                                            $filedesc = '';
				                                                        }
				                                                        //
				                                                        $other = strUtfEncode($obj->other_description).'';
				                                                        echo "<tr class='attachmentrow'>
				                                                                    <td>
				                                                                        <input type='checkbox' class='itemcheckbox prevattached'>
				                                                                    </td>
				                                                                    <td class='prevattached-filename'>$filename</td>
				                                                                    <td>$filedesc</td>
				                                                                    <td>$other</td>
				                                                                </tr>";
				                                                    }
				                                                ?>
																
															</tbody>
														</table>
													</form>
												</div>
												<div class="row">
													<div class="col-sm-12" style='margin-top:10px;'>
									    				<button class='btn btn-blue2 btn-sm mybtn' id='fileattachment-addbtn'>Add more file(s)</button>
														<button class='btn btn-blue2 btn-sm mybtn' id='fileattachment-deletebtn'>Delete file(s)</button>

									    			</div>
									    		</div>
							</div>
						</div>
					</div>
				</div>
		</div>

		<div class="panel panel-primary mypanel">
    			<div class="panel-body">
    				<div class='tabpane-white margin-top-20 margin-bottom-10' id='approverssection'>
						<ul class="nav nav-tabs">
							  <li role="presentation" class="active" data-pane='#newpr-approvers'><a>Approvers</a></li>
						</ul>
					</div>
					<div class='tab-panes'>
						<div class='pane active' id='newpr-approvers'>
							<div class="pane-content">
								<form class="form-horizontal">
									<div class="col-md-6">
										
										<div class="form-group">
											<label class="control-label col-sm-5">Section Head</label>
											<div class="col-sm-7">
												<select type='text' class='form-input defaultsign form-control sectionhead1'>
													<option value='NULL'>-</option>
													
													<?php
														$query = "select * from approver where section_department_id='$loginuserdeptid' and active_flag='1' and approval_type_id='1'";
														$rs = query($query);
														while($obj=fetch($rs)){
															$id = $obj->user_id;
															$approver = getUserName($id);
															if($id==$sh1selected){
																echo "<option value='$id' selected>$approver</option>";
															}
															else{
																echo "<option value='$id'>$approver</option>";
															}
														}
													?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-5">Section Head</label>
											<div class="col-sm-7">
												<select type='text' class='form-input defaultsign form-control sectionhead2'>
													<option value='NULL'>-</option>
													<?php 
														if($sh2selected=='1'){
															echo "<option value='1' selected>Not Applicable</option>";
														}
														else{
															echo "<option value='1'>Not Applicable</option>";
														}
														$query = "select * from approver where section_department_id='$loginuserdeptid' and active_flag='1' and approval_type_id='1'";
														$rs = query($query);
														while($obj=fetch($rs)){
															$id = $obj->user_id;
															$approver = getUserName($id);
															if($id==$sh2selected){
																echo "<option value='$id' selected>$approver</option>";
															}
															else{
																echo "<option value='$id'>$approver</option>";
															}
														}
													?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-5">Conformance Head</label>
											<div class="col-sm-7">
												<select type='text' class='form-input defaultsign form-control confhead'>
													<option value='NULL'>-</option>
													<?php
														//$query = "select * from approver where section_department_id='$loginuserdeptid' and active_flag='1' and approval_type_id='7'";
														$query = "select * from approver where approval_type_id='7' and active_flag='1'";
														$rs = query($query);
														while($obj=fetch($rs)){
															$id = $obj->user_id;
															$approver = getUserName($id);
															if($id==$cfselected){
																echo "<option value='$id' selected>$approver</option>";
															}
															else{
																echo "<option value='$id'>$approver</option>";
															}
														}
													?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-5">General Manager</label>
											<div class="col-sm-7">
												<select type='text' class='form-input defaultsign form-control genman'>
													<option value='NULL'>-</option>
													<?php 
														if($gmselected=='1'){
															echo "<option value='1' selected>Not Applicable</option>";
														}
														else{
															echo "<option value='1'>Not Applicable</option>";
														}
														$query = "select * from approver where active_flag='1' and approval_type_id='3'";
														$rs = query($query);
														while($obj=fetch($rs)){
															$id = $obj->user_id;
															$approver = getUserName($id);
															if($id==$gmselected){
																echo "<option value='$id' selected>$approver</option>";
															}
															else{
																echo "<option value='$id'>$approver</option>";
															}
														}
													?>
												</select>
											</div>
										</div>	
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-sm-5">Asst. Vice Pres./Vice President</label>
											<div class="col-sm-7">
												<select type='text' class='form-input defaultsign form-control asstvp'>
													<option value='NULL'>-</option>
													<?php 
														if($avpselected=='1'){
															echo "<option value='1' selected>Not Applicable</option>";
														}
														else{
															echo "<option value='1'>Not Applicable</option>";
														}
														$query = "select * from approver where active_flag='1' and approval_type_id='4'";
														$rs = query($query);
														while($obj=fetch($rs)){
															$id = $obj->user_id;
															$approver = getUserName($id);
															if($id==$avpselected){
																echo "<option value='$id' selected>$approver</option>";
															}
															else{
																echo "<option value='$id'>$approver</option>";
															}
														}
													?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-5">Sr. Vice President</label>
											<div class="col-sm-7">
												<select type='text' class='form-input defaultsign form-control srvp'>
													<option value='NULL'>-</option>
													<?php 
														if($svpselected=='1'){
															echo "<option value='1' selected>Not Applicable</option>";
														}
														else{
															echo "<option value='1'>Not Applicable</option>";
														}
														$query = "select * from approver where approval_type_id='5' and active_flag='1'";
														$rs = query($query);
														while($obj=fetch($rs)){
															$id = $obj->user_id;
															$approver = getUserName($id);
															if($id==$svpselected){
																echo "<option value='$id' selected>$approver</option>";
															}
															else{
																echo "<option value='$id'>$approver</option>";
															}
														}
													?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-5">President</label>
											<div class="col-sm-7">
												<select type='text' class='form-input defaultsign form-control president'>
													<option value='NULL'>-</option>
													<?php

														$query = "select * from approver where approval_type_id='6' and active_flag='1'";
														$rs = query($query);
														while($obj=fetch($rs)){
															$id = $obj->user_id;
															$approver = getUserName($id);
															if($id==$prselected){
																echo "<option value='$id' selected>$approver</option>";
															}
															else{
																echo "<option value='$id'>$approver</option>";
															}
														}


														
													?>
												</select>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>

    			</div>
    	</div>

    	<div class="panel panel-primary mypanel">
    		<div class="panel-body">
    			<div class="panel-content text-center">
    				<button class='btn btn-blue2 mybtn preditsavesubmit' id='predit-savebtn'>Save</button>
    				<button class='btn btn-blue2 mybtn preditsavesubmit' id='predit-submitbtn'>Submit</button>
    				<button class='btn btn-blue2 mybtn' id='prcreate-voidbtn'>Cancel</button>
    				<button class='btn btn-blue2 mybtn' id='prcreate-cancelbtn'>Back</button>
    			</div>
			</div>
    	</div>
	</div>
</div>

<div class="modal fade lookupmodal" id="addprlotitemmodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Add by Lot
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
							<div class="form-group">
								<label class='control-label col-md-4'>Enter Lot Number</label>
								<div class="col-md-8">
									<input type='text' class='form-input form-control addbylot-lotnumber'>
								</div>
							</div>
					</div>
				</div>
				<div class="tablewithpagination">
							<div class='table-wrapper' style='height:350px;'>
										<table class='table table-striped table-condensed mytable table-hover' cellspacing='0' id='itemtable'>
											<thead>
												<tr>
													<th></th>
													<th>CLASS'FN CODE</th>
													<th>QUANTITY</th>
													<th>SUPPLIER</th>
													<th>ITEM CODE</th>
													<th>ITEM NAME</th>
													<th>DESCRIPTION</th>
													<th>UNIT PRICE</th>
													<th></th>
												</tr>


											</thead>
											<tbody>
												<tr>
													<td colspan='20' class='noresult'>No Results Found</td>
												</tr>
											</tbody>
										</table>
							</div>
				</div>
			</div>
			<div class="modal-footer">
							<div class="text-center">
								<button class='btn btn-blue2 mybtn' id='addprlotitemmodal-addbtn'>Add</button>
								<button class='btn btn-blue2 mybtn modal-cancelbtn' id='addprlotitemmodal-cancelbtn'>Cancel</button>
							</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="allocationmodal">
	<div class="modal-dialog" style='width:600px'>
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Add Allocation
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-md-12">
					<form class="form-horizontal">
						<div class="form-group">
							<div class="col-md-12">
								<div class="errordiv"></div>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-3'>Department</label>
							<div class="col-md-9">
								<select class='form-input form-control department chosen-select' style='width:100%'>
									<option value=''>-</option>
									<?php
										$query = "select * from section_department where status='ACTIVE'";
										$rs = query($query);
										while ($obj=fetch($rs)) {
											$id = $obj->id;
											$name = $obj->section_department_name;
											echo "<option value='$id'>$name</option>";
										}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-3'>Percentage</label>
							<div class="col-md-9">
								<input type='number' max='100' class='form-input form-control percentage'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-3'>Remarks</label>
							<div class="col-md-9">
								<textarea rows='4' class='form-input form-control allocationremarks'></textarea>
							</div>
						</div>
					</form>
				</div>
				
			</div>
			<div class="modal-footer">
							<div class="text-center">
								<button class='btn btn-blue2 mybtn' id='allocationmodal-addbtn'>Add</button>
								<button class='btn btn-blue2 mybtn modal-cancelbtn' id='allocationmodal-cancelbtn'>Cancel</button>
							</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="editpritemmodal">
	<div class="modal-dialog" style='width:550px'>
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Edit Quantity
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
					<div class="col-md-12">
						<div class="form-group">
								<label class='control-label col-md-4'>Line Number</label>
								<div class="col-md-8">
									<input type='text' class='form-input form-control itemcode' readonly>
								</div>
						</div>
						<div class="form-group">
								<label class='control-label col-md-4'>Quantity</label>
								<div class="col-md-8">
									<input type='number' step='1' class='form-input form-control quantity'>
								</div>
						</div>		
					</div>
				</form>
						
			</div>
			<div class="modal-footer">
							<div class="text-center">
								<button class='btn btn-blue2 mybtn' id='editpritemmodal-confirmbtn'>Confirm</button>
								<button class='btn btn-blue2 mybtn modal-cancelbtn' id='editpritemmodal-cancelbtn'>Cancel</button>
							</div>
			</div>
		</div>
	</div>  
</div>
<div class="modal fade" id="editpricemodal">
	<div class="modal-dialog" style='width:550px'>
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Edit Quantity
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
					<div class="col-md-12">
						<div class="form-group">
								<label class='control-label col-md-4'>Item Code</label>
								<div class="col-md-8">
									<input type='text' class='form-input form-control itemcode' readonly>
								</div>
						</div>
						<div class="form-group">
								<label class='control-label col-md-4'>Unit Price</label>
								<div class="col-md-8">
									<input type='number' step='1' class='form-input form-control editpriceinput'>
								</div>
						</div>		
					</div>
				</form>
						
			</div>
			<div class="modal-footer">
							<div class="text-center">
								<button class='btn btn-blue2 mybtn' id='editpricemodal-confirmbtn'>Confirm</button>
								<button class='btn btn-blue2 mybtn modal-cancelbtn' id='editpricemodal-cancelbtn'>Cancel</button>
							</div>
			</div>
		</div>
	</div>  
</div>
<div class="modal fade" id="addpritemmodal">
	<div class="modal-dialog" style='width:550px'>
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Add an Item
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-md-12">
					<form class="form-horizontal">
						<div class="form-group">
							<div class="errordiv"></div>
						</div>
						<div class="form-group">
								<label class='control-label col-md-4'>Item Code</label>
								<div class="col-md-8">
									<div class="input-group">
										<input type='text' class='form-input form-control itemcode disabled' readonly>
										<div class="input-group-addon button-addon itemlookupbtn" from='edit' data-toggle='modal' href='#itemlistmodal'>
											<i class='fa fa-search'></i>
										</div>
									</div>
								</div>
						</div>
						<div class="form-group">
								<label class='control-label col-md-4'>Item Name</label>
								<div class="col-md-8">
									<input type='text' class='form-input form-control description disabled' readonly>
								</div>
						</div>
						<div class="form-group">
								<label class='control-label col-md-4'>Description</label>
								<div class="col-md-8">
									<textarea rows='5' class='form-input form-control itemdesc disabled' readonly></textarea>
								</div>
						</div>
						<div class="form-group">
								<label class='control-label col-md-4'>Classification Code</label>
								<div class="col-md-8">
									<div class="input-group">
										<input type='text' class='form-input form-control classfn disabled uppercase' readonly>
										<div class="input-group-addon button-addon classcodelookupbtn" data-toggle='modal' href='#editpr-classcodemodal'>
											<i class='fa fa-search'></i>
										</div>
									 </div>
								</div>
						</div>
						<div class="form-group">
								<label class='control-label col-md-4'>Supplier</label>
								<div class="col-md-8">
									<div class="input-group">
										<input type='text' class='form-input form-control supplier disabled' readonly>
										<div class="input-group-addon button-addon supplierlookupbtn" data-toggle='modal' href='#editpr-supplierlistmodal'>
											<i class='fa fa-search'></i>
										</div>
									</div>
								</div>
						</div>
						<div class="form-group">
								<label class='control-label col-md-4'>Quantity</label>
								<div class="col-md-8">
									<input type='number' step='1' class='form-input form-control quantity'>
								</div>
						</div>
						<div class="form-group">
								<label class='control-label col-md-4'>Unit Price</label>
								<div class="col-md-8">
										<input type='number' class='form-input form-control unitprice disabled' readonly>
			
								</div>
						</div>
					</form>		
				</div>
			</div>
			<div class="modal-footer">
							<div class="text-center">
								<button class='btn btn-blue2 mybtn' id='addpritemmodal-addbtn'>Add</button>
								<button class='btn btn-blue2 mybtn modal-cancelbtn' id='addpritemmodal-cancelbtn'>Cancel</button>
							</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade lookupmodal" id="itemlistmodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Item List
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="tablewrapper-sm">
					<table class='table table-striped table-condensed mytable table-hover table-bordered' cellspacing='0' id='editpr-itemlisttable'>
						<thead>
							<tr>

								<th>CODE</th>
								<th>NAME</th>
								<th>DESCRIPTION</th>
								<th>SUPPLIER</th>
								<th>UNIT PRICE</th>
								<th>CURRENCY</th>
								<th>DATE CREATED</th>
								<th>CREATED BY</th>
								<th>LAST UPDATE</th>
								<th>UPDATED BY</th>
							</tr>


						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>



<div class="modal fade lookupmodal" id="editpr-supplierlistmodal">
	<input type='hidden' id='supplierlookupsource'>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Supplier List
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
							<div class='tablewrapper-sm'>
										<table class='table table-striped table-hover table-condensed mytable' cellspacing='0' id='suppliertable' style='width:100%'>
											<thead>
												<tr>
													<th>SUPPLIER</th>
												</tr>
											</thead>
											<tbody id='editpr-supplierlistrows'>
												
											</tbody>
										</table>
							</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade lookupmodal" id="editpr-classcodemodal">
	<input type='hidden' id='classcodelookupsource'>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Classification Code List
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class='tablewrapper-sm'>
							<table class='table table-striped table-hover table-condensed mytable' cellspacing='0' id='classcodetable' style='width:100%'>
								<thead>
									<tr>
										<th>CLASSIFICATION CODE</th>
										<th>ACCOUNT NAME</th>
									</tr>
								</thead>
								<tbody id='addpr-classcodelistrows'>
									
								</tbody>
							</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade lookupmodal" id="inactive_suppliers">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Removed Items
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="row">
					<label class='control-label col-md-12'>The following items are tagged with inactive suppliers. Please add again the items with active suppliers.</label><br>
				</div>
				<div class="tablewithpagination">
					<table class='table table-striped table-condensed table-bordered no-side-borders table-hover mytable'>
						<thead>
							<tr>
								<th class='text-center text-nowrap'>LINE</th>
								<th class='text-nowrap'>ITEM CODE</th>
								<th class='text-nowrap'>ITEM NAME</th>
								<th class='text-nowrap'>DESCRIPTION</th>
								<th class='text-nowrap text-center'>CLASSF'N CODE</th>
								<th class='text-right text-nowrap'>QTY</th>
								<th class='text-nowrap text-center'>UOM</th>
								<th>SUPPLIER</th>
								<th class='text-right text-nowrap'>UNIT PRICE</th>
								<th class='text-right text-nowrap'>AMOUNT</th>
							</tr>
						</thead>
						<tbody id='pritems2'>
							<?php
							$query = "select item_code,
										item_name,
										classification_code,
										item_id,
										quantity,
										purchase_requisition_details.unit_price,
										unit_of_measure_code,
										item.conformance_flag,
										purchase_requisition_details.supplier_id,
										supplier_name,
										long_description
										from purchase_requisition_details
										left join item on item_id=item.id
										left join supplier on purchase_requisition_details.supplier_id=supplier.id
										left join unit_of_measure on unit_of_measure_id=unit_of_measure.id
							where requisition_number='$pr' and supplier.active_flag = 0";
							$rs1 = query($query);
							$line1 = 1;
							$q1 = 'qty';
							$a1 = 'amount';
							$checkifconformance = false;
							while($obj=fetch($rs1)){
								$itemId = $obj->item_id;
								$itemcode = $obj->item_code;
								$shortdesc = strUtfEncode($obj->item_name);
								$longdesc = strUtfEncode($obj->long_description);
								$conf = $obj->conformance_flag;
								if($conf=='1'){
									$checkifconformance=true;
								}
								$classfn = $obj->classification_code;
								$qty = $obj->quantity;
								$price = $obj->unit_price;
								$uom = $obj->unit_of_measure_code;
								$suppid = $obj->supplier_id;
								$supplier = strUtfEncode($obj->supplier_name);
								$floatamount = $price*$qty;
								$amount = $price * $qty;
								$grandtotal = $grandtotal + $amount;
								$qty = convertWithDecimal($obj->quantity,0);
								$price = convertWithDecimal($price,5);
								$amount = convertWithDecimal($amount,2);
								echo "<tr prrowid1='$itemId' conformance='$conf' class='pointer'>
								<td class='text-center'>$line1</td>
								<td class='rowclick text-nowrap'>$itemcode</td>
								<td class='rowclick'>$shortdesc</td>
								<td class='rowclick'>$longdesc</td>
								<td class='rowclassfn text-center rowclick'>$classfn</td>
								<td class='text-right rowqty rowclick' id='$itemcode$q1'>$qty</td>
								<td class='text-center rowclick'>$uom</td>
								<td class='rowsupplier rowclick' supplierid='$suppid'>$supplier</td>
								<td class='text-right rowprice rowclick'>$price</td>
								<td class='text-right amount rowclick' id='$itemcode$a1' amount='$floatamount'>$amount</td>
								</tr>";
								$line1++; 
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<div class="text-right">
					<button class='btn btn-blue2 mybtn modal-cancelbtn' id='removedinactivesupplieritems-cancelbtn'>Close</button>
				</div>
			</div>
		</div>
	</div>
</div>


<script src="../resources/selectoption/chosen.jquery.js"></script>
<script src="../resources/selectoption/docsupport/prism.js"></script>


<script type="text/javascript">
		$('.totalcurrencycode').html('<b>'+$('.currency option:selected').text()+'</b>');
		var ifconf, grandtotal, forrevision;
		ifconf = <?php echo json_encode($checkifconformance); ?>;
		grandtotal = $('#totalpramount').val().replace(/,/g,'');
		checkGrandTotal(grandtotal);
		if($('.confhead option:selected').attr('value')!='NULL'||ifconf==true){
			$('.confhead').addClass('defaultsign').closest('.form-group').removeClass('hidden');
		}
		if(ifconf==false){
			$('.confhead').removeClass('defaultsign').val('NULL').closest('.form-group').addClass('hidden');
		}
		if($('.srvp option:selected').attr('value')!='NULL'){
			$('.srvp').addClass('defaultsign').closest('.form-group').removeClass('hidden');
		}
		if($('.president option:selected').attr('value')!='NULL'){
			$('.president').addClass('defaultsign').closest('.form-group').removeClass('hidden');
		}

		//sliceTable(rowperpage,'#itemtable','#itempagination');
		//sliceTable(rowperpage,'#suppliertable','#supplierpagination');

		//hide void btn for pr revision
		forrevision = <?php echo json_encode($forrevision); ?>;
		if(parseInt(forrevision)>0){
			$('#prcreate-voidbtn').addClass('hidden');
			$('#pritems .rowqty').css({color:'blue',textDecoration:'underline'});
			// $('#pritems .rowprice').removeClass('rowclick').css({color:'blue',textDecoration:'underline'}).addClass('pointer editprrowprice').attr('title','Edit Price');
		}

		var currency = $('.currency').val();
		///$('#itemlistmodal tbody').load('Other/itemlist.php?oichf%jsl24=1&currency='+currency);
		//$('#itempagination li').addClass('hidden');
		//$('#itempagination li').slice(0,5).removeClass('hidden');


		var withdiscrepancy = <?php echo json_encode($withdiscrepancy); ?>,
		forrevision = <?php echo json_encode($forrevision); ?>,
		rejectedpr = <?php echo json_encode($status); ?>,
		pr = <?php echo json_encode($pr); ?>;


		if(withdiscrepancy==1){
			$('#predit-discrepancytab').removeClass('hidden');
			$('#predit-discrepancy').removeClass('hidden').load('Other/purchase-requisition-discrepancy-details.php?Kbdkldriiopirn389yd9830='+pr);
			
		}
		
		if(forrevision>0){
			$('#predit-revisionremarkstab').removeClass('hidden');
			$('#predit-revisionremarks').removeClass('hidden').load('Other/purchase-requisition-revision-remarks.php?Kbdkldriiopirn389yd9830='+pr);
			$('#addpritemmodal .unitprice').removeClass('disabled').removeAttr('readonly');
		}

		if(rejectedpr=='REJECTED'){
			$('#predit-rejectionremarkstab').removeClass('hidden');
			$('#predit-rejectionremarks').removeClass('hidden');
			
		}

		var datetoday = new Date();
		$('.dateneeded').datepicker({
			minDate: datetoday
		});
		$('.modal-dialog').draggable();
		$('.chosen-select').select2(); 

			var currencystr = '';
			if(currency!='NULL'){
				currencystr = "?currency="+currency;
			}

			var itemlistscript = "Other/ajax/itemlist.php"+currencystr;
			var itemtbl = $('#editpr-itemlisttable').DataTable({
						pagingType:"full_numbers",
						aaSorting: [[ 1, "asc" ]],
			        	columnDefs: [
						      				 {
										        targets: "column-nosort", 
										        orderable: false,
										     }
									 ],

			            "columns": [
				            			
								        
				                        { data: 'item_code' } ,
				                        { data: 'item_name' },
				                        { data: 'long_description' },
				                        { data: 'supplier_name' },
				                        { data: 'unit_price'},
				                        { data: 'currency_code'},
				                        { data: 'created_date' },
				                        { data: 'created_by' },
				                        { data: 'updated_date' },
				                        { data: 'updated_by' }
				                   ],
						"processing": true,
						"serverSide": true,
						"ajax": {
						                url: itemlistscript,
						                type: 'POST'
						        },
						/*"sScrollY": "350px",
						"sScrollX": "auto"*/
			});

			var suppliertbl = $('#editpr-supplierlistmodal #suppliertable').DataTable({
						pagingType:"full_numbers",
						aaSorting: [[ 0, "asc" ]],
			        	columnDefs: [
						      				 {
										        targets: "column-nosort", 
										        orderable: false,
										     }
									 ],

			            "columns": [
				            			
								        
				                        { data: 'supplier_name',
				                        	className:'suppliername' } 
				                   ],
						"processing": true,
						"serverSide": true,
						"ajax": {
						                url: "Other/ajax/supplierlist.php",
						                type: 'POST'
						        },
						/*"sScrollY": "350px",
						"sScrollX": "auto"*/
			});
			var classcodetbl = $('#editpr-classcodemodal #classcodetable').DataTable({
						pagingType:"full_numbers",
						aaSorting: [[ 0, "asc" ]],
			        	columnDefs: [
										 {
											targets: "column-nosort", 
											orderable: false,
										 }
									 ],

			            "columns": [
										{ data: 'acct_code' } ,
				                        { data: 'acct_name' },
				                   ],
						"processing": true,
						"serverSide": true,
						"ajax": {
						                url: "Other/ajax/classcodelist.php",
						                type: 'POST'
						        },
						/*"sScrollY": "350px",
						"sScrollX": "auto"*/
			});
</script>

