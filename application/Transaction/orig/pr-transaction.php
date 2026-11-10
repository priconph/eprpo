<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
    include('../../config/functions.php');
    $deptid = getUserDeptId($userid);
    $transNumber = getNextTransactionNumber(1);

?>
<div class='header-page' >
	<div class='header-page-inner'>
		New Purchase Requisition
	</div>
</div>
<div class="container-fluid">
	<div class="pageContent">
	
		<div class="panel panel-primary mypanel">
    			<div class="panel-body">
    				<div class='tabpane-white margin-top-20 margin-bottom-10' id='generalinfosection'>
						<ul class="nav nav-tabs">
							  <li role="presentation" class="active" data-pane='#newpr-geninfo'><a>General Information</a></li>
						</ul>
					</div>
					<div class='tab-panes'>
						<div class='pane active' id='newpr-geninfo'>
							<div class="pane-content" style='padding-bottom:10px'>
								<form class="form-horizontal">
									<div class="col-md-12">
										<div class="errordiv"></div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-sm-4">Requisition No.</label>
											<div class="col-sm-8">
												<input type='text' class='form-control reqnum' readonly value="<?php echo $transNumber; ?>">
											</div>
										</div>
										
										<div class="form-group">
											<label class="control-label col-sm-4">Requisitioner</label>
											<div class="col-sm-8">
												<input type='text' name='prnumber' class='form-input form-control' value="<?php echo getUserName($userid);?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Department</label>
											<div class="col-sm-8">
												<input type='text' name='sec-dept-div' class='form-input form-control' value="<?php echo getDeptName($userid); ?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Type</label>
											<div class="col-sm-8">
												<select name='type' class='form-input form-control reqtype'>
													<option value='regular'>Regular</option>
													<option value='urgent'>Urgent</option>
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
															echo "<option value='$id'>$code</option>";
														}
													?>
												</select>
											</div>
										</div>
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
												<input type='text' class='form-input form-control dateinput dateneeded' id='dateneeded'>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Justification</label>
											<div class="col-sm-8">
												<textarea rows='2' name='justification' class='form-input form-control justification'></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Remarks</label>
											<div class="col-sm-8">
												<textarea rows='2' class='form-input form-control prnotes'></textarea>
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
																<th><input type='checkbox' class='selectall'></th>
														
																<th class='text-center'>LINE</th>
																<th>ITEM CODE</th>
																<th>ITEM NAME</th>
																<th>DESCRIPTION</th>
																<th class='text-center'>CLASSF'N CODE</th>
																<th class='text-right'>QTY</th>
																<th class='text-center'>UOM</th>
																<th>SUPPLIER</th>
																<th class='text-right'>UNIT PRICE</th>
																<th class='text-right'>AMOUNT</th>
															</tr>

														</thead>
														<tbody id='pritems'>

														</tbody>
													</table>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6" style='margin-top:10px;'>
								    				<button class='btn btn-sm btn-blue2 mybtn' id='prcreate-additembtn' >Add an Item</button><!--data-toggle='modal' href='#addpritemmodal'-->
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
																			<input type='text' class='form-input form-control text-right bold disabled' id='totalpramount' readonly>
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
														<input type='hidden' value='' name='prnumber' id='prnum'>
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
																	<tr>
																		<td><input type='checkbox' class='itemcheckbox'></td>
																		<td>
																			<input type='file' class='fileattachment' name='file[]' id='abc'>
																		</td>
																		<td class='text-center'>
																			<select class='form-input form-control filetype' name='filetype[]'>
																				<option value=''>-</option>
																				<option value='Part Description/Specification'>Part Description/Specification</option>
																				<option value='Sample Unit'>Sample Unit</option>
																				<option value='Drawing Layout'>Drawing Layout</option>
																				<option value='Computation'>Computation</option>
																				<option value='NULL'>Other Description</option>
							
																			</select>
																		</td>
																		<td class='text-center'>
																			<input type='text' class='form-input form-control disabled otherfiletype' name='otherfiletype[]' readonly>
																		</td>
																	</tr>
																	<tr>
																		<td><input type='checkbox' class='itemcheckbox'></td>
																		<td>
																			<input type='file' class='fileattachment' name='file[]'>
																		</td>
																		<td class='text-center'>
																			<select class='form-input form-control filetype' name='filetype[]'>
																				<option value=''>-</option>
																				<option value='Part Description/Specification'>Part Description/Specification</option>
																				<option value='Sample Unit'>Sample Unit</option>
																				<option value='Drawing Layout'>Drawing Layout</option>
																				<option value='Computation'>Computation</option>
																				<option value='NULL'>Other Description</option>

																			</select>
																		</td>
																		<td class='text-center'>
																			<input type='text' class='form-input form-control disabled otherfiletype' readonly name='otherfiletype[]'>
																		</td>
																	</tr>
																
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
											<label class="control-label col-sm-5">Checked By / Section Head</label>
											<div class="col-sm-7">
												<select type='text' class='form-input defaultsign form-control sectionhead1'>
													<option value='NULL'>-</option>
													<?php 
														$query = "select * from approver where section_department_id='$deptid' and approval_type_id='1' and active_flag='1'";
														$rs = query($query);
														while($obj=fetch($rs)){
															$id = $obj->user_id;
															$approver = getUserName($id);
															echo "<option value='$id'>$approver</option>";
														}
													?>
												</select>
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-sm-5">Section Head (2) </label>
											<div class="col-sm-7">
												<select type='text' class='form-input defaultsign form-control sectionhead2'>
													<option value='NULL'>-</option>
													<option value='1'>Not Applicable</option>
													<?php 
														$query = "select * from approver where section_department_id='$deptid' and approval_type_id='1' and active_flag='1'";
														$rs = query($query);
														while($obj=fetch($rs)){
															$id = $obj->user_id;
															$approver = getUserName($id);
															echo "<option value='$id'>$approver</option>";
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
														//$query = "select * from approver where section_department_id='$deptid' and approval_type_id='7' and active_flag='1'";
														$query = "select * from approver where approval_type_id='7' and active_flag='1'";
														$rs = query($query);
														while($obj=fetch($rs)){
															$id = $obj->user_id;
															$approver = getUserName($id);
															echo "<option value='$id'>$approver</option>";
														}
													?>
												</select>
											</div>
										</div>
										
										<!-- <div class="form-group">
											<label class="control-label col-sm-5">General Manager</label>
											<div class="col-sm-7">
												<select type='text' class='form-input defaultsign form-control genman'>
													<option value='NULL'>-</option>
													<option value='1'>Not Applicable</option>
													<?php 
														// $query = "select * from approver where approval_type_id='3' and active_flag='1'";
														// $rs = query($query);
														// while($obj=fetch($rs)){
														// 	$id = $obj->user_id;
														// 	$approver = getUserName($id);
														// 	echo "<option value='$id'>$approver</option>";
														// }
													?>
												</select>
											</div>
										</div>	 -->
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-sm-5">GM / AVP / VP</label>
											<div class="col-sm-7">
												<select type='text' class='form-input defaultsign form-control asstvp'>
													<option value='NULL'>-</option>
													<option value='1'>Not Applicable</option>
													<?php 
														$query = "select * from approver where approval_type_id='4' and active_flag='1'";
														$rs = query($query);
														while($obj=fetch($rs)){
															$id = $obj->user_id;
															$approver = getUserName($id);
															echo "<option value='$id'>$approver</option>";
														}
													?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-5">Operations Division Head</label>
											<div class="col-sm-7">
												<select type='text' class='form-input defaultsign form-control srvp'>
													<option value='NULL'>-</option>
													<option value='1'>Not Applicable</option>
													<?php 
														$query = "select * from approver where approval_type_id='5' and active_flag='1'";
														$rs = query($query);
														while($obj=fetch($rs)){
															$id = $obj->user_id;
															$approver = getUserName($id);
															echo "<option value='$id'>$approver</option>";
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
															echo "<option value='$id'>$approver</option>";
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
								<button class='btn btn-blue2 mybtn prsavesubmit' id='prcreate-savebtn'>Save</button>
								<button class='btn btn-blue2 mybtn prsavesubmit' id='prcreate-submitbtn'>Submit</button>
								<button class='btn btn-blue2 mybtn' id='prcreate-cancelbtn'>Back</button>
							</div>
			</div>
    	</div>

	</div>
</div>
<div class="modal fade" id="allocationmodal">
	<div class="modal-dialog" style='width:600px;'>
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
						<!--<div class="form-group">
							<label class='control-label col-md-4'>Item</label>
							<div class="col-md-8">
								<select id='allocationitemselection' class='form-input form-control item'>
								</select>
							</div>
						</div>-->
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
										<div class="input-group-addon button-addon itemlookupbtn" from='edit' data-toggle='modal' href='#addpr-itemlistmodal'>
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
										<div class="input-group-addon button-addon classcodelookupbtn" data-toggle='modal' href='#addpr-classcodemodal'>
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
										<div class="input-group-addon button-addon supplierlookupbtn" data-toggle='modal' href='#addpr-supplierlistmodal'>
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
										<input type='number' step='.01' class='form-input form-control unitprice' readonly>
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
										<table class='table table-striped table-condensed mytable table-bordered table-hover' cellspacing='0' id='itemtable'>
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
<div class="modal fade lookupmodal" id="addpr-itemlistmodal">
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
					<table class='table table-striped table-condensed mytable table-hover table-bordered' cellspacing='0' id='newpr-itemlisttable'>
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


<div class="modal fade lookupmodal" id="addpr-supplierlistmodal">
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
											<tbody id='addpr-supplierlistrows'>
												
											</tbody>
										</table>
							</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade lookupmodal" id="addpr-classcodemodal">
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

<script src="../resources/selectoption/chosen.jquery.js"></script>
<script src="../resources/selectoption/docsupport/prism.js"></script>
<script type="text/javascript">
	$(document).ready(function(){


	
			var datetoday = new Date();
			$('#dateneeded').datepicker({
				minDate: datetoday
			});
			$('.modal-dialog').draggable();
			$('.chosen-select').select2(); 

			var itemlistscript = "Other/ajax/itemlist.php";
			var itemtbl = $('#newpr-itemlisttable').DataTable({
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

			var suppliertbl = $('#addpr-supplierlistmodal #suppliertable').DataTable({
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

			var classcodetbl = $('#addpr-classcodemodal #classcodetable').DataTable({
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
	});

	$('.department').on('change', function() {
	  // alert( $(this).find("option:selected").text() );
	  if ($(this).find("option:selected").text() == 'Charge to YEC'){
	  	$('.allocationremarks').val('Charge to YEC')
	  }
	});


</script>