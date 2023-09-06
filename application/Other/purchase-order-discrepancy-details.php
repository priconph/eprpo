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

		echo "<label class='control-label col-md-2'>Discrepancy</label>
				<div class='col-md-10'>
					<textarea class='form-input form-control' rows='5' readonly>";

		 	$rs = query("select * from purchase_order_variance_details where order_number='$orderNum' and flag='1'");
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
