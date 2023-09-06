<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
    include('../../config/functions.php');
	$supplier = isset($_GET['JKeao4ihslk'])?$_GET['JKeao4ihslk']:'';
	$ponumber = isset($_GET['skrbOIrhlk'])?$_GET['skrbOIrhlk']:'';
	if($ponumber==''||$supplier==''){
		exit('Direct Access Not Allowed');
	}
	else{
	 	$query = "select * from purchase_order_header, supplier where supplier_id=supplier.id and order_number='$ponumber'";
	 	$rs = query($query);
	 	if($obj=fetch($rs)){
	 		$prnum = $obj->reference_requisition_number;
	 		$suppliername = strUtfEncode($obj->supplier_name);
	 		$suppid = $obj->supplier_id;
	 		$currencyId=$obj->currency;
	 		$currency = getCurrencyCode($currencyId);
	 		$prrevsuffix = appendPrRevSuffix($prnum);
	 	}
    }
?>
<div class='header-page' >
	<div class='header-page-inner'>
		Receiving - Purchase Order
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
											<label class="control-label col-sm-4">PR Reference</label>
											<div class="col-sm-8">
												<span class="form-control-static"><?php echo $prnum.$prrevsuffix; ?></span>&nbsp;&nbsp;&nbsp; 
												<span class='form-control-static rcvtrans-prhistorytoggle' prnumber="<?php echo $prnum; ?>" style='font-style:italic; color:blue; cursor:pointer;'>View Details</span>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Supplier</label>
											<div class="col-sm-8">
												<input type='text' class='form-input form-control supplier' supplierId="<?php echo $suppid; ?>" value="<?php echo $suppliername; ?>" readonly>
											</div>
										</div>
										
										<div class="form-group">
											<label class="control-label col-sm-4">PO No.</label>
											<div class="col-sm-8">
												<input type='text' class='form-input form-control disabled referenceponumber' value="<?php echo $ponumber;?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Currency</label>
											<div class="col-sm-8">
												<input type='text' class='form-input form-control currency disabled' value="<?php echo $currency; ?>" currencyId="<?php echo $currencyId; ?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Reference</label>
											<div class="col-sm-8">
												<input type='text' class='form-input form-control otherreference'>
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
												<input type='date' class='form-input form-control disabled datereceived' value="<?php echo date('Y-m-d'); ?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Actual Delivery Date</label>
											<div class="col-sm-8">
												<input type='text' class='form-input form-control actualdatedelivered datepicker'>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Received by</label>
											<div class="col-sm-8">
												<input type='text' class='form-input form-control dateinput receivedby disabled' value="<?php echo getUserName($userid); ?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Remarks</label>
											<div class="col-sm-8">
												<textarea rows='4' id='remarks' class='form-input form-control remarks'></textarea>
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
							  <li role="presentation" class="active" data-pane='#rcv-lineitems'><a>Line Items</a></li>
						</ul>
					</div>
					<div class='tab-panes'>
						<div class='pane active' id='rcv-lineitems'>
							<div class="pane-content" style='padding-bottom:10px'>
								<div class="form-group">
									<label class="control-label col-sm-12">Note: Line item/s in <font color='red'>red</font> are <u>Open P.O. and subject for approval if submitted.</u></label>
								</div>
								<div class="table-responsive">
											<div class='tablewithbuttons'>
														<div class='table-wrapper' style='height:300px'>
															<table class='table table-striped table-condensedss table-bordered no-side-borders mytable table-hover' cellspacing='0'>
																<thead>
																	<tr>
																		<th>LINE</th>
																		<th class='text-nowrap'>ITEM CODE</th>
																		<th class='text-nowrap'>NAME</th>
																		<th class='text-nowrap'>DESCRIPTION</th>
																		<th class='text-center'>CLASSF'N CODE</th>
																		<th class='text-nowrap'>UNIT PRICE (PO)</th>
																		<th>CURRENCY</th>
																		<th class='text-nowrap text-right'>ORDERED</th>
																		<th class='text-nowrap'>RECEIVED</th>
																		<th>UOM</th>
																		<th class='text-right'>PO AMOUNT</th>
																		
																		<th class='text-right'>QUANTITY</th>
																		<th class='text-nowrap text-right'>UNIT PRICE</th>
																		<th>OPEN PO # (Qty)</th>
																		<th>JUSTIFICATION</th>
																		<th>CORRECTIVE ACTION</th>
																		<th>UNDERDELIVERY</th>
																	</tr>
																</thead>
																<tbody id='polineitems'>
																	  <?php
			                                                            $query = "select item_code,
																						item.item_name as item_name1,
																						purchase_order_details.item_name,
																						purchase_order_details.long_description,
																						item.long_description as long_description1,
			                                                                             item_id,
			                                                                             quantity,
			                                                                             purchase_order_details.unit_price,
			                                                                             unit_of_measure_code,
			                                                                             classification_code
			                                                                      from purchase_order_details, item, unit_of_measure 
			                                                                      where unit_of_measure_id=unit_of_measure.id and 
			                                                                            item_id=item.id and 
			                                                                            order_number='$ponumber'";
			                                                            $rs = query($query);
			                                                            $line = 1;
			                                                            $grandtotal = 0;
			                                                            while($obj=fetch($rs)){
			                                                            	$itemId = $obj->item_id;
			                                                                $itemcode = strUtfEncode($obj->item_code);
																			$shortdesc = strUtfEncode($obj->item_name) == '' ? ''.strUtfEncode($obj->item_name1) : strUtfEncode($obj->item_name);
																			$longdesc = strUtfEncode($obj->long_description) == '' ? ''.strUtfEncode($obj->long_description1) : strUtfEncode($obj->long_description);
			                                                                $qty = $obj->quantity;
			                                                                $price = $obj->unit_price;
			                                                                $classfn = $obj->classification_code;
			                                                                $prc = $price;
			                                                                $uom = $obj->unit_of_measure_code;
			                                                                $floatamount = $price*$qty;
			                                                                $amount = $price * $qty;
			                                                                $justification = '-'; $corrective_action = '-';
																			
																			$qtyprevreceived =convertWithDecimal(getQtyReceived_2($ponumber,$itemId,$price,$shortdesc,$longdesc),0);
			                                                                $bal = $qty - getQtyReceived_2($ponumber,$itemId,$price,$shortdesc,$longdesc);

			                                                                $rcvdamount = $bal*$price;
			                                                                $grandtotal = $grandtotal + round($rcvdamount,2);
			                                                                $price = convertWithDecimal($price,5);
			                                                                $amount = convertWithDecimal($amount,2);
			                                                                $rcvdamount = convertWithDecimal($rcvdamount,2);
			                                                                
																			$qty = convertWithDecimal($qty,0);
																			
																			//Check for old PO record per item
																			$old_po_query = query("SELECT details.order_number, details.quantity, details.item_name, details.long_description, details.unit_price FROM `purchase_order_details` details INNER JOIN purchase_order_header header ON details.order_number = header.order_number WHERE details.`item_id` ='".$itemId."' AND order_status in ('UNSERVED', 'PARTIAL DELIVERY') AND details.order_number != '".$ponumber."' AND details.order_number < '".$ponumber."' ORDER BY details.order_number ASC LIMIT 0,1");
																			$old_po_row_count = getNumRows($old_po_query);
																			if($old_po_row_count == 0) {
																				$open_po = '-';
			                                                                } else {
																				//Now check if old PO has balance
																				if($obj_old_po=fetch($old_po_query)){
																					if(str_replace('PO-','',$obj_old_po->order_number) < str_replace('PO-','',$ponumber)) {
																						$open_po_bal = $obj_old_po->quantity - getQtyReceived_2($obj_old_po->order_number,$itemId,$obj_old_po->unit_price,$obj_old_po->item_name,$obj_old_po->long_description);
																						
																						if($open_po_bal == 0) {
																							$open_po = '-';
																						} else {																						
																							$open_po_bal = convertWithDecimal($open_po_bal,0);
																							// $open_po = "<label style='color:red' class='has_open_po'>".($obj_old_po->order_number.'('.$open_po_bal.')')."</label>";
																							$open_po = "<input type='text' style='color:red' class='has_open_po' value='".($obj_old_po->order_number.'('.$open_po_bal.')')."' disabled>";
																							$shortdesc = "<font color='red'>$shortdesc</font>";
																							$justification = "<textarea class='form-control justification'></textarea>";
																							$corrective_action = "<textarea class='form-control corrective_action'></textarea>";
																						}
																					}
																				}
																			}
																			echo "<tr porowitemid='$itemId'>
			                                                                            <td class='text-center'>$line</td>
			                                                                            <td>$itemcode</td>
			                                                                            <td style='min-width:200px'>$shortdesc</td>
			                                                                            <td style='min-width:200px'>$longdesc</td>
			                                                                            <td class='text-center'>$classfn</td>
			                                                                            <td class='text-right'>$price</td>
			                                                                            <td class='text-center'>$currency</td>
			                                                                            <td class='text-right orderedqty'>$qty</td>
			                                                                            <td class='text-right receivedqty'>$qtyprevreceived</td>
			                                                                            <td class='text-center'>$uom</td>
			                                                                            <td class='text-right'>$rcvdamount</td>
			                                                                            
			                                                                            <td class='text-center width-sm'><input type='number' class='form-input form-input-xs qtytobereceived rcvpoqtyinput text-right' style='border:none' max='$bal' value='$bal'></td>
			                                                                            <td class='price'><input type='number' disabled step='.01' min='.01' class='priceinput form-input form-input-xs text-right rcvpopriceinput' defaultprice='$prc' value='$prc' style='border:none'></td>
			                                                                            <td class='text-center'>$open_po</td>
			                                                                            <td class='text-center'>$justification</td>
			                                                                            <td class='text-center'>$corrective_action</td>
			                                                                            <td class='text-center'><select class='form-control rowunderdelivery'><option value='NO'>No</option><option value='YES'>Yes</option></select></td>
			                                                                           	
			                                                                      </tr>";
			                                                                $line++;      
			                                                            }
			                                                            $grandtotal = convertWithDecimal($grandtotal,2);
			                                                        ?>
																</tbody>
															</table>
														</div>
														<div class="row">
											    			<div class="col-md-offset-6 col-md-6" style='margin-top:10px;'>
											    				<form class='form-horizontal'>
																			<div class="form-group" >
																				<label class='control-label col-xs-5'>Total Amount Received</label> 
																				<div class="col-xs-7">
																					<div class="input-group">
																						<div class="input-group-addon"><b><?php echo $currency; ?></b></div>
																						<input type='text' class='form-control text-right' id='grandtotal-rcvpo' value='<?php echo $grandtotal; ?>' readonly>
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
    	</div>

    	<div class="panel panel-primary mypanel">
    			<div class="panel-body text-center">
    				<button class='btn btn-blue2 mybtn' id='rcvpo-submitbtn'>Submit</button>
					<button class='btn btn-blue2 mybtn' id='rcvpo-cancelbtn'>Back</button>
					<button class='btn btn-blue2 mybtn' id='rcvpo-holdbtn' data-toggle='modal' href='#rejectionmodal'>Hold</button>
    			</div>
    	<div>

	</div>
</div>

<!-- 01222021 -->
<div class="modal fade" id="rejectionmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                   Hold Supplier's Invoice
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="errordiv"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3">PO Number</label>
                    <div class="col-md-5 col-sm-8">
                        <input type='text' class='form-input form-control holdinvoicemodal-ponumber' value='<?php echo $ponumber;?>' disabled>
                    </div>
                </div>
                <br>
                <br>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3">Reason for hold</label>
                    <div class="col-sm-8">
                        <textarea class='form-input form-control rejectreason' rows='4'></textarea>
                    </div>
                </div>
                                    
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button class='btn btn-blue2 mybtn' id='prmodal-rejectbtn'>Hold</button>
                    <button class='btn btn-blue2 mybtn modal-cancelbtn' id='itemlist-cancelbtn'>Cancel</button>
                </div>
            </div>
        </div>
    </div>  
</div>



<script type="text/javascript">

	$(document).on("keyup"," .rcvpoqtyinput, .rcvpopriceinput",function(){
		var grandtotal = 0;
		if($(this).val()==''){
			$(this).val(0);
		}

		
		$('#polineitems tr').each(function(){
			var qty = $(this).find('.rcvpoqtyinput').val();
			var price = $(this).find('.rcvpopriceinput').val();
			var amount = parseFloat(qty)*parseFloat(price);
			grandtotal = grandtotal + amount;
			console.warn(qty,price,amount,grandtotal);

		});
		grandtotal = convertFloat(grandtotal);
		$('#grandtotal-rcvpo').val(grandtotal);

	});

	//-01222021
	$(".panel").on('click', '#rcvpo-holdbtn', function(){
		$('#holdinvoicemodal').modal('show');
	});	

	$('.datepicker').datepicker();


</script>

