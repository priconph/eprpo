<?php

    include("../../config/checkurlaccess.php");
    session_start();
    $alert='';
    $msg = (isset($_SESSION['jebfrrosdhwikn%khl']))?$_SESSION['jebfrrosdhwikn%khl']:'';

    if($msg!=''){
    	if($msg=='ok'){
			$alert= "<div class='message-success'><div class='message-content'><span class='closemessage'>&times;</span>New Supplier(s) added.</div></div>";
    	}
    	else{
    		$alert= "<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>$msg</div></div>";
    	}
    	unset($_SESSION['jebfrrosdhwikn%khl']);
    }
?>
<div class='header-page' >
	<div class='header-page-inner'>
		Upload A File - Supplier
	</div>
</div>
<div class="container-fluid">
    <div class="pageContent" >
    	<div class="panel panel-primary mypanel">
    		<div class="panel-body">
    				<div class="col-sm-offset-3 col-sm-6 col-xs-12 text-center">
    					<br>

    					<form class='form-horizontal' action='../scripts/supplier.php' enctype='multipart/form-data' method='POST' id='uploadsupplier-form'>
    						<div class="form-group">
								<div class="errordiv"><?php echo $alert; ?></div>
							</div>
									
							<div class="form-group">
								<label class='control-label col-sm-4'>Choose a file</label>
								<div class="col-sm-8">
									<input type='file' name='fileupload-supplier' class='form-control'>
								</div>
							</div>
							</br>
    					</form>

   					</div>
			
					<div class='col-xs-12'>
								<div class="text-center">
									<button class='btn btn-blue2 mybtn' id='fileupload-uploadbtn'>Upload</button>	
									<button class='btn btn-blue2 mybtn' id='fileupload-cancelbtn'>Back</button>					
								</div>
					</div>
			</div>

		</div>
	</div>
</div>