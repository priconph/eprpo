<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
     include("../../config/functions.php");
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Department Allocation
		<div class="button-group">
			<button class="btn btn-sm btn-blue2 mybtn border-right hidden" id='addsectionbtn' data-toggle='modal' href='#adddepartmentmodal'>
				<i class='fa fa-plus fa-lg fa-space'></i>Add
			</button>
			<button class="btn btn-sm btn-blue2 mybtn removebtn hidden" id='deletesectionbtn'>
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
	    				<table class='table table-striped table-bordered no-side-borders text-nowrap table-condensed mytable' id='sectiontable' style='width:100%'>
	    					<thead>
	    						<tr>
	    							<th class='text-center column-nosort'><input type='checkbox' class='selectall'></th>
	    							<th class='text-center column-nosort'>ACTIONS</th>
	    							<th>ID</th>
	    							<th>STATUS</th>
	    							<th>CODE</th>
	    							<th>DEPARTMENT NAME</th>
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

<div class="modal fade" id="adddepartmentmodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					New Department
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">

				<div class="col-md-4">
					<div class='modal-body-info'>
						<span class='title'>Department</span><br>
						To add new department, please provide a department name.
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
									<input type='text' class='form-input form-control deptcode' >
								</div>
						</div>
						<div class="form-group">
								<label class='control-label col-md-4'>Department Name</label>
								<div class="col-md-8">
									<input type='text' name='deptname' class='form-input form-control deptname' >
								</div>
						</div>
						<div class="form-group">
								<label class='control-label col-md-4'>Status</label>
								<div class="col-md-8">
									<select name='status' class='form-input form-control status'>
										<option value="ACTIVE">ACTIVE</option>
										<option value="INACTIVE">INACTIVE</option>
									</select>
								</div>
						</div>

					</form>
				</div>
			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn btn-blue2 mybtn sectiondepartment-modal-savebtn' id='departmentcreate-savebtn'>Save</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn' id='departmentcreate-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editdepartmentmodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Existing Department
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">

				<div class="col-md-4">
					<div class='modal-body-info'>
						<span class='title'>Department</span><br>
						To edit department, please update department name.
					</div>
				</div>
				<div class="col-md-8">
					<form class="form-horizontal">
						<div class="form-group">
							<div class="col-md-12">
								<div class="errordiv"></div>
							</div>
						</div>
						<input type='hidden' class='deptid'>
						<div class="form-group">
								<label class='control-label col-md-4'>Code</label>
								<div class="col-md-8">
									<input type='text' class='form-input form-control deptcode' >
								</div>
						</div>
						<div class="form-group">
								<label class='control-label col-md-4'>Department Name</label>
								<div class="col-md-8">
									<input type='text' name='deptname' class='form-input form-control deptname' >
								</div>
						</div>
						<div class="form-group">
								<label class='control-label col-md-4'>Next Number Series</label>
								<div class="col-md-8">
									<input type='text' name='nextnumberseries' class='form-input form-control nextnumberseries' >
								</div>
						</div>
						<div class="form-group">
								<label class='control-label col-md-4'>Status</label>
								<div class="col-md-8">
									<select name='status' class='form-input form-control status'>
										<option value="ACTIVE">ACTIVE</option>
										<option value="INACTIVE">INACTIVE</option>
									</select>
								</div>
						</div>


					</form>
				</div>
			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn btn-blue2 mybtn sectiondepartment-modal-savebtn' id='departmentedit-savebtn'>Save</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn' id='departmentedit-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>





<script type="text/javascript">
	//sliceTable(rowperpage,'#sectiontable','#sectionpagination');
	userAccess();
	$('.modal-dialog').draggable();
	$('#sectiontable').DataTable({
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
		            "sAjaxSource": "Other/ajax/section-department.php",
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
		                        { mData: 'CODE' },
		                        { mData: 'NAME' },
		                        { mData: 'DATE CREATED' },
		                        { mData: 'CREATED BY' },
		                        { mData: 'LAST MODIFIED' },
		                        { mData: 'MODIFIED BY' }
		                        ]
	});
</script>