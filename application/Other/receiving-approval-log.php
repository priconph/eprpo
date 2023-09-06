<?php
	include("../../config/connection.php");
	include("../../config/checklogin.php");
	include("../../config/functions.php");

	$rcv = isset($_GET['dasdJGsbdfuwn1d'])?$_GET['dasdJGsbdfuwn1d']:'';
	$revision = isset($_GET['rev'])?$_GET['rev']:'';
	$rcvnum = $rcv;

	if($rcv!=''){
		$fl = '';
		$rcvrev = $rcv.'-R'.$_GET['rev'];

		$rs = query("select * from receiving_header_revision where receiving_number='$rcvrev'");
		if(getNumRows($rs)==1){
			$fl = 'exist';
			$rcv = $rcvrev;
		}

		if($fl!='exist'){
			$query = "select * 
				      from receiving_header
				      left join supplier on supplier.id=receiving_header.supplier_id
				      where receiving_number='$rcv'";
		}
		else{
			$query = "select * 
				      from receiving_header_revision
				      left join supplier on supplier.id=receiving_header_revision.supplier_id
				      where receiving_number='$rcv'";
		}
		
		$suffx = '';
		
		$rs = query($query);

		if ($obj=fetch($rs)) {
			$receiving_number = $obj->receiving_number;
			$reference_po_number = $obj->reference_po_number;
			$orderStatus = $obj->receiving_status;
			$supplier = strUtfEncode($obj->supplier_name);
			$supplierAddr = strUtfEncode($obj->address);
			$received_date = formatDate2($obj->received_date);
			$actual_delivery_date = formatDate4($obj->actual_delivery_date);
			$remarks = strUtfEncode($obj->remarks);
			$other_reference = strUtfEncode($obj->other_reference);
			$received_by = getUserName($obj->received_by);
			$currency = getCurrencyCode($obj->currency);
			
			$rcv_no = explode('-R',$receiving_number);
			$approverfirst = getUserName($obj->first_approver).' '.getRcvApprovalHistoryTimestamp($rcv_no[0],1,$revision);
			$approversecond = getUserName($obj->second_approver).' '.getRcvApprovalHistoryTimestamp($rcv_no[0],2,$revision);
			
			if($fl==''){
				$suffx='-R'.$_GET['rev'];
			}
		}

		if(getNumRows($rs)<1){
			exit('No Revision History for the given Purchase Order');
		}
	}
	else{
		exit('Purchase Order does not exist.');
	}
?>

<!doctype html>
<html style='background-color:#fff'>
	<head>

		<title>EPRPO - Pricon Microelectronics, Inc.</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="../../css/bootstrap.css">
		<link rel="stylesheet" href="../../css/main.css">
		<link rel="shortcut icon" href="../../resources/pricon.ico">
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>

		<link href="../../css/fontAwesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	</head>
	<body>
		<div class="container">
			<div class="col-md-12"><h2>Receiving</h2></div>

			<form class="form-horizontal">
				
								<div class='tabpane-white margin-top-20 margin-bottom-10' id='generalinfosection'>
										<ul class="nav nav-tabs">
										  <li role="presentation" class="active" data-pane='#po-geninfo' id='geninfotab'><a>General Information</a></li>
										  <li role="presentation" data-pane='#po-approvers' id='poapproverstab'><a>Approvers</a></li>
										 
										</ul>
										<div class='tab-panes'>
											<div class='pane active' id='po-geninfo'>
												<div class="pane-content">
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label col-sm-4">Receiving No.</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='rcvnumber'><?php echo $receiving_number.$suffx; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Status</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='orderstatus'><?php echo $orderStatus; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">PO No.</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='vendor'><?php echo $reference_po_number; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Supplier</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='vendor'><?php echo $supplier; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Supplier Address</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='supplierAddress'><?php echo $supplierAddr; ?></span>
															</div>
														</div>
													</div>
													<div class="col-sm-6">
														
														<div class="form-group">
															<label class="control-label col-sm-4">Currency</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='rcvcurrency'><?php echo $currency; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Reference</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='other_reference'><?php echo $other_reference; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Date Received</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='received_date'><?php echo $received_date; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Actual Delivery Date</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='actual_delivery_date' ><?php echo $actual_delivery_date; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Received by</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='received_by'><?php echo $received_by; ?></span>
															</div>
														</div>
														
														
														<div class="form-group">
															<label class="control-label col-sm-4">Remarks</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='remarks'><?php echo $remarks; ?></span>
															</div>
														</div>
													</div>

												</div>
											</div>
											<div class="pane" id='po-approvers'>
					                            <div class="pane-content">
					                                <form class='form-horizontal'>
					                                    <div class="col-sm-6">
					                                        <div class="form-group">
					                                            <label class="control-label col-sm-5">Asst. Manager</label>
					                                            <div class="col-sm-7">
					                                                <span class='form-control-static asstmngr'><?php echo $approverfirst; ?></span>
					                                            </div>
					                                        </div>
					                                    </div>
					                                    <div class="col-sm-6">
					                                        <div class="form-group">
					                                            <label class="control-label col-sm-5">General Manager</label>
					                                            <div class="col-sm-7">
					                                                <span class='form-control-static genmngr'><?php echo $approversecond; ?></span>
					                                            </div>
					                                        </div> 
					                                    </div>
					                                </form>
					                            </div>
					                        </div>
									
										</div>
								</div>
								<br>
								<div class='tabpane-white margin-top-20 margin-bottom-10' id='polineitems'>
										<ul class="nav nav-tabs">
										  <li role="presentation" class="active" data-pane='#lineitems'><a>Line Items</a></li>
										 
										</ul>
										<div class='tab-panes'>
											<div class='pane active' id='lineitems'>
												<div class="pane-content">
													<div class='tablewithbuttons'>
														<div class='table-wrapper' style='height:200px'>
															<table class='table table-striped table-condensed table-bordered no-side-borders mytable' cellspacing='0'>
																<thead>
																	<tr>
																		<th class='text-center'>LINE NO.</th>
																		<th>ITEM CODE</th>
																		<th>NAME</th>
																		<th>DESCRIPTION</th>
																		<th class='text-center'>CLASSF'N CODE</th>
																		
																		<th class='text-center'>UNIT PRICE (PO)</th>
																		<th class='text-center'>CURRENCY</th>
																		<th class='text-right'>ORDERED</th>
																		<th class='text-right'>RECEIVED QTY</th>
																		<th class='text-right'>QTY APPROVAL</th>
																		<th class='text-right'>UOM</th>
																		<th class='text-right'>PO AMOUNT</th>
																		<th class='text-right'>UNIT PRICE</th>
																		<th class='text-right'>JUSTIFICATION</th>
																		<th class='text-right'>CORRECTIVE ACTION</th>
																		

																	</tr>
																</thead>
																<tbody id='rcvitems'>
																	 <?php 
																	 	if($fl!='exist'){
																	 		$query = "select item_code,
																	                        receiving_details.item_name,
																	                        receiving_details.long_description,
																	                        receiving_details.item_id,
																							purchase_order_details.quantity as orderedqty,
																	                        quantity_received,
																	                        qty_received_for_approval,
																	                        receiving_details.unit_price,
																	                        unit_of_measure_code,
																	                        classification_code,
																							justification,
																							corrective_action
																	                from receiving_details, item, unit_of_measure, receiving_header,purchase_order_details
																	                where unit_of_measure_id=unit_of_measure.id and 
																	                      receiving_details.receiving_number = receiving_header.receiving_number and
																	                      receiving_details.item_id=item.id and 
																						  purchase_order_details.order_number = receiving_header.reference_po_number and 
																	                      receiving_details.receiving_number='$rcvnum'
																						  and qty_received_for_approval > 0
																						  GROUP BY receiving_details.item_id";
																	 	}
																	 	else{
																	 		$query = "select item_code,
																	                        receiving_details_revision.item_name,
																	                        receiving_details_revision.long_description,
																	                        receiving_details_revision.item_id,
																							purchase_order_details.quantity as orderedqty,
																	                        quantity_received,
																	                        qty_received_for_approval,
																	                        receiving_details_revision.unit_price,
																	                        unit_of_measure_code,
																	                        classification_code,
																							justification,
																							corrective_action
																	                from receiving_details_revision, item, unit_of_measure, receiving_header_revision,
																					purchase_order_details
																	                where unit_of_measure_id=unit_of_measure.id and 
																	                      receiving_details_revision.receiving_number = receiving_header_revision.receiving_number and
																	                      receiving_details_revision.item_id=item.id and 
																	                      purchase_order_details.order_number = receiving_header_revision.reference_po_number and receiving_details_revision.receiving_number='$receiving_number'
																						  and qty_received_for_approval > 0
																						  GROUP BY receiving_details_revision.item_id";
																	    }
																	        $rs = query($query);
																	        $line = 1;
																	        $grandtotal = 0;
																	        while($obj=fetch($rs)){
																	           $itemId = $obj->item_id;
																	           $itemcode = $obj->item_code;
																			   $shortdesc = strUtfEncode($obj->item_name);
																			   $longdesc = strUtfEncode($obj->long_description);
																	           $classfn=$obj->classification_code;
																	           $price = $obj->unit_price;
																	           $qty = $obj->orderedqty;
																	           $qty_approval = $obj->qty_received_for_approval;
																	           $uom = $obj->unit_of_measure_code;
																	           $floatamount = $price*$qty;
																	           $amount = $price * $qty;
																			   $justification = $obj->justification;
			                                                                   $corrective_action = $obj->corrective_action;
																	           $grandtotal = $grandtotal + round($amount,2);
																	           // $rcvdqty = getQtyReceived($rcv,$itemId,$price);
																	           $rcvdqty = getQtyReceived_2($reference_po_number,$itemId,$price,$shortdesc,$longdesc);
																	           $bal = $qty-$rcvdqty;
																	           $bal = convertWithDecimal($bal,0);
																	           $qty = convertWithDecimal($qty,0);
																	           $price = convertWithDecimal($price,5);
																	           $amount = convertWithDecimal($amount,2);
																	           $rcvdqty = convertWithDecimal($rcvdqty,0);
																	           echo "<tr prrowid='$itemId' title='Item Description: \n$longdesc'>
																	           <td class='text-center'>$line</td>
																	           <td>$itemcode</td>
																	           <td>$shortdesc</td>
																	           <td>$longdesc</td>
																	           <td class='text-center'>$classfn</td>
																	           
																	           <td class='text-center'>$uom</td>
																	           <td class='text-center'>$currency</td>
																	           <td class='text-right'>$qty</td>
																	           <td class='text-right'>$rcvdqty</td>
																	           <td class='text-right'>$qty_approval</td>
																	           <td class='text-right'>$uom</td>
																	           <td class='text-right amount' amount='$floatamount'>$amount</td>
																	           <td class='text-right rowprice'>$price</td>
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
														<div class="col-md-offset-7 col-md-5" style='margin-top: 10px; margin-bottom:10px;'>
															<form class='form-horizontal'>
																<div class="form-group" >
																	<label class='control-label col-xs-5'>Total Amount</label> 
																	<div class="col-xs-7">
																		<div class="input-group">
																			<div class="input-group-addon"><b><?php echo $currency; ?></b></div>
																			<input type='text' class='form-input form-control text-right disabled' value='<?php echo $grandtotal; ?>' id='grandtotal' readonly>
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

			</form>
			<?php include('rcv-legend.php'); ?>
		</div>
		




		<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->

		<script src="../../js/jquery-1.11.3.min.js"></script>
		<script src="../../js/bootstrap.min.js"></script>
		<script src="../../js/jsfunctions.js"></script>
		<script src="../../js/custom.js"></script>
		
	</body>
</html>