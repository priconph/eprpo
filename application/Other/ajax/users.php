<?php
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

	$data = array();

	$rs = query("select user.id,
						username,
						first_name,
						middle_name,
						last_name,
						user.section_department_id,
						section_department_name,
						email_address,
						user.active_flag,
						user.user_group_id,
						user_group_name,
						user.created_by_user_id,
						user.created_date,
						user.updated_by_user_id,
						user.updated_date
				   from user_group, 
						section_department, 
						user 
					where section_department_id=section_department.id and 
					user_group_id=user_group.id and 
					user.id!=1"
				);

	$hidebtn = (userAccess(USERID,'.edituserbtn')==false)?'hidden':'';
	$hidebtn1 = (userAccess(USERID,'.resetpassword')==false)?'hidden':'';
	while($obj=mysql_fetch_object($rs)){
		$name = array();
		$id = $obj->id;
		$username = $obj->username;
		$fname = strUtfEncode($obj->first_name);
		$mname = strUtfEncode($obj->middle_name);
		$lname = strUtfEncode($obj->last_name);

		if($fname!=''){
			array_push($name, $fname);
		}
		if($mname!=''){
			array_push($name, $mname);
		}
		if($lname!=''){
			array_push($name, $lname);
		}
		$name = implode(' ', $name);

		$deptid = $obj->section_department_id;
		$dept = $obj->section_department_name;
		$email = $obj->email_address;
		$stat = $obj->active_flag;
		$status = ($stat==0)?"INACTIVE":"ACTIVE";
		$usergroupid = $obj->user_group_id;
		if($usergroupid==NULL){
			$usergroupid = 'NULL';
		}
		$usergroup = $obj->user_group_name;
		$createdby = $obj->created_by_user_id;
		$createdby = getUserName($createdby);
		$createddate = formatDate4($obj->created_date);
		$updatedby = $obj->updated_by_user_id;
		$updatedby = getUserName($updatedby);
		$updateddate = formatDate4($obj->updated_date);	

		$arraydata =  array(	
								'USERLISTACTION'=>"<img src='../resources/img/tick_16.png' title='Select User' class='userlistselect' id='$id' name='$name'>",
								'CHECKBOX'=>"<input type='checkbox' class='itemcheckbox' rowid='$id'>",
								'ACTION'=>"<img id='$id' email='$email' fname='$fname' mname='$mname' lname='$lname' dept='$deptid' stat='$stat' username='$username' usergroup='$usergroupid' class='nopadding edituserbtn $hidebtn' src='../resources/img/File_Edit.png' title='Edit User' data-toggle='modal' href='#editusermodal'><img id='$id' class='nopadding resetpassword $hidebtn1' src='../resources/img/password.png' title='Reset Password'>",
								'ID'=>$id, 
								'STATUS'=>$status, 
								'NAME'=>$name, 
								'USERNAME'=>$username,
								'DEPARTMENT'=>$dept,
								'EMAIL'=>$email,
								'USERGROUP'=>$usergroup,
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