<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/db-function.php");

    if(isset($_POST['Ldou3in48Fbkuaf209janr'])){
    	if($_POST['Ldou3in48Fbkuaf209janr']=='Kdeiah3jsu40j20s00ne'){

    		// if pr approver
    		$query = "select COUNT(user_id) as approvalcount from approver where user_id='$userid' and not approval_type_id='8'";
    		// $prapprover = getNumRows(query($query));
			$rs = query($query);
			if($obj=fetch($rs)){
				$prapprover = $obj->approvalcount;														
			}

    		// if po approver
    		$query = "select COUNT(user_id) as approvalcount from approver where user_id='$userid' and approval_type_id='8'";
    		// $poapprover = getNumRows(query($query));
			$rs = query($query);
			if($obj=fetch($rs)){
				$poapprover = $obj->approvalcount;														
			}

            // if pr approver
            $query = "select COUNT(requisitioner) as approvalcount from purchase_requisition_header where requisitioner='$userid'";
            // $requisitioner = getNumRows(query($query));
			$rs = query($query);
			if($obj=fetch($rs)){
				$requisitioner = $obj->approvalcount;														
			}
			
			// if purchaser
            $query = "select COUNT(assigned_to) as approvalcount from purchase_order_header where assigned_to='$userid'";
            // $purchaser = getNumRows(query($query));
			$rs = query($query);
			if($obj=fetch($rs)){
				$purchaser = $obj->approvalcount;														
			}

    		if($prapprover>0&&$poapprover>0){
    			echo "both";
    		}
    		else if($prapprover>0){
    			echo "pr";
    		}
    		else if($poapprover>0){
    			echo "po";
    		}
            else if($requisitioner>0){
                echo "requisitioner";
            }
            else if($purchaser>0){
                echo "purchaser";
            }

    	}
    }


?>