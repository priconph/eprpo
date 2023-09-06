<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/functions.php");
    ini_set("max_execution_time", 0);

?>
<form class='form-horizontal'>
    <div class="form-group">

<?php
    $pr = isset($_GET['Kbdkldriiopirn389yd9830'])?$_GET['Kbdkldriiopirn389yd9830']:'';
    if($pr!=''){
        $sources = array();
        $remarks = '';
        $rs = query("select * from purchase_requisition_revision_sources where requisition_number='$pr'");
        while($obj=fetch($rs)){
            array_push($sources, $obj->order_number);
        }

        for($i=0;$i<count($sources);$i++){
            $reason = trim(getStatusRemarks('purchase_order_revision_history',"order_number='".$sources[$i]."'",'po_revision_id',"remarks")).'&#13;&#13;';
            $remarks = strUtfEncode($remarks.$reason);
        }
        
        echo "<label class='control-label col-md-2'>Revision Remarks</label>
                <div class='col-md-10'>
                    <textarea class='form-input form-control' rows='8' readonly>$remarks</textarea>
                </div>";
        
    }
?>
    </div>
</form>


