<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/checklogin.php");
    include("../../config/db-function.php");

    if(isset($_GET['Dkrojfknnkeh%pdD'])){
        $pr = $_GET['Dkrojfknnkeh%pdD'];
        $line = 1;
        $query="SELECT a.*, b.section_department_name as dept, c.section_department_name as bu, d.section_department_name as pl 
                FROM `purchase_requisition_allocation` a 
                left JOIN section_department b ON b.id = a.department_id
                left JOIN section_department c ON c.id = a.business_unit
                left JOIN section_department d ON d.id = a.product_line
                where a.requisition_number='$pr'";
    $rs = query($query);
        if(getNumRows($rs)<1){
            echo "<tr><td class='noresult' colspan='20'>No Allocation</td></tr>";
        }
        while($obj=fetch($rs)){;
            $dept = $obj->dept;
            $bu = $obj->bu;
            $pl = $obj->pl;
            $perc = $obj->percentage;
            $remarks = strUtfEncode($obj->remarks);
            echo "<tr>
            <td class='text-center'>$line</td>
            <td>$dept</td>
            <td>$bu</td>
            <td>$pl</td>
            <td class='text-center'>$perc</td>
            <td>$remarks</td>
            </tr>";
            $line++;
        }
    }
?>