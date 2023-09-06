<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
    include("../../config/functions.php");
?>
<div class='header-page' >
	<div class='header-page-inner'>
		Supplier
		<div class="button-group">
			<button class="btn btn-sm btn-blue2 mybtn hidden" id='addsupplierbtn' data-toggle='modal' href='#addsuppliermodal'>
				<i class='fa fa-plus fa-lg fa-space'></i>Add
			</button>
			<button class="btn btn-sm btn-blue2 mybtn removebtn hidden" id='deletesupplierbtn'>
				<i class='fa fa-remove fa-lg fa-space'></i>Delete
			</button>
			<button class="btn btn-sm btn-blue2 mybtn uploadbtn hidden" id='uploadsupplierbtn'>
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
										<table class='table table-striped table-hover table-bordered no-side-borders table-condensed mytable text-nowrap' id='suppliertable' style='width:100%'>
											<thead>
												<tr>
													<th class='text-center column-nosort'><input type='checkbox' class='selectall'></th>
													<th class='text-center column-nosort'>ACTIONS</th>
													<th>ID</th>
													<th>STATUS</th>
													<th>SUPPLIER</th>
													<th>CREATED BY</th>
													<th>CREATED DATE</th>
													<th>MODIFIED BY</th>
													<th>LAST MODIFIED</th>
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

<!-- MODAL ADD SUPPLIER-->
<div class="modal fade" id="addsuppliermodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					New Supplier
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-md-4">
					<div class='modal-body-info'>
						<span class='title'>Supplier</span><br>
						To add a supplier, please provide the following information.
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
							<label class='control-label col-md-4'>Supplier Code</label>
							<div class="col-md-8">
								<input type='text' name='suppliercode' class='form-input form-control uppercase suppliercode' >
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Supplier Name</label>
							<div class="col-md-8">
								<input type='text' name='suppliername' class='form-input form-control suppliername'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Address</label>
							<div class="col-md-8">
								<textarea class='form-input form-control address' name='address' rows='4'></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Supplier TIN</label>
							<div class="col-md-8">
								<input type='text' name='suppliertin' class='form-input form-control uppercase suppliertin' >
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Payment Terms</label>
							<div class="col-md-8">
								<select name='paymentterms'  data-placeholder='Select payment terms' class='chosen-select form-input form-control paymentterms'>
												<option value='NULL'>-</option>
												<?php
													$query = "select * from payment_terms";
													$rs = query($query);
													while ($obj=fetch($rs)) {
														$id=$obj->id;
														$code=$obj->payment_terms_code;
														$description=$obj->description;
														echo "<option value='$id'>$description</option>";
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
							&nbsp;
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Contact Person</label>
							<div class="col-md-8">
								<input type='text' name='contactperson' class='form-input form-control contactperson'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Position</label>
							<div class="col-md-8">
								<input type='text' class='form-input form-control position'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Email</label>
							<div class="col-md-8">
								<input type='text' name='email' class='form-input form-control lowercase email'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Mobile</label>
							<div class="col-md-8">
								<input type='text' name='mobile' class='form-input form-control mobile'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Tel</label>
							<div class="col-md-8">
								<input type='text' name='tel' class='form-input form-control tel'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Fax</label>
							<div class="col-md-8">
								<input type='text' name='fax' class='form-input form-control fax'>
							</div>
						</div>

					</form>
				</div>
			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn btn-blue2 mybtn supplier-modal-savebtn' id='suppliercreate-savebtn'>Save</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn' id='suppliercreate-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editsuppliermodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Existing Supplier
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-md-4">
					<div class='modal-body-info'>
						<span class='title'>Supplier</span><br>
						To edit supplier, please update the following information.
					</div>
				</div>
				<div class="col-md-8">
					<form class="form-horizontal">
						<div class="form-group">
							<div class="col-md-12">
								<div class="errordiv"></div>
							</div>
						</div>
						<input type='hidden' class='supplierid'>
						<div class="form-group">
							<label class='control-label col-md-4'>Supplier Code</label>
							<div class="col-md-8">
								<input type='text' name='suppliercode' class='form-input form-control uppercase suppliercode' >
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Supplier Name</label>
							<div class="col-md-8">
								<input type='text' name='suppliername' class='form-input form-control suppliername'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Address</label>
							<div class="col-md-8">
								<textarea class='form-input form-control address' name='address' rows='4'></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Supplier TIN</label>
							<div class="col-md-8">
								<input type='text' name='suppliertin' class='form-input form-control uppercase suppliertin' >
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
							<label class='control-label col-md-4'>Payment Terms</label>
							<div class="col-md-8">
								<select name='paymentterms' data-placeholder='Select payment terms' class='chosen-select form-input form-control paymentterms'>
												<option value='NULL'>-</option>
												<?php
													$query = "select * from payment_terms";
													$rs = query($query);
													while ($obj=fetch($rs)) {
														$id=$obj->id;
														$code=$obj->payment_terms_code;
														$description=$obj->description;
														echo "<option value='$id'>$description</option>";
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
							&nbsp;
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Check if New Contact</label>
							<div class="col-md-8">
								<input type='checkbox' class='checkifnewcontact'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Contact Person</label>
							<div class="col-md-8">
								<input type='text' name='contactperson' class='form-input form-control contactperson'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Position</label>
							<div class="col-md-8">
								<input type='text' class='form-input form-control position'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Email</label>
							<div class="col-md-8">
								<input type='text' name='email' class='form-input form-control lowercase email'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Mobile</label>
							<div class="col-md-8">
								<input type='text' name='mobile' class='form-input form-control mobile'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Tel</label>
							<div class="col-md-8">
								<input type='text' name='tel' class='form-input form-control tel'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Fax</label>
							<div class="col-md-8">
								<input type='text' name='fax' class='form-input form-control fax'>
							</div>
						</div>

					</form>
				</div>
			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn btn-blue2 mybtn supplier-modal-savebtn' id='supplieredit-savebtn'>Save</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn' id='supplieredit-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>


<script src="../resources/selectoption/chosen.jquery.js"></script>
<script src="../resources/selectoption/docsupport/prism.js"></script>


<script type="text/javascript">
	sliceTable(rowperpage,'#suppliertable','#supplierpagination');
	userAccess();
	$('.modal-dialog').draggable();
	$('.chosen-select').chosen({ 
			no_results_text: "No results found for: ",
			width:'100%'
	});

	$('#suppliertable').DataTable({
					"sScrollY": "350px",
					"sScrollX": "auto",
		 			aaSorting: [[ 4, "asc" ]], //initially, table is sorted by second column desc
				    columnDefs: [
				      				 {
								        targets: "column-nosort", //class of columns you dont want to be sortable
								        orderable: false,
								        //visible: false,
		               					//searchable: true

								     }
								 ],		
					pagingType: "full_numbers", //numbers, simple, simple_numbers, full, full_numbers
					"bProcessing": true,
		            "sAjaxSource": "Other/ajax/supplier.php",
		            "aoColumns": [
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
						        
		                        { mData: 'ID' },
		                        { mData: 'STATUS' },
		                        { mData: 'SUPPLIER' },
		                        { mData: 'DATE CREATED' },
		                        { mData: 'CREATED BY' },
		                        { mData: 'LAST MODIFIED' },
		                        { mData: 'MODIFIED BY' }
		                        ]
	});
</script>