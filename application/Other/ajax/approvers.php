<?php
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

	$data = array();

	$rs = query("select approver.id, 
	    						approver.user_id, 
	    						approver.section_department_id,
	    						approver.approval_type_id,
	    						first_name,
	    						middle_name,
	    						last_name,
	    						section_department_name,
	    						approval_type,
	    						approver.active_flag,
	    						approver.created_by_user_id,
	    						approver.created_date,
	    						approver.updated_by_user_id,
	    						approver.updated_date
	    		from approver, user, approval_type, section_department 
	    		where user_id=user.id 
	    						and approver.section_department_id=section_department.id 
	    						and approval_type_id=approval_type.id"
				);

	$hidebtn = (userAccess(USERID,'.editapproverbtn')==false)?'hidden':'';
	while($obj=mysql_fetch_object($rs)){
		$name = array();
		$id = $obj->id;
		$userid = $obj->user_id;
		$deptid = $obj->section_department_id;
		$approvaltypeid=$obj->approval_type_id;
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

		$dept = $obj->section_department_name;
		$approvaltype = $obj->approval_type;
		$stat = $obj->active_flag;
		$status = ($stat==0)?"INACTIVE":"ACTIVE";
		$createdby = $obj->created_by_user_id;
		$createdby = getUserName($createdby);
		$createddate = formatDate4($obj->created_date);
		$updatedby = $obj->updated_by_user_id;
		$updatedby = getUserName($updatedby);
		$updateddate = formatDate4($obj->updated_date);	

		

		$arraydata =  array(	
								'CHECKBOX'=>"<input type='checkbox' class='itemcheckbox' rowid='$id'>",
								'ACTION'=>"<img id='$id'  userid='$userid' name='$name' department='$deptid', approval='$approvaltypeid' stat='$stat' class='nopadding editapproverbtn $hidebtn' src='../resources/img/File_Edit.png' title='Edit Approver' data-toggle='modal' href='#editapprovermodal'>",
								'ID'=>$id, 
								'STATUS'=>$status, 
								'NAME'=>$name, 
								'DEPARTMENT'=>$dept,
								'APPROVALTYPE'=>$approvaltype,								
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