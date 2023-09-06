<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Terms of Payment
		<div class="button-group">
			<button class="btn btn-sm btn-blue2 mybtn border-right hidden" id='addpaymenttermbtn' data-toggle='modal' href='#addpaymenttermsmodal'>
				<i class='fa fa-plus fa-lg fa-space'></i>Add 
			</button>
			<button class="btn btn-sm btn-blue2 mybtn removebtn hidden" id='deletepaymenttermbtn'>
				<i class='fa fa-remove fa-lg fa-space'></i>Delete 
			</button>
		</div>
	</div>
</div>
<div class="container-fluid">
    <div class="pageContent">
    	<div class="panel panel-primary mypanel">
    			<div class="panel-body">

	    			<div class="col-sm-12">
	    				<table class='table text-nowrap table-striped table-condensed table-bordered no-side-borders table-hover mytable' id='termstable' style='width:100%'>
	    					<thead>
	    						<tr>
	    							<th class='text-center column-nosort'><input type='checkbox' class='selectall'></th>
	    							<th class='text-center column-nosort'>ACTIONS</th>
	    							<th>ID</th>
	    							<th>CODE</th>
	    							<th>DESCRIPTION</th>
	    							<th class='text-center'>DAYS</th>
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

<div class="modal fade" id="addpaymenttermsmodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					New Term of Payment
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-md-4">
					<div class='modal-body-info'>
											<span class='title'>Term of Payment</span><br>
											To add a payment term, please provide the following information.
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
								<label class='control-label col-md-4'>Code</label>
								<div class="col-md-8">
									<input type='text' name='code' class='form-input form-control code'>
								</div>
						</div>
						<div class="form-group">
								<label class='control-label col-md-4'>Description</label>
								<div class="col-md-8">
									<input type='text' name='description' class='form-input form-control description'>
								</div>
						</div>
						<div class="form-group">
								<label class='control-label col-md-4'>Days</label>
								<div class="col-md-8">
									<input type='number' name='days' class='form-input form-control days'>
								</div>
						</div>
					</form>
				</div>
					
			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn btn-blue2 mybtn modal-savebtn' id='paymenttermcreate-savebtn'>Save</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn' id='paymenttermcreate-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editpaymenttermsmodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Existing Term of Payment
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-md-4">
					<div class='modal-body-info'>
											<span class='title'>Term of Payment</span><br>
											To edit payment term, please update the following information.
					</div>
				</div>
				<div class="col-md-8">
					<form class="form-horizontal">
						<div class="form-group">
							<div class="col-md-12">
								<div class="errordiv"></div>
							</div>
						</div>
						<input type='hidden' class='termid'>
						<div class="form-group">
								<label class='control-label col-md-4'>Code</label>
								<div class="col-md-8">
									<input type='text' name='code' class='form-input form-control code'>
								</div>
						</div>
						<div class="form-group">
								<label class='control-label col-md-4'>Description</label>
								<div class="col-md-8">
									<input type='text' name='description' class='form-input form-control description'>
								</div>
						</div>
						<div class="form-group">
								<label class='control-label col-md-4'>Days</label>
								<div class="col-md-8">
									<input type='number' name='days' class='form-input form-control days'>
								</div>
						</div>
					</form>
				</div>
					
			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn btn-blue2 mybtn modal-savebtn' id='paymenttermedit-savebtn'>Save</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn' id='paymenttermedit-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>


<script type="text/javascript">
	//sliceTable(rowperpage,'#termstable','#termspagination');
	userAccess();
	$('.modal-dialog').draggable();
	$('#termstable').DataTable({
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
		            "sAjaxSource": "Other/ajax/terms-of-payment.php",
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
		                        { mData: 'CODE' },
		                        { mData: 'DESC' },
		                        { mData: 'DAYS' },
		                        { mData: 'DATE CREATED' },
		                        { mData: 'CREATED BY' },
		                        { mData: 'LAST MODIFIED' },
		                        { mData: 'MODIFIED BY' }
		                        ]
	});
</script>