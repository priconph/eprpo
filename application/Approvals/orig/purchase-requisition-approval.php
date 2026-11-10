<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/checklogin-page.php");
    include('../../config/functions.php');
   $pr=(isset($_GET['LdekFprJdlURd']))?$_GET['LdekFprJdlURd']:'';
   if($pr==''){
        header('location:../../main.php');
    }
    else{
        $query = "select * from purchase_requisition_header, currency where requisition_number='$pr' and currency=currency.id";
        $rs=query($query);
        while($obj=fetch($rs)){
            $status = $obj->requisition_status;
            $revnumber = $obj->next_revision_series;
            $requisitioner = strUtfEncode($obj->requisitioner);
            $reqdate = formatDate3($obj->requisition_date);
            $reqtype = $obj->requisition_type;
            $dateneeded = formatDate3($obj->date_needed);
            $remarks = strUtfEncode($obj->justification);
            $currency = $obj->currency_name;
            $currencysymbol = $obj->currency_code;
            $prnotes = strUtfEncode($obj->pr_notes);
            //$forrevision = $obj->revision_flag;

            //////////////////////
            $lastapprovedby = getLastPrApprover($pr,$revnumber);
            $approver_conf = ($obj->conformance=='1')?'Not Applicable':(($obj->conformance==NULL)?'Not Required':getUserName($obj->conformance)).' '.getPrApprovalTimestampWithRev($pr,$obj->conformance,2,$revnumber);
            $approver_sh1 =($obj->section_head1=='1')?'Not Applicable':(($obj->section_head1==NULL)?'Not Required':getUserName($obj->section_head1)).' '.getPrApprovalTimestampWithRev($pr,$obj->section_head1,0,$revnumber);
            $approver_sh2 =($obj->section_head2=='1')?'Not Applicable':(($obj->section_head2==NULL)?'Not Required':getUserName($obj->section_head2)).' '.getPrApprovalTimestampWithRev($pr,$obj->section_head2,1,$revnumber);
            $approver_gm = ($obj->general_manager=='1')?'Not Applicable':(($obj->general_manager==NULL)?'Not Required':getUserName($obj->general_manager)).' '.getPrApprovalTimestampWithRev($pr,$obj->general_manager,3,$revnumber);
            $approver_avp = ($obj->assistant_vice_president=='1')?'Not Applicable':(($obj->assistant_vice_president==NULL)?'Not Required':getUserName($obj->assistant_vice_president)).' '.getPrApprovalTimestampWithRev($pr,$obj->assistant_vice_president,4,$revnumber);
            $approver_svp = ($obj->senior_vice_president=='1')?'Not Applicable':(($obj->senior_vice_president==NULL)?'Not Required':getUserName($obj->senior_vice_president)).' '.getPrApprovalTimestampWithRev($pr,$obj->senior_vice_president,5,$revnumber);
            $approver_pr = ($obj->president=='1')?'Not Applicable':(($obj->president==NULL)?'Not Required':getUserName($obj->president)).' '.getPrApprovalTimestampWithRev($pr,$obj->president,6,$revnumber);
            ///////////////////
        }
        $forrevision = getRevisionSources($pr,'requisition_number');
        $withdiscrepancy = checkIfDiscrepancyExistPR($pr);
    }
?>
<div class='header-page' >
    <div class='header-page-inner'>
        Purchase Requisition Approval
    </div>
</div>
<div class="container-fluid">
    <div class="pageContent">
        <div class="panel panel-primary mypanel">
                <div class="panel-body">
                    <div class='tabpane-white margin-top-20 margin-bottom-10'>
                        <ul class="nav nav-tabs">
                              <li role="presentation" class="active" data-pane='#prapproval-geninfo'><a>General Information</a></li>
                              <li role="presentation" data-pane='#prapproval-approverspane'><a>Approvers</a></li>
                              <li role="presentation" class="hidden" data-pane='#prapproval-revisionremarks' id='prapproval-revisionremarkstab'><a>Revision Remarks</a></li>
                              <li role="presentation" class="hidden" data-pane='#prapproval-discrepancy' id='prapproval-discrepancytab'><a>Discrepancy Details</a></li>
                        </ul>
                    </div>
                    <div class='tab-panes'>
                        <div class='pane active' id='prapproval-geninfo'>
                            <div class="pane-content" style='padding-bottom:10px'>
                                <form class="form-horizontal">
                                    <div class="col-md-12">
                                        <div class="errordiv"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Requisition No.</label>
                                            <div class="col-sm-8">
                                                <input type='text' name='prnumber' prnumber='<?php echo $pr; ?>' class='form-input form-control reqnum' value="<?php echo $pr.appendPrRevSuffix($pr); ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Type</label>
                                            <div class="col-sm-8">
                                                <input type='text' class='form-input form-control' value="<?php echo strtoupper($reqtype); ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Department</label>
                                            <div class="col-sm-8">
                                                <input type='text' name='sec-dept-div' class='form-input form-control' value="<?php echo getDeptName($requisitioner); ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Requisitioner</label>
                                            <div class="col-sm-8">
                                                <input type='text' name='prnumber' class='form-input form-control' value="<?php echo getUserName($requisitioner); ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Currency</label>
                                            <div class="col-sm-8">
                                                <input type='text' class='form-input form-control' value="<?php echo $currency; ?>" readonly>
                                            </div>
                                        </div>
                                        <?php 
                                            if($forrevision>0){
                                                echo "<div class=\"form-group\">
                                                                <label class=\"control-label col-sm-4\">PR History</label>
                                                                <div class=\"col-sm-8\">
                                                                     <span class='form-control-static prappr-prhistorytoggle' style='font-style:italic; color:blue; cursor:pointer;'>Click here</span>
                                                                </div>
                                                </div>";
                                            
                                            }
                                        ?>

                                    </div>
                                    <div class="col-md-6">
                                        
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Requisition Date</label>
                                            <div class="col-sm-8">
                                                 <input type='text' class='form-input form-control dateinput reqdate' value="<?php echo $reqdate; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Date Needed</label>
                                            <div class="col-sm-8">
                                                 <input type='text' class='form-input form-control dateinput dateneeded' value="<?php echo $dateneeded; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Justification</label>
                                            <div class="col-sm-8">
                                                 <textarea rows='2' name='justification' class='form-input form-control justification disabled capitalize' readonly><?php echo $remarks; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Remarks</label>
                                            <div class="col-sm-8">
                                                 <textarea rows='2' class='form-input form-control prnotes disabled capitalize' readonly><?php echo $prnotes; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="pane" id='prapproval-approverspane'>
                            <div class="pane-content">
                                <form class='form-horizontal'>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label col-sm-5">Checked By / Section Head</label>
                                            <div class="col-sm-7">
                                                <span class='form-control-static sectionhead1'><?php echo $approver_sh1; ?></span>
                                            </div>
                                        </div>
                                       <div class="form-group">
                                            <label class="control-label col-sm-5">Section Head (2) </label>
                                            <div class="col-sm-7">
                                                <span class='form-control-static sectionhead2'><?php echo $approver_sh2; ?></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-5">Conformance Head</label>
                                            <div class="col-sm-7">
                                                <span class='form-control-static confhead'><?php echo $approver_conf; ?></span>
                                            </div>
                                        </div>
                                        <!-- <div class="form-group">
                                            <label class="control-label col-sm-5">General Manager</label>
                                            <div class="col-sm-7">
                                               <span class='form-control-static genman'><?php //echo $approver_gm; ?></span>
                                           </div>
                                        </div>             -->
                                        <br>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label col-sm-5">GM / AVP / VP</label>
                                            <div class="col-sm-7">
                                               <span class='form-control-static asstvp'><?php echo $approver_avp; ?></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-sm-5">Operations Division Head</label>
                                            <div class="col-sm-7">
                                               <span class='form-control-static srvp'><?php echo $approver_svp; ?></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-5">President</label>
                                            <div class="col-sm-7">
                                               <span class='form-control-static president'><?php echo $approver_pr; ?></span>
                                           </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-5">Last approved by</label>
                                            <div class="col-sm-7">
                                               <span class='form-control-static lastapprovedby' id='prnumber'><?php echo $lastapprovedby; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class='pane' id='prapproval-revisionremarks'>
                        </div>
                        <div class='pane' id='prapproval-discrepancy'>
                        </div>
                    </div>
                </div>
        </div>

        <div class="panel panel-primary mypanel">
                <div class="panel-body">
                    <div class='tabpane-white margin-top-20 margin-bottom-10' id='generalinfosection'>
                        <ul class="nav nav-tabs">
                              <li role="presentation" class="active" data-pane='#prapproval-lineitems'><a>Line Items</a></li>
                              <li role="presentation" data-pane='#prapproval-allocation'><a>Allocation Summary</a></li>
                              <li role="presentation" data-pane='#prapproval-attachments'><a>Attachments</a></li>
                        </ul>
                    </div>
                    <div class='tab-panes'>
                        <div class='pane active' id='prapproval-lineitems'>
                            <div class='tablewithbuttons'>
                                            <div class='table-wrapper' style='height:200px'>
                                                <table class='table table-striped table-bordered no-side-borders table-condensed table-hover mytable'>
                                                    <thead>
                                                        <tr>
                                                            <th class='text-center'>LINE</th>
                                                            <th>ITEM CODE</th>
                                                            <th>ITEM NAME</th>
                                                            <th>DESCRIPTION</th>
                                                            <th>SUPPLIER</th>
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
                                                                             item_name,
                                                                             classification_code,
                                                                             quantity,
                                                                             purchase_requisition_details.unit_price,
                                                                             unit_of_measure_code,
                                                                             long_description,
                                                                             supplier_name
                                                                      from purchase_requisition_details, item, supplier, unit_of_measure 
                                                                      where unit_of_measure_id=unit_of_measure.id and 
                                                                            item_id=item.id and 
                                                                            purchase_requisition_details.supplier_id=supplier.id and 
                                                                            requisition_number='$pr'";
                                                            $rs = query($query);
                                                            $line = 1;
                                                            $grandtotal = 0;
                                                            while($obj=fetch($rs)){
                                                                $itemcode = $obj->item_code;
                                                                $shortdesc = strUtfEncode($obj->item_name);
                                                                $desc = strUtfEncode($obj->long_description);
                                                                $classfn = strUtfEncode($obj->classification_code);
                                                                $qty = $obj->quantity;
                                                                $price = $obj->unit_price;
                                                                $uom = $obj->unit_of_measure_code;
                                                                $supplier = strUtfEncode($obj->supplier_name);
                                                                $amount = $price * $qty;
                                                                $grandtotal = $grandtotal + round($amount,2);
                                                                $qty = convertWithDecimal($qty,0);
                                                                $price = convertWithDecimal($price,5);
                                                                $amount = convertWithDecimal($amount,2);
                                                               
                                                                echo "<tr>
                                                                            <td class='text-center'>$line</td>
                                                                            <td class='text-nowrap'>$itemcode</td>
                                                                            <td>$shortdesc</td>
                                                                            <td>$desc</td>
                                                                            <td>$supplier</td>
                                                                            <td class='text-center'>$classfn</td>
                                                                            
                                                                            <td class='text-center'>$uom</td>
                                                                            
                                                                            <td class='text-center'>$currencysymbol</td>
                                                                            <td class='text-right'>$qty</td>
                                                                            <td class='text-right'>$price</td>
                                                                            <td class='text-right'>$amount</td>
                                                                      </tr>";
                                                                $line++;      
                                                            }
                                                            $grandtotal = convertWithDecimal($grandtotal,2);
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row">
                                                    <div class="col-sm-offset-6 col-sm-6" style='margin-top:10px;'>
                                                        <form class='form-horizontal'>
                                                                    <div class="form-group" >
                                                                        <label class='control-label col-sm-5'>Total Amount</label> 
                                                                        <div class="col-sm-7">
                                                                            <div class="input-group">
                                                                                <div class="input-group-addon"><b><?php echo $currencysymbol; ?></b></div>
                                                                                <input type='text' class='form-control text-right text-right' id='totalpramount' value="<?php echo $grandtotal; ?>" readonly>
                                                                            </div>
                                                                            
                                                                        </div>
                                                                    </div>          
                                                        </form>
                                                    </div>
                                                
                                            </div>
                                            
                            </div>
                        </div>
                        <div class='pane' id='prapproval-allocation'>
                            <div class="pane-content">
                                        <div class='table-wrapper' style='height:200px'>
                                            <table class='table table-striped table-condensed table-hover mytable' cellspacing='0'>
                                                <thead>
                                                    <tr>
                                                       <th class='text-center'>LINE</th>
                                                       <th>DEPARTMENT</th>
                                                       <th class='text-center'>PERCENTAGE %</th>
                                                       <th>REMARKS</th>
                                                    </tr>

                                                </thead>
                                                <tbody id='allocationsummary'>
                                                    <?php
                                                    $line = 1;
                                                    $query="select section_department_name, purchase_requisition_allocation.remarks, percentage
                                                            from purchase_requisition_allocation, section_department 
                                                            where requisition_number='$pr' and
                                                            department_id=section_department.id";
                                                    $rs = query($query);
                                                    while($obj=fetch($rs)){;
                                                        $dept = $obj->section_department_name;
                                                        $perc = $obj->percentage;
                                                        $remarks = strUtfEncode($obj->remarks);
                                                        echo "<tr>
                                                        <td class='text-center'>$line</td>
                                                        <td>$dept</td>
                                                        <td class='text-center'>$perc</td>
                                                        <td>$remarks</td>
                                                        </tr>";
                                                        $line++;
                                                    }
                                                        
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                            </div>
                        </div>
                        <div class='pane' id='prapproval-attachments'>
                            <div class="pane-content">
                                <div class="table-wrapper" style='height:200px'>
                                            <table class='table table-striped table-condensed table-hover mytable'>
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>FILE</th>
                                                        <th>DESCRIPTION</th>
                                                        <th>OTHER DESCRIPTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody id='attachmentstable'>
                                                <?php
                                                     $query = "select * from attachments where requisition_number='$pr'";
                                                     $rs = query($query);
                                                     if(getNumRows($rs)<1){
                                                        echo "<tr><td class='noresult' colspan='20'>No Attachments</td></tr>";
                                                    }
                                                     while($obj=fetch($rs)){
                                                        $filename = $obj->file_name;
                                                        $filedesc = $obj->file_description;
                                                        //temp
                                                        if($filedesc=='NULL'){
                                                            $filedesc = '';
                                                        }
                                                        //
                                                        $other = strUtfEncode($obj->other_description);
                                                        echo "<tr class='attachmentrow'>
                                                                    <td class='text-center'>
                                                                        <form action='Attachments/download.php' method='post'>
                                                                        <input type='hidden' name='file' value='$filename'>
                                                                        <input class='btn btn-xs btn-default' type='submit' value='download' name='downloadbtn'>
                                                                        </form>
                                                                    </td>
                                                                    <td>$filename</td>
                                                                    <td>$filedesc</td>
                                                                    <td>$other</td>
                                                                </tr>";
                                                    }
                                                ?>
                                                </tbody>
                                            </table>
                                </div>   
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        <div class="panel panel-primary mypanel">
                <div class="panel-body">
                    <div class="panel-content text-center">
                                <button class='btn btn-blue2 mybtn' id='pr-approval-approvebtn' data-toggle='modal' href='#prapprovedmodal'prnumber="<?php echo $pr; ?>">Approve</button>
                                <button class='btn btn-blue2 mybtn' id='pr-approval-rejectbtn' data-toggle='modal' href='#rejectionmodal'>Reject</button>
                                <button class='btn btn-blue2 mybtn' id='pr-approval-cancelbtn'>Back</button>
                    </div>
                </div>
        </div>


    </div>
</div>
<div class="modal fade" id="prapprovedmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Purchase Requisition Approval
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="errordiv"></div>
                    </div>
                </div>
                <input type='hidden' class='prapprovedprnumber'>
                <div class="form-group">
                    <label class="control-label col-sm-4">Remarks</label>
                    <div class="col-sm-8">
                        <textarea class='form-input form-control prapprovedremarks' rows='4'></textarea>
                    </div>
                </div>
                                    
            </div>
            <div class="modal-footer">
                            <div class="text-center">
                                <button class='btn btn-blue2 mybtn' id='prapprovedmodal-approvebtn'>Approve</button>
                                <button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
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
                    Purchase Requisition Rejection
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
                            <div class="text-center">
                                <button class='btn btn-blue2 mybtn' id='prmodal-rejectbtn'>Reject</button>
                                <button class='btn btn-blue2 mybtn modal-cancelbtn' id='itemlist-cancelbtn'>Cancel</button>
                            </div>
            </div>
        </div>
    </div>  
</div>


<script type="text/javascript">
var withdiscrepancy = <?php echo json_encode($withdiscrepancy); ?>,
        forrevision = <?php echo json_encode($forrevision); ?>,
        pr = <?php echo json_encode($pr); ?>;


        if(withdiscrepancy==1){
            $('#prapproval-discrepancytab').removeClass('hidden');
            $('#prapproval-discrepancy').removeClass('hidden').load('Other/purchase-requisition-discrepancy-details.php?Kbdkldriiopirn389yd9830='+pr);
        }
        
        if(forrevision>0){
            $('#prapproval-revisionremarkstab').removeClass('hidden');
            $('#prapproval-revisionremarks').removeClass('hidden').load('Other/purchase-requisition-revision-remarks.php?Kbdkldriiopirn389yd9830='+pr);
        }
        $('.modal-dialog').draggable();
</script>