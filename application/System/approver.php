<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/functions.php");
    include("../../config/checklogin-page.php");
?>
<div class='header-page' >
	<div class='header-page-inner'>
		Approver
		<div class="button-group">
			<button class="btn btn-sm mybtn btn-blue2 hidden" id='addapproverbtn' data-toggle='modal' href='#addapprovermodal'>
				<i class='fa fa-plus fa-lg fa-space'></i>Add
			</button>
			<button class="btn btn-sm mybtn btn-blue2 removebtn hidden" id='deleteapproverbtn'>
				<i class='fa fa-remove fa-lg fa-space'></i>Delete
			</button>
			<button class="btn btn-sm mybtn btn-blue2" id='reactivateapproverbtn'>
				<i class='fa fa-check-square-o fa-lg fa-space'></i>Reactivate
			</button>
		</div>
	</div>
</div>
<div class="container-fluid">
    <div class="pageContent">
    	<div class="panel panel-primary mypanel">
    			<div class="panel-body">
	    			<div class="col-sm-12 tablewrapper">
	    				<table class='table table-condensed table-striped table-bordered no-side-borders text-nowrap table-hover mytable' id='approvertable' style='width:100%'>
	    					<thead>
	    						<tr>
	    							<th class='text-center column-nosort'><input type='checkbox' class='selectall'></th>
	    							<th class='text-center column-nosort'>ACTIONS</th>
	    							<th>ID</th>
	    							<th>STATUS</th>
	    							<th>NAME</th>
	    							<th>DEPARTMENT</th>
	    							<th>APPROVAL TYPE</th>
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

<div class="modal fade" id="addapprovermodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					New Approver
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body" style='min-height:130px; overflow:visible'>
					<div class="col-md-4">
						<div class='modal-body-info'>
							<span class='title'>Approver</span><br>
							To add an approver for a department, please select a user, department, and type of approval.

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
								<label class='control-label col-md-4'>User</label>
								<div class="col-md-8">
									<div class="input-group">
										<input type='text' name='approver' class='form-control form-input userinput' readonly>
										<div class="input-group-addon button-addon searchuserbtn" from='add' data-toggle='modal' href='#userlist'>
											<i class='fa fa-search'></i>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-md-4'>Department</label>
								<div class="col-md-8">
									<select data-placeholder='Select a department' class='chosen-select form-control department' id='addselectdep'>
												<option value=''>-</option>
												<?php
													$query='select * from section_department';
													$rs = query($query);
													while($obj=fetch($rs)){
														$id = $obj->id;
														$name = $obj->section_department_name;
														echo "<option value='$id'>$name</option>";
													}
												?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-md-4'>Approval Type</label>
								<div class="col-md-8">
									<select class='form-control approvaltype' >
												<option value=''>-</option>
												<?php
													$query='select * from approval_type';
													$rs = query($query);
													while($obj=fetch($rs)){
														$id = $obj->id;
														$name = $obj->approval_type;
														echo "<option value='$id'>$name</option>";								
													}
												?>
									</select>
								</div>
							</div>
						</form>
						
						
					</div>
			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn mybtn btn-blue2 modal-savebtn' id='approvercreate-savebtn'>Save</button>
									<button class='btn mybtn btn-blue2 modal-cancelbtn' id='approvercreate-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editapprovermodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Edit Approver
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body" style='overflow:visible; min-height:160px;'>
					<div class="col-md-4">
						<div class='modal-body-info'>
							<span class='title'>Approver</span><br>
							To edit an approver, please update the following information.
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
								<label class='control-label col-md-4'>User</label>
								<div class="col-md-8">
									<div class="input-group">
										<input type='text' name='approver' class='form-control form-input userinput' readonly>
										<div class="input-group-addon button-addon searchuserbtn" from='edit' data-toggle='modal' href='#userlist'>
											<i class='fa fa-search'></i>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-md-4'>Department</label>
								<div class="col-md-8">
									<select data-placeholder='Select a department' class='chosen-select form-control department'>
												<option value=''>-</option>
												<?php
													$query='select * from section_department';
													$rs = query($query);
													while($obj=fetch($rs)){
														$id = $obj->id;
														$name = $obj->section_department_name;
														echo "<option value='$id'>$name</option>";
													}
												?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-md-4'>Approval Type</label>
								<div class="col-md-8">
									<select class='form-control approvaltype' >
												<option value=''>-</option>
												<?php
													$query='select * from approval_type';
													$rs = query($query);
													while($obj=fetch($rs)){
														$id = $obj->id;
														$name = $obj->approval_type;
														echo "<option value='$id'>$name</option>";								
													}
												?>
									</select>
								</div>
							</div>

							<div class="form-group">
									<label class='control-label col-md-4'>Status</label>
									<div class="col-md-8">
											<select name='status' class='form-control activeflag' >
												<option value='1'>Active</option>
												<option value='0'>Inactive</option>
											</select>
									</div>
							</div>			
						</form>
						
						
					</div>
			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn mybtn btn-blue2 modal-savebtn' id='approveredit-savebtn'>Save</button>
									<button class='btn mybtn btn-blue2 modal-cancelbtn' id='approveredit-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade lookupmodal" id="userlist">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					<i class='fa fa-search'></i>&nbsp; User List
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-md-12">
					<table class='table table-condensed table-bordered no-side-borders table-hovered table-striped mytable' id='userstable' style='width:100%'>
						<thead>
							<tr>
								<th class='column-nosort'></th>
								<th>NAME</th>
								<th>DEPARTMENT</th>

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

<div class="modal fade" id="activationmodal">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Approver Reactivation
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="form-horizontal">
							<br>
							<div class="form-group">
								<div class="col-md-12">
									<div class="errordiv"></div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label class='control-label'>Approver</label>
									<select data-placeholder='Select an approver' class='chosen-select form-control userapprover'>
												<option value=''>-</option>
												<?php
													$query='select  approver.user_id,
																	user.first_name,
															        user.last_name
															from approver 
															inner join user on approver.user_id=user.id
															where approver.active_flag=0
															group by approver.user_id';
													$rs = query($query);
													while($obj=fetch($rs)){
														$id = $obj->user_id;
														$name = $obj->first_name.' '.$obj->last_name;
														echo "<option value='$id'>$name</option>";
													}
												?>
									</select>
								</div>
							</div>
							<br><br><br><br>

				</div>
			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn mybtn btn-blue2 modal-savebtn' id='activationmodal-reactivatebtn'>Reactivate</button>
									<button class='btn mybtn btn-blue2 modal-cancelbtn'>Cancel</button>
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
	$('.chosen-select').chosen({ 
			no_results_text: "No results found for: ",
			width:'100%'
	});

	$('#approvertable').DataTable({
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
		            "sAjaxSource": "Other/ajax/approvers.php",
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
		                        { mData: 'STATUS' },
		                        { mData: 'NAME' },
		                        { mData: 'DEPARTMENT' },
		                        { mData: 'APPROVALTYPE'},
		                        { mData: 'DATE CREATED' },
		                        { mData: 'CREATED BY' },
		                        { mData: 'LAST MODIFIED' },
		                        { mData: 'MODIFIED BY' }
		                        ]
	});

	$('#userstable').DataTable({
					"sScrollY": "350px",
					"sScrollX": "auto",
		 			aaSorting: [[ 1, "asc" ]], //initially, table is sorted by second column desc
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
		            "sAjaxSource": "Other/ajax/userlist.php",
		            "aoColumns": [
						        
		                         {
						        	data:'USERLISTACTION',
						        	"className":'text-center',
						            "mRender":function(data){
                            				return data;
                            			}
						        },
		                        { mData: 'NAME' },
		                        { mData: 'DEPARTMENT' }
		                        ]
	});

	$('#userstable').DataTable().columns.adjust().draw();
</script>