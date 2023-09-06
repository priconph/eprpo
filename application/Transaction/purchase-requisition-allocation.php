<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/checklogin.php");
    include("../../config/db-function.php");

    if(isset($_GET['Dkrojfknnkeh%pdD'])){
        $pr = $_GET['Dkrojfknnkeh%pdD'];
        $line = 1;
        $query="select section_department_name, purchase_requisition_allocation.remarks, percentage
        from purchase_requisition_allocation, section_department 
        where requisition_number='$pr' and
        department_id=section_department.id";
        $rs = query($query);
        if(getNumRows($rs)<1){
            echo "<tr><td class='noresult' colspan='20'>No Allocation</td></tr>";
        }
        while($obj=fetch($rs)){;
            $dept = $obj->section_department_name;
            $perc = $obj->percentage;
            $remarks = strUtfEncode($obj->remarks);
            echo "<tr>
            <td class='text-center'>$line</td>
            <td>$dept</td>
            <td class='text-center'>$perc</td>
            <td>$remarks</td>
            </tr>";
            $line++;
        }
    }
?>