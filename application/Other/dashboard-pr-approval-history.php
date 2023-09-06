<?php
	include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/functions.php");
    //include("../../config/db-function.php");
		$index = isset($_GET['Kdjroi34hl5k4oajDLeo'])?$_GET['Kdjroi34hl5k4oajDLeo']:'';
		if($index==''){
			exit('DIRECT ACCESS NOT ALLOWED.');
		}
		else{
			$index = ($index-1)*10;
			$query = "select * 
					  from purchase_requisition_approval_history, 
					       purchase_requisition_header 
					  where purchase_requisition_header.requisition_number=purchase_requisition_approval_history.requisition_number and 
					        user_id='$userid' order by date desc LIMIT $index, 10";
			$rs = query($query);
			if(getNumRows($rs)<1){
				echo "<tr><td class='noresult' colspan='20'>No Results Found</td></tr>";
			}
			while($obj=fetch($rs)){
				$pr = $obj->requisition_number;
				$action = $obj->action;
				$reason = strUtfEncode($obj->reason_for_rejection);
				$requisitioner = getUserName($obj->requisitioner);
				$date = formatDate2($obj->date);
				$flag = $obj->pr_flag;
				echo "<tr class='tablerow prrowlog pointer' prnumber='$pr' prflag='$flag'>
						<td class='text-center'>$action</td>
						<td class='text-center'>$pr</td>
						<td>$requisitioner</td>
						<td>$reason</td>
						<td class='text-center'>$date</td>
					  </tr>";
			}
	}
?>
