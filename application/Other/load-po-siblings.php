<?php
	include("../../config/connection.php");
	include("../../config/functions.php");


	$ponumber = isset($_GET['ponumber'])?escapeString($_GET['ponumber']):'';
	if($ponumber!=''){
		$pr = null;
		$rs = query("select * from purchase_order_header where order_number='$ponumber'");
		while($obj=fetch($rs)){
			$pr = $obj->reference_requisition_number;
		}

		$rs = query("select * from purchase_order_header where reference_requisition_number='$pr'");
		while($obj=fetch($rs)){
			$po = $obj->order_number;
			$status=$obj->order_status;
			$checkbox = '';
			$reasonfield = '';
			if($status!='CANCELLED'&&$status!='FOR CANCELLATION'){
				if($po==$ponumber){
					$checked = "checked";
				}
				else{
					$checked = "";
				}
				$checkbox = "<input style='-webkit-transform: scale(1.5);' type='checkbox' class='pocancel-checkbox' ponumber='$po' $checked>";
				$reasonfield = "<textarea rows='2' class='form-control pocancel-reasonfield' ponumber='$po'></textarea>";
			}
			
			echo "<tr prnumber='$pr'>	
						<td class='text-center'>$checkbox</td>
						<td>$po</td>
						<td class='text-center'>$status</td>
						<td>$pr</td>
						<td>$reasonfield</td>

				  </tr>";
		}
	}
	else{
		echo "Invalid PO Number";
	}






?>