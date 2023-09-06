<?php
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

	$data = array();

	$rs = query("select * from section_department");

	$hidebtn = (userAccess(USERID,'.editdepartmentbtn')==false)?'hidden':'';
	while($obj=mysql_fetch_object($rs)){
		$id = $obj->id;
		$code = $obj->section_department_code;
		$name = strUtfEncode($obj->section_department_name);
		$createdby = $obj->created_by_user_id;
		$createdby = strUtfEncode(getUserName($createdby));
		$createddate = formatDate4($obj->created_date);
		$updatedby = $obj->updated_by_user_id;
		$updatedby=strUtfEncode(getUserName($updatedby));
		$updateddate = formatDate4($obj->updated_date);	
		$nextnumseries = $obj->next_number_series;	
		$status = $obj->status;	

		$arraydata =  array(	
								'CHECKBOX'=>"<input type='checkbox' class='itemcheckbox' rowid='$id'>",
								'ACTION'=>"<img id='$id' code='$code' nextnum='$nextnumseries' status='$status' name='$name' class='nopadding editdepartmentbtn $hidebtn' src='../resources/img/File_Edit.png' title='Edit Section/Department' data-toggle='modal' href='#editdepartmentmodal'>",
								'ID'=>$id, 
								'CODE'=>$code, 
								'STATUS'=>$status, 
								'NAME'=>$name,
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