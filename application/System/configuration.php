<?php
	include("../../config/connection.php");
	include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
	include("../../config/functions.php");
?>
<div class='header-page' >
	<div class='header-page-inner'>
		Configuration
	</div>
</div>
<div class="container-fluid">
    <div class="pageContent">
			<div class='panel panel-primary mypanel'>
				<div class="panel-body">
					<div class="table-wrapper">
						<table class='table mytable table-striped table-condensed'  id='rcv-summary'>
							<thead>
								<tr>
									<th></th>
									<th>Settings</th>
									<th class='text-center'>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td></td>
									<td>
										Email Notification
									</td>
									<td class='text-center'>
										<button class='btn btn-blue2 mybtn btn-xs' data-toggle='modal' href='#email-notification-settings'>
											<i class='fa fa-edit fa-lg fa-space'></i>Edit
										</button>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										Purchase Order Approval
									</td>
									<td class='text-center'>
										<button class='btn btn-blue2 mybtn btn-xs' data-toggle='modal' href='#po-approval-settings'>
											<i class='fa fa-edit fa-lg fa-space'></i>Edit
										</button>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										PO Approval Assignment
									</td>
									<td class='text-center'>
										<button class='btn btn-blue2 mybtn btn-xs' data-toggle='modal' href='#po-approval-assignment'>
											<i class='fa fa-edit fa-lg fa-space'></i>Edit
										</button>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										PR Approval Assignment
									</td>
									<td class='text-center'>
										<button class='btn btn-blue2 mybtn btn-xs' data-toggle='modal' href='#pr-approval-assignment'>
											<i class='fa fa-edit fa-lg fa-space'></i>Edit
										</button>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										PO Printing Alignment
									</td>
									<td class='text-center'>
										<button class='btn btn-blue2 mybtn btn-xs' data-toggle='modal' href='#po-printing-alignment'>
											<i class='fa fa-edit fa-lg fa-space'></i>Edit
										</button>
									</td>
								</tr>
								
							</tbody>							
						</table>	
					</div>
				</div>
			</div>
	</div>
</div>
<div class="modal fade" id="email-notification-settings">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Email Notification
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-md-4">
					<div class='modal-body-info'>
						<span class='title'>Email Setup</span><br>
						Please provide the following information to setup email notification.
					</div>
				</div>
				<div class="col-md-8">
					<?php
									$rs = query("select * from email_settings where id='1'");
									$host = '';
									$port = '';
									$uname='';
									$pword='';
									$from = '';
									$sender = '';
									$encryption = '';
									while($obj=fetch($rs)){
										$host = $obj->host;
										$uname = $obj->username;
										$pword = $obj->password;
										$port = $obj->port;
										$from = $obj->email_sent_from;
										$sender = $obj->email_sender;
										$encryption = $obj->encryption;
									}
					?>
					<form class='form-horizontal'>
						<div class="form-group">
							<div class="errordiv"></div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-md-4">Host</label>
							<div class="col-md-8">
								<input type='text' class='form-input form-control host' value="<?php echo $host; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">TCP Port</label>
							<div class="col-md-8">
								<input type='number' class='form-input form-control tcpport' value="<?php echo $port; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">TLS Encryption</label>
							<div class="col-md-8">
								<input type='text' class='form-input form-control encryption' value="<?php echo $encryption; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">SMTP Username</label>
							<div class="col-md-8">
								<input type='text' class='form-input form-control smtpusername' value="<?php echo $uname; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">SMTP Password</label>
							<div class="col-md-8">
								<input type='password' class='form-input form-control smtppassword' value="<?php echo $pword; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">Email Sender</label>
							<div class="col-md-8">
								<input type='text' class='form-input form-control emailsender' value="<?php echo $sender; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">Email Sent From</label>
							<div class="col-md-8">
								<input type='text' class='form-input form-control emailsentfrom' value="<?php echo $from; ?>">
							</div>
						</div>

					</form>
				</div>                                              
			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn btn-blue2 mybtn' id='emailnotification-savebtn'>Save</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn' id='emailnotification-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="po-approval-settings">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Purchase Order Approval
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-md-4">
					<div class='modal-body-info'>
											<span class='title'>Approvers Setup</span><br>
											Please select approvers for purchase order approval.
					</div>
				</div>
				<div class="col-md-8">
					<?php 
									$query = "select * from purchase_order_configuration";
									$rs = query($query);
									$purchasinghead = '';
									$fapprover ='';
									$sapprover='';
									$divpprover=''; 
									$lapprover = '';
									while($obj=fetch($rs)){
										$purchasinghead = $obj->purchasing_head;
										$fapprover = $obj->first_approver;
										$sapprover = $obj->second_approver;
										$divpprover = $obj->division_approver; 
										$lapprover = $obj->third_approver;
										$agm = $obj->assistant_manager;
										$gm = $obj->general_manager;
										$pr = $obj->president;
									}
					?>
					<form class="form-horizontal">
						<div class="form-group">
							<div class="errordiv"></div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">Purchasing Head</label>
							<div class="col-md-8">
								<select class='form-input form-control purchasingsectionhead'>
									<option value='NULL'>-</option>
									<?php 
										// $query = "select * from user where active_flag='1'";
										$query = "select  user.id,
																	  first_name,
																	  middle_name,
																	  last_name
															  from approver, user 
															  where approver.user_id = user.id and
															  		approval_type_id='8' and 
															  		approver.active_flag='1'"; // 04222024 by nessa
										$rs = query($query);
										while($obj=fetch($rs)){
											if($obj->id==$purchasinghead){
												echo "<option value='$obj->id' selected>$obj->first_name $obj->middle_name $obj->last_name</option>";
											}
											else{
												echo "<option value='$obj->id'>$obj->first_name $obj->middle_name $obj->last_name</option>";
											}

										}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<!-- <label class="control-label col-md-4">Logistics General Manager Approval</label> -->
							<label class="control-label col-md-4">Section Head Approval</label>
							<div class="col-md-8">
								<select class='form-input form-control pofirstapprover'>
												<option value='NULL'>-</option>
												<?php 
													$query = "select  user.id,
																	  first_name,
																	  middle_name,
																	  last_name
															  from approver, user 
															  where approver.user_id = user.id and
															  		approval_type_id='8' and 
															  		approver.active_flag='1'"; 
													$rs = query($query);
													while($obj=fetch($rs)){
														if($obj->id==$fapprover){
															echo "<option value='$obj->id' selected>$obj->first_name $obj->middle_name $obj->last_name</option>";
														}
														else{
															echo "<option value='$obj->id'>$obj->first_name $obj->middle_name $obj->last_name</option>";
														}
														
													}
												?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<!-- <label class="control-label col-md-4">General Manager Approval</label> -->
							<label class="control-label col-md-4">Department Head Approval</label>
							<div class="col-md-8">
								<select class='form-input form-control posecondapprover'>
												<option value='NULL'>-</option>
												<?php 
													$query = "select  user.id,
																	  first_name,
																	  middle_name,
																	  last_name
															  from approver, user 
															  where approver.user_id = user.id and
															  		approval_type_id='8' and 
															  		approver.active_flag='1'";
													$rs = query($query);
													while($obj=fetch($rs)){
														if($obj->id==$sapprover){
															echo "<option value='$obj->id' selected>$obj->first_name $obj->middle_name $obj->last_name</option>";
														}
														else{
															echo "<option value='$obj->id'>$obj->first_name $obj->middle_name $obj->last_name</option>";
														}
														
													}
												?>
											</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">Division Head Approval</label>
							<div class="col-md-8">
								<select class='form-input form-control podivapprover'>
												<option value='NULL'>-</option>
												<?php 
													$query = "select  user.id,
																	  first_name,
																	  middle_name,
																	  last_name
															  from approver, user 
															  where approver.user_id = user.id and
															  		approval_type_id='8' and 
															  		approver.active_flag='1'";
													$rs = query($query);
													while($obj=fetch($rs)){
														if($obj->id==$divpprover){
															echo "<option value='$obj->id' selected>$obj->first_name $obj->middle_name $obj->last_name</option>";
														}
														else{
															echo "<option value='$obj->id'>$obj->first_name $obj->middle_name $obj->last_name</option>";
														}
														
													}
												?>
											</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">President Approval</label>
							<div class="col-md-8">
								<select class='form-input form-control polastapprover'>
												<option value='NULL'>-</option>
												<?php 
													$query = "select  user.id,
																	  first_name,
																	  middle_name,
																	  last_name
															  from approver, user 
															  where approver.user_id = user.id and
															  		approval_type_id='6' and 
															  		approver.active_flag='1'";
													$rs = query($query);
													while($obj=fetch($rs)){
														if($obj->id==$lapprover){
															echo "<option value='$obj->id' selected>$obj->first_name $obj->middle_name $obj->last_name</option>";
														}
														else{
															echo "<option value='$obj->id'>$obj->first_name $obj->middle_name $obj->last_name</option>";
														}
														
													}
												?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-4">

							PO Signatories
							</div><hr>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">Logistics General Manager</label>
							<div class="col-md-8">
								<select class='form-input form-control asstmng'>
												<option value='NULL'>-</option>
												<?php 
													// $query = "select  user.id,
													// 				  first_name,
													// 				  middle_name,
													// 				  last_name
													// 		  from  user 
													// 		  where user.active_flag='1'"; 
												$query = "select  user.id,
																	  first_name,
																	  middle_name,
																	  last_name
															  from approver, user 
															  where approver.user_id = user.id and
															  		approval_type_id='8' and 
															  		approver.active_flag='1'"; // 04222024 by nessa
													$rs = query($query);
													while($obj=fetch($rs)){
														if($obj->id==$agm){
															echo "<option value='$obj->id' selected>$obj->first_name $obj->middle_name $obj->last_name</option>";
														}
														else{
															echo "<option value='$obj->id'>$obj->first_name $obj->middle_name $obj->last_name</option>";
														}
														
													}
												?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">General Manager</label>
							<div class="col-md-8">
								<select class='form-input form-control genmng'>
												<option value='NULL'>-</option>
												<?php 
													$query = "select DISTINCT (a.user_id) as id, b.first_name, b.middle_name, b.last_name 
														from approver a 
														INNER JOIN user b ON b.id = a.user_id
														where a.approval_type_id='4' and a.active_flag='1'"; 
													// $query = "select  user.id,
													// 				  first_name,
													// 				  middle_name,
													// 				  last_name
													// 		  from approver, user 
													// 		  where approver.user_id = user.id and
													// 		  		approval_type_id='4' and 
													// 		  		approver.active_flag='1'"; // 04222024 by nessa
															  				  
													$rs = query($query);
													while($obj=fetch($rs)){
														if($obj->id==$gm){
															echo "<option value='$obj->id' selected>$obj->first_name $obj->middle_name $obj->last_name</option>";
														}
														else{
															echo "<option value='$obj->id'>$obj->first_name $obj->middle_name $obj->last_name</option>";
														}
														
													}
												?>
											</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">President</label>
							<div class="col-md-8">
								<select class='form-input form-control pres'>
												<option value='NULL'>-</option>
												<?php 
													// $query = "select  user.id,
													// 				  first_name,
													// 				  middle_name,
													// 				  last_name
													// 		  from  user 
													// 		  where user.active_flag='1'"; 
													$query = "select  user.id,
																	  first_name,
																	  middle_name,
																	  last_name
															  from approver, user 
															  where approver.user_id = user.id and
															  		approval_type_id='6' and 
															  		approver.active_flag='1'"; // 04222024 by nessa		  
													$rs = query($query);
													while($obj=fetch($rs)){
														if($obj->id==$pr){
															echo "<option value='$obj->id' selected>$obj->first_name $obj->middle_name $obj->last_name</option>";
														}
														else{
															echo "<option value='$obj->id'>$obj->first_name $obj->middle_name $obj->last_name</option>";
														}
														
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
									<button class='btn btn-blue2 mybtn'  id='poapprovalsettings-savebtn'>Save</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn' id='poapprovalsettings-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>


<div class="modal fade" id="pr-approval-assignment">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					PR Approval Assignment
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-md-4">
					<div class='modal-body-info'>
											<span class='title'>Approvers Setup</span><br>
											Please select approvers for purchase requisition approval.
					</div>
				</div>
				<div class="col-md-8">
					<form class="form-horizontal">
						<div class="form-group">
							<div class="errordiv"></div>
						</div>

						<div class="form-group">
								<label class="control-label col-md-4">Approval Type</label>
								<div class="col-md-8">
									<select class='form-input form-control prapprovaltype'>
													<option value='NULL'>-</option>
													<option value='1'>Section Head 1</option>
													<option value='2'>Section Head 2</option>
													<option value='3'>General Manager</option>
													<option value='4'>Assistant Vice President</option>
													<option value='5'>Senior Vice President</option>
													<option value='6'>President</option>
													<option value='7'>Conformance</option>
									</select>
								</div>
						</div>
						<div class="form-group">
								<label class="control-label col-md-4">Old Approver</label>
								<div class="col-md-8">
									<select class='form-input form-control prevapprover'>
													<option value='NULL'>-</option>
													<?php 
														$query = "select  distinct user.id,
																		  first_name,
																		  middle_name,
																		  last_name
																  from approver, user 
																  where approver.user_id = user.id and
																  		approval_type_id!='8'";
														$rs = query($query);
														while($obj=fetch($rs)){
																echo "<option value='$obj->id'>$obj->first_name $obj->middle_name $obj->last_name</option>";
														}
													?>
									</select>
								</div>
						</div>
						<div class="form-group">
								<label class="control-label col-md-4">New Approver</label>
								<div class="col-md-8">
									<select class='form-input form-control assignprto'>
													<option value='NULL'>-</option>
													<?php 
														$query = "select  distinct user.id,
																		  first_name,
																		  middle_name,
																		  last_name
																  from approver, user 
																  where approver.user_id = user.id and
																  		approval_type_id!='8' and 
																  		approver.active_flag='1'";
														$rs = query($query);
														while($obj=fetch($rs)){
																echo "<option value='$obj->id'>$obj->first_name $obj->middle_name $obj->last_name</option>";
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
									<button class='btn btn-blue2 mybtn' id='prapprovalsettings-savebtn'>Save</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn' id='prapprovalsettings-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>


<div class="modal fade" id="po-approval-assignment">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					PO Approval Assignment
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-md-4">
					<div class='modal-body-info'>
											<span class='title'>Approvers Setup</span><br>
											Please select approvers for purchase order approval.
					</div>
				</div>
				<div class="col-md-8">
					<form class="form-horizontal">
						<div class="form-group">
							<div class="errordiv"></div>
						</div>

						<div class="form-group">
								<label class="control-label col-md-4">Approval Type</label>
								<div class="col-md-8">
									<select class='form-input form-control poapprovaltype'>
													<option value='NULL'>-</option>
													<!-- <option value='1'>Assistant Manager</option>
													<option value='2'>General Manager</option> -->

													<option value='1'>Section Head</option>
													<option value='2'>Department Head</option>
													<option value='4'>Division Head</option>
													<option value='3'>President</option>

									</select>
								</div>
						</div>
						<div class="form-group">
								<label class="control-label col-md-4">Old Approver</label>
								<div class="col-md-8">
									<select class='form-input form-control prevpoapprover'>
													<option value='NULL'>-</option>
													<?php 
														$query = "select  distinct user.id,
																		  first_name,
																		  middle_name,
																		  last_name
																  from approver, user 
																  where approver.user_id = user.id and
																  		approval_type_id='8' and 
																  		approver.active_flag='1'";
														$rs = query($query);
														while($obj=fetch($rs)){
																echo "<option value='$obj->id'>$obj->first_name $obj->middle_name $obj->last_name</option>";
														}
													?>
									</select>
								</div>
						</div>
						<div class="form-group">
								<label class="control-label col-md-4">New Approver</label>
								<div class="col-md-8">
									<select class='form-input form-control assignpoto'>
													<option value='NULL'>-</option>
													<?php 
														$query = "select  distinct user.id,
																		  first_name,
																		  middle_name,
																		  last_name
																  from approver, user 
																  where approver.user_id = user.id and
																  		approval_type_id='8' and 
																  		approver.active_flag='1'";
														$rs = query($query);
														while($obj=fetch($rs)){
																echo "<option value='$obj->id'>$obj->first_name $obj->middle_name $obj->last_name</option>";
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
									<button class='btn btn-blue2 mybtn' id='poapprovalassignment-savebtn'>Save</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn' id='poapprovalassignment-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="po-printing-alignment">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					PO Printing Alignment
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-md-4">
					<div class='modal-body-info'>
							Adjust top margin for proper printing alignment.
					</div>
				</div>
				<div class="col-md-8">
					<form class="form-horizontal">
						<div class="form-group">
							<div class="errordiv"></div>
						</div>
						<div class="form-group">
								<label class="control-label col-md-4">Top Margin</label>
								<div class="col-md-8">
									<?php
										$margintop = getInfo("purchase_order_configuration","po_printing_alignment","where id='1'");
									?>
									<input type='number' class='topmargin form-control' value="<?php echo $margintop; ?>">
								</div>
						</div>
					</form>	
				</div>                                             
			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn btn-blue2 mybtn' id='poprinting-savebtn'>Save</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn' id='poprinting-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>

<script type="text/javascript">
	$('.modal-dialog').draggable();
</script>