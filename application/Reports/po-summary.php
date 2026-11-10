<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
    include('../../config/functions.php');
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Purchase Order Summary
	</div>
</div>
<div class="container-fluid">
    <div class="pageContent">

    	<div class="panel panel-primary mypanel">
    			<div class="panel-body">
    				<div class="col-md-offset-2 col-md-8" id='rcv-summary'>
    					<form class="form-horizontal">
    						<div class="form-group">
	    						<div class="errordiv"></div>
	    					</div>
	    					<div class="form-group">
	    						<label class="control-label col-md-4">Purchasing Staff</label>
	    						<div class="col-md-8">
	    							<select class='form-input form-control popurchasingstaff chosen-select'>
	    								<option value='any'>Any</option>
	    								<?php
	    								// $query='select * from user where section_department_id=17';
	    								$query='select * from user where section_department_id=17 and active_flag=1';// 06062024 by Nessa
	    								$rs = query($query);
	    								while($obj=fetch($rs)){
	    									$id = $obj->id;
	    									$name = $obj->first_name.' '.$obj->last_name;
	    									$name = strUtfEncode($name);
	    									echo "<option value='$id'>$name</option>";
	    								}
	    								?>
	    							</select>
	    						</div>
	    					</div>
	    					<div class="form-group">
	    						<label class="control-label col-md-4">Supplier</label>
	    						<div class="col-md-8">
	    							<select class='form-input form-control posupplier chosen-select'>
	    								<option value='any'>Any</option>
	    								<?php
	    								$query='select * from supplier';
	    								$rs = query($query);
	    								while($obj=fetch($rs)){
	    									$id = $obj->id;
	    									$name = $obj->supplier_name;
	    									$name = strUtfEncode($name);
	    									echo "<option value='$id'>$name</option>";
	    								}
	    								?>
	    							</select>
	    						</div>
	    					</div>
	    					<div class="form-group">
	    						<label class="control-label col-md-4">Status</label>
	    						<div class="col-md-8">
	    							<select class='form-input form-control postatus chosen-select'>
	    								<option value='any'>Any</option>
	    								<option value='open'>Open</option>
	    								<option value='unserved'>Unserved</option>
	    								<option value='partial delivery'>Partial Delivery</option>
	    								<option value='served'>Served</option>
	    								<option value='CANCELLED'>Cancelled</option>
	    								<option value='FOR CANCELLATION'>For Cancellation</option>
	    								<option value='Revised'>Revised</option>
	    								<option value='FOR REVISION'>For Revision</option>
	    							</select>
	    						</div>
	    					</div>
	    					<div class="form-group">
	    						<label class="control-label col-md-4">With PO Balance</label>
	    						<div class="col-md-8">
	    							<select class='form-input form-control pobalanceflag chosen-select'>
	    								<option value='any'>Any</option>
	    								<option value='0'>No</option>
	    								<option value='1'>Yes</option>
	    							</select>
	    						</div>
	    					</div>
	    					<div class="form-group">
	    						<label class="control-label col-md-4">Order Date</label>
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

	    					<div class="form-group">
	    						<label class="control-label col-md-4">Date Needed</label>
	    						<div class="col-md-8">
	    							<input type='date' class='form-input form-control dateneededfrom'>
	    						</div>
	    					</div>
	    					<div class="form-group">
	    						<label class="control-label col-md-4"></label>
	    						<div class="col-md-8">
	    							<input type='date' class='form-input form-control dateneededto'>
	    						</div>
	    					</div>

    					</form>
    				</div>
    			</div>
    	</div>

			<div class="panel panel-primary mypanel">
    			<div class="panel-body text-center">
						<button class='btn btn-blue2 mybtn' id='posummary-submitbtn'>PDF</button>
						<button class='btn btn-blue2 mybtn' id='posummary-exporttoexcel'>Excel</button>
				</div>
			</div>
	</div>
</div>

<script src="../resources/selectoption/chosen.jquery.js"></script>
<script src="../resources/selectoption/docsupport/prism.js"></script>

<script type="text/javascript">
	$('.chosen-select').chosen({ 
			no_results_text: "No results found for: ",
			width:'100%'
	});
</script>
