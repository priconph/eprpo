<?php
	include("config/connection.php");
	include("config/db-function.php");
	
	//Get PO with order_status = 'PARTIAL DELIVERY' and balance as 0
	   $qry = "SELECT
			   DISTINCT(poh.order_number) 
			FROM
			   purchase_order_header poh 
			   INNER JOIN
				  purchase_order_details pod 
				  ON pod.order_number = poh.order_number 
			WHERE
			   poh.order_status = 'PARTIAL DELIVERY' 
			   AND 
			   (
			(
				  SELECT
					 SUM(prd.quantity) 
				  FROM
					 purchase_order_header prh 
					 INNER JOIN
						purchase_order_details prd 
						ON prh.order_number = prd.order_number 
				  WHERE
					 prh.order_number = poh.order_number LIMIT 0, 1) - (
					 SELECT
						SUM(rd.quantity_received) 
					 FROM
						receiving_header rh 
						INNER JOIN
						   receiving_details rd 
						   ON rh.receiving_number = rd.receiving_number 
					 WHERE
						rh.reference_po_number = poh.order_number LIMIT 0, 1)
			   )
			   = 0.00 LIMIT 0,100";


		$rs = query($qry);
		
		$rscount = getNumRows($rs);
		if($rscount<1){
			echo "No Results Found. Good job! :)<br><br>";
		}
		while($obj=fetch($rs)){
			echo "UPDATE purchase_order_header SET order_status = 'SERVED', manually_updated_status=1 WHERE order_number = '{$obj->order_number}'; <br>";
		}


echo "Reload every 5 seconds<br><br>";

header("refresh: 5; url = http://systemone/eprpo/manual_po_update");
echo date('H:i:s Y-m-d');
exit;


?>