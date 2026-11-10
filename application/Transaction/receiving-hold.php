<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
    include('../../config/functions.php');
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Hold PO / Invoices
	</div>
</div>

<div class='tabpane-white tab-pane-wrapper margin-top-20 margin-bottom-10'>
	<ul class="nav nav-tabs">
		<li role="presentation" class="active" data-pane='#hold-po-pane'><a>On-hold PO / Invoices </a></li>
		<li role="presentation" data-pane='#hold-invoices-pane'><a>Unrecorded to EPRPO (From OMRS)</a></li>
	</ul>

	<div class='tab-panes bg-white'>
		<div class='pane active' id='hold-po-pane'>
			<div class="pane-content">
				<div class="panel-body">
					<div class="col-sm-12">
						<table class='table table-striped table-bordered text-nowrap no-side-borders table-condensed mytable table-hover' id='hold_po' style='width:100%'>
							<thead>
								<tr>
									<th>ACTION</th>
									<th>PO NUMBER</th>
									<th>SUPPLIER NAME</th>
									<th>REMARKS</th>
									<th>HOLD BY</th>
									<th>HOLD DATE</th>
									<th style="color:red;" >ACTUAL RECEIVED DATE (OMRS) (On-going modification)</th>
								</tr>
							</thead>
							<tbody>								
							</tbody>
						</table>
					</div>
				</div>
			</div>					
		</div>	
		<div class='pane ' id='hold-invoices-pane'>
			<div class="pane-content">
				<div class="panel-body">
					<div class="col-sm-12">
						<table class='table table-striped table-bordered text-nowrap no-side-borders table-condensed mytable table-hover' id='hold_invoice' style='width:100%'>
							<thead>
								<tr>
									<th>ACTION</th>
									<th>PO NUMBER</th>
									<th>SUPPLIER NAME</th>
									<th>REMARKS</th>
									<th style="color:red;" >HOLD BY (On-going modification)</th>
									<th style="color:red;" >HOLD DATE (On-going modification)</th>
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
</div>					


<!-- <div class="container-fluid">
<div class="pageContent">
	<div class="panel panel-primary mypanel">
		<div class="panel-body">
			<div class="col-sm-12">
				<table class='table table-striped table-bordered text-nowrap no-side-borders table-condensed mytable table-hover' id='hold_invoice' style='width:100%'>
					<thead>
						<tr>
							<th>ACTION</th>
							<th>PO NUMBER</th>
							<th>SUPPLIER NAME</th>
							<th>REMARKS</th>
							<th>HOLD BY</th>
							<th>HOLD DATE</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>
</div> -->

<div class="modal fade" id="holdcancellationmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Hold PO Cancellation
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class="form-horizontal">
            		<input type='hidden' id='holdcancellationmodal-ponumber'>
	                <div class="form-group">
	                    <div class="col-sm-12">
	                        <div class="errordiv"></div>

	                    </div>
	                </div>
	                <div class="form-group">
	                    <label class="control-label col-md-3 col-sm-3">PO Number</label>
	                    <div class="col-md-5 col-sm-8">
	                        <input type='text' class='form-input form-control holdcancellationmodal-ponumber' disabled>
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
                    <button class='btn btn-blue2 mybtn' id='holdcancellationmodal-confirmbtn'>Confirm</button>
                    <button class='btn btn-blue2 mybtn modal-cancelbtn'>Close</button>
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
	
	$('#hold_po').DataTable({
		pagingType:"full_numbers",
		aaSorting: [[ 1, "desc" ]],
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

				        { data: 'reference_po_number'
						},
						{ data: 'supplier_name'
						},
						{ data: 'remarks'
						},
						{ data: 'hold_by'
						},
						{ data: 'lastupdate'
						},
						{ data: 'lastupdate'
						},
						// { data: 'act_received_date'
						// },
			
						
				   ],
		"processing": true,
		"serverSide": true,
		"ajax": {
					url: "Other/ajax/receiving-hold.php",
					type: 'POST'
				},
		"sScrollY": "350px",
		"sScrollX": "auto"
	});

	$('#hold_invoice').DataTable({
		pagingType:"full_numbers",
		aaSorting: [[ 1, "desc" ]],
		columnDefs: [
							 {
								targets: "column-nosort", 
								orderable: false,
							 }
					 ],

		"columns": [
						
						{
				        	data:'action',
				        	"className":'text-center',
				            "mRender":function(data){
                    				return data;
                    			}
				        },	
						{ data: 'po_num'
						},
						{ data: 'supplier_name'
						},
						{ data: 'ui_remarks'
						},
						{ data: 'updated_at'
						},
						{ data: 'updated_at'
						},
						
						
				   ],
		"processing": true,
		"serverSide": true,
		"ajax": {
						url: "http://rapidx/omrs/api/get_omrs_header",
						type: 'GET'
				},
		"sScrollY": "350px",
		"sScrollX": "auto"
	});

	// getOMRSHeaders();
	// function getOMRSHeaders(){
	// $.ajax({
	// 	type: "GET",
	// 	url: "http://rapidx/omrs/api/get_omrs_header",
	// 	dataType: "json",
	// 	success: function (response) {
	// 		console.log('data ', response);
	// 	}
	// });
	// }

</script>

