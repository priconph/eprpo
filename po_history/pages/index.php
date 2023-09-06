
<?php
include_once('system/includes.php');
require_once('po_history.php');

if(isset($_GET['page'])){

	$page = $_GET['page'];
	include($page);	
}					  
?>


