<?php
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

	$data = array();

	$rs = query("select * from user_group"
				);

	$hidebtn = (userAccess(USERID,'.editusergroupbtn')==false)?'hidden':'';
	while($obj=mysql_fetch_object($rs)){
		$id = $obj->id;
		$name = strUtfEncode($obj->user_group_name);
		$createdby = $obj->created_by_user_id;
		$createdby = strUtfEncode(getUserName($createdby));
		$createddate = formatDate4($obj->created_date);
		$updatedby = $obj->updated_by_user_id;
		$updatedby = strUtfEncode(getUserName($updatedby));
		$updateddate = formatDate4($obj->updated_date);	


		$arraydata =  array(	
								'CHECKBOX'=>"<input type='checkbox' class='itemcheckbox' rowid='$id'>",
								'ACTION'=>"<img id='$id' name='$name' class='nopadding editusergroupbtn $hidebtn' src='../resources/img/File_Edit.png' title='Edit User Group' data-toggle='modal' href='#editusergroupmodal'>",
								'ID'=>$id, 
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