
<?php
	include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/functions.php");
		$index = isset($_GET['oichf%jsl24'])?$_GET['oichf%jsl24']:'';
		echo $index;
		if($index==''){
			exit('DIRECT ACCESS NOT ALLOWED.');
		}
		else{
			
			$index = ($index - 1)*10;
			$query = "select item.id, 
							 item_code,
							 item_name,
							 item.lot_tags,
							 long_description,
							 item.default_supplier_id,
							 item.unit_of_measure_id,
							 unit_price,
							 currency.id as currencyid,
							 currency_code,
							 item.remarks,
							 item.active_flag,
							 conformance_flag,
							 unit_of_measure_code,
							 item.delivery_lead_time,
							 item.created_by_user_id,
							 item.created_date,
							 item.updated_by_user_id,
							 item.updated_date
					  from item, unit_of_measure, currency
					  where default_currency=currency.id and
			  			    item.unit_of_measure_id=unit_of_measure.id order by item_name asc LIMIT $index, 10";
			  		$rs = query($query);
			  		if(getNumRows($rs)<1){
			  			echo "<tr class='noresult'><td colspan='20'>No Results Found</td></tr>";
			  		}
			  		while($obj=fetch($rs)){
			  			$id = $obj->id;
			  			$itemcode = $obj->item_code;
			  			$shortdesc = strUtfEncode($obj->item_name);
			  			$longdesc = strUtfEncode($obj->long_description);
			  			$uomid = $obj->unit_of_measure_id;
			  			$uom = $obj->unit_of_measure_code;
			  			$price = $obj->unit_price;
			  			$price2 = convertWithDecimal($price,5);
			  			$currencyid = ($obj->currencyid==''||$obj->currencyid==NULL)?'NULL':$obj->currencyid;
			  			$currency = $obj->currency_code;
						//$supplierid = ($obj->default_supplier_id==''||$obj->default_supplier_id==NULL)?'NULL':$obj->default_supplier_id;
						//$suppliername = $obj->supplier_name;
						$supplierid = getSuppliers($id);
			  			$lead = $obj->delivery_lead_time;
			  			$remarks = strUtfEncode($obj->remarks);
			  			$stat = $obj->active_flag;
			  			$conf = $obj->conformance_flag;
			  			$conformance = ($conf==0)?"NO":"YES";
			  			$status = ($stat==0)?"INACTIVE":"ACTIVE";
			  			$createdby = $obj->created_by_user_id;
			  			$createdby = strUtfEncode(getUserName($createdby));
			  			$createddate = formatDate4($obj->created_date);
			  			$updatedby = $obj->updated_by_user_id;
			  			$updatedby = strUtfEncode(getUserName($createdby));
			  			$updateddate = formatDate4($obj->updated_date);
			  			$enableedititem = userAccess($userid,'.edititembtn');
			  			$tags = $obj->lot_tags;
			  			if($enableedititem==true){
			  				$enabled = "<img id='$id' supplier='$supplierid' tags='$tags' code='$itemcode' currency='$currencyid' shortdesc='$shortdesc' longdesc='$longdesc' uom='$uomid' price='$price' leadtime='$lead' remarks='$remarks' stat='$stat' conformance='$conf' class='nopadding edititembtn' src='../resources/img/File_Edit.png' title='Edit Item' data-toggle='modal' href='#edititemmodal'>";
			  			}
			  			else{
			  				$enabled = "";
			  			}

			  			echo "<tr class='tablerow'>
					  			<td class='text-center'><input type='checkbox' class='itemcheckbox' rowid='$id'></td>
					  			<td class='text-center'>$enabled</td>
					  			<td>$id</td>
					  			<td>$status</td>
					  			<td>$itemcode</td>
					  			<td>$shortdesc</td>
					  			<td>$longdesc</td>
					  			<td class='text-right'>$price2</td>
					  			<td class='text-center'>$currency</td>
					  			<td>$uom</td>
					  			<td class='text-center'>$conformance</td>
					  			<td>$createddate</td>
					  			<td>$createdby</td>
					  			<td>$updateddate</td>
					  			<td>$updatedby</td>
			  				</tr>
			  			";	
			  		}
		}
?>