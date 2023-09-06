<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/checklogin-page.php");
    include("../../config/functions.php");
    $rcv_no = (isset($_GET['Gde*$jddwRx']))?$_GET['Gde*$jddwRx']:'';
    $purch_revision_no = (isset($_GET['prn']))?$_GET['prn']:'';
    
    if($rcv_no==''){
    	exit('DIRECT ACCESS NOT ALLOWED');
    }
    $query = "select receiving_header.*, reference_requisition_number, supplier_name, purchase_order_header.next_revision_series as po_rev from receiving_header LEFT JOIN purchase_order_header on purchase_order_header.order_number = receiving_header.reference_po_number 
	LEFT JOIN supplier ON receiving_header.supplier_id = supplier.id
	where receiving_number='$rcv_no' ";
	$rs = query($query);
	if($obj=fetch($rs)){
		$actual_delivery_date = formatDate($obj->actual_delivery_date);
		$carrier = $obj->carrier;
		$received_by = $obj->received_by;
		// $ponumber = $obj->reference_po_number.'-R'.$obj->po_rev;
		$ponumber = $obj->reference_po_number;
		$received_date = formatDate2($obj->received_date);
		$remarks = $obj->remarks;
		$prnum = $obj->reference_requisition_number;
		$suppliername = strUtfEncode($obj->supplier_name);
		$suppid = $obj->supplier_id;
		$currencyId=$obj->currency;
		$reference=$obj->other_reference;
		$currency = getCurrencyCode($currencyId);
		$prrevsuffix = appendPrRevSuffix($prnum);
	}
?>
<div class='header-page' >
    <div class='header-page-inner'>
        Receiving Approval
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
												<span class='form-control-static rcvtrans-prhistorytoggle pointer viewdetails' prnumber="<?php echo $prnum; ?>" style='font-style:italic; color:blue; cursor:pointer;'>View Details</span>
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
												<input type='text' class='form-input form-control otherreference' value='<?php echo $reference; ?>' readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Carrier</label>
											<div class="col-sm-8">
												<input type='text' class='form-input form-control carrier' value="<?php echo $carrier; ?>" readonly>
											</div>
										</div>

									</div>
									<div class="col-md-6">
										
										<div class="form-group">
											<label class="control-label col-sm-4">Date Received</label>
											<div class="col-sm-8">
												<input type='datetime' class='form-input form-control disabled datereceived' value="<?php echo $received_date; ?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Actual Delivery Date</label>
											<div class="col-sm-8">
												<input type='text' class='form-input form-control' value='<?php echo $actual_delivery_date; ?>' readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Received by</label>
											<div class="col-sm-8">
												<input type='text' class='form-input form-control dateinput receivedby disabled' value="<?php echo getUserName($received_by); ?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Remarks</label>
											<div class="col-sm-8">
												<textarea rows='4' id='remarks' class='form-input form-control remarks' readonly><?php echo $remarks; ?></textarea>
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
																		<th class='text-right'>QUANTITY APPROVAL</th>
																		<th>UOM</th>
																		<th class='text-right'>PO AMOUNT</th>
																		
																		<th class='text-nowrap text-right'>UNIT PRICE</th>
																		<th>OPEN PO # (Qty)</th>
																		<th>JUSTIFICATION</th>
																		<th>CORRECTIVE ACTION</th>
																	</tr>
																</thead>
																<tbody id='rcvlineitems'>
																	  <?php
			                                                            $query = "
																		select receiving_details.*, item_code,purchase_order_details.quantity as orderedqty, classification_code,unit_of_measure_code  from receiving_details INNER JOIN item on receiving_details.item_id=item.id INNER JOIN purchase_order_details ON purchase_order_details.order_number='$ponumber' and receiving_details.item_id=purchase_order_details.item_id INNER JOIN unit_of_measure ON item.unit_of_measure_id=unit_of_measure.id where receiving_number='$rcv_no' AND quantity_received=0 AND qty_received_for_approval > 0";
			                                                            $rs = query($query);
			                                                            $line = 1;
			                                                            $grandtotal = 0;
			                                                            while($obj=fetch($rs)){
			                                                            	$itemId = $obj->item_id;
			                                                                $itemcode = strUtfEncode($obj->item_code);
																			$shortdesc = strUtfEncode($obj->item_name);
																			$longdesc = strUtfEncode($obj->long_description);
			                                                                $qty = $obj->orderedqty;
			                                                                $qty_approval = $obj->qty_received_for_approval;
			                                                                $price = $obj->unit_price;
			                                                                $classfn = $obj->classification_code;
			                                                                $prc = $price;
			                                                                $uom = $obj->unit_of_measure_code;
			                                                                $floatamount = $price*$qty;
			                                                                $amount = $price * $qty;
			                                                                $justification = $obj->justification;
			                                                                $corrective_action = $obj->corrective_action;
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
																					$open_po_bal = $obj_old_po->quantity - getQtyReceived_2($obj_old_po->order_number,$itemId,$obj_old_po->unit_price,$obj_old_po->item_name,$obj_old_po->long_description);
																					
																					if($open_po_bal == 0) {
																						$open_po = '-';
																					} else {																						
																						$open_po_bal = convertWithDecimal($open_po_bal,0);
																						$open_po = "<label style='color:red' class='has_open_po'>".($obj_old_po->order_number.'('.$open_po_bal.')')."</label>";
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
			                                                                            <td class='text-right'>$qty_approval</td>
			                                                                            <td class='text-center'>$uom</td>
			                                                                            <td class='text-right'>$rcvdamount</td>
			                                                                            <td class='price'>$prc</td>
			                                                                            <td class='text-center'>$open_po</td>
			                                                                            <td class='text-center'>$justification</td>
			                                                                            <td class='text-center'>$corrective_action</td>
			                                                                           	
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
            <div class="panel-body">
                <div class="panel-content text-center">
                    <button class='btn btn-blue2 mybtn' id='rcvapproval-approvebtn' data-toggle='modal' href='#rcvapprovedmodal' rcvnumber="<?php echo $rcv_no; ?>">Approve</button>
                        <button class='btn btn-blue2 mybtn' id='rcvapproval-rejectbtn' data-toggle='modal' href='#rejectionmodal' rcvnumber="<?php echo $rcv_no; ?>">Reject</button>
                        <button class='btn btn-blue2 mybtn' id='rcvapproval-cancelbtn'>Back</button>
                </div>
            </div>
        </div>

	</div>
</div>


<div class="modal fade" id="rejectionmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Receiving Approval
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="errordiv"></div>
                    </div>
                </div>
                <input type='hidden' class='rcvrejectrcvno'>
                <div class="form-group">
                    <label class="control-label col-sm-4">Reason for Rejection</label>
                    <div class="col-sm-8">
                        <textarea class='form-input form-control rejectreason' rows='4'></textarea>
                    </div>
                </div>
                                          
            </div>
            <div class="modal-footer">
                            <div class="panel-content text-center">
                                <button class='btn btn-blue2 mybtn' id='rcvapprovalmodal-rejectbtn'>Reject</button>
                                <button class='btn btn-blue2 mybtn modal-cancelbtn' id='rcvapprovalmodal-cancelbtn'>Cancel</button>
                            </div>
            </div>
        </div>
    </div>  
</div>

<div class="modal fade" id="rcvapprovedmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Receiving Approval
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="errordiv"></div>
                    </div>
                </div>
                <input type='hidden' class='rcvapprovedrcv'>
                <div class="form-group">
                    <label class="control-label col-sm-4">Remarks</label>
                    <div class="col-sm-8">
                        <textarea class='form-input form-control rcvapprovedremarks' rows='4'></textarea>
                    </div>
                </div>
                                    
            </div>
            <div class="modal-footer">
                            <div class="text-center">
                                <button class='btn btn-blue2 mybtn' id='rcvapprovedmodal-approvebtn'>Approve</button>
                                <button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
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

		});
		grandtotal = convertFloat(grandtotal);
		$('#grandtotal-rcvpo').val(grandtotal);

		

	});

	
		$('.datepicker').datepicker();


</script>