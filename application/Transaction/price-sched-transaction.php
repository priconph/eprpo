<?php
	//CREATION OF NEW ITEM PRICE SCHEDLE PAGE
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");

    $suppid = isset($_GET['suppId'])&&isset($_GET['dkjfhskjnkjoi'])?$_GET['suppId']:'';
    if($suppid!=''){
    	if($_GET['dkjfhskjnkjoi']=='dsfsdfewodjs'){
    		$query="select * from supplier where id='$suppid'";
    		$rs = mysql_query($query);
    		while($obj=mysql_fetch_object($rs)){
    			$suppliername=ucwords(strtolower($obj->supplier_name));
    		}
    	}
    	else{
	    	die('DIRECT ACCESS NOT ALLOWED');
	    }
    }
    else{
    	die('DIRECT ACCESS NOT ALLOWED');
    }
?>


<div class="container-fluid">
    <div class="pageContent">
    		<p class='page-title'>New Item Price Schedule</p>
			<div class='panel'>
				<div class="panel-content">

					<div class="panel" id='generalinformationpanel'>
						<div class="panel-content">
							<table class='table-structure'>

										<tr>
											<td>
												
												<div class="form-group">
													<label class='label'>Transaction Number</label>
													<input type='text' name='transactionnum' class='form-input transactionnumber disabled'>
												</div>
												<div class="form-group">
													<label class='label'>Supplier</label>
													<input type='text' class='form-input form-input-lg supplier disabled capitalize' suppid="<?php echo $suppid; ?>" value="<?php echo $suppliername; ?>" readonly>
												</div>
												<div class="form-group">
													<label class='label'>Reference Number</label>
													<input type='text' name='shortdesc' class='form-input form-input-sm referencenum' >
												</div>
												<div class="form-group">
													<label class='label align-top'>Remarks</label>
													<textarea class='form-input form-input-lg remarks' rows='3'></textarea>
												</div>
			
											</td>
											<td class="gap"></td>
											<td>
												<div class="form-group">
													<label class='label'>Date Created</label>
													<input type='date' name='prdate' class='form-input form-input-sm datecreated lowercase' value="<?php echo date('Y-m-d'); ?>">
												</div>
												<div class="form-group">
													<label class='label'> Effectivity Date Start</label>
													<input type='date' name='prdate' class='form-input form-input-sm datestart lowercase' value="<?php echo date('Y-m-d'); ?>">
												</div>
												<div class="form-group">
													<label class='label'>Effectivity Date End</label>
													<input type='date' name='prdate' class='form-input form-input-sm dateend lowercase' value="<?php echo date('Y-m-d'); ?>">
												</div>
											</td>

										</tr>
									
							</table> 
						</div>
					</div>  
					<br>
					<!-- LINE ITEM SECTION -->
					<div class=''>
						<ul class='nav-tabs'>
							<li class='active' data-pane='#lineitems'>Line Items</li>
						</ul>
						<div class='tab-panes'>
							<div class='pane active' id='lineitems'>
								<div class="pane-content">
									<div class='tablewithbuttons'>
										<div class='table-wrapper' style='height:200px'>
											<table class='table table-extend withitemnumber' cellspacing='0'>
												<thead>
													<tr>
														<th><input type='checkbox' class='selectall'></th>
														<th>ITEM NO.</th>
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
										<div class='panel no-border panel-condensed bg-transparent'>
											<div class='panel-content text-center'>
												<div style='display:inline-block'>
													<button class='btn btn-gray' id='triggeritemmodal-addbtn' data-toggle='modal' href='#itemlistmodal'><img src="../resources/set2/Add_Symbol.png">Add Item(s)</button>
													<button class='btn btn-gray' id='removefromcart-removebtn'><img src="../resources/set2/Error_Symbol.png">Remove Item(s)</button>
												</div>
											</div>
										</div>
										
									</div>
								</div>	
							</div>
						</div>
					</div>
					<!-- LINE ITEM SECTION - END -->
					<br>
					<!-- BUTTONS SECTION -->
					<div class='panel'>
						<div class="panel-content text-center">
							<button class='btn btn-white' id='priceschedule-savebtn'><img src="../resources/basic/save_32.png">Save</button>
							<button class='btn btn-white' id='priceschedule-cancelbtn'><img src="../resources/basic/block_32.png">Cancel</button>
						</div>
					</div>
					<!-- BUTTONS SECTION - END -->

				</div>
			</div>
	</div>
</div>

<!-- MODAL ADD APPROVER-->
<div class="modal fade" id="itemlistmodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					<img src="../resources/set2/Search.png">Items
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
						<div class="errordiv"></div>
						<div class='table-wrapper'>
									<table class='table table-extend withitemnumber' cellspacing='0'>
										<thead>
											<tr>
												<th><input type='checkbox' class='selectall'></th>
												<th>UNIT PRICE</th>
												<th>ID</th>
												<th>ITEM CODE</th>
												<th>SHORT DESCRIPTION</th>
												<th>LONG DESCRIPTION</th>
												<th>REMARKS</th>
											</tr>
										</thead>
										<tbody id='itemlist'>
											<tr class='noresult hidden'><td colspan='9'>No Results Found</td></tr>
											<?php
												$query = "select * from item
														  where default_supplier_id=$suppid	and 
														  		active_flag='1'";
												$rs = mysql_query($query);
											
												while($obj=mysql_fetch_object($rs)){
													$id = $obj->id;
													$itemcode = $obj->item_code;
													$shortdesc = $obj->short_description;
													$longdesc = $obj->long_description;							

													echo "<tr class='tablerow'>
															<td><input type='checkbox' class='itemcheckbox' itemid='$id' code='$itemcode' desc='$shortdesc'></td>
															<td><input type='number' step='.01' class='form-input form-input-xs unitprice'></td>
															<td>$id</td>
															<td>$itemcode</td>
															<td>$shortdesc</td>
															<td>$longdesc</td>
															<td class='text-center'><input type='text' class='form-input form-input-md itemremarks'></td>
														</tr>";	
												}
											?>
										</tbody>
									</table>
						</div>
						<br>
						<div class='panel'>
							<div class="panel-content text-center">
								<button class='btn btn-white' id='itemlist-addbtn'><img src="../resources/set2/Add_Symbol.png">Add</button>
								<button class='btn btn-white' id='itemlist-cancelbtn'><img src="../resources/basic/block_32.png">Cancel</button>
							</div>
						</div>
			</div>
		</div>
	</div>
</div>
