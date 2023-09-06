<?php
    include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include('../config/functions.php');
    //include("../config/db-function.php");
	
	if(isset($_POST['OIrlksfho'])){
		if($_POST['OIrlksfho']=='Kh4Iiou&3hdk#k'){
			$data = array();
			$supplier = escapeString($_POST['supplierId']);
			$query = "select order_number from purchase_order_header, supplier where supplier.id=supplier_id and supplier_id='$supplier' and (order_status='UNSERVED' or order_status='PARTIAL DELIVERY' or order_status='HOLD')";
			$rs = query($query);
			while($obj=fetch($rs)){
				$po = $obj->order_number;
				array_push($data, $po);
			}
			echo implode('%', $data);
		}
	}
?>