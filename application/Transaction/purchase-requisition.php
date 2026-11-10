<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
    include('../../config/functions.php');
?>
<div class='header-page' >
	<div class='header-page-inner'>
		Purchase Requisition
		<div class="button-group">
			<button class="btn btn-sm btn-blue2 mybtn border-right hidden" id='addpr'>
				<i class='fa fa-plus fa-lg fa-space'></i>New Purchase Requisition
			</button>
		</div>
	</div>
</div>
<div class="container-fluid">
    <div class="pageContent">
    	<div class="panel panel-primary mypanel">
    			<div class="panel-body">

	    			<div class="col-sm-12">
										<table class='table table-striped table-hover table-bordered no-side-borders table-condensed mytable' id='prtable' style='width:100%'>
											<thead>
												<tr>
													<th class='text-center column-nosort'>ACTIONS</th>
													<th>STATUS</th>
													<th class='text-nowrap'>PR NUMBER</th>
													<th class='text-nowrap'>REVISION NO.</th>
													<th class='text-nowrap'>REQUISITION DATE</th>
													<th>DESCRIPTION</th>
													<th>REMARKS</th>
													<th>LAST MADE CHANGES</th>
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

<div class="modal fade" id="prblowup">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form class="form-horizontal">
				<div class="modal-header">
					<div class='page-title'>
						Purchase Requisition
						<button class="close" data-dismiss="modal">&times;</button>
					</div>
				</div>
				<div class="modal-body">
								<div class='tabpane-white margin-top-20 margin-bottom-10' id='generalinfosection'>
										<ul class="nav nav-tabs">
										  <li role="presentation" class="active" data-pane='#pr-geninfo' id='pr-geninfotab'><a>General Information</a></li>
										  <li role="presentation" data-pane='#pr-approverspane'><a>Approvers</a></li>
										  <li role="presentation" class='hidden' data-pane='#pr-revisionremarks' id='pr-revisionremarkstab'><a>Revision Remarks</a></li>
										  <li role="presentation" class='hidden' data-pane='#pr-discrepancy' id='pr-discrepancytab'><a>Discrepancy Details</a></li>
										  <li role="presentation" class='hidden' data-pane='#pr-rejectionremarks' id='pr-rejectionremarkstab'><a>Rejection Remarks</a></li>
										</ul>
										<div class='tab-panes'>
											<div class='pane active' id='pr-geninfo'>
												<div class="pane-content">
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label col-sm-4">Requisition No.</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='prnumber'></span>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4">Status</label>
															<div class="col-sm-8">
																<span class='form-control-static' id='reqstat'></span>
															</div>
														</div>

														<div class="form-group">
															<label class="control-label col-sm-4">Requisitioner</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='requisitioner'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Department</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='dept'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Currency</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='reqcurrency'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Ship To</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='facshipto'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Justification</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='justification'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Revision Number</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='revprnumber'></span>
															</div>
														</div>
														<br>
													</div>
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label col-sm-4">Type</label>
															<div class="col-sm-8">
																 <span id='reqtype'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Requisition Date</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='reqdate'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Date Needed</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='reqdateneeded'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Last Modified</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='lastmodified'></span>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4">PR Remarks</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='prnotes'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Date Cancelled</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='datecanceled'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Reason for Cancellation</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='cancellationreason'></span>
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
															<label class="control-label col-sm-5">Checked By / Section Head</label>
															<div class="col-sm-7">
																 <span class='form-control-static sectionhead1'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-5">Section Head (2)</label>
															<div class="col-sm-7">
																 <span class='form-control-static sectionhead2'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-5">Conformance Head</label>
															<div class="col-sm-7">
																 <span class='form-control-static confhead'></span>
															</div>
														</div>
														<!-- <div class="form-group">
															<label class="control-label col-sm-5">General Manager</label>
															<div class="col-sm-7">
																 <span class='form-control-static genman'></span>
															</div>
														</div> -->
														
														
														<br>
													</div>
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label col-sm-5">GM / AVP / VP</label>
															<div class="col-sm-7">
																 <span class='form-control-static asstvp'></span>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-5">Operations Division Head</label>
															<div class="col-sm-7">
																 <span class='form-control-static srvp'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-5">President</label>
															<div class="col-sm-7">
																 <span class='form-control-static president'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-5">Last approved/rejected by</label>
															<div class="col-sm-7">
																 <span class='form-control-static lastapprovedby' id='prnumber'></span>
															</div>
														</div>
													</div>


													

												</div>
											</div>
											<div class="pane" id='pr-revisionremarks'>
											</div>
											<div class="pane" id='pr-discrepancy'>
											</div>
											<div class="pane" id='pr-rejectionremarks'>
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
																		<th>CLASSF'N CODE</th>
																		<th class='text-right'>QTY</th>
																		<th class='text-center'>UOM</th>
																		<th class='text-center'>CURRENCY</th>
																		<th class='text-right'>UNIT PRICE</th>
																		<th class='text-right'>AMOUNT</th>
																		<th class='text-center'>PO NUMBER</th>
																		<th class='text-center'>STATUS</th>
																		<th class='text-center'>PO CANCELLATION REASON</th>
																	</tr>
																</thead>
																<tbody id='cartitems'>		
																</tbody>
															</table>
														</div>
														<div class="row">
															<div class="col-md-offset-5 col-md-7" style='margin-top: 10px;'>
																<form class='form-horizontal'>
																	<div class="form-group" >
																		<label class='control-label col-xs-5'>Total Amount</label> 
																		<div class="col-xs-7">
																			<div class="input-group">
																				<div class="input-group-addon bold" id='prblowupcurrencysymbol'></div>
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
											<div class='pane' id='allocationsummarypane'>
												<div class="pane-content">
													<div class='table-wrapper' style='height:200px'>
			                                            <table class='table table-striped table-condensed mytable'>
			                                                <thead>
			                                                    <tr>
			                                                        <th class='text-center'>LINE</th>
			                                                        <th>DEPARTMENT</th>
			                                                        <th>BUSINESS UNIT</th>
			                                                        <th>PRODUCT LINE</th>
			                                                        <th class='text-center'>PERCENTAGE %</th>
			                                                        <th>REMARKS</th>
			                                                    </tr>

			                                                </thead>
			                                                <tbody id='allocationsummary'>
			                                                    
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
			                                                
			                                                </tbody>
			                                            </table>
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

<div class="modal fade" id="followupmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Follow-up Approval
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<input type='hidden' id='followup-prnumber'>
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="errordiv"></div>

                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">Message</label>
                    <div class="col-sm-10">
                        <textarea class='form-input form-control followupmessage' rows='4'></textarea>
                    </div>
                </div>
                                    
            </div>
            <div class="modal-footer">
                            <div class="text-center">
                                <button class='btn btn-blue2 mybtn' id='followup-confirmbtn'>Send</button>
                                <button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
                            </div>
            </div>
        </div>
    </div>  
</div>


<div class="modal fade" id="prcancellationmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    PR Cancellation
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class="form-horizontal">
            		<input type='hidden' id='prcancellationmodal-prnumber'>
	                <div class="form-group">
	                    <div class="col-sm-12">
	                        <div class="errordiv"></div>

	                    </div>
	                </div>
	                <div class="form-group">
	                    <label class="control-label col-md-3 col-sm-3">PR Number</label>
	                    <div class="col-md-5 col-sm-8">
	                        <input type='text' class='form-input form-control prcancellationmodal-prnumber' disabled>
	                    </div>
	                </div>

	                <div class="form-group">
	                    <label class="control-label col-sm-3">Reason</label>
	                    <div class="col-sm-8">
	                        <textarea class='form-input form-control reasonofcancellation' rows='4'></textarea>
	                    </div>
	                </div>
	            </div>
                                    
            </div>
            <div class="modal-footer">
                            <div class="text-center">
                                <button class='btn btn-blue2 mybtn' id='prcancellationmodal-confirmbtn'>Confirm</button>
                                <button class='btn btn-blue2 mybtn modal-cancelbtn'>Close</button>
                            </div>
            </div>
        </div>
    </div>  
</div>






<script type="text/javascript">
	sliceTable(rowperpage,'#prtable','#prpagination');
	userAccess();
	$('.modal-dialog').draggable();
	$('#prtable').DataTable({
				pagingType:"full_numbers",
				aaSorting: [[ 7, "desc" ]],
	        	columnDefs: [
				      				 {
								        targets: "column-nosort", 
								        orderable: false,
								     }
							 ],

	            "columns": [
		            			
						        {
						        	data:'ACTION',
						        	"className":'text-center',
						            "mRender":function(data){
                            				return data;
                            			}
						        },
						        
		                        { data: 'requisition_status',
		                          "className":'clickable text-center' 
		                        },
		                        { data: 'requisition_number',
		                          "className":'clickable'
		                        },
		                        { data: 'next_revision_series',
		                          "className":'clickable text-center' 
		                        },
		                        { data: 'requisition_date',
		                          "className":'clickable text-center' 
		                        },
		                        { data: 'pr_description', 
		                          "className":'clickable'
		                        },
		                        { data: 'pr_notes',
		                          "className":'clickable' 
		                        },
		                        { data: 'last_made_changes',
		                          "className":'clickable' 
		                        }
		                   ],
				"processing": true,
				"serverSide": true,
				"ajax": {
				                url: "Other/ajax/purchase-requisition.php",
				                type: 'POST'
				        },
				"sScrollY": "350px",
				"sScrollX": "auto"
		});

	
</script>