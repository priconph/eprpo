<?php
	ini_set("max_execution_time", 0);
	ini_set("max_input_vars", 10000);
	ini_set("max_allowed_packet", 64000000);

	include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include('../config/functions.php');
	if(isset($_POST['itemtransaction'])){
		$action = escapeString($_POST['itemtransaction']);
		$id = escapeString($_POST['id']);
		$code=escapeString($_POST['code']);
		$shortdesc=escapeString($_POST['shortdesc']);
		$longdesc=escapeString($_POST['longdesc']);
		$conformance=escapeString($_POST['conformance']);
		@$supplier=$_POST['supplier'];
		$lead=escapeString($_POST['lead']);
		$price=escapeString($_POST['price']);
		$uom=escapeString($_POST['uom']);
		$remarks=escapeString($_POST['remarks']);
		$currency = escapeString($_POST['currency']);
		$tags = escapeString($_POST['tags']);
		
		$loginuser = $userid;
		$date = date('Y-m-d H:i:s');
		
		//Check if item name, description, unit of measure, default supplier, unit price and currency already exist
		$checkquery = query("SELECT * FROM `item` WHERE `item_name`='$shortdesc' AND `long_description`='$longdesc' AND `unit_of_measure_id`='$uom' AND `unit_price`='$price' AND `default_currency`=$currency AND id != '$id'");
		//echo "SELECT * FROM `item` WHERE `item_name`='$shortdesc' AND `long_description`='$longdesc' AND `unit_of_measure_id`='$uom' AND `unit_price`='$price' AND `default_currency`=$currency AND id != '$id' ";
		$count = getNumRows($checkquery);
		if($count > 0) {
			$response = "exist";
		} else {
		
			$query = "insert into item(item_code,item_name,long_description,unit_of_measure_id,unit_price,default_currency,delivery_lead_time,remarks,active_flag,conformance_flag,created_by_user_id,created_date,lot_tags) values('$code','$shortdesc','$longdesc',$uom,'$price',$currency,'$lead','$remarks','1',$conformance,'$loginuser','$date','$tags')";
			if($action=='edit'){
				$id = escapeString($_POST['id']);
				$stat=escapeString($_POST['stat']);
				$query = "update item set lot_tags='$tags', item_code='$code', item_name='$shortdesc',long_description='$longdesc', unit_of_measure_id=$uom, unit_price='$price', default_currency=$currency, delivery_lead_time='$lead', remarks='$remarks', active_flag='$stat', conformance_flag=$conformance, updated_by_user_id='$loginuser',updated_date='$date' where id='$id'";
			}
			else{								
				$incr = "update transaction_type set next_number_series = next_number_series + 1 where id='4'";
				query($incr);
			}
			
			$rs = query($query);

			$response=($rs==true)?"success":error();
			if($response=="success"){

				if($action=='edit'){
					$newitem = $id;
					query("delete from item_supplier where item_id='$newitem'");
				}
				else{
					$newitem = mysql_insert_id();
				}
				
				$supplierlen = count($supplier);
				for($i=0;$i<$supplierlen;$i++){
					if(@$supplier[$i]==''){
						$supId = 'NULL';
					}
					else{
						@$supId = "'".escapeString($supplier[$i])."'";
						query("insert into item_supplier values('$newitem',$supId)");
					}
					
				}
			}
			
		}

		echo $response;
	}
	if(isset($_POST['deleteselected'])){
		$idarray = $_POST['id'];
		foreach ($idarray as $id) {
			$id = escapeString($id);
			$query = "delete from item where id='$id'";
			//$rs = query($query);
			$rs = mysql_query($query);
			if(!$rs){
				if(mysql_errno()=='1451'){
					echo "ERROR1451";
				}
			}
		}
		if(error()==''){
			echo "success";
		}
	}
	
	if(isset($_FILES['fileupload-item'])){
			$msg='';
			$fileuploaded = $_FILES['fileupload-item'];
			$format = $fileuploaded['name'];
			$type = $fileuploaded['type'];
			$format = strtolower(substr($format, strpos($format, '.')+1));

			$date = date('Y-m-d H:i:s');
			$loginuser = USERID;
			
			

			/*if($type==''){
				echo "aaaaaaaaaaaaaaaaaaa";
				$msg='You have not uploaded any file.';
			}*/
			if($format!='csv'&&$type!='application/vnd.ms-excel'){
				$msg = 'Invalid File Format';
			}
			else{
				$tmpfile = $fileuploaded['tmp_name'];
				$handle = fopen($tmpfile, 'r');
				$numberofrows = count(file($tmpfile));
				$rowdata = fgetcsv($handle);
				$strvalues =array();
				///////////////////////////
				$supvalues = array();
				/////////////////////////////
				$itemcode = getNextTransactionNumber(4);
				for($i=1;$i<$numberofrows;$i++){
					$rowdata = fgetcsv($handle);
					//$code = $rowdata[0];
					$name = $rowdata[0];
					$name = str_replace(',', ' ',trimString($name));
					$description = str_replace("'", '',trimString($name));
					$description = str_replace('"', '',trimString($name));
					$description = $rowdata[1];
					$description = str_replace(',', ' ',trimString($description));
					$description = str_replace("'", '',trimString($description));
					$description = str_replace('"', '',trimString($description));

				
					$price = str_replace(',', '', $rowdata[2]);
					$price = (float)$price;
					$price = (is_float($price)==true||is_float($price)==1)?$price:1;


					$price = number_format($price,2,'.','');
					$currency = (str_replace(' ', '', $rowdata[3])=='')?'NULL':$rowdata[3];

					$uom = (str_replace(' ', '', $rowdata[4])=='')?'NULL':$rowdata[4];
					$conformance = (str_replace(' ', '', $rowdata[5])=='')?0:$rowdata[5];
					$leadtime = (str_replace(' ', '', $rowdata[6])=='')?0:$rowdata[6];
					$suppliers = (str_replace(' ', '', $rowdata[7])=='')?'NULL':$rowdata[7];
					
					if($name!=''&&$uom!='NULL'&&$price!='0.00'&&$currency!='NULL'&&$i!=1){
						$str = "('".$itemcode."','".$name."','".$description."',".$uom.",'".$price."',NULL,".$currency.",'1',".$conformance.",'".$loginuser."','".$date."')";
						array_push($strvalues, $str);
						$itemcode++;
						$lst = $itemcode;
						$itemcode = str_pad($itemcode, 5,'0',STR_PAD_LEFT);
						$itmid = $itmid + 1;
					}
					///////////////////////////
					if($i==1){
						$firstinsert = "('".$itemcode."','".$name."','".$description."',".$uom.",'".$price."',NULL,".$currency.",'1',".$conformance.",'".$loginuser."','".$date."')";
						$frs = query("insert into 
								item(
									item_code, 
									item_name, 
									long_description, 
									unit_of_measure_id, 
									unit_price, 
									default_supplier_id,
									default_currency, 
									active_flag, 
									conformance_flag, 
									created_by_user_id, 
									created_date
								) 
								values$firstinsert");
						$lastinsertid = mysql_insert_id();

						if($suppliers!='NULL'){
							$firsupp = explode('#', $suppliers);
							for($s=0;$s<count($firsupp);$s++){
								query("insert into item_supplier values('$lastinsertid','".$firsupp[$s]."')");
							}
						}
						$itmid = $lastinsertid + 1;
						$itemcode++;
						$itemcode = str_pad($itemcode, 5,'0',STR_PAD_LEFT);
						

					}
					//////////////////////////



					///////////////////////////////////////////
					if($suppliers!='NULL'&&$i!=1&&$name!=''&&$uom!='NULL'&&$price!='0.00'&&$currency!='NULL'){
						$sup = explode('#', $suppliers);
						for($s=0;$s<count($sup);$s++){
							$supstr = "('".$itmid."','".$sup[$s]."')";
							array_push($supvalues, $supstr);
						}
					}
					///////////////////////////////////////////////



				}
				$strvalues = implode(', ', $strvalues);
				////////////////////////////////////////
				$supvalues = implode(', ', $supvalues);
				///////////////////////////////////////////
				
				$query="insert into 
								item(
									item_code, 
									item_name, 
									long_description, 
									unit_of_measure_id, 
									unit_price, 
									default_supplier_id,
									default_currency, 
									active_flag, 
									conformance_flag, 
									created_by_user_id, 
									created_date
								) 
								values$strvalues";
				$rs = query($query);
				if(!$rs){
					$msg = mysql_error();
				}
				else{
					query("update transaction_type set next_number_series='$itemcode' where id='4'");
					$rs = query("insert into item_supplier values".$supvalues);
					if(!$rs){
						$msg = mysql_error();
					}
					else{
						$msg = 'ok';
					}
					
					
					//$msg = $supvalues;
				}
					
				fclose($handle);
			}
			$_SESSION['MdjKeoIdhk']='item-upload-file';
			if($msg!=''){
				$_SESSION['jebfrrosdhwikn%khl'] = $msg;
			}
			header('location:../application/main.php');
	

	}

	





?>