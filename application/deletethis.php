<?php

    include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include('../config/functions.php');


    $rs = query("Select * from user");
    if(getNumRows($rs)>0){
    	$output = "<table>
    					<tr>
    						<th>ID</th>
    						<th>FIRST NAME</th>
    						<th>LAST NAME</th>
    					</tr>
    				";
    }
    while($obj = fetch($rs)){
    	$output .= "<tr>
    				<td>$obj->id</td>
    				<td>$obj->first_name</td>
    				<td>$obj->last_name</td>
    		  </tr>";
    }
    $output .= "</table>";
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=download.xls");
    echo $output;

?>