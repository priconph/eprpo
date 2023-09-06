<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Ship Via
		<div class="button-group">
			<button class="btn btn-sm btn-blue2 mybtn border-right hidden" id='addshipviabtn' data-toggle='modal' href='#addshipviamodal'>
				<i class='fa fa-plus fa-lg fa-space'></i>Add 
			</button>
			<button class="btn btn-sm btn-blue2 mybtn removebtn hidden" id='deleteshipviabtn'>
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
	    				<table class='table table-striped table-condensed table-bordered no-side-borders table-hover mytable' id='shipviatable' style='width:100%'>
	    					<thead>
	    						<tr>
	    							<th class='text-center column-nosort'><input type='checkbox' class='selectall'></th>
	    							<th class='text-center column-nosort'>ACTIONS</th>
	    							<th>ID</th>
	    							<th>DESCRIPTION</th>
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

<div class="modal fade" id="addshipviamodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					New
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-md-4">
					<div class='modal-body-info'>
											<span class='title'>Ship Via</span><br>
											To add, please provide description.
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
								<label class='control-label col-md-4'>Description</label>
								<div class="col-md-8">
									<input type='text' class='form-control description'>
								</div>
						</div>
					</form>
				</div>
					
			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn btn-blue2 mybtn shipviamodal-savebtn' id='shipviacreate-savebtn'>Save</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn' id='shipviacreate-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editshipviamodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Edit
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-md-4">
					<div class='modal-body-info'>
											<span class='title'>Ship Via</span><br>
											To edit, please update description.
					</div>
				</div>
				<div class="col-md-8">
					<form class="form-horizontal">
						<div class="form-group">
							<div class="col-md-12">
								<div class="errordiv"></div>
							</div>
						</div>
						<input type='hidden' class='shipviaid'>
						<div class="form-group">
								<label class='control-label col-md-4'>Description</label>
								<div class="col-md-8">
									<input type='text' class='form-input form-control description'>
								</div>
						</div>
						
					</form>
				</div>
					
			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn btn-blue2 mybtn shipviamodal-savebtn' id='shipviaedit-savebtn'>Save</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn' id='shipviaedit-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>


<script type="text/javascript">
	//sliceTable(rowperpage,'#shipviatable','#shipviapagination');
	userAccess();
	$('.modal-dialog').draggable();
	$('#shipviatable').DataTable({
					"sScrollY": "350px",
					"sScrollX": "auto",
		 			aaSorting: [[ 3, "asc" ]], //initially, table is sorted by second column desc
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
		            "sAjaxSource": "Other/ajax/ship-via.php",
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
		                        { mData: 'DESC' },
		                        { mData: 'DATE CREATED' },
		                        { mData: 'CREATED BY' },
		                        { mData: 'LAST MODIFIED' },
		                        { mData: 'MODIFIED BY' }
		                        ]
	});
</script>