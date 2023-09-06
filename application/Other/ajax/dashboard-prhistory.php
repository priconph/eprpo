<?php
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

	$data = array();

	$rs = query("select * 
				from purchase_requisition_approval_history, 
				purchase_requisition_header 
				where purchase_requisition_header.requisition_number=purchase_requisition_approval_history.requisition_number order by date desc LIMIT 5000"
				);
	$line = 1;
	$hidebtn = (userAccess(USERID,'.editpaymenttermbtn')==false)?'hidden':'';
	while($obj=mysql_fetch_object($rs)){
		$pr = $obj->requisition_number;
		$action = $obj->action;
		$revnum = $obj->pr_flag;
		$revnum = $revnum==0?'R0':'R'.$revnum;
		$reason = $obj->reason_for_rejection;
		$requisitioner = getUserName($obj->requisitioner);
		$date = formatDate2($obj->date);
		$flag = $obj->pr_flag;
		$user = getUserName($obj->user_id);

		switch ($obj->pr_approval_flag){
			case '0':
			$approvaltype = 'Section Head 1';
			break;
			case '1':
			$approvaltype = 'Section Head 2';
			break;
			case '2':
			$approvaltype = 'Conformance';
			break;
			case '3':
			$approvaltype = 'General Manager';
			break;
			case '4':
			$approvaltype = 'Assistant Vice President';
			break;
			case '5':
			$approvaltype = 'Senior Vice President';
			break;
			case '6':
			$approvaltype = 'President';
			break;
			default:
			$approvaltype = '';
			break;
		}


		$arraydata =  array(	
								'BUTTON'=>"<img prnumber='$pr' title='View Details' prflag='$flag' class='nopadding prhistorybutton' src='../resources/img/File_Edit.png'>",
								'ACTION'=>$action,
								'PRNUM'=>$pr, 
								'REVISION'=>$revnum, 
								'TYPE'=>$approvaltype, 
								'REQUISITIONER'=>$requisitioner,
								'REMARKS'=>$reason,
								'DATE'=>$date,
								'USER'=>$user
							);
		array_push($data, $arraydata);
		$line++;
	}
	$totalnumrows = mysql_num_rows($rs);

    $results = array(
            		"sEcho" => 1,
        			"iTotalRecords" => $totalnumrows,
        			"iTotalDisplayRecords" => 1,
          			"aaData"=>$data
          			);

	echo json_encode($results);



?>