<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Transaction Type
	</div>
</div>
<div class="container-fluid">
    <div class="pageContent">
    	<div class="panel panel-primary mypanel">
    			<div class="panel-body">
	    			<div class="col-sm-12">
	    				<table class='table table-striped table-bordered no-side-borders table-condensed mytable' id='transactiontypetable' style='width:100%'>
	    					<thead>
	    						<tr>
	    							<th class='text-center column-nosort'><input type='checkbox' class='selectall'></th>
	    							<th class='text-center column-nosort'>ACTIONS</th>
	    							<th>ID</th>
	    							<th>CODE</th>
	    							<th>DESCRIPTION</th>
	    							<th>PREFIX</th>
	    							<th>NEXT NUMBER SERIES</th>
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

<div class="modal fade" id="edittransactiontypemodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Edit Transaction Type
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<div class="col-md-4">
						<div class='modal-body-info'>
							<span class='title'>Transaction type</span><br>
							To edit an transaction type, please update the following information.
						</div>
					</div>
					<div class="col-md-8">
						<form class="form-horizontal">
							<div class="form-group">
								<div class="col-md-12">
									<div class="errordiv"></div>
								</div>
							</div>
							<input type='hidden' class='transactiontypeid'>

							<div class="form-group">
								<label class='control-label col-md-4'>Code</label>
								<div class="col-md-8">
									<input type='text' name='code' class='form-input form-control code'>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-md-4'>Description</label>
								<div class="col-md-8">
									<input type='text' name='description' class='form-input form-control desc'>
								</div>
							</div>	
							<div class="form-group">
								<label class='control-label col-md-4'>Prefix</label>
								<div class="col-md-8">
									<input type='text' name='prefix' class='form-input form-control prefix'>
								</div>
								
							</div>
							<div class="form-group">
								<label class='control-label col-md-4'>Next Number Series</label>
								<div class="col-md-8">
									<input type='number' name='numberseries' class='form-control numseries'>
								</div>
								
							</div>				
						</form>
						
						
					</div>
			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn mybtn btn-blue2' id='transactiontypeedit-savebtn'>Save</button>
									<button class='btn mybtn btn-blue2 modal-cancelbtn' id='transactiontypeedit-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>

<script type="text/javascript">
	userAccess();
	$('.modal-dialog').draggable();

	$('#transactiontypetable').DataTable({
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
		            "sAjaxSource": "Other/ajax/transaction-type.php",
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
						        
		                        { mData: 'ID' } ,
		                        { mData: 'CODE' },
		                        { mData: 'DESC' },
		                        { mData: 'PREFIX' },
		                        { mData: 'SERIES'},
		                        { mData: 'LAST MODIFIED' },
		                        { mData: 'MODIFIED BY' }
		                        ]
	});
</script>


	



