<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/db-function.php");


    if(isset($_POST['getDepartmentUsers'])){
    	if($_POST['getDepartmentUsers']=='sk@O#$@lai$juosn%Liso%snl'){

    		$dept = escapeString($_POST['dept']);
    		$rs = query("select * from user where section_department_id='$dept'");

    		$data = "<option value=''></option>";
    		while($obj=fetch($rs)){
		    	$data .= "<option value='".$obj->id."'>".$obj->first_name." ".$obj->last_name."</option>";
		    }
		    echo $data;
    	}
    }
?>



