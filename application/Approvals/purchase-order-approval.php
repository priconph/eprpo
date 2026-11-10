<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/checklogin-page.php");
    include("../../config/functions.php");
    $po = (isset($_GET['Ksfrlwowp%kspej%Dhor']))?$_GET['Ksfrlwowp%kspej%Dhor']:'';
    $purch_revision_no = (isset($_GET['prn']))?$_GET['prn']:'';
    
    if($po==''){
    	exit('DIRECT ACCESS NOT ALLOWED');
    }

    /* PR Query */
    $pr_query = "select poh.order_number, SUM(pod.quantity) as po_qty, poh.reference_requisition_number, SUM(prd.quantity) as pr_qty
        from purchase_order_header poh
        inner join purchase_requisition_details prd on prd.requisition_number = poh.reference_requisition_number 
        inner join purchase_order_details pod on pod.order_number = poh.order_number 
        where pod.order_number='$po'";

    $rs1 = query($pr_query);
    while($obj=fetch($rs1)){

        $po_qty = $obj->po_qty;
        $pr_qty = $obj->pr_qty;
    }
    /* Up to here */   


    $query = "select supplier_id,
                     supplier_name,
                     supplier_contact,
                     address,
                     expected_date,
                     delivery_date,
                     posted_date,
                     order_date,
                     order_status,
                     payment_terms.description as paymentterms, 
                     po_remarks,
                     mode_of_shipment.description,
                     ship_by,
                     currency_name,
                     reference_requisition_number,
                     currency_code,
                     first_approver,
                     second_approver,
                     division_approver,
                     third_approver,
                     purch_revision_no,
                     purchase_order_header.posted_by,
                     purchase_requisition_header.facshipto
              from purchase_order_header, supplier, payment_terms, mode_of_shipment, currency, ship_via, purchase_requisition_header
              where currency.id=purchase_order_header.currency and 
                    ship_via.id=purchase_order_header.ship_via and
                    mode_of_shipment=mode_of_shipment.id and 
                    payment_terms.id=purchase_order_header.terms and 
                    purchase_requisition_header.requisition_number=purchase_order_header.reference_requisition_number and 
                    order_number='$po' and 
                    supplier.id=supplier_id";
    $rs = query($query);
    while($obj=fetch($rs)){

    	$supplierId = $obj->supplier_id;
    	$suppliername=strUtfEncode($obj->supplier_name);
        $attn = strUtfEncode($obj->supplier_contact);
    	$supplieraddr = strUtfEncode($obj->address);
    	$dateneeded = formatDate3($obj->expected_date);
        $orderdate = formatDate2($obj->order_date);
    	$deliverydate = formatDate3($obj->delivery_date);
    	$orderstatus=$obj->order_status;
    	$poterms = $obj->paymentterms;
    	$remarks = strUtfEncode($obj->po_remarks);
        $mode = $obj->description;
    	$via = $obj->ship_by;
    	$facshipto = $obj->facshipto;
        $currency = $obj->currency_name;
        $currencysymbol = $obj->currency_code;
        $pr = $obj->reference_requisition_number;
        $prrevsuffix = appendPrRevSuffix($pr);
        $preparedby = getUserName($obj->posted_by);
        $prepareddate = formatDate2($obj->posted_date);

        $approverfirst = getUserName($obj->first_approver).' '.getPoApprovalTimestamp($po,$obj->first_approver,1);;
        $approversecond = getUserName($obj->second_approver).' '.getPoApprovalTimestamp($po,$obj->second_approver,2);;
        $approverdivision = $obj->division_approver==NULL?'Not Required':getUserName($obj->division_approver).' '.getPoApprovalTimestamp($po,$obj->division_approver,3);
        $approverlast = $obj->third_approver==NULL?'Not Required':getUserName($obj->third_approver).' '.getPoApprovalTimestamp($po,$obj->third_approver,4);
    }
    $withdiscrepancy = checkIfDiscrepancyExist($po);
    $forrevisionremarks = getRevisionSources($po, 'order_number');
?>


<div class='header-page' >
    <div class='header-page-inner'>
        Purchase Order Approval
    </div>
</div>
<div class="container-fluid">
	<div class="pageContent">

        <div class="panel panel-primary mypanel">
                <div class="panel-body">
                    <div class='tabpane-white margin-top-20 margin-bottom-10' id='generalinfosection'>
                        <ul class="nav nav-tabs">
                              <li role="presentation" class="active" data-pane='#poapproval-geninfo'><a>General Information</a></li>
                              <li role="presentation" data-pane='#poapproval-approverspane'><a>Approvers</a></li>
                              <li role="presentation" class="hidden" data-pane='#poapproval-revisionremarks' id='poapproval-revisionremarkstab'><a>Revision Remarks</a></li>
                              <li role="presentation" class="hidden" data-pane='#poapproval-discrepancy' id='poapproval-discrepancytab'><a>Discrepancy Details</a></li>
                        </ul>
                    </div>
                    <div class='tab-panes'>
                        <div class='pane active' id='poapproval-geninfo'>
                            <div class="pane-content" style='padding-bottom:10px'>
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
                                                                     <span class='form-control-static poappr-pohistorytoggle' style='font-style:italic; color:blue; cursor:pointer;'>View Details</span>
                                                                </div>
                                                </div>";
                                            }
                                        ?>
                                         <div class="form-group">
                                            <label class="control-label col-sm-4">Reference PR No. </label>
                                            <div class="col-sm-8">
                                                <span class='form-control-static'><?php echo $pr.$prrevsuffix; ?></span>&nbsp;&nbsp;&nbsp;
                                                <span prnumber='<?php echo $pr;?>' class='pointer viewdetails' title='Click to see details' style="font-style:italic;color:blue;font-size:11px">View details</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Purchase Order No.</label>
                                            <div class="col-sm-8">
                                                <input type='text' class='form-control ponumber' value="<?php echo $po.appendPoRevSuffix($po); ?>" ponumber='<?php echo $po; ?>' readonly>
                                            </div>
                                        </div>
                                        <!--<div class="form-group">
                                            <label class="control-label col-sm-4">Order Date</label>
                                            <div class="col-sm-8">
                                               <input type='text' class='form-input form-control orderdate' value='<?php echo $orderdate; ?>' readonly>
                                           </div>
                                        </div>-->
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Supplier</label>
                                            <div class="col-sm-8">
                                                <textarea class='form-input form-control' suppid='<?php echo $supplierId; ?>' readonly><?php echo $suppliername; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Supplier Address</label>
                                            <div class="col-sm-8">
                                                <textarea rows='3' class='form-input form-control supplieraddress disabled' readonly><?php echo $supplieraddr; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Attn</label>
                                            <div class="col-sm-8">
                                                <input type='text' class='form-input form-control attn' value='<?php echo $attn; ?>' readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Currency</label>
                                            <div class="col-sm-8">
                                                <input type='text' class='form-input form-control' value='<?php echo $currency;?>' readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Ship To</label>
                                            <div class="col-sm-8">
                                                <input type='text' class='form-input form-control' value='<?php echo $facshipto;?>' readonly>
                                            </div>
                                        </div>
                                        
                                        

                                        
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Date Needed</label>
                                            <div class="col-sm-8">
                                               <input type='text' class='form-input form-control dateneeded' value='<?php echo $dateneeded; ?>' readonly>
                                           </div>
                                        </div>
                                        
                                        <!--<div class="form-group">
                                            <label class="control-label col-sm-4">Expected Delivery</label>
                                            <div class="col-sm-8">
                                                <input type='date' class='form-input form-control deliverydate' value='<?php echo $deliverydate; ?>' readonly>
                                            </div>
                                        </div>-->
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Terms</label>
                                            <div class="col-sm-8">
                                                <textarea class='form-input form-control paymentterms disabled' readonly><?php echo $poterms; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Ship Via</label>
                                            <div class="col-sm-8">
                                                <input type='text' class='form-input form-control shipvia' title='<?php echo $via;?>' value='<?php echo $via;?>' readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Mode of Shipment</label>
                                            <div class="col-sm-8">
                                                <input type='text' class='form-input form-control modeofshipment' title='<?php echo $mode;?>' value='<?php echo $mode;?>' readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Remarks</label>
                                            <div class="col-sm-8">
                                                <textarea class='form-input form-control remarks disabled' readonly><?php echo $remarks; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Prepared by</label>
                                            <div class="col-sm-8">
                                                <input type='text' class='form-input form-control preparedby' value='<?php echo $preparedby;?>' readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Prepared date</label>
                                            <div class="col-sm-8">
                                                <input type='text' class='form-input form-control prepareddate' value='<?php echo $prepareddate;?>' readonly>
                                            </div>
                                        </div>
                                        
                                        
                                        
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="pane" id='poapproval-approverspane'>
                            <div class="pane-content">
                                <form class='form-horizontal'>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <!-- <label class="control-label col-sm-5">Asst. Manager</label> -->
                                            <label class="control-label col-sm-5">Section Head</label>
                                            <div class="col-sm-7">
                                                <span class='form-control-static sectionhead1'><?php echo $approverfirst; ?></span>
                                            </div>
                                        </div>
                                       <div class="form-group">
                                            <!-- <label class="control-label col-sm-5">General Manager</label> -->
                                            <!-- <label class="control-label col-sm-5">Asst. VP</label> -->
                                            <label class="control-label col-sm-5">Department Head</label>
                                            <div class="col-sm-7">
                                                <span class='form-control-static sectionhead2'><?php echo $approversecond; ?></span>
                                            </div>
                                        </div> 
                                        <div class="form-group">
                                            <label class="control-label col-sm-5">Division Head</label>
                                            <div class="col-sm-7">
                                                <span class='form-control-static sectionhead3'><?php echo $approverdivision; ?></span>
                                            </div>
                                        </div>      
                                        <br>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label col-sm-5">President</label>
                                            <div class="col-sm-7">
                                               <span class='form-control-static asstvp'><?php echo $approverlast; ?></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-5">Last approved by</label>
                                            <div class="col-sm-7">
                                               <span class='form-control-static lastapprovedby' id='prnumber'>
                                                    <?php echo getLastPoApprover($po); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class='pane hidden' id='poapproval-discrepancy'>
                        </div>
                        <div class='pane hidden' id='poapproval-revisionremarks'>

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
                                            <table class='table table-striped table-bordered no-side-borders table-condensed table-hover mytable' cellspacing='0'>
                                                <thead>
                                                    <tr>
                                                        <th class='text-center'>LINE</th>
                                                        <th>ITEM CODE</th>
                                                        <th>NAME</th>
                                                        <th>DESCRIPTION</th>
                                                        <th class='text-center'>CLASSF'N CODE</th>
                                                        
                                                        <th class='text-center'>UOM</th>
                                                        <th class='text-center'>CURRENCY</th>
                                                        <th class='text-right'>QTY</th>
                                                        <th class='text-right'>UNIT PRICE</th>
                                                        <th class='text-right'>AMOUNT</th>
                                                    </tr>
                                                </thead>
                                                <tbody id='pritems'>
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
                                                                            order_number='$po'";
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
                                                                $qty = $obj->quantity;
                                                                $price = $obj->unit_price;
                                                                $uom = $obj->unit_of_measure_code;
                                                                $classfn = $obj->classification_code;
                                                                $floatamount = $price*$qty;
                                                                $amount = $price * $qty;
                                                                $grandtotal = $grandtotal + round($amount,2);
                                                                $qty = convertWithDecimal($qty,0);
                                                                $price = convertWithDecimal($price,5);
                                                                $amount = convertWithDecimal($amount,2);
                                                                echo "<tr prrowid='$itemId'>
                                                                            <td class='text-center'>$line</td>
                                                                            <td class='text-center'>$itemcode</td>
                                                                            <td>$shortdesc</td>
                                                                            <td>$longdesc</td>
                                                                            <td class='text-center'>$classfn</td>
                                                                            
                                                                            <td class='text-center'>$uom</td>
                                                                            <td class='text-center'>$currencysymbol</td>
                                                                            <td class='text-right'>$qty</td>
                                                                            <td class='text-right rowprice'>$price</td>
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
                                                            <div class="input-group-addon"><b><?php echo $currencysymbol; ?></b></div>
                                                             <input type='text' class='form-control text-right' readonly value='<?php echo $grandtotal; ?>'>
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
                    <button class='btn btn-blue2 mybtn' id='poapproval-approvebtn' data-toggle='modal' href='#poapprovedmodal' ponumber="<?php echo $po; ?>" poqty="<?php echo $po_qty; ?>" prqty="<?php echo $pr_qty; ?>">Approve</button>
                        <button class='btn btn-blue2 mybtn' id='poapproval-rejectbtn' data-toggle='modal' href='#rejectionmodal'>Reject</button>
                        <button class='btn btn-blue2 mybtn' id='poapproval-cancelbtn'>Back</button>
                        <button class='btn btn-blue2 mybtn' id='poapproval-viewbtn'>VIEW PO FORM</button>

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
                    Purchase Order
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
                    <label class="control-label col-sm-4">Reason for Rejection</label>
                    <div class="col-sm-8">
                        <textarea class='form-input form-control rejectreason' rows='4'></textarea>
                    </div>
                </div>
                                          
            </div>
            <div class="modal-footer">
                            <div class="panel-content text-center">
                                <button class='btn btn-blue2 mybtn' id='poapprovalmodal-rejectbtn'>Reject</button>
                                <button class='btn btn-blue2 mybtn modal-cancelbtn' id='poapprovalmodal-cancelbtn'>Cancel</button>
                            </div>
            </div>
        </div>
    </div>  
</div>

<div class="modal fade" id="poapprovedmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Purchase Order Approval
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="errordiv"></div>
                    </div>
                </div>
                <input type='hidden' class='poapprovedponumber'>
                <input type='hidden' class='poqty'>
                <input type='hidden' class='prqty'>
                <div class="form-group">
                    <label class="control-label col-sm-4">Remarks</label>
                    <div class="col-sm-8">
                        <textarea class='form-input form-control poapprovedremarks' rows='4'></textarea>
                    </div>
                </div>
                                    
            </div>
            <div class="modal-footer">
                            <div class="text-center">
                                <button class='btn btn-blue2 mybtn' id='poapprovedmodal-approvebtn'>Approve</button>
                                <button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
                            </div>
            </div>
        </div>
    </div>  
</div>

<script type="text/javascript">
	$('.shipvia').val(<?php echo json_encode($via); ?>);

    var withdiscrepancy = <?php echo json_encode($withdiscrepancy); ?>,
        forrevision = <?php echo json_encode($forrevisionremarks); ?>,
        ponumber = <?php echo json_encode($po); ?>;


    if(withdiscrepancy==1){
        $('#poapproval-discrepancytab').removeClass('hidden');
        $('#poapproval-discrepancy').removeClass('hidden').load('Other/purchase-order-discrepancy-details.php?Kbdkldriiopirn389yd9830='+ponumber);
    }
    
    if(forrevision>0){
        $('#poapproval-revisionremarkstab').removeClass('hidden');
        $('#poapproval-revisionremarks').removeClass('hidden').load('Other/purchase-order-revision-remarks.php?Kbdkldriiopirn389yd9830='+ponumber);
    }
    $('.modal-dialog').draggable();

    $(document).off('click','#poapproval-viewbtn').on('click','#poapproval-viewbtn', function(){
        window.open('Printouts/purchase-order-printout_draft.php?jkHIejPoeiwMlkdA='+ponumber);
    });

</script>