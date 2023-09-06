<?php
	//ITEM PRICE SCHEDULE VIEW
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include('../../config/functions.php');
    include("../../config/db-function.php");
?>


<div class="container-fluid">
    <div class="pageContent">
    		<p class='page-title'>Item Price Schedule</p>
			<div class='panel'>
				<div class="panel-content">

						<div class="panel panel-condensed panel-default">
							<div class="panel-content">
								<div class="float-left">
									<button class="btn btn-sm btn-default border-right" id='addpriceschedulebtn' data-toggle='modal' href='#addpriceschedulemodal'>
										<img src="../resources/img/Add_Symbol.png">Add New Price Schedule
									</button>
									<button class="btn btn-sm btn-default removebtn" id='deletepriceschedulebtn'>
										<img src="../resources/img/Error_Symbol.png">Delete Price Schedule
									</button>
								</div>
								<div class="form-group nomargin float-right">
									<div class='form-input-group'>
										<input type='text' class='form-input searchinput' placeholder='Search'>
										<button class='btn input-btn no-border'><img class='img' src="../resources/img/Search.png"></button>
									</div>
								</div>

							</div>
						</div>
						<div class='table-wrapper' style='height:200px'>
									<table class='table table-extend' cellspacing='0' id='priceschedtable'>
										<thead>
											<tr>
												<th><input type='checkbox' class='selectall'></th>
												<th></th>
												<th>TRANSACTION NO.</th>
												<th>SUPPLIER</th>
												<th>REFERENCE NO.</th>
												<th>TRANSACTION DATE</th>
											</tr>
										</thead>
										<tbody>
											<tr class='noresult hidden'><td colspan='13'>No Results Found</td></tr>
											<?php 
												$query = "select transaction_number,
															 	 date_created,
															 	 effectivity_date_start,
															 	 effectivity_date_end,
															 	 supplier_id,
															 	 reference_number,
															 	 supplier_name,
															 	 item_price_schedule_header.created_by_user_id,
																 item_price_schedule_header.created_date,
																 item_price_schedule_header.updated_by_user_id,
																 item_price_schedule_header.updated_date
														  from item_price_schedule_header, supplier
														  where supplier_id=supplier.id
														  ";
												$rs = query($query);
												while($obj=fetch($rs)){
													$id=$obj->transaction_number;
													$supplier=$obj->supplier_name;
													$suppid=$obj->supplier_id;
													$ref=$obj->reference_number;
													$date=$obj->date_created;
													$start=$obj->effectivity_date_start;
													$end=$obj->effectivity_date_end;

													$createdby = $obj->created_by_user_id;
													$createdby = getUserName($createdby);
													$createddate = $obj->created_date;
													$updatedby = $obj->updated_by_user_id;
													$updatedby = getUserName($updatedby);
													$updateddate = $obj->updated_date;	

													echo "<tr class='tablerow' id='$id' supp='$supplier' ref='$ref' start='$start' end='$end' cby='$createdby' cdate='$createddate' uby='$updatedby' udate='$updateddate'>
															<td><input type='checkbox' class='itemcheckbox' rowid='$id'></td>
															<td class='text-center'>
																<img transnum='$id' class='nopadding editpaymenttermbtn' src='../resources/img/File_Edit.png' title='Edit User Group' data-toggle='modal' href='#editpaymenttermsmodal'>
															</td>
															<td class='clickable'>$id</td>
															<td class='clickable'>$supplier</td>
															<td class='clickable'>$ref</td>
															<td class='clickable'>$date</td>
															
														  </tr>";
												}
											?>
										</tbody>
									</table>
						</div>

				</div>
			</div>
			<!-- TRANSACTION ITEMS -->
			<div class='tabpane-white' style='margin-top:20px;' id='transactiondetails'>
						<ul class='nav-tabs'>
							<li class='active' data-pane='#lineitems'>Transaction Details</li>
						</ul>
						<div class='tab-panes'>
							<div class='pane active' id='lineitems'>
								<div class="pane-content">

									<div class="panel">
										<div class="panel-content">
											<table class='table-structure'>
												<tr>
													<td>
														<div class='form-group'>
															<label class='label'>Transaction No.</label>
															<input type='text' class='form-input form-input-md' id='transactionnum' readonly>
														</div>
														<div class='form-group'>
															<label class='label'>Supplier</label>
															<input type='text' class='form-input form-input-lg' id='supplier' readonly>
														</div>
														<div class='form-group'>
															<label class='label'>Reference No.</label>
															<input type='text' class='form-input form-input-sm' id='referencenum' readonly>
														</div>
														<div class='form-group'>
															<label class='label'>Effectivity Date Start</label>
															<input type='date' class='form-input form-input-sm' id='startdate' readonly>
														</div>
														<div class='form-group'>
															<label class='label'>Effectivity Date End</label>
															<input type='date' class='form-input form-input-sm' id='enddate' readonly>
														</div>
													<td>
													<td class="gap"></td>
													<td>
														<div class='form-group'>
															<label class='label'>Created By</label>
															<input type='text' class='form-input' id='createdby' readonly>
														</div>
														<div class='form-group'>
															<label class='label'>Date Created</label>
															<input type='text' class='form-input form-input-sm' id='datecreated' readonly>
														</div>
														<div class='form-group'>
															<label class='label'>Modified By</label>
															<input type='text' class='form-input ' id='modifiedby' readonly>
														</div>
														<div class='form-group nomargin'>
															<label class='label'>Last Modified</label>
															<input type='text' class='form-input form-input-sm' id='datemodified' readonly>
														</div>
													</td>
												</tr>
											</table>
										</div>
									</div>
									
									<div class='tablewithbuttons'>
										<div class='table-wrapper' style='height:200px'>
											<table class='table table-extend' cellspacing='0'>
												<thead>
													<tr>
														<th></th>
														<th>LINE NO.</th>
														<th>CODE</th>
														<th>DESCRIPTION</th>
														<th>UNIT PRICE</th>
														<th>REMARKS</th>
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
			<!-- TRANSACTION ITEMS - END -->


	</div>
</div>

<!-- MODAL ADD PRICE SHCEDULE-->
<div class="modal fade" id="addpriceschedulemodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					<img src="../resources/img/Add_Symbol.png">New Price Schedule
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<table class='table-structure'>
								<tr class="space"></tr>
								<tr>
									<td>
										<div class='modal-body-info'>
											<span class='title'>New Price Schedule</span><br>
											To add a new price schedule, please select a supplier.
										</div>
									</td>
									<td>
										<div class="errordiv"></div>
										<div class="form-group">
											<label class='label'>Supplier</label>
											<select name='defaultsupplier' class='form-input form-input-md supplier'>
												<option value=''>-</option>
												<?php
													$query='select * from supplier';
													$rs = query($query);
													while($obj=fetch($rs)){
														$id = $obj->id;
														$name = $obj->supplier_name;
														echo "<option value='$id'>$name</option>";
													}
												?>
											</select>
										</div>		
									</td>
								</tr>
								<tr class="space"></tr>
					</table>  
			</div>
			<div class="modal-footer">
					<div class='panel nomargin'>
								<div class="panel-content text-center">
									<button class='btn btn-white modal-nextbtn' id='newpriceschedcreate-nextbtn'>Next</button>
									<button class='btn btn-white modal-cancelbtn' id='newpriceschedcreate-cancelbtn'>Cancel</button>
								</div>
					</div>   
			</div>
		</div>
	</div>  
</div>
<!-- END ADD PRICE SCHEDULE MODAL-->

