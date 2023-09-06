<?php
	include("../../config/connection.php");
	include("../../config/checklogin.php");
	include("../../config/functions.php");

	$po = isset($_GET['JndeLIe93o0jn49NDLiu3n'])?$_GET['JndeLIe93o0jn49NDLiu3n']:'';

	if($po!=''){
		$query = "select * 
		          from purchase_order_header_revision
		          left join ship_via on ship_via.id=purchase_order_header_revision.ship_via
		          left join supplier on supplier.id=purchase_order_header_revision.supplier_id
		          where order_number like '%$po%' 
		          order by date_flag desc limit 1";
		$rs = query($query);

		while ($obj=fetch($rs)) {
			$orderNum = $obj->order_number;
			$referenceprnum = $obj->reference_requisition_number;
			$orderStatus = $obj->order_status;
			$supplier = strUtfEncode($obj->supplier_name);
			$supplierAddr = strUtfEncode($obj->address);
			$remarks = strUtfEncode($obj->po_remarks);
			$expdelivery = formatDate($obj->expected_date);
			$terms = getPaymentTermCode($obj->terms);
			$shipvia = $obj->ship_by;
			$mode =getModeOfShipment($obj->mode_of_shipment);
			$orderdate = formatDate($obj->order_date);
			$pototal = getPoGrandTotal($orderNum);
			$attn = $obj->supplier_contact;
			$assignedto = getUserName($obj->assigned_to);
			$postedby = getUserName($obj->posted_by);
			$currency = getPoCurrency($orderNum);
			$lastapprovedby = getLastPoApprover2($orderNum);
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
			<div class="col-md-12"><h2>Purchase Order History</h2></div>

			<form class="form-horizontal">
				
								<div class='tabpane-white margin-top-20 margin-bottom-10' id='generalinfosection'>
										<ul class="nav nav-tabs">
										  <li role="presentation" class="active" data-pane='#po-geninfo' id='geninfotab'><a>General Information</a></li>
										 
										</ul>
										<div class='tab-panes'>
											<div class='pane active' id='po-geninfo'>
												<div class="pane-content">
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label col-sm-4">Order No.</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='ponumber'><?php echo $orderNum; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Status</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='orderstatus'><?php echo $orderStatus; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Reference PR No.</label>
															<div class="col-sm-8">
																<span class='form-control-static' id='prnumber'><?php echo $referenceprnum; ?></span>
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
														<div class="form-group">
															<label class="control-label col-sm-4">Attn</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='attn'><?php echo $attn; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Currency</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='pocurrency'><?php echo $currency; ?></span>
															</div>
														</div>
													</div>
													<div class="col-sm-6">
														
														<div class="form-group">
															<label class="control-label col-sm-4">Order Date</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='orderdate'><?php echo $orderdate; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Date Needed</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='expdelivery' ><?php echo $expdelivery; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Terms</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='paymentterms'><?php echo $terms; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Ship Via</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='shipvia'><?php echo $shipvia; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Mode of Shipment</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='shipmentmode'><?php echo $mode; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Assigned to</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='assignedto'><?php echo $assignedto; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Posted by</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='postedby'><?php echo $postedby; ?></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Last Approved by</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='postedby'><?php echo $lastapprovedby; ?></span>
															</div>
														</div>


													</div>

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
															<table class='table table-striped table-condensed table-bordered mytable' cellspacing='0'>
																<thead>
																	<tr>
																		<th class='text-center'>LINE NO.</th>
																		<th>ITEM CODE</th>
																		<th>NAME</th>
																		<th class='text-center'>CLASSF'N CODE</th>
																		<th class='text-right'>QTY</th>
																		<th class='text-center'>UOM</th>
																		<th class='text-right'>UNIT PRICE</th>
																		<th class='text-right'>AMOUNT</th>
																		<th class='text-right'>RECEIVED</th>
																		<th class='text-right'>BALANCE</th>

																	</tr>
																</thead>
																<tbody id='poitems'>
																	 <?php $query = "select item_code,
																	                        item_name,
																	                        long_description,
																	                        item_id,
																	                        quantity,
																	                        purchase_order_details_revision.unit_price,
																	                        unit_of_measure_code,
																	                        classification_code
																	                from purchase_order_details_revision, item, unit_of_measure, purchase_order_header_revision
																	                where unit_of_measure_id=unit_of_measure.id and 
																	                      purchase_order_details_revision.order_number = purchase_order_header_revision.order_number and
																	                      item_id=item.id and 
																	                      purchase_order_details_revision.order_number='$orderNum'";
																	        $rs = query($query);
																	        $line = 1;
																	        $grandtotal = 0;
																	        while($obj=fetch($rs)){
																	           $itemId = $obj->item_id;
																	           $itemcode = strUtfEncode($obj->item_code);
																	           $shortdesc = strUtfEncode($obj->item_name);
																	           $longdesc = strUtfEncode($obj->long_description);
																	           $qty = $obj->quantity;
																	           $price = $obj->unit_price;
																	           $uom = $obj->unit_of_measure_code;
																	           $classfn = $obj->classification_code;
																	           //$currency = $obj->currency_code;
																	           $floatamount = $price*$qty;
																	           $amount = $price * $qty;
																	           $grandtotal = $grandtotal + round($amount,2);
																	           $rcvdqty = getQtyReceived($po,$itemId,$price);
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
																	           <td class='text-center'>$classfn</td>
																	           <td class='text-right'>$qty</td>
																	           <td class='text-center'>$uom</td>
																	           <td class='text-right rowprice'>$price</td>
																	           <td class='text-right amount' amount='$floatamount'>$amount</td>
																	           <td class='text-right'>$rcvdqty</td>
																	           <td class='text-right'>$bal</td>
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
								<br>

			</form>
			<?php include('po-legend.php'); ?>
		</div>
		




		<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->

		<script src="../../js/jquery-1.11.3.min.js"></script>
		<script src="../../js/bootstrap.min.js"></script>
		<script src="../../js/jsfunctions.js"></script>
		<script src="../../js/custom.js"></script>
	</body>
</html>