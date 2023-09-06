<?php
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

	$data = array();

	$rs = query("select mode_of_shipment.id as modeId,
	    			   ship_via.id as viaId,
	    			   mode_of_shipment.description,
	    			   mode_of_shipment.created_by_user_id,
	    			   mode_of_shipment.updated_by_user_id,
	    			   mode_of_shipment.created_date,
	    			   mode_of_shipment.updated_date,
	    			   ship_by 
	    		  from mode_of_shipment
	    		  inner join ship_via on ship_via.id=mode_of_shipment.via");

	$hidebtn = (userAccess(USERID,'.editmodeofshipmentbtn')==false)?'hidden':'';
	while($obj=mysql_fetch_object($rs)){
		$id = $obj->modeId;
		$via = $obj->viaId;
		$shipby = $obj->ship_by;
		$desc = $obj->description;
		$createdby = strUtfEncode(getUserName($obj->created_by_user_id));
		$createddate = formatDate4($obj->created_date);
		$updatedby = $obj->updated_by_user_id;
		$updatedby = strUtfEncode(getUserName($updatedby));
		$updateddate = formatDate4($obj->updated_date);	

		$arraydata =  array(	
								'CHECKBOX'=>"<input type='checkbox' class='itemcheckbox' rowid='$id'>",
								'ACTION'=>"<img id='$id' via='$via' mode='$desc' class='nopadding $hidebtn editmodeofshipmentbtn' src='../resources/img/File_Edit.png' title='Edit Ship Via' data-toggle='modal' href='#editmodeofshipmentmodal'>",
								'ID'=>$id, 
								'VIA'=>$shipby, 
								'MODE'=>$desc,
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