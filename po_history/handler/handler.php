<?php
	if(is_ajax()) {
		if(isset($_POST["action"]) && !empty($_POST["action"])) {
			$action = $_POST["action"];
			switch($action) {
				/* Asset Category */
				case "display_po_list"				: display_po_list(); break;
				case "get_po_history_details"		: get_po_history_details(); break;
			}
		}
	}

	function is_ajax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

	/* Asset Category */
	function display_po_list(){
		require_once('../oop/oop.php');
		
		$return 		= $_POST;
		$result 		= '';
		$array_fields 	= array('a.order_number, a.order_status, b.supplier_name, c.requisition_date, c.date_needed, a.date_flag, d.remarks, d.revision_number');
		$table 	   		= 'purchase_order_header a ';
		$joins 	   		= 'INNER JOIN supplier b ON b.id  = a.supplier_id
							INNER JOIN purchase_requisition_header c ON c.requisition_number = a.reference_requisition_number
							INNER JOIN purchase_order_revision_history d ON d.order_number = a.order_number';
		$sql_where 		= "";
		$sql_order 		= 'GROUP BY a.order_number ORDER BY a.order_number DESC';
		$sql_limit 		= '';
		$result 		= EPRPO::getInstance()->select_query_eprpo($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);

		if (!$result) {
			echo mysql_error();
		}
		$ctr = 0;
		while($row = mysqli_fetch_array($result)){
						
			$return['order_number'][$ctr] 			= $row['order_number'];
			$return['order_status'][$ctr] 			= $row['order_status'];
			$return['supplier_name'][$ctr] 			= $row['supplier_name'];
			$requisition_date				 		= $row['requisition_date'];
			$return['requisition_date'][$ctr] 		= date('M d, Y H:i:s',strtotime($requisition_date));
			$date_needed				 			= $row['date_needed'];
			$return['date_needed'][$ctr] 			= date('M d, Y',strtotime($date_needed));
			$date_flag				 				= $row['date_flag'];
			$return['date_flag'][$ctr] 				= date('M d, Y H:i:s',strtotime($date_flag));
			$ctr++;
		}

		$return['ctr']	  		= $ctr;
		$return['result'] 		= json_encode($result);
		echo json_encode($return);

	}	
	
	function get_po_history_details(){
		require_once('../oop/oop.php');
		
		$return 		= $_POST;
		$result 		= '';
		$array_fields 	= array('a.order_number, b.remarks, b.revision_number');
		$table 	   		= 'purchase_order_header a ';
		$joins 	   		= 'INNER JOIN purchase_order_revision_history b ON b.order_number = a.order_number';
		$sql_where 		= "WHERE a.order_number = '".$return['order_number']."' ";
		$sql_order 		= '';
		$sql_limit 		= '';
		$result 		= EPRPO::getInstance()->select_query_eprpo($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);

		if (!$result) {
			echo mysql_error();
		}
		$ctr = 0;
		$order_number = array();

		while($row = mysqli_fetch_array($result)){
		
			if(!in_array($row['order_number'],$order_number)){
				$order_number[] = $row['order_number'];
			}

			$return['order_number'][$ctr] 			= $row['order_number'];
			$return['remarks'][$ctr] 				= $row['remarks'];
			$return['revision_number'][$ctr] 		= $row['revision_number'];
			$ctr++;
		}

		$return['order_number']	= $order_number;
		$return['ctr']	  		= $ctr;
		$return['result'] 		= json_encode($result);
		echo json_encode($return);

	}	
	
?>

