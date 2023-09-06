<?php
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

	$data = array();

	$rs = query("select * from transaction_type"
				);

	$hidebtn = (userAccess(USERID,'.edittranstypebtn')==false)?'hidden':'';
	while($obj=mysql_fetch_object($rs)){
		$id=$obj->id;
		$code=$obj->transaction_type_code;
		$desc=strUtfEncode($obj->transaction_description);
		$prefix=$obj->prefix;
		$num=$obj->next_number_series;
		$updatedby=$obj->updated_by_user_id;
		$updatedby = strUtfEncode(getUserName($updatedby));
		$updateddate=formatDate4($obj->updated_date);

		

		$arraydata =  array(	
								'CHECKBOX'=>"<input type='checkbox' class='itemcheckbox'>",
								'ACTION'=>"<img id='$id' code='$code' desc='$desc' prefix='$prefix' numseries='$num' class='nopadding edittranstypebtn $hidebtn' src='../resources/img/File_Edit.png' title='Edit Transaction Type' data-toggle='modal' href='#edittransactiontypemodal'>",
								'ID'=>$id, 
								'CODE'=>$code, 
								'DESC'=>$desc, 
								'PREFIX'=>$prefix,
								'SERIES'=>$num,
								'LAST MODIFIED'=>$updateddate,
								'MODIFIED BY'=>$updatedby
							);
		array_push($data, $arraydata);
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