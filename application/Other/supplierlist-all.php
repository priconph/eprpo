<?php
	include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/functions.php");
    //include("../../config/db-function.php");

		$query = "select * from supplier where active_flag='1' order by supplier_name asc";
		$rs = query($query);
		$rscount = getNumRows($rs);
		if($rscount<1){
			echo "<tr class='noresult'><td colspan='20'>No Results Found</td></tr>";
		}
		while($obj=fetch($rs)){
			$id= $obj->id;
			$code= $obj->code;
			$name= strUtfEncode($obj->supplier_name);

			echo "<tr class='tablerow supplierlookuprow hidden' supplierid='$id'>
			<td>$name</td>
			</tr>";
		}
	
?>

