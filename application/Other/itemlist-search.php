<?php
	include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/functions.php");
    //include("../../config/db-function.php");
		$input = isset($_GET['oichf%jsl24'])?escapeString($_GET['oichf%jsl24']):'';
		$crncy = isset($_GET['currency'])?"and default_currency='".$_GET['currency']."'":'';

		if($input==''){
			exit('DIRECT ACCESS NOT ALLOWED.');
		}
		else{
			$query = "select item.id,
					item_name,
					item_code,
					long_description,
					default_supplier_id,
					item.unit_price,
					unit_of_measure_code,
					item.conformance_flag,
					unit_of_measure.id as uom,
					unit_of_measure_code as uomcode,
					item.created_date,
					item.created_by_user_id,
					item.updated_date,
					item.updated_by_user_id,
					currency_code,
					created_by,
					updated_by,
					c.supplier_name, 
					c.supplier_id
					from item
					left join unit_of_measure on  unit_of_measure_id=unit_of_measure.id
					left join currency on currency.id=item.default_currency
					left join (select id, concat(COALESCE(first_name,''),' ',COALESCE(last_name,'')) as created_by from user) as a on a.id=item.created_by_user_id
					left join (select id, concat(COALESCE(first_name,''),' ',COALESCE(last_name,'')) as updated_by from user) as b on b.id=item.updated_by_user_id
					left join (
									select supplier.id as supplier_id, 
										   supplier_name,
										   item_supplier.item_id
								    from item_supplier 
									inner join supplier on supplier.id=item_supplier.supplier_id
									limit 1
							) as c on c.item_id=item.id
						where item.active_flag='1' and
							  (item_name regexp '$input' or 
							   item_code regexp '$input' or 
							   long_description regexp '$input' or
							   supplier_name regexp '$input' or 
							   created_by regexp '$input' or
							   updated_by regexp '$input' or
							   unit_price regexp '$input' or
							   lot_tags='$input')";

			if($crncy!=''){
				$query = $query.' '.$crncy;
			} 

			$rs = query($query);
			$rscount = getNumRows($rs);
			if($rscount<1){
				echo "<tr class='noresult'><td colspan='20'>No Results Found</td></tr>";
			}
			while($obj=fetch($rs)){
				$id = $obj->id;
				$itemcode = $obj->item_code;
				$suppliers = getSupplierString($id);
				$name = strUtfEncode($obj->item_name);
				$longdesc = strUtfEncode($obj->long_description);
				$createdby = strUtfEncode($obj->created_by);
				$conf = $obj->conformance_flag;
				$datecreated = formatDate2($obj->created_date);
				$updateddate = formatDate2($obj->updated_date);
				$updatedby = strUtfEncode($obj->updated_by);
				$dftsupplierid = explode('#@$', getSuppliers($id));
				$dftsupplierid = $dftsupplierid[0];
				$dftsuppliername = strUtfEncode(getInfo('supplier','supplier_name',"where id='$dftsupplierid'"));
				$uom = $obj->uom;
				$uomcode = $obj->unit_of_measure_code;
				$price=$obj->unit_price;
				$prc = convertWithDecimal($price,5);
				$priceamount = number_format($price,5,'.','');
				$curr = $obj->currency_code;
				echo "<tr class='tablerow itemlookuprow pointer' dftsuppid='$dftsupplierid' dftsuppname='$dftsuppliername' itemid='$id' conformance='$conf' uomcode='$uomcode' uom='$uom' price='$priceamount'>

						<td>$itemcode</td>
						<td style='min-width:150px'>$name</td>
						<td style='min-width:150px'>$longdesc</td>
						<td style='min-width:150px'>$suppliers</td>
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