<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
    include("../../config/functions.php");
?>


<div class='header-page' >
	<div class='header-page-inner'>
		Users
		<div class="button-group">
			<button class="btn btn-sm mybtn btn-blue2 border-right hidden" id='adduserbtn' data-toggle='modal' href='#addusermodal' disabled='disabled'>
				<i class='fa fa-plus fa-lg fa-space'></i>Add
			</button>
			<button class="btn btn-sm mybtn btn-blue2 removebtn hidden" id='deleteuserbtn' disabled='disabled'>
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
										<table class='table table-condensed table-bordered no-side-borders table-striped mytable text-nowrap' id='userstable'>
											<thead>
												<tr>
													<th class='text-center column-nosort'><input type='checkbox' class='selectall'></th>
													<th class='text-center column-nosort'>ACTIONS</th>
													<th>ID</th>
													<th>STATUS</th>
													<th>NAME</th>
													<th>USERNAME</th>
													<th>DEPARTMENT</th>
													<th>EMAIL ADDRESS</th>
													<th>USER GROUP</th>
													<th>DATE CREATED</th>
													<th>CREATED BY</th>
													<th>LAST MODIFIED</th>
													<th>MODIFIED BY</th>
												</tr>
											</thead>
											<tbody>
												<tr class='noresult hidden'><td colspan='15'>No Results Found</td></tr>
												
											</tbody>
										</table>
							
						
	    			</div>
    			</div>
    	</div>

	</div>
</div>

<div class="modal fade" id="addusermodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					New User
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-xs-12">
				
									<div class='tabpane-white margin-top-20 margin-bottom-10'>
										<ul class="nav nav-tabs">
										  <li role="presentation" class="active" data-pane='#userinfo'><a href="#">User Information</a></li>
										  <li role="presentation" data-pane='#useraccess'><a href="#">User Permission</a></li>
										</ul>
										<div class='tab-panes'>
											<div class='pane active' id='userinfo' style='overflow:visible;'>
												<div class="pane-content">
													<div class="col-md-4">
														<div class='modal-body-info'>
																	<span class='title'>User</span><br>
																	To add user, please provide the following information.
														</div>
													</div>

													<div class="col-md-8">
														<form class="form-horizontal">
															<div class="form-group">
																<div class="errordiv"></div>
															</div>
															
															<div class="form-group">
																<label class='control-label col-md-4'>First Name*</label>
																<div class="col-md-8">
																	<input type='text' class='form-input form-control requiredinput capitalize firstname' >
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>Middle Name</label>
																<div class="col-md-8">
																	<input type='text' class='form-input form-control capitalize middlename' >
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>Last Name*</label>
																<div class="col-md-8">
																	<input type='text'class='form-input form-control requiredinput capitalize lastname' >
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>Department*</label>
																<div class="col-md-8">
																	<select name='sec-dept-div' data-placeholder=' ' class='chosen-select form-input form-control uppercase requiredinput department'>
																			<option value=''></option>
																			<?php
																				// $query='select * from section_department WHERE status = "ACTIVE" order by section_department_name asc';
																				$query='select * from section_department WHERE category ="0" order by section_department_name asc';
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
																<label class='control-label col-md-4'>Email Address*</label>
																<div class="col-md-8">
																	<input type='email' name='sec-dept-div' class='form-input form-control requiredinput lowercase email'>
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>Username*</label>
																<div class="col-md-8">
																	<input type='hidden' id='checkifusername'>
																	<input type='text' name='status' class='form-input form-control requiredinput lowercase username'>
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>User Group</label>
																<div class="col-md-8">
																	<select name='type'  class='form-input form-control capitalize usergroup'>
																			<option value='NULL'></option>
																			<?php
																				$query='select * from user_group order by user_group_name asc';
																				$rs = query($query);
																				while($obj=fetch($rs)){
																					$id = $obj->id;
																					$name = $obj->user_group_name;									
																					echo "<option value='$id'>$name</option>";
																				}
																			?>
																	</select>
																</div>
															</div>
															<br>
														</form>
														<br>
													</div>

													
												</div>
											</div>
											<div class='pane' id='useraccess'>
												<div class="pane-content">
													<div class="table-wrapper">
														<table class='table table-condensed mytable table-useraccess'  id='rcv-summary'>
															<thead>
																<tr>
																	<th class='width-xs'></th>
																	<th>MENU</th>
																	<th>SECTION</th>
																	<th>VIEW</th>
																	<th>ADD</th>
																	<th>DELETE</th>
																	<th>EDIT</th>
																	<th>UPLOAD</th>
																	<th>RESET PASSWORD</th>
																	<th>PRINT</th>
																	<th>ASSIGN</th>
																	<th>REVISE</th>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td class='text-center'><input type='checkbox' class='useraccess section' data-section='dashboard' value='#dash' checked='checked' disabled='disabled'></td>
																	<td>Dashboard</td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='system'>
																	<td rowspan='5' class='text-center'><input type='checkbox' class='useraccess section' data-section='system' value='#system-section'></td>
																	<td rowspan='5'>System</td>
																	<td>Configuration</td>
																	<td class='text-center'><input type='checkbox' class='useraccess view' value='#configuration'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='system'>
																	<td>User</td>
																	<td class='text-center'><input type='checkbox' class='useraccess view' value='#user'></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='#adduserbtn'></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='#deleteuserbtn'></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='.edituserbtn'></td>
																	<td></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='.resetpassword'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='system'>
																	<td>User Group</td>
																	<td class='text-center'><input type='checkbox' class='useraccess view' value='#usergroup'></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='#addusergroupbtn'></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='#deleteusergroupbtn'></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='.editusergroupbtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='system'>
																	<td>Approver</td>
																	<td class='text-center'><input type='checkbox' class='useraccess view' value='#approver'></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='#addapproverbtn'></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='#deleteapproverbtn'></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='.editapproverbtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='system'>
																	<td>Transaction Type</td>
																	<td class='text-center'><input type='checkbox' class='useraccess view' value='#transaction'></td>
																	<td></td>
																	<td></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='.edittranstypebtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='maintenance'>
																	<td rowspan='7' class='text-center'><input type='checkbox' class='useraccess section' data-section='maintenance' value='#maintenance-section'></td>
																	<td rowspan='7'>Maintenance</td>
																	<td>Item</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#items'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#additembtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#deleteitembtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.edititembtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#uploaditembtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='maintenance'>
																	<td>Supplier</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#supplier'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#addsupplierbtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#deletesupplierbtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.editsupplierbtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#uploadsupplierbtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='maintenance'>
																	<td>Section/Department</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#department'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#addsectionbtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#deletesectionbtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.editdepartmentbtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='maintenance'>
																	<td>Term of Payment</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#terms'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#addpaymenttermbtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#deletepaymenttermbtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.editpaymenttermbtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='maintenance'>
																	<td>Ship Via</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#shipvia'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#addshipviabtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#deleteshipviabtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.editshipviabtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='maintenance'>
																	<td>Mode of Shipment</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#modeofshipment'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#addmodeofshipmentbtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#deletemodeofshipmentbtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.editmodeofshipmentbtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='maintenance'>
																	<td>Classification Code</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#classificationcode'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#addclasscodebtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#deleteclasscodebtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.editclasscodebtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='transaction'>
																	<td rowspan='4' class='text-center'><input type='checkbox' class='useraccess section' data-section='transaction' value='#transaction-section'></td>
																	<td rowspan='4'>Transaction</td>
																	<td>Purchase Requisition</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#pr'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#addpr'></td>
																	<td></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.editpurchaserequisition'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='transaction'>
																	<td>Purchase Order</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#po'></td>
																	<td></td>
																	<td></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.editpurchaseorder'></td>
																	<td></td>
																	<td></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.printpurchaseorder'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.assignpurchaseorder'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.revisepurchaseorder'></td>

																</tr>
																<tr class='transaction'>
																	<td>Receiving</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#receiving'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#receiveitemsbtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	
																</tr>
																<tr class='transaction'>
																	<td>Hold Invoice</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#hold-invoice'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	
																</tr>
																<tr class='report'>
																	<td rowspan='3' class='text-center'><input type='checkbox' class='useraccess section' data-section='report' value='#reports-section'></td>
																	<td rowspan='3'>Reports</td>
																	<td>Purchase Requisition</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#prsumm'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='report'>
																	<td>Purchase Order</td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#posumm'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>

																</tr>
																<tr class='report'>
																	<td>Receiving</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#rcvsumm'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	
																</tr>



															</tbody>							
														</table>	
													</div>
												</div>
											</div>
										</div>
									</div>
							
				</div>

			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn btn-blue2 mybtn modal-savebtn' id='usercreate-savebtn'>Save</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn' id='usercreate-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editusermodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Existing User
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-xs-12">
				
									<div class='tabpane-white margin-top-20 margin-bottom-10'>
										<ul class="nav nav-tabs">
										  <li role="presentation" class="active" data-pane='#userinfo-edit'><a href="#">User Information</a></li>
										  <li role="presentation" data-pane='#useraccess-edit'><a href="#">User Permission</a></li>
										</ul>
										<div class='tab-panes'>
											<div class='pane active' id='userinfo-edit' style='overflow:visible;'>
												<div class="pane-content">
													<div class="col-md-4">
														<div class='modal-body-info'>
																	<span class='title'>User</span><br>
																	To edit user, please update the following information.
														</div>
													</div>

													<div class="col-md-8">
														<form class="form-horizontal">
															<div class="form-group">
																<div class="errordiv"></div>

															</div>
															<input type='hidden' class='form-input userid'>
															<div class="form-group">
																<label class="control-label col-md-4">Status*</label>
																<div class="col-md-8">
																	<select name='sec-dept-div' class='form-input form-control stat'>
																		<option value='1'>Active</option>
																		<option value='0'>Inactive</option>
																	</select>
																</div>
															</div>
															
															<div class="form-group">
																<label class='control-label col-md-4'>First Name*</label>
																<div class="col-md-8">
																	<input type='text' class='form-input form-control requiredinput capitalize firstname' >
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>Middle Name</label>
																<div class="col-md-8">
																	<input type='text' class='form-input form-control capitalize middlename' >
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>Last Name*</label>
																<div class="col-md-8">
																	<input type='text'class='form-input form-control requiredinput capitalize lastname' >
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>Department*</label>
																<div class="col-md-8">
																	<!-- <select name='sec-dept-div' data-placeholder='Select a department' class='chosen-select form-input form-control uppercase requiredinput department' disabled> -->
																	<select name='sec-dept-div' data-placeholder='Select a department' class='chosen-select form-input form-control uppercase requiredinput department'>
																			<option value=''>-</option>
																			<?php
																				$query='select * from section_department WHERE status ="ACTIVE" order by section_department_name asc';
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
																<label class='control-label col-md-4'>Email Address*</label>
																<div class="col-md-8">
																	<input type='email' name='sec-dept-div' class='form-input form-control requiredinput lowercase email'>
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>Username*</label>
																<div class="col-md-8">
																	<input type='hidden' id='checkifusername'>
																	<input type='text' name='status' class='form-input form-control requiredinput lowercase username'>
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>User Group</label>
																<div class="col-md-8">
																	<select name='type'  class='form-input form-control capitalize usergroup'>
																			<option value='NULL'>-</option>
																			<?php
																				$query='select * from user_group order by user_group_name asc';
																				$rs = query($query);
																				while($obj=fetch($rs)){
																					$id = $obj->id;
																					$name = $obj->user_group_name;									
																					echo "<option value='$id'>$name</option>";
																				}
																			?>
																	</select>
																</div>
															</div>
														</form>
														<br><br>
													</div>

													
												</div>
											</div>
											<div class='pane' id='useraccess-edit'>
												<div class="pane-content">
													<div class="table-wrapper">
														<table class='table table-condensed mytable table-useraccess'  id='rcv-summary'>
															<thead>
																<tr>
																	<th class='width-xs'></th>
																	<th>MENU</th>
																	<th>SECTION</th>
																	<th>VIEW</th>
																	<th>ADD</th>
																	<th>DELETE</th>
																	<th>EDIT</th>
																	<th>UPLOAD</th>
																	<th>RESET PASSWORD</th>
																	<th>PRINT</th>
																	<th>ASSIGN</th>
																	<th>REVISE</th>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td class='text-center'><input type='checkbox' class='useraccess section' data-section='dashboard' value='#dash' checked='checked' disabled='disabled'></td>
																	<td>Dashboard</td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='system'>
																	<td rowspan='5' class='text-center'><input type='checkbox' class='useraccess section' data-section='system' value='#system-section'></td>
																	<td rowspan='5'>System</td>
																	<td>Configuration</td>
																	<td class='text-center'><input type='checkbox' class='useraccess view' value='#configuration'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='system'>
																	<td>User</td>
																	<td class='text-center'><input type='checkbox' class='useraccess view' value='#user'></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='#adduserbtn'></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='#deleteuserbtn'></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='.edituserbtn'></td>
																	<td></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='.resetpassword'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='system'>
																	<td>User Group</td>
																	<td class='text-center'><input type='checkbox' class='useraccess view' value='#usergroup'></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='#addusergroupbtn'></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='#deleteusergroupbtn'></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='.editusergroupbtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='system'>
																	<td>Approver</td>
																	<td class='text-center'><input type='checkbox' class='useraccess view' value='#approver'></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='#addapproverbtn'></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='#deleteapproverbtn'></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='.editapproverbtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='system'>
																	<td>Transaction Type</td>
																	<td class='text-center'><input type='checkbox' class='useraccess view' value='#transaction'></td>
																	<td></td>
																	<td></td>
																	<td class='text-center'><input type='checkbox' class='useraccess' value='.edittranstypebtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='maintenance'>
																	<td rowspan='7' class='text-center'><input type='checkbox' class='useraccess section' data-section='maintenance' value='#maintenance-section'></td>
																	<td rowspan='7'>Maintenance</td>
																	<td>Item</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#items'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#additembtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#deleteitembtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.edititembtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#uploaditembtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='maintenance'>
																	<td>Supplier</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#supplier'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#addsupplierbtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#deletesupplierbtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.editsupplierbtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#uploadsupplierbtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='maintenance'>
																	<td>Section/Department</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#department'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#addsectionbtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#deletesectionbtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.editdepartmentbtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='maintenance'>
																	<td>Term of Payment</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#terms'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#addpaymenttermbtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#deletepaymenttermbtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.editpaymenttermbtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='maintenance'>
																	<td>Ship Via</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#shipvia'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#addshipviabtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#deleteshipviabtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.editshipviabtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='maintenance'>
																	<td>Mode of Shipment</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#modeofshipment'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#addmodeofshipmentbtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#deletemodeofshipmentbtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.editmodeofshipmentbtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='maintenance'>
																	<td>Classification Code</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#classificationcode'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#addclasscodebtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#deleteclasscodebtn'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.editclasscodebtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='transaction'>
																	<td rowspan='4' class='text-center'><input type='checkbox' class='useraccess section' data-section='transaction' value='#transaction-section'></td>
																	<td rowspan='4'>Transaction</td>
																	<td>Purchase Requisition</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#pr'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#addpr'></td>
																	<td></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.editpurchaserequisition'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='transaction'>
																	<td>Purchase Order</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#po'></td>
																	<td></td>
																	<td></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.editpurchaseorder'></td>
																	<td></td>
																	<td></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.printpurchaseorder'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.assignpurchaseorder'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='.revisepurchaseorder'></td>

																</tr>
																<tr class='transaction'>
																	<td>Receiving</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#receiving'></td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#receiveitemsbtn'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	
																</tr>
																<tr class='transaction'>
																	<td>Receiving-Hold Invoice</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#receiving-hold'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	
																</tr>
																<tr class='report'>
																	<td rowspan='3' class='text-center'><input type='checkbox' class='useraccess section' data-section='report' value='#reports-section'></td>
																	<td rowspan='3'>Reports</td>
																	<td>Purchase Requisition</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#prsumm'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr class='report'>
																	<td>Purchase Order</td>
																	<td class='text-center'><input class='useraccess' type='checkbox' value='#posumm'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>

																</tr>
																<tr class='report'>
																	<td>Receiving</td>
																	<td class='text-center'><input class='useraccess view' type='checkbox' value='#rcvsumm'></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	
																</tr>



															</tbody>							
														</table>	
													</div>
												</div>
											</div>
										</div>
									</div>
							
				</div>

			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn btn-blue2 mybtn modal-savebtn' id='useredit-savebtn'>Save</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn' id='useredit-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>

<script src="../resources/selectoption/chosen.jquery.js"></script>
<script src="../resources/selectoption/docsupport/prism.js"></script>

<script type="text/javascript">
	
	$('.modal-dialog').draggable();
	$('.chosen-select').chosen({ 
			no_results_text: "No results found for: ",
			width:'100%'
	});

	$('#userstable').DataTable({
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
		            "sAjaxSource": "Other/ajax/users.php",
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
		                        { mData: 'USERNAME' },
		                        { mData: 'DEPARTMENT' },
		                        { mData: 'EMAIL'},
		                        { mData: 'USERGROUP' },
		                        { mData: 'DATE CREATED' },
		                        { mData: 'CREATED BY' },
		                        { mData: 'LAST MODIFIED' },
		                        { mData: 'MODIFIED BY' }
		                        ]
	});
	userAccess();
</script>