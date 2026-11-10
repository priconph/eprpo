<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
    include('../../config/functions.php');
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Receiving
		<?php 
            if($userid == 1){
				$rs = query("select COUNT(receiving_number) as numrows from receiving_header WHERE receiving_status='REJECTED'");
			}else{
				$rs = query("select COUNT(receiving_number) as numrows from receiving_header WHERE receiving_status='REJECTED' AND `received_by`='$userid'");
            }
			if($obj=fetch($rs)){
                $numrows = $obj->numrows;
                $for_print_badge = '<span class="badge badge-light"> '.$numrows.'</span>';
            }
        ?>
		<div class="button-group">
			<button class="btn btn-sm mybtn btn-blue2" id='load_for_printing' value="<?php echo $userid; ?>" data-toggle="modal" href="#rejectrcvmodal">
				<i class='fa fa-warning fa-lg fa-space'></i>Rejected Receive <?php echo $for_print_badge; ?>
			</button>
			<button class="btn btn-sm btn-blue2 mybtn border-right hidden" id='receiveitemsbtn' data-toggle='modal' href='#newrcvmodal'>
				<i class='fa fa-plus fa-lg fa-space'></i>Receive Items
			</button>
		</div>
	</div>
</div>

<div class="container-fluid">
    <div class="pageContent">
    	<div class="panel panel-primary mypanel">
			<div class="panel-body">
				<div class="col-sm-12">
					<table class='table table-striped table-bordered text-nowrap no-side-borders table-condensed mytable table-hover' id='rcvtable' style='width:100%'>
						<thead>
							<tr>
								<th>RECEIVING NUMBER</th>
								<th>OPEN PO STATUS</th>
								<th>PO NUMBER</th>
								<th>OTHER REFERENCE</th>
								<th>SUPPLIER</th>
								<th>ACTUAL DATE DELIVERED</th>
								<th>DATE RECEIVED</th>
								<th>RECEIVED BY</th>
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

<div class="modal fade" id="rcvblowup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Receiving
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class='tabpane-white margin-top-20 margin-bottom-10' id='generalinfosection'>
					<ul class="nav nav-tabs">
						<li role="presentation" class="active" data-pane='#rcvdetails'><a>General Information</a></li>
						<li role="presentation" data-pane='#rcv-approvers' id='rcvapproverstab'><a>Approvers</a></li>
					</ul>
					<div class='tab-panes'>
						<div class='pane active' id='rcvdetails'>
							<div class="pane-content">
								<form class="form-horizontal">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label col-sm-4">Receiving No.</label>
											<div class="col-sm-8">
												<span class='form-control-static' id='rcvnum'></span>
											</div>
										</div>
										<div class="form-group" id="div_rcv_status">
											<label class="control-label col-sm-4">Status</label>
											<div class="col-sm-8">
												<span class='form-control-static' id='receiving_status'></span>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Supplier</label>
											<div class="col-sm-8">
												<span class='form-control-static' id='suppliername'></span>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">PR Reference</label>
											<div class="col-sm-8">
												<span class='form-control-static' id='prreference'></span>
												<br>
												<span class='form-control-static rcvviewtrans-prhistorytoggle' style='font-style:italic; color:blue; cursor:pointer;'>View Details</span>
											</div>
										</div>
										
										<div class="form-group">
											<label class="control-label col-sm-4">PO No.</label>
											<div class="col-sm-8">
												<span class='form-control-static' id='ponumber'></span>
											</div>
										</div>
										
										
										<div class="form-group">
											<label class="control-label col-sm-4">Currency</label>
											<div class="col-sm-8">
												<span class='form-control-static' id='currency'></span>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label col-sm-4">Actual Date Delivered</label>
											<div class="col-sm-8">
												<span class='form-control-static' id='actualdatedelivered'></span>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Date Received</label>
											<div class="col-sm-8">
												<span class='form-control-static' id='receiveddate'></span>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Received by</label>
											<div class="col-sm-8">
												<span class='form-control-static' id='receivedby'></span>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Other Reference</label>
											<div class="col-sm-8">
												<span class='form-control-static' id='reference'></span>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Carrier</label>
											<div class="col-sm-8">
												<span class='form-control-static' id='carrier'></span>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Remarks</label>
											<div class="col-sm-8">
												<span class='form-control-static' id='remarks'></span>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Hold Remarks</label>
											<div class="col-sm-8">
												<span class='form-control-static' id='hold_remarks'></span>
											</div>
										</div>


									</div>
								</form>
							</div>
						</div>
						<div class="pane" id='rcv-approvers'>
							<div class="pane-content">
								<form class='form-horizontal'>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label col-sm-5">Asst. Manager</label>
											<div class="col-sm-7">
												<span class='form-control-static asstmngr'></span>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label col-sm-5">General Manager</label>
											<div class="col-sm-7">
												<span class='form-control-static genmngr'></span>
											</div>
										</div> 
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<br>

				<div class='tabpane-white margin-top-20 margin-bottom-10' id='rcvline'>
					<ul class="nav nav-tabs">
						<li role="presentation" class="active" data-pane='#rcvline'><a>Line Items</a></li>
					</ul>
					<div class='tab-panes'>
						<div class='pane active' id='rcvline'>
							<div class="pane-content">
								<div class='tablewithbuttons'>
									<div class='table-wrapper' style='height:200px'>
										<table class='table table-striped table-hover table-bordered no-side-borders table-condensed mytable' cellspacing='0'>
											<thead>
												<tr>
													<th class='text-center'>LINE NO.</th>
													<th>ITEM CODE</th>
													<th>NAME</th>
													<th>DESCRIPTION</th>
													<th class='text-right'>UNIT PRICE</th>
													<th class='text-center'>UOM</th>
													<th class='text-right'>QTY RECEIVED</th>
													<th class='text-right qty_approval'>QTY APPROVAL</th>
												</tr>
											</thead>
											<tbody id='cartitems'>		
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>  
</div>


<div class="modal fade" id="newrcvmodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Receiving
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">

				<form class="form-horizontal">
					<div class="col-md-4">
						<div class='modal-body-info'>
							<span class='title'>Receive Items</span><br>
							To receive items, please select transaction type.
						</div>
					</div>
					<div class="col-md-8">
						<div class="form-group">
							<div class="errordiv"></div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3">Transaction Type</label>
							<div class="col-sm-9">
								<select class='form-input form-control modal-transactiontype'>
									<option value=''>-</option>
									<option value='Purchase Order'>Purchase Order</option>
									<option value='Generic'>Non-Purchase Order</option>
								</select>
							</div>
						</div>
						<div class="form-group" id='supplierformgroup'>
							<label class="control-label col-sm-3">Supplier</label>
							<div class="col-sm-9">
								<select style='width:100%' name='defaultsupplier' data-placeholder=' ' class='chosen-select form-control form-input modal-supplier ' id='supplierdropdown'>
									<option value=''></option>
									<?php
									$query='select * from supplier where active_flag=1 order by supplier_name asc';
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
						<div class="form-group hidden" id='ponumberformgroup'>
							<label class="control-label col-sm-3">PO Number</label>
							<div class="col-sm-9">
								<select class='form-input form-control modal-ponumber chosen-select' id='podropdownlist' style='width:100%'>
									<option value=''>-</option>
								</select>
							</div>
						</div>
					</div>
				</form>
					
			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn btn-blue2 mybtn modal-nextbtn' id='rcvmodal-nextbtn'>Next</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn' id='rcvmodal-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="rejectrcvmodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Rejected Receive
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-sm-12">
					<table class='table table-striped table-bordered text-nowrap no-side-borders table-condensed mytable table-hover' id='rejectedrcvtable' style='width:100%'>
						<thead>
							<tr>
								<th>RECEIVING NUMBER</th>
								<th>PO NUMBER</th>
								<th>OTHER REFERENCE</th>
								<th>SUPPLIER</th>
								<th>ACTUAL DATE DELIVERED</th>
								<th>DATE RECEIVED</th>
								<th>RECEIVED BY</th>
								<th>ID</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>					
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn modal-cancelbtn' id='rcvmodal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>


<script src="../resources/selectoption/chosen.jquery.js"></script>
<script src="../resources/selectoption/docsupport/prism.js"></script>

	 


<script type="text/javascript">
	userAccess();

	$('.modal-dialog').draggable();
	$('.chosen-select').select2(); 
	$('#rcvtable').DataTable({
		pagingType:"full_numbers",
		aaSorting: [[ 6, "desc" ]],
		columnDefs: [
							 {
								targets: "column-nosort", 
								orderable: false,
							 }
					 ],

		"columns": [
						
						
						{ data: 'receiving_number'
						},
						{ data: 'receiving_status'
						},
						{ data: 'reference_po_number'
						},
						{ data: 'other_reference'
						},
						{ data: 'supplier_id'
						},
						{ data: 'actual_delivery_date'
						},
						{ data: 'received_date'
						},
						{ data: 'received_by'
						}
				   ],
		"processing": true,
		"serverSide": true,
		"ajax": {
						url: "Other/ajax/receiving.php",
						type: 'POST'
				},
		// "sScrollY": "350px",
		"sScrollX": "auto"
	});
	$('#rejectedrcvtable').DataTable({
		pagingType:"full_numbers",
		aaSorting: [[ 5, "desc" ]],
		columnDefs: [
							 {
								targets: "column-nosort", 
								orderable: false,
							 }
					 ],

		"columns": [
						
						
						{ data: 'receiving_number'
						},
						{ data: 'reference_po_number'
						},
						{ data: 'other_reference'
						},
						{ data: 'supplier_name'
						},
						{ data: 'actual_delivery_date'
						},
						{ data: 'received_date'
						},
						{ data: 'rcvd_by'
						},
						{
						  data: 'supplier_id'
						}
				   ],
		"processing": true,
		"serverSide": true,
		"ajax": {
						url: "Other/ajax/receiving-rejected.php",
						type: 'POST'
				},
		// "sScrollY": "350px",
		"sScrollX": "100%"
	});
</script>

