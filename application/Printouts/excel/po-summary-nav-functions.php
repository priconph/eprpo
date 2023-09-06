<?php
	// if( isset($_POST['action_copy']) ){
	// 	if (!copy('PATTERN/index.php', $Username.'/index.php')) {
	// 	    echo "failed to copy file...";
	// 	}
	// }

// \\192.168.3.196\dataExtractionPoint
$target_path =  '\\\\192.168.3.196\\dataExtractionPoint\\';
$target_path = 'C:\Users\naayes\Downloads\eprpo_po_2019-12-19.csv'); 
if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
    echo "The file ".  'x.csv'. 
    " has been uploaded";
} else{
    echo "There was an error uploading the file, please try again!";
}


?>