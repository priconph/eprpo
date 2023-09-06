<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
    include("../../config/functions.php");
?>
<div class='header-page' >
	<div class='header-page-inner'>
		Item
		<div class="button-group">
			<button class="btn btn-sm btn-blue2 mybtn border-right hidden" id='additembtn' data-toggle='modal' href='#additemmodal'>
				<i class='fa fa-plus fa-lg fa-space'></i>Add
			</button>
			<button class="btn btn-sm btn-blue2 mybtn removebtn border-right hidden" id='deleteitembtn'>
				<i class='fa fa-remove fa-lg fa-space'></i>Delete
			</button>
			<button class="btn btn-sm btn-blue2 mybtn hidden" id='uploaditembtn'>
				<i class='fa fa-upload fa-lg fa-space'></i>Upload
			</button>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="pageContent">

		<div class="panel panel-primary mypanel">
    			<div class="panel-body">
	    			

	    			<div class="col-sm-12">
	    				<table class='table table-striped text-nowrap table-bordered no-side-borders table-condensed mytable withitemnumber' id='itemtable'>
	    					<thead>
	    						<tr>
	    							<th class='text-center column-nosort'><input type='checkbox' class='selectall'></th>
	    							<th class='text-center column-nosort'>ACTIONS</th>
	    							<th>APPROVAL STATUS</th>
	    							<th>ID</th>
	    							<th>STATUS</th>
	    							<th>ITEM CODE</th>
	    							<th>ITEM NAME</th>
	    							<th>DESCRIPTION</th>
	    							<th>SUPPLIER</th>
	    							<th>UNIT PRICE</th>
	    							<th>CURRENCY</th>
	    							<th>UOM</th>
	    							<th>CONFORMANCE</th>
	    							<th>DATE CREATED</th>
	    							<th>CREATED BY</th>
	    							<th>LAST MODIFIED</th>
	    							<th>MODIFIED BY</th>
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

<div class="modal fade" id="additemmodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					New Item
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-md-4">
					<div class='modal-body-info'>
						<span class='title'>Item</span><br>
						To add item, please provide the following information.
					</div>
				</div>
				<div class="col-md-8">
					<form class="form-horizontal">
						<div class="form-group">
							<div class="col-md-12">
								<div class="errordiv"></div>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Item Code</label>
							<div class="col-md-8">
								<input type='text' name='itemcode' value="<?php echo getNextTransactionNumber(4) ?>" class='form-input form-control uppercase itemcode' readonly>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Item Name*</label>
							<div class="col-md-8">
								<input type='text' name='shortdesc' class='form-input form-control shortdesc'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Description</label>
							<div class="col-md-8">
								<textarea class='form-input form-control longdesc' name='longdesc' rows='4'></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Unit of Measure*</label>
							<div class="col-md-8">
								<select name='uom' data-placeholder='Select unit of measure' class='chosen-select form-input form-control uom'>
												<option value='NULL'>-</option>
												<?php
													$query='select * from unit_of_measure order by unit_of_measure_code asc';
													$rs = query($query);
													while($obj=fetch($rs)){
														$id = $obj->id;
														$code = $obj->unit_of_measure_code;
														echo "<option value='$id'>$code</option>";
													}
												?>
								</select>						
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Conformance</label>
							<div class="col-md-8">
								<select name='conformance' class='form-input form-control conformanceflag'>
												<option value='NULL'>-</option>
												<option value='1'>Yes</option>
												<option value='0'>No</option>
								</select>
							</div>
						</div>	
						<div class="form-group">
							<label class='control-label col-md-4'>Default Supplier*</label>
							<div class="col-md-8">
								<select name='defaultsupplier' data-placeholder=' ' multiple class='chosen-select form-input form-control defaultsupplier'>
									<option value='NULL'>-</option>
									<?php
									$query="select * from supplier where active_flag='1' order by supplier_name asc";
									$rs = query($query);
									while($obj=fetch($rs)){
										$id = $obj->id;
										$name = strUtfEncode($obj->supplier_name);
										echo "<option value='$id'>$name</option>";
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Delivery Lead Time</label>
							<div class="col-md-8">
								<input type='number' step='1' name='price' class='form-input form-control leadtime'>						
							</div>
						</div>		
						<div class="form-group">
							<label class='control-label col-md-4'>Unit Price*</label>
							<div class="col-md-8">
								<input type='number' step='.01' name='price' class='form-control form-input unitprice'>						
							</div>
						</div>	
						<div class="form-group">
							<label class='control-label col-md-4'>Currency*</label>
							<div class="col-md-8">
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
						
						<div class="form-group">
							<label class='control-label col-md-4'>Remarks</label>
							<div class="col-md-8">
								<textarea class='form-input form-control remarks' name='remarks' rows='3'></textarea>						
							</div>
						</div>	

						<div class="form-group">
							<label class='control-label col-md-4' hidden>Approval Status</label>
							<div class="col-md-8">
								<input type='hidden' name='app_status' class='form-control form-input app_status' value="1" readonly>						
							</div>
						</div>	

						 						

					</form>
				</div>

			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn btn-blue2 mybtn item-modal-savebtn' id='itemcreate-savebtn'>Save</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn' id='itemcreate-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="edititemmodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Existing item
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-md-4">
					<div class='modal-body-info'>
											<span class='title'>Item</span><br>
											To edit item, please update the following information.
					</div>
				</div>
				<div class="col-md-8">
					<form class="form-horizontal">
						<div class="form-group">
							<div class="col-md-12">
								<div class="errordiv"></div>
							</div>
						</div>
						<input type='hidden' class='itemid'>
						<div class="form-group">
							<label class='control-label col-md-4'>Item Code</label>
							<div class="col-md-8">
								<input type='text' name='itemcode' class='form-input form-control uppercase itemcode' readonly>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Item Name*</label>
							<div class="col-md-8">
								<input type='text' name='shortdesc' class='form-input form-control shortdesc'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Description</label>
							<div class="col-md-8">
								<textarea class='form-input form-control longdesc' name='longdesc' rows='4'></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Unit of Measure*</label>
							<div class="col-md-8">
								<select name='uom' data-placeholder='Select unit of measure' class='chosen-select form-input form-control uom'>
												<option value='NULL'>-</option>
												<?php
													$query='select * from unit_of_measure';
													$rs = query($query);
													while($obj=fetch($rs)){
														$id = $obj->id;
														$code = $obj->unit_of_measure_code;
														echo "<option value='$id'>$code</option>";
													}
												?>
								</select>						
							</div>
						</div>
						<div class="form-group">
											<label class="control-label col-md-4">Status</label>
											<div class="col-md-8">
												<select name='status' class='form-input form-control activeflag'>
													<option value='1'>Active</option>
													<option value='0'>Inactive</option>
												</select>
											</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Conformance</label>
							<div class="col-md-8">
								<select name='conformance' class='form-input form-control conformanceflag'>
												<option value='NULL'>-</option>
												<option value='1'>Yes</option>
												<option value='0'>No</option>
								</select>
							</div>
						</div>	
						<div class="form-group">
							<label class='control-label col-md-4'>Default Supplier*</label>
							<div class="col-md-8">
								<select name='defaultsupplier' data-placeholder=' ' multiple class='chosen-select form-input form-control defaultsupplier'>
									<option value='NULL'>-</option>
									<?php
									$query="select * from supplier where active_flag='1' order by supplier_name asc";
									$rs = query($query);
									while($obj=fetch($rs)){
										$id = $obj->id;
										$name = strUtfEncode($obj->supplier_name);
										echo "<option value='$id'>$name</option>";
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Delivery Lead Time</label>
							<div class="col-md-8">
								<input type='number' step='1' name='price' class='form-input form-control leadtime'>						
							</div>
						</div>		
						<div class="form-group">
							<label class='control-label col-md-4'>Unit Price*</label>
							<div class="col-md-8">
								<input type='number' step='.01' name='price' class='form-control form-input unitprice'>						
							</div>
						</div>	
						<div class="form-group">
							<label class='control-label col-md-4'>Currency*</label>
							<div class="col-md-8">
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
						
						<div class="form-group">
							<label class='control-label col-md-4'>Remarks</label>
							<div class="col-md-8">
								<textarea class='form-input form-control remarks' name='remarks' rows='3'></textarea>						
							</div>
						</div>	

						<div class="form-group">
							<label class='control-label col-md-4'>Lot Number(s)</label>
							<div class="col-md-8" id='smp'>
								
							</div>
						</div>	


				


					</form>
				</div>

			</div>
			<div class="modal-footer">
				<div class="text-center">
									<button class='btn btn-blue2 mybtn item-modal-savebtn' id='itemedit-savebtn'>Save</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn' id='itemedit-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="approvalitemmodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Item Approval
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-md-8">
					<form class="form-horizontal">
						<div class="form-group">
							<div class="col-md-12">
								<div class="errordiv"></div>
							</div>
						</div>
						<input type='hidden' class='itemidapp'>
						
						<div class="form-group">
							<label class='control-label col-md-4'>Remarks</label>
							<div class="col-md-8">
								<textarea class='form-input form-control app_remarks' name='app_remarks' rows='3'></textarea>						
							</div>
						</div>	

					</form>
				</div>

			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn app_item-modal-savebtn' id='app_itemedit-savebtn'>Approved</button>
					<button class='btn btn-blue2 mybtn disapp_item-modal-savebtn' id='disapp_itemedit-savebtn'>Disapproved</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn' id='itemedit-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>

<script src="../resources/selectoption/chosen.jquery.js"></script>
<script src="../resources/selectoption/docsupport/prism.js"></script>

<script type="text/javascript">

		
		userAccess();
		$('#addmodal-taginput').html("<input type='text' class='form-control lotnumbertags' data-role='tagsinput'>");
		$('#additemmodal .lotnumbertags').tagsinput('refresh');

		$('.modal-dialog').draggable();

		$('.chosen-select').chosen({ 
			no_results_text: "No results found for: ",
			width:'100%'
		});

		$('#itemtable').DataTable({
				pagingType:"full_numbers",
				aaSorting: [[ 2, "asc" ]],
	        	columnDefs: [
				      				 {
								        targets: "column-nosort", 
								        orderable: false,
								     }
							 ],

	            "columns": [
		            			{
						           data:'CHECKBOX',
						        	"className":'text-center',
						            "mRender":function(data){
                            				return data;
                            			}
						        },
						        {
						        	data:'ACTION',
						        	"className":'text-center',
						            "mRender":function(data){
                            				return data;
                            			}
						        },
						        
		                        { data: 'app_status' } ,
		                        { data: 'id' } ,
		                        { data: 'active_flag' },
		                        { data: 'item_code' },
		                        { data: 'item_name' },
		                        { data: 'long_description'},
		                        { data: 'supplier_name'},
		                        { data: 'unit_price',
		                          "className":'text-right' },
		                        { data: 'currency_code' },
		                        { data: 'unit_of_measure_code' },
		                        { data: 'conformance_flag' },
		                        { data: 'created_date' },
		                        { data: 'created_by' },
		                        { data: 'updated_date' },
		                        { data: 'updated_by' }
		                   ],
				"processing": true,
				"serverSide": true,
				"ajax": {
				                url: "Other/ajax/item.php",
				                type: 'POST'
				        },
				"sScrollY": "350px",
				"sScrollX": "auto",
		});



</script>

