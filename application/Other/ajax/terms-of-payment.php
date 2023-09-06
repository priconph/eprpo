<?php
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

	$data = array();

	$rs = query("select payment_terms.id,
	    			    payment_terms_code,
	    				description,
	    				days,
	    				payment_terms.created_by_user_id,
	    				payment_terms.created_date,
	    				payment_terms.updated_by_user_id,
	    				payment_terms.updated_date 
	    		   from payment_terms"
				);

	$hidebtn = (userAccess(USERID,'.editpaymenttermbtn')==false)?'hidden':'';
	while($obj=mysql_fetch_object($rs)){
		$id = $obj->id;
		$code = $obj->payment_terms_code;
		$desc = strUtfEncode($obj->description);
		$days = $obj->days;
		$createdby = $obj->created_by_user_id;
		$createdby = strUtfEncode(getUserName($createdby));
		$createddate = formatDate4($obj->created_date);
		$updatedby = $obj->updated_by_user_id;
		$updatedby = strUtfEncode(getUserName($updatedby));
		$updateddate = formatDate4($obj->updated_date);

		$arraydata =  array(	
								'CHECKBOX'=>"<input type='checkbox' class='itemcheckbox' rowid='$id'>",
								'ACTION'=>"<img id='$id' code='$code' desc='$desc' days='$days' class='nopadding $hidebtn editpaymenttermbtn' src='../resources/img/File_Edit.png' title='Edit Terms' data-toggle='modal' href='#editpaymenttermsmodal'>",
								'ID'=>$id, 
								'CODE'=>$code, 
								'DESC'=>$desc, 
								'DAYS'=>$days, 
								'DATE CREATED'=>$createddate,
								'CREATED BY'=>$createdby,
								'LAST MODIFIED'=>$updateddate,
								'MODIFIED BY'=>$updatedby,
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