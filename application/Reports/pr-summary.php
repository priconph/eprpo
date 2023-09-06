<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/checklogin-page.php");
    include('../../config/functions.php');
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Purchase Requisition Summary
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
                            <label class="control-label col-md-4">Status</label>
                            <div class="col-md-8">
                                <select class='form-input form-control prstatus chosen-select' data-placeholder='Any'>
                                    <option value='ANY'>Any</option>
                                    <option value='APPROVED'>Approved</option>
                                    <option value='IN REVIEW'>Cancelled</option>
                                    <option value='FOR REVISION'>For Revision</option>
                                    <option value='FOR CANCELLATION'>For Cancellation</option>
                                    <option value='CANCELLED'>Cancelled</option>
                                    <option value='REJECTED'>R</option>

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">PR Number</label>
                            <div class="col-md-8">
                               <input type='text' class='form-control prnumber' placeholder='Any'>
                            </div>
                        </div>
    					<div class="form-group">
    						<label class="control-label col-md-4">Department</label>
    						<div class="col-md-8">
    							<select class='form-input form-control department chosen-select' data-placeholder='Any'>
    								<option value='any'>Any</option>
    								<?php
    								$query='select * from section_department';
    								$rs = query($query);
    								while($obj=fetch($rs)){
    									$id = $obj->id;
    									$name = $obj->section_department_name;
                                        $name = strUtfEncode($name);
    									echo "<option value='$id'>$name</option>";
    								}
    								?>
    							</select>
    						</div>
    					</div>
    					<div class="form-group">
    						<label class="control-label col-md-4">Requisitioner</label>
    						<div class="col-md-8">
    							<select class='form-input form-control requestedby chosen-select'>
    								<option value='any'>Any</option>
    								<?php
    								$query='select * from user';
    								$rs = query($query);
    								while($obj=fetch($rs)){
    									$id = $obj->id;
    									$name = $obj->first_name.' '.$obj->middle_name.' '.$obj->last_name;
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
    							<select class='form-input form-control supplier chosen-select'>
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
    						<label class="control-label col-md-4">Requisition Date</label>
    						<div class="col-md-8">
    								<input type='date' class='form-input form-control reqfrom'>
    						
    						</div>
    					</div>
    					<div class="form-group">
    						<label class="control-label col-md-4"></label>
    						<div class="col-md-8">
    								<input type='date' class='form-input form-control reqto'>
    						
    						</div>
    					</div>
    					<div class="form-group">
    						<label class="control-label col-md-4">Date Needed</label>
    						<div class="col-md-8">
    								<input type='date' class='form-input form-control neededfrom'>
    						
    						</div>
    					</div>
    					<div class="form-group">
    						<label class="control-label col-md-4"></label>
    						<div class="col-md-8">
    								<input type='date' class='form-input form-control neededto'>
    						
    						</div>
    					</div>
    					<div class="form-group">
    						<label class="control-label col-md-4">Item Quantity</label>
    						<div class="col-md-8">
    								<input type='number' step='1' class='form-input form-control itemqtymin' placeholder='Minimum Quantity'>
    						
    						</div>
    					</div>
    					<div class="form-group">
    						<label class="control-label col-md-4"></label>
    						<div class="col-md-8">
    								<input type='number' step='1' class='form-input form-control itemqtymax' placeholder='Maximum Quantity'>
    						
    						</div>
    					</div>
    					<div class="form-group">
    						<label class="control-label col-md-4">Unit Price</label>
    						<div class="col-md-8">
    								<input type='number' step='.01' class='form-input form-control unitpricemin' placeholder='Minimum Unit Price'>
    						
    						</div>
    					</div>
    					<div class="form-group">
    						<label class="control-label col-md-4"></label>
    						<div class="col-md-8">
    								<input type='number' step='.01' class='form-input form-control unitpricemax' placeholder='Maximum Unit Price'>
    						
    						</div>
    					</div>
    					</form>

    				</div>
    			</div>
    	</div>

			<div class="panel panel-primary mypanel">
    			<div class="panel-body text-center">
						<button class='btn btn-blue2 mybtn' id='prsummary-submitbtn'>PDF</button>
                        <button class='btn btn-blue2 mybtn' id='prsummary-exporttoexcel'>EXCEL</button>
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