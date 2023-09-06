<?php
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

	$data = array();

	$rs = query("select * 
				from purchase_requisition_header 
				where ((section_head1='$userid' and flag='0' and sh_approved is null) or
				       (section_head2='$userid' and flag='1' and sh2_approved is null) or
					   (conformance='$userid' and flag='2' and conf_approved is null) or
					   (general_manager='$userid' and flag='3' and gm_approved is null) or
					   (assistant_vice_president='$userid' and flag='4' and avp_approved is null) or
					   (senior_vice_president='$userid' and flag='5' and svp_approved is null) or
					   (president='$userid' and flag='6' and pr_approved is null)) and requisition_status='IN REVIEW'
				order by last_made_changes desc"
				);
	$line = 1;
	$hidebtn = (userAccess(USERID,'.editpaymenttermbtn')==false)?'hidden':'';
	while($obj=mysql_fetch_object($rs)){
		$prnumber = $obj->requisition_number;
		$reqtype = strtoupper($obj->requisition_type);
		$dateneeded = $obj->date_needed;
		$dateneeded = date('M d, Y', strtotime($dateneeded));
		$requisitioner = getUserName($obj->requisitioner);
		$numofrev = $obj->next_revision_series;


		$arraydata =  array(	
								'ACTION'=>"<img id='$prnumber' class='nopadding prapprovalbutton' src='../resources/img/File_List.png'>",
								'NO'=>$line, 
								'TYPE'=>$reqtype, 
								'PRNUM'=>$prnumber.appendPrRevSuffix($prnumber), 
								'REQUISITIONER'=>$requisitioner, 
								'DATENEEDED'=>$dateneeded
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