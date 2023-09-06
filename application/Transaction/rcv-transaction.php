<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
    include('../../config/functions.php');
	$supplierid = isset($_GET['kdekdfoKJhej2'])?$_GET['kdekdfoKJhej2']:'';
	if($supplierid==''){
		exit('Direct Access Not Allowed');
	}
	else{
	 	$query = "select * from supplier where id='$supplierid'";
	 	$rs = query($query);
	 	while($obj=fetch($rs)){
	 		$suppliername = strUtfEncode($obj->supplier_name);

	 	}

    }
?>
<div class='header-page' >
	<div class='header-page-inner'>
		Receiving - Non-Purchase Order
	</div>
</div>
<div class="container-fluid">
	<div class="pageContent">

		<div class="panel panel-primary mypanel">
    			<div class="panel-body">
    				<div class='tabpane-white margin-top-20 margin-bottom-10' id='generalinfosection'>
						<ul class="nav nav-tabs">
							  <li role="presentation" class="active" data-pane='#rcv-geninfo'><a>General Information</a></li>
						</ul>
					</div>
					<div class='tab-panes'>
						<div class='pane active' id='rcv-geninfo'>
							<div class="pane-content" style='padding-bottom:10px'>
								<form class="form-horizontal">
									<div class="col-md-12">
										<div class="errordiv"></div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-sm-4">Supplier</label>
											<div class="col-sm-8">
												<input type='text' class='form-input form-control disabled supplier' suppid='<?php echo $supplierid; ?>' value="<?php echo $suppliername; ?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Reference</label>
											<div class="col-sm-8">
												<input type='text' class='form-input form-control reference'>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Currency</label>
											<div class="col-sm-8">
												<select name='type' class='form-input form-control currency'>
													<option value=''>-</option>
													<?php 
														$query = "select * from currency";
														$rs = query($query);
														while($obj=fetch($rs)){
															$id = $obj->id;
															$code = $obj->currency_code;
															echo "<option value='$id'>$code</option>";
														}
													?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Carrier</label>
											<div class="col-sm-8">
												<input type='text' class='form-input form-control carrier'>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										
										<div class="form-group">
											<label class="control-label col-sm-4">Date Received</label>
											<div class="col-sm-8">
												<input type='date' class='form-input form-control datereceived' value="<?php echo date('Y-m-d'); ?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Actual Delivery Date</label>
											<div class="col-sm-8">
												<input type='text' class='form-input form-control actualdatedelivered'>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Received by</label>
											<div class="col-sm-8">
												<input type='text' class='form-input form-control dateinput receivedby' value="<?php echo getUserName($userid); ?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Remarks</label>
											<div class="col-sm-8">
												<textarea rows='3' id='remarks' class='form-input form-control remarks'></textarea>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
    			</div>
    	</div>

    	<div class="panel panel-primary mypanel">
    			<div class="panel-body">
    				<div class='tabpane-white margin-top-20 margin-bottom-10'>
						<ul class="nav nav-tabs">
							  <li role="presentation" class="active" data-pane='#rcvlineitems'><a>Line Items</a></li>
						</ul>
					</div>
					<div class='tab-panes'>
						<div class='pane active' id='rcvlineitems'>
							<div class="pane-content" style='padding-bottom:10px'>
								<div class='tablewithbuttons'>
											<div class='table-wrapper' style='height:200px'>
												<div class="table-responsive">
													<table class='table table-striped table-condensed mytable' cellspacing='0'>
														<thead>
															<tr>
																<th><input type='checkbox' class='selectall'></th>
																<th class='text-center'>LINE</th>
																<th class='text-nowrap'>ITEM CODE</th>
																<th>NAME</th>
																<th>DESCRIPTION</th>
																<th class='text-right'>QUANTITY</th>
																<th class='text-center'>UOM</th>
																<th class='text-right text-nowrap'>UNIT PRICE</th>
																
																<th class='text-right'>AMOUNT</th>

															</tr>
														</thead>
														<tbody id='polineitems'>
															 
														</tbody>
													</table>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6" style='margin-top:10px;'>
								    				<button class='btn btn-blue2 mybtn' id='rcvnonpo-additembtn' data-toggle='modal' href='#additemmodal'>Add Item(s)</button>
													<button class='btn btn-blue2 mybtn' id='rcvnonpo-removeitembtn'>Remove Item(s)</button>
								    			</div>
								    			<div class="col-sm-6" style='margin-top:10px;'>
								    				<form class='form-horizontal'>
																<div class="form-group" >
																	<label class='control-label col-xs-5'>Total Amount</label> 
																	<div class="col-xs-7">
																		<div class="input-group">
																			<div class="input-group-addon" id='rcvnonpocurrency'></div>
																			<input type='text' class='form-control text-right' id='grandtotal' value='<?php echo 0; ?>' readonly>
																		</div>
																		
																	</div>
																</div>			
													</form>
								    			</div>
								    		</div>
										</div>
							</div>
						</div>
					</div>
    			</div>
    	</div>

    	<div class="panel panel-primary mypanel">
    		<div class="panel-body text-center">
    			<button class='btn btn-blue2 mybtn' id='rcvnonpo-submitbtn'>Submit</button>
				<button class='btn btn-blue2 mybtn' id='rcvnonpo-cancelbtn'>Back</button>
    		</div>
    	</div>



	</div>
</div>


<div class="modal fade" id="additemmodal">
	<div class="modal-dialog" style='width:650px'>
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Add an Item
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="modal-body">
					<div class="col-md-12">
						<form class="form-horizontal">
							<div class="form-group">
								<div class="errordiv"></div>
							</div>
							<div class="form-group">
									<label class='control-label col-md-4'>Item Code</label>
									<div class="col-md-8">
										<div class="input-group">
											<input type='text' class='form-input form-control itemcode' itemid='NEW'>
											<div class="input-group-addon button-addon itemlookupbtn" from='edit' data-toggle='modal' href='#itemlistmodal'>
												<i class='fa fa-search'></i>
											</div>
										</div>
									</div>
							</div>
							<div class="form-group">
									<label class='control-label col-md-4'>Item Name</label>
									<div class="col-md-8">
										<input type='text' class='form-input form-control itemname'>
									</div>
							</div>
							<div class="form-group">
									<label class='control-label col-md-4'>Description</label>
									<div class="col-md-8">
										<textarea rows='4' class='form-input form-control itemdesc'></textarea>
									</div>
							</div>
							<div class="form-group">
									<label class='control-label col-md-4'>Quantity</label>
									<div class="col-md-8">
										<input type='number' step='1' class='form-input form-control quantity'>
									</div>
							</div>
							<div class="form-group">
									<label class='control-label col-md-4'>Unit Price</label>
									<div class="col-md-8">
										<input type='number' step='.01' class='form-input form-control unitprice'>
									</div>
							</div>
							<div class="form-group">
									<label class='control-label col-md-4'>Unit of Measure</label>
									<div class="col-md-8">
										<select name='uom' class='form-input form-control uom'>
												<option value='NULL'>-</option>
												<?php
													$query='select * from unit_of_measure';
													$rs = query($query);
													while($obj=fetch($rs)){
														$id = $obj->id;
														$code = $obj->unit_of_measure_code;
														echo "<option value='$id'>$code</option>";
													}
												?>
											</select>
									</div>
							</div>
						</form>
					</div>
				</div>


			</div>
			<div class="modal-footer">
							<div class="text-center">
								<button class='btn btn-blue2 mybtn' id='additemmodal-addbtn'>Add</button>
								<button class='btn btn-blue2 mybtn modal-cancelbtn' id='additemmodal-cancelbtn'>Cancel</button>
							</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade lookupmodal" id="itemlistmodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Item List
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="tablewrapper-md">
					<table class='table table-striped table-condensed mytable table-bordered table-hover' id='itemtable'>
						<thead>
							<tr>
								<th>CODE</th>
								<th style='min-width:200px'>NAME</th>
								<th>DESCRIPTION</th>
								<th>SUPPLIER</th>
								<th class='text-nowrap'>UNIT PRICE</th>
								<th class='text-center'>CURRENCY</th>
								<th class='text-nowrap'>DATE CREATED</th>
								<th class='text-nowrap'>CREATED BY</th>
								<th class='text-nowrap'>LAST MODIFIED</th>
								<th class='text-nowrap'>UPDATED BY</th>
							</tr>


						</thead>
						<tbody  id='itemlist-line-data'>

						</tbody>
					</table>
				</div>		
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	/*$('#itempagination li').addClass('hidden');
	$('#itempagination li').slice(0,5).removeClass('hidden');
	$('#itemlist-line-data').load('Other/itemlist.php?oichf%jsl24=1'); //ITEMLIST */

	$('.modal-dialog').draggable();
	$('.actualdatedelivered').datepicker();

	var itemlistscript = "Other/ajax/itemlist.php";
	var itemtbl = $('#itemtable').DataTable({
		pagingType:"full_numbers",
		aaSorting: [[ 1, "asc" ]],
		columnDefs: [
		{
			targets: "column-nosort", 
			orderable: false,
		}
		],

		"columns": [


						{ data: 'item_code' } ,
						{ data: 'item_name' },
						{ data: 'long_description' },
						{ data: 'supplier_name' },
						{ data: 'unit_price'},
						{ data: 'currency_code'},
						{ data: 'created_date' },
						{ data: 'created_by' },
						{ data: 'updated_date' },
						{ data: 'updated_by' }
					],
		"processing": true,
		"serverSide": true,
		"ajax": {
			url: itemlistscript,
			type: 'POST'
		}
  });
</script>