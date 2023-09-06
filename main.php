<?php
	include("../config/connection.php");
	include("../config/checklogin.php");
	if(isset($_SESSION['MdjKeoIdhk'])){
		$viewtobeloaded = $_SESSION['MdjKeoIdhk'];
	}else{
		$viewtobeloaded = "";
	}

	if(isset($_SESSION['reqnumXoslapEsnIE'])&&isset($_SESSION['emailtoXoslapEsnIE'])&&isset($_SESSION['emailtonameXoslapEsnIE'])){
		$email_reqnum = $_SESSION['reqnumXoslapEsnIE'];
		$email_to = $_SESSION['emailtoXoslapEsnIE'];
		$email_toname = $_SESSION['emailtonameXoslapEsnIE'];
		$sourcebtn = $_SESSION['sourcebtnPR'];
	}
	else{
		$email_reqnum = '';
		$email_to = '';
		$email_toname = '';
		$sourcebtn = '';
	}

?>

<!doctype html>
<html>
	<head>

		<title>EPRPO - Pricon Microelectronics, Inc.</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
		<link rel="stylesheet" href="../css/bootstrap.css">
		<link rel="stylesheet" href="../css/main.css">
		<!--<link href='https://fonts.googleapis.com/css?family=Pacifico|Open+Sans|Lato:400,700,300,900|Carter+One' rel='stylesheet' type='text/css'>-->
		<link rel="shortcut icon" href="../resources/pricon.ico">
		<!-- <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'> -->

    	<link rel="stylesheet" href="../resources/tags/dist/bootstrap-tagsinput.css">

		<link href="../css/fontAwesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link href="../css/animate.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src='../js/functions.js'></script>

		<!-- FOR DROPDOWN -->
		<link rel="stylesheet" href="../resources/selectoption/chosen.css">
		<link href="../resources/select/dist/css/select2.css" rel="stylesheet" />

		<!-- DIALOG, CONFIRM, ALERTS-->
		<link rel="stylesheet" type="text/css" href="../resources/dialog/jquery-confirm.css" />

		<!--DATE PICKER -->
		<link rel="stylesheet" href="../resources/datepicker/jquery-ui.css">
		<link rel="stylesheet" href="../resources/datepicker/jquery-ui.theme.css">
		<link rel="stylesheet" href="../resources/datepicker/jquery-ui.structure.css">

		<!-- DATATABLES -->
		<link rel="stylesheet" href="../resources/datatables/media/css/dataTables.bootstrap.css">


	</head>
	<body style='background-color: #0F3057;' tabindex='1'>
				<img src="../resources/img/loading.gif" height='150px' id='loading-img' style='position:fixed; z-index:10000; top:30%; left:45%;'>

			    <!-- ********************************* MOBILE NAV *********************************************************************-->
    			<div class="visible-xs visible-sm">
        			<div class="navbar navbar-inverse navbar-fixed-top">
           				<div class="container">

                			<div class="navbar-header">
			                    <div class="navbar-brand">EPRPO</div>
			                    <button class="navbar-toggle" data-toggle="collapse" data-target=".collapseThisNav">
			                        <span class="icon-bar"></span>
			                        <span class="icon-bar"></span>
			                        <span class="icon-bar"></span>
			                    </button>
			                </div>

			                <div class="collapse navbar-collapse collapseThisNav">
			                    <ul class="nav navbar-nav">
			                    	<li class='nosubmenu' data-file='dashboard.php'><a>Dashboard</a></li>
			                        
			                        <li class="dropdown hidden" data-id='system-section'>
			                            <a class="dropdown-toggle" data-toggle="dropdown">System &nbsp;<i class='fa fa-caret-down'></i></a>
			                                <ul class="dropdown-menu">
			                                	 <li class='nosubmenu hidden' data-file='System/configuration.php' data-id='configuration' data-js='configuration.js'>
				                                	<a>Configuration</a>
				                                </li>
				                                <li class='nosubmenu hidden' data-file='System/user.php' data-id='user' data-js='user.js'>
				                                	<a>User</a>
				                                </li>
				                                <li class='nosubmenu hidden' data-file='System/user-group.php' data-js='user-group.js' data-id='usergroup'>
				                                	<a>User Group</a>
				                                </li>
				                                <li class='nosubmenu hidden' data-file='System/approver.php' data-id='approver' data-js='approver.js'>
				                                	<a>Approver</a>
				                                </li>
				                                <li class='nosubmenu hidden' data-file='System/transaction-type.php' data-js='transaction-type.js' data-id='transaction'>
				                                	<a>Transaction Type</a>
				                                </li>
			                                </ul>   
			                        </li>
			                         <li class="dropdown hidden" data-id='maintenance-section'>
          								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Maintenance&nbsp; <span class="caret"></span></a>
							          	<ul class="dropdown-menu">
								            <li class='nosubmenu hidden' data-file='Maintenance/items.php' data-js='item.js' data-id='items'>
			                                	<a>Item</a>
			                                </li>
			                                <li class='nosubmenu hidden' data-file='Maintenance/supplier.php' data-js='supplier.js' data-id='supplier'>
			                                	<a>Supplier</a>
			                         		 </li>
			                                <li class='nosubmenu hidden' data-file='Maintenance/terms-of-payment.php' data-js='terms-of-payment.js' data-id='terms'>
			                                	<a>Term of Payment</a>
			                                </li>
			                                <li class='nosubmenu hidden' data-file='Maintenance/section-department.php'  data-js='section-department.js' data-id='department'>
			                                	<a>Section/Department</a>
			                                </li>

         								 </ul>
       								</li>
       								<li class="dropdown hidden" data-id='transaction-section'>
          								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Transaction&nbsp;<span class="caret"></span></a>
							          	<ul class="dropdown-menu">
										    <li class='nosubmenu hidden' data-file='Transaction/purchase-requisition.php' data-js='purchase-requisition.js' data-id='pr'>
			                                	<a>Purchase Requisition</a>
			                                </li>
			                                <li class='nosubmenu hidden' data-file='Transaction/purchase-order.php' data-js='purchase-order.js' data-id='po'>
			                                	<a>Purchase Order</a>
			                                </li>
			                                <li class='nosubmenu hidden' data-file='Transaction/receiving.php' data-js='receiving.js' data-id='receiving'>
			                                	<a>Receiving</a>
			                                </li>
			                                <li class='nosubmenu hidden' data-file='Transaction/hold-invoice.php' data-js='hold-invoice.js' data-id='hold-invoice'>
			                                	<a>Hold Invoice</a>
			                                </li>

         								 </ul>
       								</li>
       								<li class="dropdown hidden" data-id='reports-section'>
          								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reports&nbsp; <span class="caret"></span></a>
							          	<ul class="dropdown-menu">
							          		<li class='nosubmenu hidden' data-file='Reports/pr-summary.php' data-js='pr-summary.js' data-id='prsumm'>
			                                	<a>Purchase Request Summary</a>
			                                </li>
			                                <li class='nosubmenu hidden' data-file='Reports/po-summary.php' data-js='po-summary.js' data-id='posumm'>
			                                	<a>Purchase Order Summary</a>
			                                </li>
			                                <li class='nosubmenu hidden' data-file='Reports/rcv-summary.php' data-js='rcv-summary.js' data-id='rcvsumm'>
			                                	<a>Receiving Summary</a>
			                                </li>
							          	</ul>
							         </li>
							         <li><a href='logout.php'>Logout</a></li>
			                       




			                    </ul>
			                </div>

			            </div>
			        </div>
			    </div>
			    <!-- ********************************* END MOBILE NAV ******************************************************************-->





			   <!-- ************************************ SIDEBAR NAV ****************************************************************** -->
			   <div id="sidebar">
			   		<ul id="sidebar-nav">
			   			<li class="sidebarlogo">
			   				<a href='http://www.pricon.com.ph'><img class='img img-responsive' src="../resources/logo.png?v=2"></a>
			   			</li>
			   			<li class='nosubmenu active hidden' data-file='dashboard.php' data-js='dashboard.js' id='dash'><img class='img' src="../resources/icons/home.png"><span>Dashboard</span></li>
			   			<li class='withsubmenu hidden' id='system-section'><img class='img' src="../resources/icons/system.png">System</li>
	                            <ul class="submenu">
	                                <li class='hidden' data-file='System/configuration.php' id='configuration' data-js='configuration.js'>
	                                	<img class='img' src="../resources/img/folder_64.png">Configuration
	                                </li>
	                                <li class='hidden' data-file='System/user.php' id='user' data-js='user.js'>
	                                	<img class='img' src="../resources/img/folder_64.png">User
	                                </li>
	                                <li class='hidden' data-file='System/user-group.php' data-js='user-group.js' id='usergroup'>
	                                	<img class='img' src="../resources/img/folder_64.png">User Group
	                                </li>
	                                <li class='hidden' data-file='System/approver.php' id='approver' data-js='approver.js'>
	                                	<img class='img' src="../resources/img/folder_64.png">Approver
	                                </li>
	                                <li class='hidden' data-file='System/transaction-type.php' data-js='transaction-type.js' id='transaction'>
	                                	<img class='img' src="../resources/img/folder_64.png">Transaction Type
	                                </li>
	                            </ul>
	                    <li class='withsubmenu hidden' id='maintenance-section'><img class='img' src="../resources/icons/maintenance.png">Maintenance</li>
	                            <ul class="submenu">
	                                <li class='hidden' data-file='Maintenance/items.php' data-js='item.js' id='items'>
	                                	<img class='img' src="../resources/img/folder_64.png">Item
	                                </li>
	                                <li class='hidden' data-file='Maintenance/supplier.php' data-js='supplier.js' id='supplier'>
	                                	<img class='img' src="../resources/img/folder_64.png">Supplier
	                                </li>
	                                <li class='hidden' data-file='Maintenance/terms-of-payment.php' data-js='terms-of-payment.js' id='terms'>
	                                	<img class='img' src="../resources/img/folder_64.png">Term of Payment
	                                </li>
	                                <li class='hidden' data-file='Maintenance/section-department.php'  data-js='section-department.js' id='department'>
	                                	<img class='img' src="../resources/img/folder_64.png">Section/Department
	                                </li>
	                                <li class='hidden' data-file='Maintenance/ship-via.php'  data-js='ship-via.js' id='shipvia'>
	                                	<img class='img' src="../resources/img/folder_64.png">Ship Via
	                                </li>
	                                <li class='hidden' data-file='Maintenance/mode-of-shipment.php'  data-js='mode-of-shipment.js' id='modeofshipment'>
	                                	<img class='img' src="../resources/img/folder_64.png">Mode of Shipment
	                                </li>
	                                <li class='hidden' data-file='Maintenance/classification-code.php'  data-js='classification-code.js' id='classificationcode'>
	                                	<img class='img' src="../resources/img/folder_64.png">Classification Code
	                                </li>
	                            </ul>
	                    <li class='withsubmenu hidden' id='transaction-section'><img class='img' src="../resources/icons/approver.png">Transaction</li>
	                            <ul class="submenu">
	                                <li class='hidden' data-file='Transaction/purchase-requisition.php' data-js='purchase-requisition.js' id='pr'>
	                                	<img class='img' src="../resources/img/folder_64.png">Purchase Requisition
	                                </li>
	                                <li class='hidden' data-file='Transaction/purchase-order.php' data-js='purchase-order.js' id='po'>
	                                	<img class='img' src="../resources/img/folder_64.png">Purchase Order
	                                </li>
	                                <li class='hidden' data-file='Transaction/receiving.php' data-js='receiving.js' id='receiving'>
	                                	<img class='img' src="../resources/img/folder_64.png">Receiving
	                                </li>
	                                <li class='hidden' data-file='Transaction/hold-invoice.php' data-js='hold-invoice.js' id='hold-invoice'>
	                                	<img class='img' src="../resources/img/folder_64.png">Hold Invoice
	                                </li>

	                            </ul>
	                    <li class='withsubmenu hidden' id='reports-section'><img class='img' src="../resources/icons/po.png">Reports</li>
	                            <ul class="submenu">
	                                <li class='hidden' data-file='Reports/pr-summary.php' data-js='pr-summary.js' id='prsumm'>
	                                	<img class='img' src="../resources/img/folder_64.png">Purchase Request Summary</li>
	                                <li class='hidden' data-file='Reports/po-summary.php' data-js='po-summary.js' id='posumm'>
	                                	<img class='img' src="../resources/img/folder_64.png">Purchase Order Summary</li>
	                                <li class='hidden' data-file='Reports/rcv-summary.php' data-js='rcv-summary.js' id='rcvsumm'>
	                                	<img class='img' src="../resources/img/folder_64.png">Receiving Summary</li>
	                            </ul>

			   		</ul>
			   </div>

			   <!-- ************************************END SIDEBAR NAV ****************************************************************** -->





			   <!-- ************************************************ HEADER ******************************************** -->
			   <div class="topheader">
			   			<div class='user-header hidden-xs hidden-sm' >
			   				<div class="user-header-inner" >
				   				<i class="fa fa-user fa-2x" style='padding-right:5px; font-size:1.5em;'></i>
				   				<span>
				   					Hello, <?php echo ucwords(strtolower(@$_SESSION['fname'])); ?>
				   				</span>!&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<?php echo date('F j, Y l') ?>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
				   				<span class='account-settings-link pointer' id='usersettings' title='User Settings'>
				   					Settings
				   				</span>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
				   				<a class='signout' href="logout.php">
				   					<i class="fa fa-sign-out fa-2x" style='padding-right:5px; font-size:1.2em'></i>Sign out
				   				</a>
				   				</span>
				   			</div>	   				
			   			</div>
			   </div>

			   <!-- ************************************************ HEADER ******************************************** -->





			   <!-- ******************************************* CONTENT ****************************************************************-->

				<div class='content' id='content' style='overflow:auto;'>

				</div>

			   <!-- ************************************ END OF CONTENT ********************************************************************-->



		





		<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->

		<script src="../js/jquery-1.11.3.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../js/jsfunctions.js"></script>

		<script src="../resources/BootstrapNotification/bootstrap-notify.min.js"></script>

		<script type="text/javascript" src="../resources/dialog/jquery-confirm.js"></script> 	

		<script type="text/javascript" src="../resources/datepicker/jquery-ui.js"></script>
		<script type="text/javascript" src="../resources/jquery-interactions/jquery-ui.js"></script>

		<script src="../resources/tags/dist/bootstrap-tagsinput.min.js"></script>
		<script src="../resources/tags/examples/assets/app.js"></script>

		<!-- MY CUSTOM SCRIPTS -->
		<script type="text/javascript" src='../js/custom.js'></script>	
		<script type="text/javascript" src='../js/main.js'></script>

		<!-- DATATABLES -->
		<script type="text/javascript" src="../resources/datatables/media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" src="../resources/datatables/media/js/dataTables.bootstrap.js"></script>

		<!-- FOR DROPDOWN -->
		<script src="../resources/select/dist/js/select2.js"></script>

		<script type="text/javascript">
			$(document).ready(function(){
				
				$('#loading-img').addClass('hidden');
				
				var windowHeight = $(window).height()-51;
				$('.content').css("height",windowHeight);
				$(window).resize(function(){
					$('.content').css({height:$(window).height()-51});
				});

				userAccess();
				userAccessMobile();

				var viewtobeloaded  = <?php echo json_encode($viewtobeloaded) ?>;
				viewtobeloaded = viewtobeloaded.trim();
				///////////////
				var email_prnum = <?php echo json_encode($email_reqnum) ?>;
				var email_to = <?php echo json_encode($email_to) ?>;
				var email_toname = <?php echo json_encode($email_toname) ?>;
				var sourcebtn = <?php echo json_encode($sourcebtn) ?>;
				//////////////

				if(viewtobeloaded=='purchase-requisition'){
					$('#dash').removeClass('active');
					$('#pr').addClass('active');
					$('#transaction-section').next('ul').slideToggle(400);
					$('.content').load('Transaction/'+viewtobeloaded+'.php',function(){
						setTimeout(function(){
							$.getScript(pagescripts+viewtobeloaded+'.js');
							if(sourcebtn.trim()=='submitbtn'){
								notify("Purchase Requisition has been <b>submitted</b>", "success","fa fa-check-circle");
								$.post(server+'purchase-requisition.php',{emailnotification:'Nsl3Ep203N0amlOepan10@#0',reqnum:email_prnum,emailto:email_to,emailtoname:email_toname},function(data){
									if(data.trim()!=1||data.trim()!=true){
										alert(data);
									}
								});
							}
							else if(sourcebtn.trim()=='savebtn'){
								notify("Purchase Requisition has been <b>saved</b>", "success","fa fa-check-circle");
							}
						},400);
					});
					
					

					///////////////// PR Creation with Attachment - send email after files are attached and page redirected to pr view
					
					//////////////////////////////////////////////////////////////////////////////////////////////////////

					//////////// SESSIONS SET /////////////////////////////////////
					<?php 
						$_SESSION['MdjKeoIdhk']='';  //view to be loaded variable
						unset($_SESSION['emailtoXoslapEsnIE']); //variable - email recipient
						unset($_SESSION['reqnumXoslapEsnIE']); //variable - pr number
						unset($_SESSION['emailtonameXoslapEsnIE']); //variable - email recipient name
						unset($_SESSION['sourcebtnPR']); //var for notification PR after submit()
					?>
					//////////////////////////////////////////////////////////////

				}
				else if(viewtobeloaded=='supplier-upload-file'||viewtobeloaded=='item-upload-file'){
					$('.content').load('Other/'+viewtobeloaded+'.php',function(){
						setTimeout(function(){
							$.getScript(pagescripts+viewtobeloaded+'.js');
							$('#loading-img').addClass('hidden');
						},400);
					});
					
					<?php $_SESSION['MdjKeoIdhk']=''; ?>
				}
				else{
					$('.content').load('dashboard.php');
					$.getScript(pagescripts+'dashboard.js');
				}

				$(document).on('click','#usersettings',function(){
					$('#dash').removeClass('active');
					$('.content').load('user-settings.php');
					$.getScript(pagescripts+'user-settings.js');
				});

				/** BUTTON CLICK SOUND ***/
				var obj = document.createElement("audio"); 
				obj.src="../resources/click.mp3";
				obj.volume=1;
				obj.autoPlay=false;
				obj.preLoad=true;
				$(document).on("click","#sidebar-nav li, .pagination>li,#usersettings, .pointer, .mybtn, .approvalrow,#pohistory>tr,#prhistory>tr,#dashsettingsbtn",function(){
			        obj.play();
				});
				/***************************/

				
				
			});

		</script>






		


	</body>
</html>