<?php
	include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/functions.php");
    //include("../../config/db-function.php");
		$index = isset($_GET['oichf%jsl24'])?$_GET['oichf%jsl24']:'';
		$crncy = isset($_GET['currency'])?"and default_currency='".$_GET['currency']."'":'';


		if($index==''){
			exit('DIRECT ACCESS NOT ALLOWED.');
		}
		else{
			$index = ($index-1)*10;
			$query = "select item.id,
						item_name,
						item_code,
						long_description,
						default_supplier_id,
						item.unit_price,
						item.conformance_flag,
						unit_of_measure.id as uom,
						unit_of_measure_code as uomcode,
						item.created_date,
						item.created_by_user_id,
						item.updated_date,
						item.updated_by_user_id,
						currency_code
						from item, unit_of_measure, currency 
						where unit_of_measure_id=unit_of_measure.id  and item.active_flag='1' and currency.id=item.default_currency $crncy
						order by item_name, item_code LIMIT $index, 10";
			$rs = query($query);
			$rscount = getNumRows($rs);
			if($rscount<1){
				echo "<tr class='noresult'><td colspan='20'>No Results Found</td></tr>";
			}
			while($obj=fetch($rs)){
				$id = $obj->id;
				$suppliers = getSupplierString($id);
				$itemcode = $obj->item_code;
				$name = strUtfEncode($obj->item_name);
				$longdesc = strUtfEncode($obj->long_description);
				$datecreated = formatDate2($obj->created_date);
				$createdby = getUserName($obj->created_by_user_id);
				$updateddate = formatDate2($obj->updated_date);
				$updatedby = getUserName($obj->updated_by_user_id);
				$conf = $obj->conformance_flag;
				$dftsupplierid = explode('#@$', getSuppliers($id));
				$dftsupplierid = $dftsupplierid[0];
				$dftsuppliername = strUtfEncode(getInfo('supplier','supplier_name',"where id='$dftsupplierid'"));
				$uom = $obj->uom;
				$uomcode = $obj->uomcode;
				$price=$obj->unit_price;
				$prc = convertWithDecimal($price,5);
				$priceamount = number_format($price,5,'.','');
				$curr = $obj->currency_code;
				echo "<tr class='tablerow itemlookuprow pointer' dftsuppid='$dftsupplierid' dftsuppname='$dftsuppliername' itemid='$id' conformance='$conf' uom='$uom' uomcode='$uomcode' price='$priceamount'>

						<td class='text-nowrap'>$itemcode</td>
						<td>$name</td>
						<td>$longdesc</td>
						<td>$suppliers</td>
						<td class='text-right'>$prc</td>
						<td class='text-center'>$curr</td>
						<td style='min-width:80px'>$datecreated</td>
						<td style='min-width:80px'>$createdby</td>
						<td style='min-width:80px'>$updateddate</td>
						<td style='min-width:80px'>$updatedby</td>
					</tr>";	
			}
	}
?>