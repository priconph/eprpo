<?php	
	include("../../config/connection.php");
	include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
	include("../../config/functions.php");
?>
<div class='header-page' >
	<div class='header-page-inner'>
		User Group
		<div class="button-group">
			<button class="btn btn-sm btn-blue2 mybtn border-right hidden" id='addusergroupbtn' data-toggle='modal' href='#addusergroupmodal'>
				<i class='fa fa-plus fa-lg fa-space'></i>Add
			</button>
			<button class="btn btn-sm btn-blue2 mybtn removebtn hidden" id='deleteusergroupbtn'>
				<i class='fa fa-remove fa-lg fa-space'></i>Delete
			</button>
		</div>
	</div>
</div>
<div class="container-fluid">
    <div class="pageContent">
    		<div class="panel panel-primary mypanel">
    			<div class="panel-body">

    				<div class="col-sm-12 tablewrapper">
    					<table class='table table-striped table-hovered table-bordered no-side-borders table-condensed mytable' id='usergrouptable' style='width:100%'>
    						<thead>
    							<tr>
    								<th class='text-center column-nosort'><input type='checkbox' class='selectall'></th>
    								<th class='text-center column-nosort'>ACTIONS</th>
    								<th>ID</th>
    								<th>GROUP NAME</th>
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


<div class="modal fade" id="addusergroupmodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					New User Group
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<div class="col-md-4">
						<div class='modal-body-info'>
							<span class='title'>User Group</span><br>
							To add new user group, please provide group name.
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
								<label class='control-label col-md-4'>Group Name</label>
								<div class="col-md-8">
									<input type='text' name='usergroupname' class='form-control usergroupname uppercase' >
								</div>
							</div>
						</form>
					</div>

			</div>
			<div class="modal-footer">
				<div class="text-center">
									<button class='btn mybtn btn-blue2 modal-usergroup-savebtn' id='usergroupcreate-savebtn'>Save</button>
									<button class='btn mybtn btn-blue2 modal-cancelbtn' id='usergroupcreate-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editusergroupmodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Edit User Group
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<div class="col-md-4">
						<div class='modal-body-info'>
							<span class='title'>User Group</span><br>
							To edit user group, please update group name.
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
								<input type='hidden' class='usergroupid'>
								<label class='control-label col-md-4'>Group Name</label>
								<div class="col-md-8">
									<input type='text' name='usergroupname' class='form-control usergroupname uppercase' >
								</div>
							</div>
						</form>
					</div>

			</div>
			<div class="modal-footer">
				<div class="text-center">
									<button class='btn mybtn btn-blue2 modal-usergroup-savebtn' id='usergroupedit-savebtn'>Save</button>
									<button class='btn mybtn btn-blue2 modal-cancelbtn' id='usergroupedit-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>


<script type="text/javascript">
	userAccess();
	$('.modal-dialog').draggable();

	$('#usergrouptable').DataTable({
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
		            "sAjaxSource": "Other/ajax/user-group.php",
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
		                        { mData: 'NAME' },
		                        { mData: 'DATE CREATED' },
		                        { mData: 'CREATED BY' },
		                        { mData: 'LAST MODIFIED' },
		                        { mData: 'MODIFIED BY' }
		                        ]
	});
</script>