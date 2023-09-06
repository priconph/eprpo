<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/checklogin.php");
    include("../../config/db-function.php");

    if(isset($_GET['KdkhDNlROiu%pdD'])){
    	$pr = $_GET['KdkhDNlROiu%pdD'];
		$query = "select * from attachments where requisition_number='$pr'";
		$rs = query($query);
		if(getNumRows($rs)<1){
			echo "<tr><td class='noresult' colspan='20'>No Attachments</td></tr>";
		}
		while($obj=fetch($rs)){
			$filename = $obj->file_name;
			$filedesc = $obj->file_description;
			                                                        //temp
			if($filedesc=='NULL'){
				$filedesc = '';
			}
			                                                        //
			$other = strUtfEncode($obj->other_description);
			echo "<tr class='attachmentrow'>
					<td class='text-center'>
						<form action='Attachments/download.php' method='post'>
							<input type='hidden' name='file' value='$filename'>
							<input class='btn btn-xs btn-default' type='submit' value='download' name='downloadbtn'>
						</form>
					</td>
					<td>$filename</td>
					<td>$filedesc</td>
					<td>$other</td>
				</tr>";
		}
	}
?>