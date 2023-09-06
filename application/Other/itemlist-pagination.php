<?php
	include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/db-function.php");
?>
<div class="col-md-4">
	<div class="table-text-div">
		<div class="table-text" id='shipvia-tabletext'>
			Showing <span class='table-text-from'>
			<?php 
				$crncy = isset($_GET['currency'])?"where default_currency='".$_GET['currency']."'":'';

				$pgrs = query("select * from item $crncy");
				$rscount = getNumRows($pgrs);
				if($rscount<1){
					echo '0';
				}
				else{
					echo '1';
				}
			?>
		</span> - 
		<span class='table-text-to'>
			<?php 
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
	<ul class='pages pagination pagination-sm' id='itempagination'>
		<li class='active' value='1'><a>1</a></li>
		<?php 
			$paginationcount = ceil($rscount/10); // 10 = row per page
			for($i=2;$i<=$paginationcount;$i++){
				echo "<li value='$i' class='hidden'><a>$i</a></li>";
			}
		?>
	</ul>
</div>

<script type="text/javascript">
	$('#itempagination>li').slice(0,5).removeClass('hidden');
</script>