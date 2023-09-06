<?php

include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/db-function.php");
if(isset($_POST['action'])){
	$action = escapeString($_POST['action']);
	$code=escapeString(strtoupper($_POST['code']));
	$name=escapeString($_POST['name']);
	$address=escapeString($_POST['address']);
	$terms=escapeString($_POST['terms']);
	$contact=escapeString($_POST['contact']);
	
	$position = escapeString($_POST['position']);
	$email = escapeString($_POST['email']);
	$mobile=escapeString($_POST['mobile']);
	$tel=escapeString($_POST['tel']);
	$fax=escapeString($_POST['fax']);
	$remarks=escapeString($_POST['remarks']);

	$loginuser = $userid;

	$date = date('Y-m-d H:i:s');

	

	if($action=='edit'){
		$id = escapeString($_POST['id']);
		$stat = escapeString($_POST['stat']);
		$contactid=escapeString($_POST['contactid']);
		$ifnewcontact = escapeString($_POST['ifnewcontact']);
		$query = "update supplier set code='$code', supplier_name='$name', active_flag='$stat', address='$address', payment_term_id=$terms, remarks='$remarks', updated_by_user_id='$loginuser', updated_date='$date' where id='$id'";
		$rs = query($query);

		
			if($ifnewcontact=='false' && $contactid!=''){
				$query = "update supplier_contact set contact_person='$contact', position='$position', phone='$tel', mobile='$mobile', fax='$fax', email='$email', updated_by='$loginuser', updated_date='$date' where id='$contactid'";
				$rs=query($query);

			}
			else{
				$query = "insert into supplier_contact(supplier_id,contact_person, position, phone, mobile, fax, email, created_by, created_date) values('$id','$contact','$position','$tel','$mobile','$fax','$email','$loginuser','$date')";
				$rs = query($query);
			}
		
	}
	else{
		$query = "insert into supplier(code, supplier_name, address, payment_term_id, remarks, created_by_user_id, created_date, active_flag) values('$code','$name','$address',$terms,'$remarks','$loginuser','$date',1)";
		$rs = query($query);
		$getSupplierId = mysql_insert_id();
		if($rs){
			$query = "insert into supplier_contact(supplier_id,contact_person, position, phone, mobile, fax, email, created_by, created_date) values('$getSupplierId','$contact','$position','$tel','$mobile','$fax','$email','$loginuser','$date')";
			$rs = query($query);
		}
	}
	
	$response=($rs==true)?"success":mysql_error();
	echo $response;
}

	if(isset($_POST['deleteselected'])){
		$idarray = $_POST['id'];
		foreach ($idarray as $id) {
			$id = escapeString($id);
			$query = "delete from supplier_contact where supplier_id='$id'";
			$rs = query($query);
			$query = "delete from supplier where id='$id'";
			$rs = query($query);
			if(!$rs){
				if(mysql_errno()=='1451'){
					echo "MYSQL ERROR:1451	Unable to delete supplier with id $id.";
				}
			}
		}
		if(mysql_error()==''){
			echo "success";
		}

	}



	if(isset($_FILES['fileupload-supplier'])){
			$msg='';
			$fileuploaded = $_FILES['fileupload-supplier'];
			$format = $fileuploaded['name'];
			$type = $fileuploaded['type'];
			$format = strtolower(substr($format, strpos($format, '.')+1));

			$date = date('Y-m-d H:i:s');
			$loginuser =$userid;

			if($type==''){
				$msg='You have not uploaded any file.';
			}
			else if($format!='csv'&&$type!='application/vnd.ms-excel'){
				$msg = 'Invalid File Format';
			}
			else{
				$tmpfile = $fileuploaded['tmp_name'];
				$handle = fopen($tmpfile, 'r');
				$numberofrows = count(file($tmpfile));
					
				$rowdata = fgetcsv($handle);
				for($i=1;$i<$numberofrows;$i++){
					$x = 0;
					$rowdata = fgetcsv($handle);
					$name = strtoupper($rowdata[0]);
					$address = $rowdata[1];
					$contact = $rowdata[2];
					$email = $rowdata[3];
					$position = $rowdata[4];
					$tel = $rowdata[5];
					$fax=$rowdata[6];
					$location=$rowdata[7];
					$activeflag = $rowdata[8];

					if($name!=''){
						$query="insert into supplier(code, supplier_name, address, payment_term_id, remarks, created_by_user_id, created_date, active_flag) values(NULL,'$name','$address',NULL,NULL,'$loginuser','$date','$activeflag')";
						$rs = query($query);
						$getSupplierId = mysql_insert_id();
						if(!$rs){
							$msg = 'ERROR OCCURED';
						}
						else{
							$query = "insert into supplier_contact(supplier_id,contact_person, position, phone, mobile, fax, email, created_by, created_date) values('$getSupplierId','$contact','$position','$tel',NULL,'$fax','$email','$loginuser','$date')";
							$rs = query($query);
							if($rs){
								$msg = 'ok';
							}
						}
					}
				}
				fclose($handle);
			}
			$_SESSION['MdjKeoIdhk']='supplier-upload-file';
			if($msg!=''){
				$_SESSION['jebfrrosdhwikn%khl'] = $msg;
			}
			header('location:../application/main.php');

		}

?>