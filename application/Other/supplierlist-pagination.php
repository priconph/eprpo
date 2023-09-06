<?php
	include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/functions.php");

    $itemid = isset($_GET['itemid03903020990'])?$_GET['itemid03903020990']:'';
?>
<div class="col-md-4">
	<div class="table-text-div">
		<div class="table-text" id='shipvia-tabletext'>
			Showing <span class='table-text-from'>
			<?php 	
				if($itemid!=''){
					$query = "select * from supplier, item_supplier where supplier_id=supplier.id and item_id='$itemid' and active_flag='1' order by supplier_name asc";
					$rs = query($query);
					$rscount = getNumRows($rs);
					if($rscount<1){
						$query = "select * from supplier where active_flag='1' order by supplier_name asc";
					}
				}
				else{
					$query = "select * from supplier where active_flag='1' order by supplier_name asc";
				}
				$rs = query($query);
				$rscount = getNumRows($rs);
				if($rscount<1){
					echo '0';
				}
				else{
					echo '1';
				}
			?>
		</span> - 
		<span class='table-text-to'><?php 
		if($rscount<10){
			echo $rscount;
		}
		else{
			echo '10';
		} 
		?>
	</span> of 
	<span class='table-text-total'><?php echo $rscount; ?></span> entries
</div>	
</div>
</div>
<div class="col-md-4">
	<div class="table-text-search text-center">

	</div>
</div>
<div class="col-md-4">
	<ul class='pages pagination pagination-sm' id='supplierpagination'>
		<li class='active' value='1'><a>1</a></li>
		<?php 
																	$paginationcount = ceil($rscount/10); // 10 = row per page
																	for($i=2;$i<=$paginationcount;$i++){
																		echo "<li value='$i' class='hidden'><a>$i</a></li>";
																	}
																	?>
																</ul>
															</div>