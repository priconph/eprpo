<?php
    include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include('../config/functions.php');
    //include("../config/db-function.php");
	
	if(isset($_POST['JFKberkjoIfj23jl'])){
		if($_POST['JFKberkjoIfj23jl']=='URhksoi3Hsri'){
			$reference = strtoupper(escapeString($_POST['reference']));
			$carrier = escapeString($_POST['carrier']);
			$remarks = escapeString($_POST['remarks']);
			$suppid = escapeString($_POST['supplierid']);
			$currency = escapeString($_POST['currency']);
			$actualdate = date('Y-m-d', strtotime($_POST['actualdate']));
			@$prices = $_POST['prices'];
			@$items = $_POST['items'];
			@$qtys = $_POST['qtys'];
			$rcvnum = getTransactionNumber(3);
			$length = count($items);
			$date=date('Y-m-d H:i:s');

			//noncatalog
			@$noncode = $_POST['noncode'];
			$nonlength = count($noncode);
			@$nonname = $_POST['nonname']; 
			@$nondesc = $_POST['nondesc'];
			@$nonqty = $_POST['nonqty'];
			@$nonprice = $_POST['nonprice'];
			@$nonuom = $_POST['nonuom'];

			$query = "insert into receiving_header(receiving_number,reference_po_number,other_reference,supplier_id,received_date,received_by,carrier,transaction_type,remarks,currency,actual_delivery_date)
											values('$rcvnum',NULL,'$reference','$suppid','$date','$userid','$carrier','NON-PO','$remarks','$currency','$actualdate')";
			$rs = query($query);
			for($i=0;$i<$length;$i++){
				$item = escapeString($items[$i]);
				$qty = escapeString($qtys[$i]);
				$price = escapeString($prices[$i]);
				$query = "insert into receiving_details(receiving_number,item_id,quantity_received,unit_price)
											values('$rcvnum','$item','$qty','$price')";
				$rs=query($query);
			}
			for($i=0;$i<$nonlength;$i++){
				$code = escapeString($noncode[$i]);
				$name = escapeString($nonname[$i]);
				$desc = escapeString($nondesc[$i]);
				$qty = escapeString($nonqty[$i]);
				$price = escapeString($nonprice[$i]);
				$uom = escapeString($nonuom[$i]);
				$query = "insert into receiving_details_non_catalog(receiving_number,item_code,item_name,item_description,quantity_received,unit_of_measure,unit_price)
											values('$rcvnum','$code','$name','$desc','$qty','$uom','$price')";
				$rs=query($query);
			}
			if($rs){
				echo 'success';
			}

		}
	}
?>
