<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
    include('../../config/functions.php');

    $po = (isset($_GET['Ksfrlwowp%kspej%Dhor']))?$_GET['Ksfrlwowp%kspej%Dhor']:'';
    if($po==''){
    	exit('DIRECT ACCESS NOT ALLOWED');
    }
    $query = "select * from purchase_order_header, supplier where order_number='$po' and supplier.id=supplier_id";
    $rs = query($query);
    while($obj=fetch($rs)){
    	$refreqnum = $obj->reference_requisition_number;
    	$prrevsuffix = appendPrRevSuffix($refreqnum);
    	$supplierId = $obj->supplier_id;
    	$orderdate = formatDate2($obj->order_date);
    	$suppliername=strUtfEncode($obj->supplier_name);
    	$supplieraddr = strUtfEncode($obj->supplier_address);
    	$expdelivery = formatDate3($obj->delivery_date);
    	$dateneeded = date('m/d/Y',strtotime($obj->expected_date));
    	$orderstatus=$obj->order_status;
    	$defaultterm = ($obj->terms==NULL)?'NULL':$obj->terms;
    	$attn = strUtfEncode($obj->supplier_contact);
    	$via = $obj->ship_via;
    	$mode = $obj->mode_of_shipment;
    	if($defaultterm==NULL){
    		$defaultterm = $obj->payment_term_id;
    		if($defaultterm==NULL){
    			$defaultterm='NULL';
    		}
    	}
    	$otherterms = $obj->other_terms;
    	$remarks = strUtfEncode($obj->po_remarks);
    	$currencyId=$obj->currency;
    	$currency = getCurrencyCode($obj->currency);


    }
    $withdiscrepancy = checkIfDiscrepancyExist($po);
	$forrevisionremarks = getRevisionSources($po, 'order_number');
	$reasonforrejection = getStatusRemarks('purchase_order_approval_history',"order_number='".$po."'",'po_approval_id',"reason_for_rejection");
	$rejectedby = rejectedby('purchase_order_approval_history',"order_number='".$po."'",'po_approval_id',"reason_for_rejection");

?>

<div class='header-page' >
	<div class='header-page-inner'>
		Purchase Order Transaction
	</div>
</div>
<div class="container-fluid">
	<div class="pageContent">

		<div class="panel panel-primary mypanel">
    			<div class="panel-body">
    				<div class='tabpane-white margin-top-20 margin-bottom-10' id='generalinfosection'>
						<ul class="nav nav-tabs">
							  <li role="presentation" class="active" data-pane='#poedit-geninfo'><a>General Information</a></li>
							  <li role="presentation" class="hidden" data-pane='#poedit-revisionremarks' id='poedit-revisionremarkstab'><a>Revision Remarks</a></li>
							  <li role="presentation" class="hidden" data-pane='#poedit-discrepancy' id='poedit-discrepancytab'><a>Discrepancy Details</a></li>
							  <li role="presentation" class="hidden" data-pane='#poedit-rejectionremarks' id='poedit-rejectionremarkstab'><a>Rejection Details</a></li>
						</ul>
					</div>
					<div class='tab-panes'>
						<div class='pane active' id='poedit-geninfo'>
							<div class="pane-content" style='padding-bottom:10px;'>
								<form class="form-horizontal">
									<div class="col-md-12">
										<div class="errordiv"></div>
									</div>
									<div class="col-md-6">
										<input type='hidden' class='pocurrency' value="<?php echo $currencyId; ?>">
										<?php 

                                            if($forrevisionremarks>0){
                                                echo "<div class=\"form-group\">
                                                                <label class=\"control-label col-sm-4\">PO History</label>
                                                                <div class=\"col-sm-8\">
                                                                     <span class='form-control-static potrans-pohistorytoggle' style='font-style:italic; color:blue; cursor:pointer;'>View details</span>
                                                                </div>
                                                </div>";
                                            }
                                        ?>
                                        <div class="form-group">
											<label class="control-label col-sm-4">Requisition No.</label>
											<div class="col-sm-8">
                                                <span class='form-control-static'><?php echo $refreqnum.$prrevsuffix; ?></span>&nbsp;&nbsp;&nbsp;
                                                <span prnumber='<?php echo $refreqnum;?>' class='pointer potrans-prviewdetails' title='Click to see details' style="font-style:italic;color:blue;font-size:11px">View details</span>
                                            </div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Purchase Order No.</label>
											<div class="col-sm-8">
												<input type='text' class='form-input form-control ponumber' value="<?php echo $po.appendPoRevSuffix($po); ?>" reference="<?php echo $refreqnum; ?>"  id='ponumberinput' readonly>
											</div>
										</div>
										<!--<div class="form-group">
											<label class="control-label col-sm-4">Order date</label>
											<div class="col-sm-8">
												<input type='text' class='form-input form-control' value='<?php echo $orderdate; ?>' disabled>
											</div>
										</div>-->
										<div class="form-group">
											<label class="control-label col-sm-4">Status</label>
											<div class="col-sm-8">
												<input type='text' class='form-input form-control' value='<?php echo $orderstatus; ?>' readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Supplier</label>
											<div class="col-sm-8">
												<input type='text' class='form-input form-control ' value='<?php echo $suppliername; ?>' suppid='<?php echo $supplierId; ?>' readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Supplier Address</label>
											<div class="col-sm-8">
												<textarea rows='3' class='form-input form-control supplieraddress '><?php echo $supplieraddr; ?></textarea>
											</div>
										</div>
										
										<div class="form-group">
											<label class="control-label col-sm-4">Attn</label>
											<div class="col-sm-8">
												<input type='text' class='form-input form-control attn' value='<?php echo $attn; ?>'>
											</div>
										</div>
										
										
									</div>
									<div class="col-md-6">
										
										<div class="form-group">
											<input type='hidden' value='<?php echo $dateneeded; ?>' id='crntdateneeded'>
											<label class="control-label col-sm-4">Date Needed</label>
											<div class="col-sm-8">
												<input type='text' id='dateneeded' class='form-input form-control dateneeded' value='<?php echo $dateneeded; ?>' >
											</div>
										</div>
										<input type='hidden' class='refreqnum' value='<?php echo $refreqnum; ?>'>
										<div class="form-group">
											<label class="control-label col-sm-4">Terms</label>
											<div class="col-sm-8">
												<select data-placeholder='Select payment terms' class='chosen-select form-input form-control paymentterms'>
													<option value='NULL'>-</option>
													<?php
														$query = "select * from payment_terms";
														$rs = query($query);
														while ($obj=fetch($rs)) {
															$id=$obj->id;
															$code=$obj->payment_terms_code;
															$description=$obj->description;
															if($defaultterm==$id){
																echo "<option value='$id'selected>$description</option>";
															}
															else{
																echo "<option value='$id'>$description</option>";
															}
															
														}
													?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Ship Via</label>
											<div class="col-sm-8">
												<select class='form-input form-control shipvia'>
														<option value='NULL'>-</option>
														<?php 
															$query = "select * from ship_via";
															$rs = query($query);
															while ($obj=fetch($rs)) {
																$id=$obj->id;
																$desc=$obj->ship_by;
																if($via==$id){
																	echo "<option value='$id' selected>$desc</option>";
																}
																else{
																	echo "<option value='$id'>$desc</option>";
																}
																
															}
														?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Mode of Shipment</label>
											<div class="col-sm-8">
												<select data-placeholder='Select mode of shipment' class='chosen-select form-input form-control modeofshipment'>
													<option value='NULL'>-</option>
													<?php 
														$query = "select * from mode_of_shipment where via='$via'";
														$rs = query($query);
														while($obj=fetch($rs)){
															if($obj->id==$mode){
																echo "<option value='$obj->id' selected>$obj->description</option>";
															}
															else{
																echo "<option value='$obj->id'>$obj->description</option>";
															}
														}
													?>
												</select>
											</div>
										</div>
										
										<div class="form-group">
											<label class="control-label col-sm-4">Remarks</label>
											<div class="col-sm-8">
												<textarea rows='4' class='form-input form-control remarks'><?php echo $remarks; ?></textarea>
											</div>
										</div>
										
									

									</div>
								</form>
							</div>
						</div>
						<div class='pane hidden' id='poedit-discrepancy'>
						</div>
						<div class='pane hidden' id='poedit-revisionremarks'>
						</div>
						<div class='pane hidden' id='poedit-rejectionremarks'>
												<form class='form-horizontal'>
													
														<div class="form-group">
															<label class="control-label col-md-2">Approver</label>
															<div class="col-md-10">
																<input type='text' id='rejectedby' class='form-control' value="<?php echo $rejectedby; ?>" disabled>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-2">Reason for Rejection</label>
															<div class="col-md-10">
																<textarea class='form-control' rows='4' id='reasonforrejection' readonly><?php echo strUtfEncode($reasonforrejection); ?></textarea>
															</div>
														</div>
												</form>
											</div>
					</div>
				</div>
		</div>

		<div class="panel panel-primary mypanel">
    			<div class="panel-body">
    				<div class='tabpane-white margin-top-20 margin-bottom-10' id='generalinfosection'>
						<ul class="nav nav-tabs">
							  <li role="presentation" class="active" data-pane='#lineitems'><a>Line Items</a></li>
						</ul>
					</div>
					<div class='tab-panes'>
						<div class='pane active' id='lineitems'>
									<div class='table-wrapper' style='height:200px'>
											<table class='table table-striped table-bordered no-side-borders table-condensed mytable' cellspacing='0'>
												<thead>
													<tr>
														<th class='text-center'>LINE</th>
														<th>ITEM CODE</th>
														<th>NAME</th>
														<th>DESCRIPTION</th>
														<th class='text-center'>CLASSF'N CODE</th>
														<th class='text-right'>QTY</th>
														<th class='text-center'>UOM</th>
														<th class='text-right'>UNIT PRICE</th>
														<th class='text-center'>CURRENCY</th>
														<th class='text-right'>AMOUNT</th>
													</tr>
												</thead>
												<tbody id='pritems'>
													 <?php
                                                            $query = "select item_code,
                                                                             item.item_name as item_name1, 
                                                                             purchase_order_details.item_name,
                                                                             item_id,
                                                                             quantity,
                                                                             purchase_order_details.unit_price,
                                                                             unit_of_measure_code,
                                                                             purchase_order_details.long_description,
                                                                             item.long_description as long_description1,
                                                                             classification_code
                                                                      from purchase_order_details, item, unit_of_measure 
                                                                      where unit_of_measure_id=unit_of_measure.id and         
                                                                            item_id=item.id and 
                                                                            purchase_order_details.order_number='$po'";
                                                            $rs = query($query);
                                                            $line = 1;
                                                            $grandtotal = 0;
                                                            $q = 'qty';
                                                            $a = 'amount';
                                                            while($obj=fetch($rs)){
                                                            	$itemId = $obj->item_id;
                                                                $itemcode = strUtfEncode($obj->item_code);
                                                                $shortdesc = strUtfEncode($obj->item_name) == '' ? '*'.strUtfEncode($obj->item_name1) : strUtfEncode($obj->item_name);
																$longdesc = strUtfEncode($obj->long_description) == '' ? '*'.strUtfEncode($obj->long_description1) : strUtfEncode($obj->long_description);
                                                                $classfn = $obj->classification_code;
                                                                $qty = $obj->quantity;
                                                                $price = $obj->unit_price;
                                                                $uom = $obj->unit_of_measure_code;
                                                                $floatamount = $price*$qty;
                                                                $amount = $price * $qty;
                                                                $grandtotal = $grandtotal + round($amount,2);
                                                                $qty = convertWithDecimal($qty,0);
                                                                $price = convertWithDecimal($price,5);
                                                                $amount = convertWithDecimal($amount,2);
                                                                echo "<tr prrowid='$itemId'>
                                                                            <td class='text-center'>$line</td>
                                                                            <td>$itemcode</td>
                                                                            <td>$shortdesc</td>
                                                                            <td>$longdesc</td>
                                                                            <td class='text-center'>$classfn</td>
                                                                            <td class='text-right'>$qty</td>
                                                                            <td class='text-center'>$uom</td>
                                                                            <td class='text-right rowprice'>$price</td>
                                                                            <td class='text-center'>$currency</td>
                                                                            <td class='text-right amount' amount='$floatamount'>$amount</td>
                                                                      </tr>";
                                                                $line++;      
                                                            }
                                                            $grandtotal = convertWithDecimal($grandtotal,2);
                                                        ?>
												</tbody>
											</table>
									</div>
									<div class="row">
										<div class="col-md-offset-7 col-md-5" style='margin-top: 10px; margin-bottom:10px;'>
											<form class='form-horizontal'>
												<div class="form-group" >
													<label class='control-label col-xs-5'>Total Amount</label> 
													<div class="col-xs-7">
														<div class="input-group">
															<div class="input-group-addon"><b><?php echo $currency; ?></b></div>
															<input type='text' class='form-input form-control text-right' id='pograndtotal' value='<?php echo $grandtotal; ?>' readonly>

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

		<div class="panel panel-primary mypanel">
			<div class="panel-body">
				<div class="panel-content text-center">
					<button class='btn btn-blue2 mybtn pobuttons' id='pocreate-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn pobuttons' id='pocreate-submitbtn'>Submit</button>
					<button class='btn btn-blue2 mybtn' id='pocreate-revisebtn' data-toggle='modal' href='#revisionmodal'>Revise</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn' id='pocreate-cancelbtn'>Back</button>
				</div>
			</div>
    	</div>

	</div>
</div>

<div class="modal fade" id="revisionmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                   Revision
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class="col-md-12">
            		<div class="form-group">
            			<div class="errordiv"></div>
            		</div>
            		<div class="form-group">
            			<label class='control-label col-md-4'>Revision Remarks</label>
            			<div class="col-md-8">
            				<textarea class='form-input form-control reasonforrevision' rows='5'></textarea>
            			</div>
            		</div>
            	</div>                                     
            </div>
            <div class="modal-footer">
                            <div class="panel-content text-center">
                                <button class='btn btn-blue2 mybtn' id='revisionmodal-revisebtn'>Revise</button>
                                <button class='btn btn-blue2 mybtn modal-cancelbtn' id='revisionmodal-cancelbtn'>Cancel</button>
                            </div>
            </div>
        </div>
    </div>  
</div>

<div class="modal fade" id="revisionremarksmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                   Remarks for Revision
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class="col-md-12">
            		<div class="form-group">
            			<div class="errordiv"></div>
            		</div>
            		<div class="form-group">
            			<label class='control-label col-md-4'>Remarks</label>
            			<div class="col-md-8">
            				<textarea class='form-input form-control' rows='5' readonly><?php echo $revremarks; ?></textarea>
            			</div>
            		</div>
            	</div>                                     
            </div>
            <div class="modal-footer">
                            <div class="panel-content text-center">
                                <button class='btn btn-blue2 mybtn modal-cancelbtn' id='revisionremarksmodal-cancelbtn'>Cancel</button>
                            </div>
            </div>
        </div>
    </div>  
</div>

<script src="../resources/selectoption/chosen.jquery.js"></script>
<script src="../resources/selectoption/docsupport/prism.js"></script>

<script type="text/javascript">
	//$('.shipvia').val(<?php echo json_encode($via); ?>);
	$('.paymentterms').attr('title',$('.paymentterms option:selected').text());
	var withdiscrepancy = <?php echo json_encode($withdiscrepancy); ?>,
		forrevision = <?php echo json_encode($forrevisionremarks); ?>,
		orderstat = <?php echo json_encode($orderstatus); ?>,
		ponumber = <?php echo json_encode($po); ?>;


	if(withdiscrepancy==1){
		$('#poedit-discrepancytab').removeClass('hidden');
		$('#poedit-discrepancy').removeClass('hidden').load('Other/purchase-order-discrepancy-details.php?Kbdkldriiopirn389yd9830='+ponumber);
	}
	
	if(forrevision>0){
		$('#poedit-revisionremarkstab').removeClass('hidden');
		$('#poedit-revisionremarks').removeClass('hidden').load('Other/purchase-order-revision-remarks.php?Kbdkldriiopirn389yd9830='+ponumber);
	}

	if(orderstat=='REJECTED'){
		$('#poedit-rejectionremarkstab').removeClass('hidden');
		$('#poedit-rejectionremarks').removeClass('hidden');
	}

	$('#dateneeded').datepicker();
	$('.modal-dialog').draggable();

	$('.chosen-select').chosen({ 
			no_results_text: "No results found for: ",
			width:'100%'
	});
</script>