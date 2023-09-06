<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
    include('../../config/functions.php');
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Hold Supplier's Invoice
	</div>
</div>

<div class="container-fluid">
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
</div>


<div class="modal fade" id="holdcancellationmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Hold Supplier's Invoice Cancellation
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

</script>

