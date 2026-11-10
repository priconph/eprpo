<?php

	include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("email-class.php");

    if(isset($_POST['emailnotification'])&&$_POST['emailnotification']=='Nsl3Ep203N0amlOepan10@#0'){
		$reqnum = escapeString($_POST['reqnum']);
		$emailto = escapeString($_POST['emailto']);
		$toname = escapeString($_POST['emailtoname']);
		$date = $reqdate = date('Y-m-d H:i:s');
		$rs = query("select * from purchase_requisition_header where requisition_number='$reqnum'");
		while($obj=fetch($rs)){
			$reqtype = $obj->requisition_type;
			$rev_no = $obj->next_revision_series;
			$justification = $obj->justification;
			$requestor = getUserName($obj->requisitioner);
			$ccrequestor = getUserEmail($obj->requisitioner);
						
			$section_head1 = $obj->section_head1;
			$section_head2 = $obj->section_head2;
			$conformance = $obj->conformance;
			$general_manager = $obj->general_manager;
			$assistant_vice_president = $obj->assistant_vice_president;
			$senior_vice_president = $obj->senior_vice_president;
			$president = $obj->president;
		}
		
		if($section_head1 != '' && $section_head1 != 1 && !is_null($section_head1)) {
			$ctr++;
		}
		if($section_head2 != '' && $section_head2 != 1 && !is_null($section_head2)) {
			$ctr++;
		}
		if($conformance != '' && $conformance != 1 && !is_null($conformance)) {
			$ctr++;
		}
		if($general_manager != '' && $general_manager != 1 && !is_null($general_manager)) {
			$ctr++;
		}
		if($assistant_vice_president != '' && $assistant_vice_president != 1 && !is_null($assistant_vice_president)) {
			$ctr++;
		}
		if($senior_vice_president != '' && $senior_vice_president != 1 && !is_null($senior_vice_president)) {
			$ctr++;
		}
		if($president != '' && $president != 1 && !is_null($president)) {
			$ctr++;
		}
		
		// $rs2 = query("select * from purchase_requisition_approval_history where requisition_number='$reqnum' AND revision_flag='$rev_no'");
		// $ctr2 = returnNumRows($rs2) + 1;
		// $approval_hierarchy = return_ordinal($ctr2).' Approvals ('.$ctr.' approvers)';
		
		$rs2 = query("select * from purchase_requisition_approval_history where requisition_number='$reqnum' AND revision_flag='$rev_no'");
		$ctr_approved = returnNumRows($rs2) + 1;
		$approval_hierarchy = $ctr == $ctr_approved ? 'Final Approver (from '.$ctr.' approvers)' : return_ordinal($ctr_approved).' Approver (from '.$ctr.' approvers)';
		
		$emailsuccess = $emailClass->sendNotification($emailto,$toname,'EPRPO - Purchase Requisition Approval',prMessage($reqnum,$requestor,$date,$reqtype,$justification,$approval_hierarchy),prMessage($reqnum,$requestor,$date,$reqtype,$justification,$approval_hierarchy),array($ccrequestor));
		if($emailsuccess==true){
			echo true;
		}
		else{
			echo "Unable to send email notification. [".$reqnum."]";
		}
	}

	if(isset($_POST['JKdekJFHnKhkerjb'])){//submit
		$reqnum = escapeString($_POST['reqnum']);
		$currency = escapeString($_POST['curr']);
		$requester = escapeString($userid);
		$dateneeded = date('Y-m-d', strtotime($_POST['dateneeded']));
		$now = date('Y-m-d H:i:s');
		$reqtype = escapeString($_POST['reqtype']);
		$remarks = escapeString($_POST['remarks']);
		$sh1 = escapeString($_POST['sh1']);
		$sh2 = escapeString($_POST['sh2']);
		// $gm = escapeString($_POST['gm']);
		$gm = 1;
		$avp = escapeString($_POST['avp']);
		$svp = escapeString($_POST['svp']);
		$pr = escapeString($_POST['pr']);
		$conf = escapeString($_POST['conf']);
		$prnotes = escapeString($_POST['prnotes']);
		$flag = 0;
		$items = $_POST['items'];
		$qtys = $_POST['qty'];
		$prices = $_POST['prices'];
		$classfns = $_POST['classfns'];
		$suppliers = $_POST['suppliers'];
		$length = count($items);

		$prdesc = getItemInfo($items[0],"item_name");
		$prdesc = strUtfDecode($prdesc);

		@$filestobedeleted = $_POST['deletefiles'];
		$deletefilescount = count($filestobedeleted);

		$allocremarks = $_POST['allocremarks'];
		$allocdept = $_POST['allocdept'];
		$allocbu = $_POST['allocbu'];
		$allocpl = $_POST['allocpl'];
		$allocperc = $_POST['allocperc'];
		$alloclength = count($allocbu);

		////////////////////EMAIL NOTIFICATION/////////////////////////////////

		$emailto = getUserEmail($sh1);
		$toname = getUserName($sh1);
		/*$emailsuccess = $emailClass->sendNotification($emailto,$toname,'EPRPO - Purchase Requisition Approval',prMessage($reqnum),prMessage($reqnum));
		if($emailsuccess==true){*/
		///////////////////////////////////////////////////////////////////////

				$query = "delete from purchase_requisition_details where requisition_number='$reqnum'";
				$rs = query($query);
				$query = "delete from purchase_requisition_allocation where requisition_number='$reqnum'";
				$rs = query($query);

				if($rs){
					$query = "update purchase_requisition_header set pr_description='$prdesc', pr_notes='$prnotes', requisition_status='IN REVIEW', requisitioner='$requester', requisition_type='$reqtype', requisition_date='$now', date_needed='$dateneeded', justification='$remarks', section_head1=$sh1, section_head2=$sh2, conformance=$conf, general_manager=$gm, assistant_vice_president=$avp, senior_vice_president=$svp, president=$pr, flag='$flag', currency=$currency, last_modified='$now', last_made_changes='$now' where requisition_number='$reqnum'";
					$rs = query($query);
					if($rs){
						$ok = true;
						for($i=0;$i<$deletefilescount;$i++){
							$filetodelete = escapeString($filestobedeleted[$i]);
							$query = "delete from attachments where requisition_number='$reqnum' and file_name='$filetodelete'";
							$r = query($query);
							unlink("../application/Attachments/".$filetodelete);
						}

						for($i=0;$i<$length;$i++){
							$itemid = escapeString($items[$i]);
							$qty=escapeString($qtys[$i]);
							$price=escapeString($prices[$i]);
							$classfn = escapeString($classfns[$i]);
							$supplier = escapeString($suppliers[$i]);
							$query = "insert into purchase_requisition_details(requisition_number,item_id,quantity,unit_price,classification_code,supplier_id) values('$reqnum','$itemid','$qty','$price','$classfn','$supplier')";
							$rs = query($query);
							
						}

						for($i=0;$i<$alloclength;$i++){
							$aRem = escapeString($allocremarks[$i]);
							$aDept = escapeString($allocdept[$i]);
							$aBU = escapeString($allocbu[$i]);
							$aPL = escapeString($allocpl[$i]);
							$aPerc = escapeString($allocperc[$i]);
							$query = "insert into purchase_requisition_allocation values('$reqnum','$aDept','$aBU','$aPL','$aPerc','$aRem')";
							$rs = query($query);

						}
						echo 'success@#$'.$reqnum.'@#$'.$emailto.'@#$'.$toname;
					}
					else{
						echo mysql_error();
					}
				}
				else{
					echo mysql_error();
				}
		/*}
		else{
			echo $emailsuccess;
		}*/

	}


	if(isset($_POST['JKDlkjHFlriKDhl'])){//save
		if($_POST['JKDlkjHFlriKDhl']=='NDkdleipskje93lkj9'){
			$reqnum = escapeString($_POST['reqnum']);
			$currency = escapeString($_POST['curr']);
			$requester = escapeString($userid);
			if(trim($_POST['dateneeded'])==''){
				$dateneeded = 'NULL';
			}
			else{
				$dateneeded = date('Y-m-d', strtotime($_POST['dateneeded']));
			}
			$now = date('Y-m-d H:i:s');
			$reqtype = escapeString($_POST['reqtype']);
			$remarks = escapeString($_POST['remarks']);
			$sh1 = escapeString($_POST['sh1']);
			$sh2 = escapeString($_POST['sh2']);
			// $gm = escapeString($_POST['gm']);
			$gm = 1;
			$avp = escapeString($_POST['avp']);
			$svp = escapeString($_POST['svp']);
			$pr = escapeString($_POST['pr']);
			$conf = escapeString($_POST['conf']);
			$prnotes = escapeString($_POST['prnotes']);
			$flag = ($conf=='NULL')?1:0;
			@$items = $_POST['items'];
			@$qtys = $_POST['qty'];
			@$prices = $_POST['prices'];
			@$classfns = $_POST['classfns'];
			@$suppliers = $_POST['suppliers'];
			$length = count($items);

			if($length>0){
				$prdesc = getItemInfo($items[0],"item_name");
				$prdesc = strUtfDecode($prdesc);
			}
			else{
				$prdesc=NULL;
			}

			@$filestobedeleted = $_POST['deletefiles'];
			$deletefilescount = count($filestobedeleted);

			@$allocremarks = $_POST['allocremarks'];
			@$allocdept = $_POST['allocdept'];
			@$allocbu = $_POST['allocbu'];
			@$allocpl = $_POST['allocpl'];
			@$allocperc = $_POST['allocperc'];
			$alloclength = count($allocbu);

			$stat = 'DRAFT';
			$prstat = getPrStatus($reqnum);
			if($prstat=='REJECTED'){
				$stat ='REJECTED';
			}


			$query = "delete from purchase_requisition_details where requisition_number='$reqnum'";
			$rs = query($query);
			$query = "delete from purchase_requisition_allocation where requisition_number='$reqnum'";
			$rs = query($query);

			if($rs){
				$query = "update purchase_requisition_header set pr_description='$prdesc', pr_notes='$prnotes', requisition_status='$stat', requisitioner='$requester', requisition_type='$reqtype', date_needed='$dateneeded', justification='$remarks', section_head1=$sh1, section_head2=$sh2, conformance=$conf, general_manager=$gm, assistant_vice_president=$avp, senior_vice_president=$svp, president=$pr, flag='$flag', currency=$currency, last_modified='$now', last_made_changes='$now' where requisition_number='$reqnum'";
				$rs = query($query);
				if($rs){
					$ok = true;
					for($i=0;$i<$deletefilescount;$i++){
						$filetodelete = escapeString($filestobedeleted[$i]);
						$query = "delete from attachments where requisition_number='$reqnum' and file_name='$filetodelete'";
						$r = query($query);
						if(!$rs){
							echo mysql_error();
						}
						unlink("../application/Attachments/".$filetodelete);

					}

					for($i=0;$i<$length;$i++){
						$itemid = escapeString($items[$i]);
						$qty=escapeString($qtys[$i]);
						$price=escapeString($prices[$i]);
						$classfn = escapeString($classfns[$i]);
						$supplier = escapeString($suppliers[$i]);
						$query = "insert into purchase_requisition_details(requisition_number,item_id,quantity,unit_price,classification_code,supplier_id) values('$reqnum','$itemid','$qty','$price','$classfn','$supplier')";
						$rs = query($query);
						if(!$rs){
							echo mysql_error();
						}
					}

					for($i=0;$i<$alloclength;$i++){
						$aRem = escapeString($allocremarks[$i]);
						$aDept = escapeString($allocdept[$i]);
						$aBU = escapeString($allocbu[$i]);
						$aPL = escapeString($allocpl[$i]);
						$aPerc = escapeString($allocperc[$i]);
						// $query = "insert into purchase_requisition_allocation values('$reqnum','$aDept','$aPerc','$aRem')";
						$query = "insert into purchase_requisition_allocation values('$reqnum','$aDept','$aBU','$aPL','$aPerc','$aRem')";
						$rs = query($query);
						if(!$rs){
							echo mysql_error();
						}

					}
					echo 'success';
				}
				else{
					echo mysql_error();
				}
			}
			else{
				echo mysql_error();
			}
		}
	}

	//attachments

			if(isset($_FILES['file'])){
				$response = 'ok';
				$file = $_FILES['file'];
				$length = count($file['name']);
				$reqnum = $_POST['prnumber'];

				//
				$emailto = escapeString($_POST['email-to']);
				$emailtoname = escapeString($_POST['email-toname']);
				$source = escapeString($_POST['source-button']);
				//

				$x = 2;
				$flag = true;

				for($i=0;$i<$length;$i++){

					$tmp = $file['tmp_name'][$i];
					$filename = $file['name'][$i];
					$filetype = escapeString($_POST['filetype'][$i]);
					$otherfiletype = escapeString($_POST['otherfiletype'][$i]);

					//check if filename exist
					$fn = substr($filename, 0, strpos($filename, '.'));
					$ftype = substr($filename, strpos($filename, '.'));
					if(file_exists('../application/Attachments/'.$filename)==1){
						while($flag==true){
							if(file_exists('../application/Attachments/'.$fn.'('.$x.')'.$ftype)==1){
								$x++;
							}
							else{
								$flag = false;
								$filename = $fn.'('.$x.')'.$ftype;
								$x=2;
							}
						}
					}
					//

					move_uploaded_file($_FILES['file']['tmp_name'][$i],'../application/Attachments/'.$filename);
					$query = "insert into attachments values('$reqnum','$filename','$filetype','$otherfiletype')";
					$rs = query($query);
				
				}
				if($response=='ok'){
					$_SESSION['MdjKeoIdhk'] = 'purchase-requisition';
					//
					$_SESSION['reqnumXoslapEsnIE'] = $reqnum;
					$_SESSION['emailtoXoslapEsnIE'] = $emailto;
					$_SESSION['emailtonameXoslapEsnIE'] = $emailtoname;
					$_SESSION['sourcebtnPR']=$source;
					//
					header('location:../application/main.php');
				}
				
			}

			

	//cancel PR
	if(isset($_POST['ksdfw39d32msr'])){
		if($_POST['ksdfw39d32msr']=='kfbwjio3%jw#$Kj'){
			$prnumber = escapeString($_POST['prnumber']);
			$date = date('Y-m-d H:i:s');
			$query = "update purchase_requisition_header set requisition_status='CANCELED', canceled_date='$date' where requisition_number='$prnumber'";
			$rs = query($query);
			if($rs){
				echo "success";
			}
		}
	}

	if(isset($_POST['checkprevattachmentsize'])){
		if($_POST['checkprevattachmentsize']=='Abck$903j#l#kjp0klmsde'){
			$attachments = $_POST['prevattachments'];
			$len = count($attachments);
			$size = 0;
			for ($i=0; $i < $len; $i++) {
				$file = escapeString($attachments[$i]); 
				$size = $size+filesize('../application/Attachments/'.$file);
			}
			echo $size;
		}
	}



?>