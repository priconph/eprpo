
<?php
	include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/functions.php");
    //include("../../config/db-function.php");
		$search = isset($_GET['oichf%jsl24'])?$_GET['oichf%jsl24']:'';
		if($search==''){
			exit('DIRECT ACCESS NOT ALLOWED.');
		}
		else{
			$query = "select item.id, 
							 item_code,
							 item_name,
							 item.lot_tags,
							 long_description,
							 item.unit_of_measure_id,
							 unit_price,
							 currency_code,
							 item.remarks,
							 item.active_flag,
							 conformance_flag,
							 default_supplier_id,
							currency.id as currencyid,
							 unit_of_measure_code,
							 item.delivery_lead_time,
							 item.created_by_user_id,
							 item.created_date,
							 item.updated_by_user_id,
							 item.updated_date
					  from item, unit_of_measure, currency
					  where default_currency=currency.id and
			  			    item.unit_of_measure_id=unit_of_measure.id and
			  			    (item.id like '%$search%' or
			  			     item_code like '%$search%' or
			  			     item_name like '%$search%' or
			  			     long_description like '%$search%' or
			  			     unit_of_measure_code like '%$search%' or
			  			     unit_price like '%$search%' or
			  			     currency_code like '%$search%' or
			  			     item.remarks like '%$search%' or
			  			     item.created_date like '%$search%' or
			  			     item.updated_date like '%$search%' or
			  			     item.lot_tags like '%$search%')";
			  		$rs = query($query);
			  		$rscount = getNumRows($rs);
			  		if($rscount<1){
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
			  			$createdby = getUserName($createdby);
			  			$createddate = $obj->created_date;
			  			$updatedby = $obj->updated_by_user_id;
			  			$updatedby = getUserName($createdby);
			  			$updateddate = $obj->updated_date;
			  			$enableedititem = userAccess($userid,'.edititembtn');
			  			$tags = $obj->lot_tags;
			  			if($enableedititem==true){
			  				$enabled = "<img id='$id' supplier='$supplierid' tags='$tags' currency='$currencyid' code='$itemcode' shortdesc='$shortdesc' longdesc='$longdesc' uom='$uomid' price='$price' leadtime='$lead' remarks='$remarks' stat='$stat' conformance='$conf' class='nopadding edititembtn' src='../resources/img/File_Edit.png' title='Edit Item' data-toggle='modal' href='#edititemmodal'>";
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

