<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
    include('../../config/functions.php');
?>
<div class='header-page' >
	<div class='header-page-inner'>
		Purchase Order .
        <?php 
            $rs = query("select COUNT(order_number) as numrows from purchase_order_header WHERE `printed_flag`=0 AND (printed_timestamp='0000-00-00 00:00:00' OR printed_timestamp='') AND `assigned_to`='$userid'");
            if($obj=fetch($rs)){
                $numrows = $obj->numrows;
                $for_print_badge = '<span class="badge badge-light"> '.$numrows.'</span>';
            }
        ?>
		<div class="button-group">
			<button class="btn btn-sm mybtn btn-blue2" id='load_for_printing' value="<?php echo $userid; ?>">
				<i class='fa fa-warning fa-lg fa-space'></i>For Printing <?php echo $for_print_badge; ?>
			</button>
			<button class="btn btn-sm btn-blue2 mybtn border-right" id='findpobtn' data-toggle='modal' href='#findpomodal'>
				<i class='fa fa-search fa-lg fa-space'></i>Find Purchase Order
			</button>
			<button class="btn btn-sm mybtn btn-blue2" id='printtemplate'>
				<i class='fa fa-print fa-lg fa-space'></i>Print Template
			</button>
			<button class="btn btn-sm btn-blue2 mybtn border-right" id='findponew'>
				<i class='fa fa-asterisk fa-lg fa-space'></i>SHOW NEW
			</button>
		</div>
	</div>
</div>
<div class="container-fluid">
    <div class="pageContent">
    	<div class="panel panel-primary mypanel">
    			<div class="panel-body">
	    			<div class="col-md-12">
										<table class='table table-striped table-bordered no-side-borders table-condensed mytable' id='potable'>
											<thead>
												<tr class='text-nowrap'>
													<th class='column-nosort'>ACTIONS</th>
													<th>STATUS</th>
													<th>ORDER DATE</th>
													<th>PO NUMBER</th>
													<th>REVISION NO.</th>
													<th>SUPPLIER</th>
													<th>REQUISITION DATE</th>
													<th>DATE NEEDED</th>
													<th>PRINTED</th>
													<th>LAST MODIFIED</th>
													<th class='column-nosort'>REASON FOR REJECTION</th>
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


<div class="modal fade" id="findpomodal">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Find Purchase Order
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-md-12">
					<div class="form-horizontal">
						<div class="form-group">
	            			<label class='control-label col-sm-3'>Status</label>
	            			<div class="col-sm-9">
	            				<select id='findpomodal-status' class='form-control chosen-select' style='width:100%'>
	            					<option value='ALL'>ALL</option>
	            					<option value='NEW'>NEW</option>
	            					<option value='DRAFT'>DRAFT</option>
	            					<option value='SERVED'>SERVED</option>
	            					<option value='UNSERVED'>UNSERVED</option>
	            					<option value='FOR REVISION'>FOR REVISION</option>
	            					<option value='REVISED'>REVISED</option>
	            					<option value='PARTIAL DELIVERY'>PARTIAL DELIVERY</option>
	            					<option value='VARIANCE'>WITH VARIANCE</option>
	            					<option value='CANCELLED'>CANCELLED</option>
	            					<option value='FOR CANCELLATION'>FOR CANCELLATION</option>
	            				</select>
	            			</div>
	            		</div>
	            	</div>
				</div>

			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn btn-blue2 mybtn item-modal-savebtn' id='findpomodal-loadbtn'>Load</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>


<div class="modal fade" id="poblowup">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal">
				<div class="modal-header">
					<div class='page-title'>
						Purchase Order
						<button class="close" data-dismiss="modal">&times;</button>
					</div>
				</div>
				<div class="modal-body">
								<div class='tabpane-white margin-top-20 margin-bottom-10' id='generalinfosection'>
										<ul class="nav nav-tabs">
										  <li role="presentation" class="active" data-pane='#po-geninfo' id='geninfotab'><a>General Information</a></li>
										  <li role="presentation" data-pane='#po-approverspane'><a>Approvers</a></li>
										  <li role="presentation" class="hidden" data-pane='#po-revisionremarks' id='revisionremarkstab'><a>Revision Remarks</a></li>
										  <li role="presentation" class="hidden" data-pane='#po-discrepancy' id='discrepancytab'><a>Discrepancy Details</a></li>
										  <li role="presentation" class="hidden" data-pane='#po-rejectionremarks' id='rejectionremarkstab'><a>Rejection Details</a></li>
										</ul>
										<div class='tab-panes'>
											<div class='pane active' id='po-geninfo'>
												<div class="pane-content">
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label col-sm-4">Order No.</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='ponumber'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Revision Number</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='revisionnumber'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Status</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='orderstatus'></span>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4">Reference PR No.</label>
															<div class="col-sm-8">
																<span class='form-control-static' id='prnumber'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Supplier</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='vendor'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Supplier Address</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='supplierAddress'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Attn</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='attn'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Currency</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='pocurrency'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Remarks</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='remarks'></span>
															</div>
														</div>
														
													</div>
													<div class="col-sm-6">
														
														<div class="form-group">
															<label class="control-label col-sm-4">Date Needed</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='dateneeded'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Order Date</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='orderdate'></span>
															</div>
														</div>
														<!--<div class="form-group">
															<label class="control-label col-sm-4">Expected Delivery</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='expdelivery'></span>
															</div>
														</div>-->
														<div class="form-group">
															<label class="control-label col-sm-4">Terms</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='paymentterms'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Ship Via</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='shipviafield'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Mode of Shipment</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='shipmentmode'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Assigned to</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='assignedto'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Date Assigned</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='dateassigned'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Posted by</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='postedby'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Date Posted</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='dateposted'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Cancellation by</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='cancelledby'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Date Cancelled</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='cancelleddate'></span>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4">Reason for Cancellation</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='cancelledreason'></span>
															</div>
														</div>
														<!--<div class="form-group">
															<label class="control-label col-sm-4">Last Approved By</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='lastapprovedby'></span>
															</div>
														</div>-->
														
														


													</div>

												</div>
											</div>
											<div class="pane" id='po-approverspane'>
					                            <div class="pane-content">
					                                <form class='form-horizontal'>
					                                    <div class="col-sm-6">
					                                        <div class="form-group">
					                                            <label class="control-label col-sm-5">Asst. Manager</label>
					                                            <div class="col-sm-7">
					                                                <span class='form-control-static asstmngr'></span>
					                                            </div>
					                                        </div>
					                                       <div class="form-group">
					                                            <label class="control-label col-sm-5">General Manager</label>
					                                            <div class="col-sm-7">
					                                                <span class='form-control-static genmngr'></span>
					                                            </div>
					                                        </div>       
					                                        <br>
					                                    </div>
					                                    <div class="col-sm-6">
					                                        <div class="form-group">
					                                            <label class="control-label col-sm-5">President</label>
					                                            <div class="col-sm-7">
					                                               <span class='form-control-static pres'></span>
					                                            </div>
					                                        </div>
					                                        <div class="form-group">
					                                            <label class="control-label col-sm-5">Last approved/rejected by</label>
					                                            <div class="col-sm-7">
					                                               <span class='form-control-static lastapprovedby'>
					                                                </span>
					                                            </div>
					                                        </div>
					                                    </div>
					                                </form>
					                            </div>
					                        </div>
											<div class='pane hidden' id='po-discrepancy'>
											</div>
											<div class='pane hidden' id='po-revisionremarks'>
											</div>
											<div class='pane hidden' id='po-rejectionremarks'>
												<form class='form-horizontal'>
													
														<div class="form-group">
															<label class="control-label col-md-2">Approver</label>
															<div class="col-md-10">
																<input type='text' id='rejectedby' class='form-control' disabled>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-2">Reason for Rejection</label>
															<div class="col-md-10">
																<textarea class='form-control' rows='4' id='reasonforrejection' readonly></textarea>
															</div>
														</div>
												</form>
											</div>

										</div>
								</div>
								<br>
								<div class='tabpane-white margin-top-20 margin-bottom-10' id='polineitems'>
										<ul class="nav nav-tabs">
										  <li role="presentation" class="active" data-pane='#lineitems'><a>Line Items</a></li>
										  <li role="presentation" data-pane='#receivinghistory'><a>Receiving Historyss</a></li>
										</ul>
										<div class='tab-panes'>
											<div class='pane active' id='lineitems'>
												<div class="pane-content">
													<div class='tablewithbuttons'>
														<div class='table-wrapper' style='height:200px'>
															<table class='table table-striped table-condensed table-bordered no-side-borders mytable' cellspacing='0'>
																<thead>
																	<tr>
																		<th class='text-center'>LINE NO.</th>
																		<th>ITEM CODE</th>
																		<th>NAME</th>
																		<th>DESCRIPTION</th>
																		<th class='text-center'>CLASSF'N CODE</th>
																		<th class='text-center'>UOM</th>
																		<th class='text-right'>QTY</th>
																		
																		<th class='text-right'>RECEIVED</th>
																		<th class='text-right'>BALANCE</th>
																		<th class='text-right'>UNIT PRICE</th>
																		<th class='text-right'>AMOUNT</th>
																		

																	</tr>
																</thead>
																<tbody id='poitems'>		
																</tbody>
															</table>
														</div>
														<div class="row">
															<div class="col-md-offset-5 col-md-7" style='margin-top: 10px; margin-bottom:10px;'>
																<form class='form-horizontal'>
																	<div class="form-group" >
																		<label class='control-label col-xs-5'>Total Amount</label> 
																		<div class="col-xs-7">
																			<div class="input-group">
																				<div class="input-group-addon" id='blowupcurrencysymbol' style='font-weight:bold'></div>
																				<input type='text' class='form-input form-control text-right disabled' id='grandtotal' readonly>
																			</div>
																			
																		</div>
																	</div>			
																</form>
															</div>
														</div>
													</div>
													
												</div>
											</div>
											<div class='pane' id='receivinghistory'>
												<div class="pane-content">
													<div class='tablewithbuttons'>
														<div class='table-wrapper' style='height:200px'>
															<table class='table table-striped table-bordered no-side-borders table-condensed mytable' cellspacing='0'>
																<thead>
																	<tr>
																		<th class='text-center'>LINE NO.</th>
																		<th>OTHER REFERENCE</th><!--06292019-->
																		<th>ITEM CODE</th>
																		<th>NAME</th>
																		<th>DESCRIPTION</th>
																		<th class='text-right'>PRICE</th>
																		<th class='text-center'>UOM</th>
																		<th class='text-right'>PO QTY</th>
																		<th class='text-right'>RCV QTY</th>
																		<th class='text-right'>BALANCE</th><!--06292019-->
																		<th>RECEIVED BY</th>
																		<th class='text-center'>DATE RECEIVED</th>
																	</tr>
																</thead>
																<tbody id='poitemsreceived'></tbody>
															</table>
														</div>
													</div>
													
												</div>
											</div>
										</div>
								</div>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="revisionmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                   Revision
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class="col-md-12">
            		<div class="form-group">
            			<div class="errordiv"></div>
            		</div>

            		

            		<div class="form-group">
            			<label class='control-label col-sm-3'>Remarks</label>
            			<div class="col-sm-9">
            				<textarea class='form-input form-control reasonforrevision' rows='10'></textarea>
            			</div>
            		</div>
            		
            		
            	</div>                                         
            </div>
            <div class="modal-footer">
                            <div class="panel-content text-center">
                                <button class='btn btn-blue2 mybtn' id='poview-revisebtn'>Revise</button>
                                <button class='btn btn-blue2 mybtn modal-cancelbtn' id='poview-cancelbtn'>Cancel</button>
                            </div>
            </div>
        </div>
    </div>  
</div>

<div class="modal fade" id="printpomodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                   Print Purchase Order
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class="col-md-12">
            		<div class="form-horizontal">
	            		<div class="form-group">
	            			<label class='control-label col-sm-offset-2 col-sm-3'>Revision Suffix</label>
	            			<div class="col-sm-5">
	            				<input id='revsuffix' class='form-control' readonly>
	            			</div>
	            		</div>
	            		<div class="form-group">
	            			<label class='control-label col-sm-offset-2 col-sm-3'>Template</label>
	            			<div class="col-sm-5">
	            				<select id='printtype' class='form-control'>
	            					<option value='2'>Template 1</option>
	            					<!--<option value='1'>Template 2</option>-->
	            				</select>
	            			</div>
	            		</div>
	            	</div>
            	</div>                                         
            </div>
            <div class="modal-footer">
                            <div class="panel-content text-center">
                                <button class='btn btn-blue2 mybtn' id='printbtn-printingmodal'>Print</button>
                                <button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
                            </div>
            </div>
        </div>
    </div>  
</div>

<div class="modal fade" id="pocancellationmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    PO Cancellation
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	
	            <div class="col-md-12">
	            	<div class="table-wrapper">
		            	<div class="table-responsive">
		            		<table class='table table-striped table-bordered no-side-broders table-condensed mytable' id='pocancellationmodal-table'>
		            			<thead>
		            				<tr>
		            					<th style='width:50px'></th>
		            					<th>PO NUMBER</th>
		            					<th>STATUS</th>
		            					<th>REFERENCE PR NUMBER</th>
		            					<th>REASON</th>
		            				</tr>
		            			</thead>
		            			<tbody></tbody>
		            		</table>
		            	</div>
		            </div>
	            </div>
	            <div class="col-md-12">
	            	<br>
	            	<i><b>Note:</b> Please provide a reason for selected purchase order(s).</i>
	            </div>
                                    
            </div>
            <div class="modal-footer">
                            <div class="text-center">
                                <button class='btn btn-blue2 mybtn' id='pocancellationmodal-confirmbtn'>Cancel Selected PO(s)</button>
                                <button class='btn btn-blue2 mybtn modal-cancelbtn'>Close</button>
                            </div>
            </div>
        </div>
    </div>  
</div>

<div class="modal fade" id="multiplepocancellationmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Cancellation
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class="form-horizontal">
	                <div class="form-group">
	                    <div class="col-sm-12">
	                        <div class="errordiv"></div>

	                    </div>
	                </div>
	                <div class="col-md-6">
		                <div class="form-group">
		                    <label class="control-label col-md-4">Enter PO Number</label>
		                    <div class="col-md-8">
		                        <input type='text' class='form-input form-control multiplepocancellationmodal-ponumber'>
		                    </div>
		                </div>
		                <div class="form-group">
		                	<div class="col-md-offset-4 col-md-8">
				            	<button class="btn btn-sm mybtn btn-blue2 col-sm-12">
			    					<i class='fa fa-check-square-o fa-lg fa-space'></i>Enter
			    				</button>
			            	</div>
		                </div>
		                	
		            </div>
		            
	            </div>
	            <div class="col-md-12">
	            	<div class="table-wrapper">
		            	<div class="table-responsive">
		            		<table class='table table-striped table-bordered no-side-broders table-condensed mytable'>
		            			<thead>
		            				<tr>
		            					<th style='width:50px'></th>
		            					<th>PO NUMBER</th>
		            					<th>REFERENCE PR NUMBER</th>
		            					<th>REASON</th>
		            				</tr>
		            			</thead>
		            		</table>
		            	</div>
		            </div>
	            </div>
                                    
            </div>
            <div class="modal-footer">
                            <div class="text-center">
                                <button class='btn btn-blue2 mybtn' id='multiplepocancellationmodal-confirmbtn'>Cancel Selected PO(s)</button>
                                <button class='btn btn-blue2 mybtn modal-cancelbtn'>Close</button>
                            </div>
            </div>
        </div>
    </div>  
</div>


<div class="modal fade" id="assignmentmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                   Purchase Order
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class="col-md-4">
            		<div class='modal-body-info'>
            			<span class='title'>Assign PO to User</span><br>
            			Please select from the dropdown list.
            		</div>
            	</div>
            	<div class="col-md-8">
            		<div class="form-group">
            			<div class="errordiv"></div>
            		</div>
            		<input type='hidden' ponumber='' id='assignmentmodal-ponumber'>
            		<div class="form-group">
            			<label class='control-label col-sm-3'>User</label>
            			<div class="col-sm-9">
            				<select class='form-input form-control' id='assignmentmodal-assignedto'>
            					<option value=''>-</option>
            					<?php 
	            					$query = "select * from user where section_department_id=17 and active_flag='1'";
	            					$rs = query($query);
	            					while($obj=fetch($rs)){
	            						$id = $obj->id;
	            						$name = $obj->first_name.' '.$obj->middle_name.' '.$obj->last_name;
	            						echo "<option value='$id'>$name</option>";
	            					}
            					?>
            				</select>
            			</div>
            		</div>
            	</div>                                         
            </div>
            <div class="modal-footer">
                            <div class="panel-content text-center">
                                <button class='btn btn-blue2 mybtn' id='assignmentmodal-confirmbtn'>Confirm</button>
                                <button class='btn btn-blue2 mybtn modal-cancelbtn' id='assignmentmodal-cancelbtn'>Cancel</button>
                            </div>
            </div>
        </div>
    </div>  
</div>

<!--
	START
	Additional as of Feb. 28, 2018
	Due to accidental tagging of unprint PO
 -->
<div class="modal fade" id="printpomodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                   System Confirmation
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class="col-md-12">
            		<div class="form-horizontal">
	            		<div class="form-group">
	            			<label class='control-label col-sm'>Are you sure you want to "Tag as Printed" the PO Number <label id=""></label></label>
	            		</div>
	            	</div>
            	</div>                                         
            </div>
            <div class="modal-footer">
                            <div class="panel-content text-center">
                                <button class='btn btn-blue2 mybtn' id='printbtn-printingmodal'>Yes</button>
                                <button class='btn btn-blue2 mybtn modal-cancelbtn'>No</button>
                            </div>
            </div>
        </div>
    </div>  
</div>
<!--
	END
	Additional as of Feb. 28, 2018
	Due to accidental tagging of unprint PO
 -->
 
<script type="text/javascript">
	//sliceTable(rowperpage,'#potable','#popagination');
	userAccess();
	$('.modal-dialog').draggable();
	$(".chosen-select").select2();

	$('#potable').DataTable({
				pagingType:"full_numbers",
				aaSorting: [[ 9, "desc" ]],
	        	columnDefs: [
				      				 {
								        targets: "column-nosort", 
								        orderable: false,
								     }
							 ],

	            "columns": [
		            			
						        {
						        	data:'ACTION',
						        	"className":'text-center text-nowrap',
						            "mRender":function(data){
                            				return data;
                            			}
						        },
						        
		                        { data: 'order_status',
		                          "className":'clickable text-center text-nowrap' 
		                        },
		                        { data: 'order_date',
		                          "className":'clickable'
		                        },
		                        { data: 'order_number',
		                          "className":'clickable text-center' 
		                        },
		                        { data: 'next_revision_series',
		                          "className":'clickable text-center' 
		                        },
		                        { data: 'supplier_name', 
		                          "className":'clickable'
		                        },
		                        { data: 'requisition_date',
		                          "className":'clickable' 
		                        },
		                        { data: 'expected_date',
		                          "className":'clickable' 
		                        },
		                        { data: 'printed_timestamp',
		                          "className":'clickable' 
		                        },
		                        { data: 'date_flag',
		                          "className":'clickable' 
		                        },
		                        { data: 'reason',
		                          "className":'clickable' 
		                        }
		                   ],
				"processing": true,
				"serverSide": true,
				"ajax": {
				                url: "Other/ajax/purchase-order.php",
				                type: 'POST'
				        },
				"sScrollY": "350px",
				"sScrollX": "auto"
		});
	$('#findponew').click(function(){
		$(document).find('#potable_filter input').val('NEW').trigger('keyup');
	})
</script>

