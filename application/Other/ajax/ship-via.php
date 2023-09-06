<?php
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

	$data = array();

	$rs = query("select * from ship_via");

	$hidebtn = (userAccess(USERID,'.editshipviabtn')==false)?'hidden':'';
	while($obj=mysql_fetch_object($rs)){
		$id = $obj->id;
		$desc = strUtfEncode($obj->ship_by);
		$createdby = strUtfEncode(getUserName($obj->created_by_user_id));
		$createddate = formatDate4($obj->created_date);
		$updatedby = $obj->updated_by_user_id;
		$updatedby = strUtfEncode(getUserName($updatedby));
		$updateddate = formatDate4($obj->updated_date);		

		$arraydata =  array(	
								'CHECKBOX'=>"<input type='checkbox' class='itemcheckbox' rowid='$id'>",
								'ACTION'=>"<img id='$id' desc='$desc' class='nopadding $hidebtn editshipviabtn' src='../resources/img/File_Edit.png' title='Edit Ship Via' data-toggle='modal' href='#editshipviamodal'>",
								'ID'=>$id, 
								'DESC'=>$desc, 
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