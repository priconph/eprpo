<?php
	include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/functions.php");
    //include("../../config/db-function.php");
		$index = isset($_GET['Kdjoeiuwp3io3ipNdKlp'])?$_GET['Kdjoeiuwp3io3ipNdKlp']:'';
		if($index==''){
			exit('DIRECT ACCESS NOT ALLOWED.');
		}
		else{
			$index = ($index-1)*10;

			$query = "select * from purchase_order_approval_history where user_id='$userid' order by date desc limit $index,10";
			$rs = query($query);
			if(getNumRows($rs)<1){
				echo "<tr><td class='noresult' colspan='20'>No Results Found</td></tr>";
			}
			while($obj=fetch($rs)){
				$pr = $obj->order_number;
				$action = $obj->action;
				$reason = strUtfEncode($obj->reason_for_rejection);
				$date = formatDate($obj->date);
				$fl = $obj->po_flag;
				echo "<tr class='tablerow porowlog pointer' ponumber='$pr' poflag='$fl'>
				<td class='text-center'>$pr</td>
				<td class='text-center'>$action</td>
				<td>$reason</td>
				<td class='text-center'>$date</td>
				</tr>";
			}

		}

?>
