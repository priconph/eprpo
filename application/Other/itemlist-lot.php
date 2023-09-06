<?php
	include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/functions.php");
    //include("../../config/db-function.php");
		$input = isset($_GET['DksncooEpo30sjdoi'])?$_GET['DksncooEpo30sjdoi']:'';

		if($input==''){
			exit('DIRECT ACCESS NOT ALLOWED.');
		}
		else{
			$supp = "<option value=''></option>";
			$rsSuppliers = query("select * from supplier order by supplier_name");
			while($obj=fetch($rsSuppliers)){
				$suppid = $obj->id;
				$name = strUtfEncode(strtoupper($obj->supplier_name));
				$supp = $supp."<option value='$suppid'>$name</option>";
			}

			$query = "select item.id,
						item_name,
						item_code,
						item.unit_price,
						long_description,
						item.conformance_flag,
						default_supplier_id,
						unit_of_measure_code,
						unit_of_measure.id as uom
						from item, unit_of_measure 
						where unit_of_measure_id=unit_of_measure.id and
							   lot_tags='$input'";

			$rs = query($query);
			$rscount = getNumRows($rs);
			if($rscount<1){
				echo "<tr class='noresult'><td colspan='20'>No Results Found</td></tr>";
			}
			while($obj=fetch($rs)){
				$id = $obj->id;
				$itemcode = $obj->item_code;
				$name = strUtfEncode($obj->item_name);
				$longdesc = strUtfEncode($obj->long_description);
				$conf = $obj->conformance_flag;
				$uom = $obj->uom;
				$defaultsupplier = $obj->default_supplier_id;
				$uomcode = $obj->unit_of_measure_code;
				$price=$obj->unit_price;
				$prc = convertWithDecimal($price,2);
				echo "<tr class='tablerow pointer' itemid='$id' conformance='$conf' uomcode='$uomcode' uom='$uom' price='$price'>
						<td class='text-center'><input type='checkbox' class='lotitem-checkbox'></td>
						<td class='col-classfn'><input type='text' class='form-control uppercase lotitem-classfncode' style='width:80px;'></td>
						<td class='col-qty'><input type='number' class='form-control lotitem-quantity' style='width:120px;'></td>
						<td class='col-supplier supplierdropdowncl' defaultsupplier='$defaultsupplier'>
							<select data-placeholder='Select a Supplier...' class='chosen-select lotitem-supplier' style='width:250px;' tabindex='2'>
								$supp
							</select>
						</td>
						<td class='text-nowrap'>$itemcode</td>
						<td class='text-nowrap'>$name</td>
						<td>$longdesc</td>
						<td class='text-right'>$prc</td>
						<td></td>
					</tr>";		
			}
	}
?>

<script src="../resources/selectoption/chosen.jquery.js"></script>
<script src="../resources/selectoption/docsupport/prism.js"></script>
<script type="text/javascript">

	$('.supplierdropdowncl').each(function(){
		var td = $(this);
		var ds = td.attr('defaultsupplier');

		td.children('.lotitem-supplier').find('option').each(function(){
			if($(this).attr('value')==ds){
				$(this).attr('selected','');
			}
		});
	});
	 $(".chosen-select").chosen({no_results_text: "No results found!"}); 
</script>