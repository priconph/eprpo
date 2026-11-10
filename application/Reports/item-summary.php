<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
    include('../../config/functions.php');
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Item Summary
	</div>
</div>
<div class="container-fluid">
    <div class="pageContent">
    	<div class="panel panel-primary mypanel">
    			<div class="panel-body">
    				<div class="col-md-offset-2 col-md-8" id='rcv-summary'>
						
    					<!--<form class="form-horizontal">
    						<div class="form-group">
	    						<div class="errordiv"></div>
	    					</div>
	    					 <div class="form-group">
	    						<label class="control-label col-md-4">Receiving by</label>
	    						<div class="col-md-8">
	    							<select class='form-input form-control summaryby'>
										<option value='any'>Any</option>
										<option value='po'>Purchase Order</option>
										<option value='nonpo'>Non-Purchase Order</option>
										<option value='catalogitem'>Catalog Item</option>
										<option value='noncatalogitem'>Non-Catalog Item</option>
										<option value='supplier'>Supplier</option>
									</select>
	    						</div>
	    					</div> -->
	    					<!-- <div class='form-group hidden' id='ponumbergroup'>
	    						<label class="control-label col-md-4">PO No.</label>
	    						<div class="col-md-8">
	    							<input type='text' class='form-input form-control ponumber'>
	    						</div>
	    					</div>
	    					<div class='form-group hidden' id='referencegroup'>
	    						<label class="control-label col-md-4">Reference</label>
	    						<div class="col-md-8">
	    							<input type='text' class='form-input form-control reference'>
	    						</div>
	    					</div>
	    					<div class='form-group hidden' id='noncatalogitemgroup'>
	    						<label class="control-label col-md-4">Item Code</label>
	    						<div class="col-md-8">
	    							<input type='text' class='form-input form-control noncatalogitem'>
	    						</div>
	    					</div>
	    					<div class="form-group hidden" id='itemgroup'>
	    						<label class="control-label col-md-4">Item Code</label>
	    						<div class="col-md-8">
	    							<div class="input-group">
										  <input type='text' class='form-input form-control itemcode' itemid='' readonly>
										  <div class="input-group-addon button-addon itemlookupbtn" from='edit' data-toggle='modal' href='#itemlistmodal'>
										  	<i class="fa fa-search"></i>
										  </div>
									</div>
	    						</div>
	    					</div>
	    					<div class='form-group hidden' id='suppliergroup'>
	    						<label class="control-label col-md-4">Supplier</label>
	    						<div class="col-md-8">
	    							<select class='form-input form-control supplier chosen-select'>
														<?php
															// $query='select * from supplier';
															// $rs = query($query);
															// while($obj=fetch($rs)){
															// 	$id = $obj->id;
															// 	$name = $obj->supplier_name;
															// 	$name = strUtfEncode($name);
															// 	echo "<option value='$id'>$name</option>";
															// }
														?>
													</select>
	    						</div>
	    					</div> -->
	    					<!-- <div class="form-group">
	    						<label class="control-label col-md-4">Date</label>
	    						<div class="col-md-8">
	    							<input type='date' class='form-input form-control datefrom'>
	    						</div>
	    					</div>
	    					<div class="form-group">
	    						<label class="control-label col-md-4"></label>
	    						<div class="col-md-8">
	    							<input type='date' class='form-input form-control dateto'>
	    						</div>
	    					</div> 
    					</form>-->
    				</div>
    			</div>
    	</div>

		<div class="panel panel-primary mypanel">
			<div class="panel-body text-center">
					<!-- <button class='btn btn-blue2 mybtn' id='rcvsummary-submitbtn'>PDF</button> -->
					<button class='btn btn-blue2 mybtn' id='itemsummary-exporttoexcel'>EXCEL</button>
				</div>
		</div>
	</div>
</div>

<div class="modal fade lookupmodal" id="itemlistmodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					<img class='img' src="../resources/img/Search.png">Item List
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-offset-6 col-md-6 text-right">
						<div class="input-group">
								  <input type='text' class='form-control searchforitems' placeholder='Search'>
								  <div class="input-group-addon">Search</div>
						</div>
					</div>
				</div>

				<div class="tablewithpagination">
							<div class='table-wrapper' style='height:350px'>
								<div class="table-responsive">
										<table class='table table-striped table-condensed table-hover mytable' id='itemtable'>
											<thead>
												<tr>
													<th></th>
													<th>ITEM CODE</th>
													<th>ITEM NAME</th>
													<th>DESCRIPTION</th>
													<th>SUPPLIER</th>
													<th>UNIT PRICE</th>
													<th>CURRENCY</th>
													<th>CREATED DATE</th>
													<th>CREATED BY</th>
													<th>LAST UPDATE</th>
													<th>UPDATED BY</th>
												</tr>
											</thead>
											<tbody id='itemlist-line-data'>
											</tbody>
										</table>
								</div>
							</div>
							<ul class='pages pagination pagination-sm' id='itempagination'>
								<li class='active' value='1'><a>1</a></li>
								<?php 
									$rscount = getTotalRows('item');
									$paginationcount = ceil($rscount/10); // 10 = row per page
									for($i=2;$i<=$paginationcount;$i++){
										echo "<li value='$i'><a>$i</a></li>";
									}
								?>
							</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="../resources/selectoption/chosen.jquery.js"></script>
<script src="../resources/selectoption/docsupport/prism.js"></script>

<script type="text/javascript">
	$('#itempagination li').addClass('hidden');
	$('#itempagination li').slice(0,5).removeClass('hidden');
	$('#itemlist-line-data').load('Other/itemlist.php?oichf%jsl24=1'); //ITEMLIST 
	$('.chosen-select').chosen({
		no_results_text: "No Results found for: ",
		width:'100%'
	}); 
</script>