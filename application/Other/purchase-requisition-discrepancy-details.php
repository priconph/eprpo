<?php

	ini_set("max_execution_time", 0);

	include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/functions.php");
?>
<form class='form-horizontal'>
	<div class="form-group">

<?php
	$pr = isset($_GET['Kbdkldriiopirn389yd9830'])?$_GET['Kbdkldriiopirn389yd9830']:'';
	if($pr!=''){

		echo "<label class='control-label col-md-2'>Discrepancy</label>
				<div class='col-md-10'>
					<textarea class='form-input form-control' rows='8' readonly>";

		 	$rs = query(
		 			   "select * 
		 				from purchase_order_variance_details, 
		 					 purchase_requisition_revision_sources 
		 				where purchase_order_variance_details.order_number=purchase_requisition_revision_sources.order_number and
		 					  requisition_number='$pr' and 
		 					  flag='1'"
		 			   );
            $count = getNumRows($rs);
            $line = 1;
            while($obj=fetch($rs)){
            	echo $line.')  '.$obj->variance_remarks.'&#13;';
				$line++;
            }
			  echo "</textarea>
				</div>";
		
	}
?>
	</div>
</form>
