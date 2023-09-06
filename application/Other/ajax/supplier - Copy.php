<?php
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

	$data = array();

	$rs = query("select supplier.id,
						supplier.active_flag,
						code,
						address,
						supplier_name,
						payment_term_id,
						supplier.remarks,
						supplier.created_by_user_id,
						supplier.created_date,
						supplier.updated_by_user_id,
						supplier.updated_date
				from supplier order by supplier_name asc
						"
				);

	$hidebtn = (userAccess(USERID,'.editsupplierbtn')==false)?'hidden':'';
	while($obj=mysql_fetch_object($rs)){
		$id= $obj->id;
		$stat=$obj->active_flag;
		$status = ($stat==0)?"INACTIVE":"ACTIVE";
		$code= strUtfEncode($obj->code);
		$name= strUtfEncode($obj->supplier_name);
		$termid=($obj->payment_term_id==NULL)?'NULL':$obj->payment_term_id;
		$address = strUtfEncode($obj->address);

		$termcode = getPaymentTermDays($termid);
		$remarks=strUtfEncode($obj->remarks);
		$createdby= $obj->created_by_user_id;
		$createdby = getUserName($createdby);
		$createddate= formatDate4($obj->created_date);
		$updatedby= $obj->updated_by_user_id;
		$updatedby = getUserName($updatedby);
		$updateddate= formatDate4($obj->updated_date);

		$contact= '';
		$contactid='';
		$position = '';
		$tel= '';
		$mobile= '';
		$fax= '';
		$email= '';
		$query2 = "select * from supplier_contact where supplier_id='$id' order by supplier_contact.id desc limit 1";
		$rs2 = query($query2);
		while($obj2=fetch($rs2)){
			$contactid = $obj2->id;
			$contact= strUtfEncode($obj2->contact_person);
			$position = strUtfEncode($obj2->position);
			$tel= $obj2->phone;
			$mobile= $obj2->mobile;
			$fax= $obj2->fax;
			$email= $obj2->email;
		}


		$arraydata =  array(	
								'CHECKBOX'=>"<input type='checkbox' class='itemcheckbox' rowid='$id'>",
								'ACTION'=>"<img id='$id' stat='$stat' code='$code' name='$name' position='$position' contactid='$contactid' term='$termid' remarks='$remarks' contact='$contact' addr='$address' tel='$tel' mob='$mobile' fax='$fax' email='$email' class='nopadding editsupplierbtn $hidebtn' src='../resources/img/File_Edit.png' title='Edit Supplier' data-toggle='modal' href='#editsuppliermodal'>",
								'ID'=>$id, 
								'STATUS'=>$status, 
								'SUPPLIER'=>$name, 
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