<?php
	include('../../../config/functions.php');
	$reqnumber = escapeString($_GET['reqLm0se1po9smdsldo']);
	$emailto = escapeString($_GET['toLdsmo392nl1kws03mDl']);
	$emailtoname = escapeString($_GET['nameDPm39a0mLkeosn3a']);


?>
<html>
<head>
</head>
<body>
	<p id='response'></p>
</body>
<html>
<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
<script src="../../../js/jquery-1.11.3.min.js"></script>
<script src="../../../js/bootstrap.min.js"></script>

<script type="text/javascript">
	var reqnumber = <?php echo json_encode($reqnumber); ?>,
		emailto = <?php echo json_encode($emailto); ?>,
		emailtoname = <?php echo json_encode($emailtoname); ?>;
	$.post('../../../scripts/purchase-requisition.php',{emailnotification:'Nsl3Ep203N0amlOepan10@#0',reqnum:reqnumber,emailto:emailto,emailtoname:emailtoname},function(data){
													if(data.trim()!=1||data.trim()!=true){
														alert(data);
													}
													else{
														$('#response').text('Email Sent');
													}
												});
</script>