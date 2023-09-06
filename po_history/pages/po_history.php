<?php
require_once("system/includes.php");
require_once("../oop/oop.php");
session_start();

$username = $_SESSION["username"];
// $username = 'jangeco';
?>	

<style>
.container2 {
	position: absolute;
	height: 50px;
	width: 50%;
	top: 0;
	right: 0;
	bottom: 0;
	left: 9%;
	margin: auto;
	text-align: center;
}
div div.ball {
	width: 20px;
	position: relative;
	display: inline-block;
	margin: 5px;
	height: 20px;
	border-radius: 50%;
	z-index: 999;
}

.ball-one {
	background-color: #FFCE64;
	animation: jump .5s ease 0s infinite alternate;
}

.ball-two {
	background-color: #4BB3EF;
	animation: jump .5s ease .15s infinite alternate;
}

.ball-three {
	background-color: #63DD79;
	animation: jump .5s ease .25s infinite alternate;
}

.ball-four {
	background-color: #FD6C6C;
	animation: jump .5s ease .35s infinite alternate;
}

.shadow {
	position: relative;
	opacity: .1;
	bottom: 5px;
	width: 20px;
	height: 5px;
	right: 17%;
	border-radius: 50%;
	background-color: black;
	display: inline-block;
	margin: 5px;
	z-index: 50;
}

.shadow-one {
	animation: shrink .5s ease .0s infinite alternate;
}

.shadow-two {
	animation: shrink .5s ease .15s infinite alternate;
}

.shadow-three {
	animation: shrink .5s ease .25s infinite alternate;
}

.shadow-four {
	animation: shrink .5s ease .35s infinite alternate;
}

@keyframes jump {
	0% {transform: scaleY(.8);}
	100% {
		transform: translateY(-70px);
	}   
}

@keyframes shrink {
	100% {
		transform: scaleX(.5);
		opacity: .01;
	}    
}
</style>

<br>
<br>
<div class="col-lg-12">
	<center><strong><font size="5">PURCHASE ORDER LIST</font></strong></center>
</div>
<div class="container" style="width:100%;">
	<div class="row">
	<br>
		<div class="container2" hidden id="div_loading">
			<div class="ball ball-one"></div>
			<div class="ball ball-two"></div>
			<div class="ball ball-three"></div>
			<div class="ball ball-four"></div>
			<div class="shadow shadow-one"></div>
			<div class="shadow shadow-two"></div>
			<div class="shadow shadow-three"></div>
			<div class="shadow shadow-four"></div>
		</div>

		<div class="col-lg-12 col-md-12 col-sm-12">
			<div id="container_po_list">
			</div>
		</div>
	</div>
</div>

<div class="modal" id="id_po_history_modal_show">
	<div class="modal-dialog" style="width:50%;">
		<form id="id_form_po_history" data-confirm="Are you sure you want to edit this data?">
			<div class="modal-content">
				<div class="modal-body">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h3 class="modal-title"><center><b><span id="po_no" style="color:blue;"></span> HISTORY</b></center></h3>
					</div>
					
					<div class="container-fluid">
					<br>
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div id="container_tbl_po_details">
							</div>
						</div>
					</div>
					
				</div>
				
				<div class="modal-footer">
					<div class="container-fluid">
						<div class="row">
							<div class="col-sm-12 pull-right">
								<button type="button" id="id_po_history_close_modal" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> CLOSE</button>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</form>
	</div>
</div>

<script src="scripts/po_history.js"></script>