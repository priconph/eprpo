<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/functions.php");
?>
<form class='form-horizontal'>
    <div class="form-group">

<?php
    $orderNum = isset($_GET['Kbdkldriiopirn389yd9830'])?$_GET['Kbdkldriiopirn389yd9830']:'';
    if($orderNum!=''){
        $reason = strUtfEncode(trim(getStatusRemarks('purchase_order_revision_history',"order_number='".$orderNum."'",'po_revision_id',"remarks")));
        echo "<label class='control-label col-md-2'>Revision Remarks</label>
                <div class='col-md-10'>
                    <textarea class='form-input form-control' rows='5' readonly>$reason</textarea>
                </div>";
        
    }
?>
    </div>
</form>


