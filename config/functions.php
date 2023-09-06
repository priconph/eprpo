<?php
    include("db-function.php");


    function getPrCancelReason($prnumber){
        $reason = '';
        $rs = query("select * from purchase_requisition_cancellation_history where pr_number='$prnumber' order by created_date desc limit 1");
        while($obj=fetch($rs)){
            $reason = $obj->reason;
        }
        return $reason;
    }

    function checkIfPrHasPo($pr){
        $res = 0;
        $rs = query("select * from purchase_order_header where reference_requisition_number='$pr'");
        $count = getNumRows($rs);
        if($count>0){
            $res = 1;
        }
        return $res;
    }

    function checkIfAllPoOfPrCancelled($pr){
        $res = 0;
        $rs = query("select * from purchase_order_header where reference_requisition_number='$pr' and order_status!='CANCELLED' and order_status!='FOR CANCELLATION'");
        $count = getNumRows($rs);
        if($count==0){
            $res = 1;
        }
        return $res;
    }

    function checkIfAllPoOfPrCancelled2($pr,$ponumbers){
        $ponumbers = "('".implode("','", $ponumbers)."')";
        $res = 0;
        $rs = query("select * from purchase_order_header where reference_requisition_number='$pr' and order_status!='CANCELLED' and order_status!='FOR CANCELLATION' and order_number not in $ponumbers");
        $count = getNumRows($rs);
        if($count==0){
            $res = 1;
        }
        return $res;
    }

    function getPoCancelReason($ponumber){
        $reason = '';
        $rs = query("select * from purchase_order_cancellation_history where po_number='$ponumber' order by created_date desc limit 1");
        while($obj=fetch($rs)){
            $reason = $obj->reason;
        }
        return $reason;
    }

    function getPoCancellationInfo($ponumber){
        $date = '';
        $by = '';
        $reason = '';
        $data = array('','','');
        $rs = query("select * from purchase_order_cancellation_history where po_number='$ponumber' order by created_date desc limit 1");
        while($obj=fetch($rs)){
            $reason = $obj->reason;
            $date = formatDate2($obj->created_date);
            $by = getUserName($obj->created_by);
            $data[0]=$reason;
            $data[1]=$date;
            $data[2]=$by;
            //array_push($data, $reason,$date,$by);
        }
        return $data;
    }
    
    function getSuppliers($itemid){
        $arr = array();
        $rs = query("select * from item_supplier where item_id='$itemid'");
        while($obj=fetch($rs)){
            array_push($arr, $obj->supplier_id);
        }
        $str = implode('#@$', $arr);
        return $str;
    }

    function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 10; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    function userAccess($user,$menuid){
        $query = "select * from user_rights where user_id='$user' and menu_id='$menuid'";
        $rs = query($query);
        if(getNumRows($rs)>=1){
            return true;
        }
        else{
            return false;
        }
    }

    // function userAccess_app_item(){
    //     $query = "select * from user where (id='1' or id='106' or id='27' or id='214') ";
    //     $rs = query($query);
    //     if(getNumRows($rs)>=1){
    //         return true;
    //     }
    //     else{
    //         return false;
    //     }
    // }

    function appendPrRevSuffix($prnumber){
        $suffix = '';
        $rs = query("select * from purchase_requisition_header where requisition_number='$prnumber'");
        while($obj=fetch($rs)){
            if($obj->next_revision_series>-1){
                $suffix = '-R'.$obj->next_revision_series;
            }
        }
        return $suffix;
    }
    function appendPoRevSuffix($ponumber){
        $suffix = '';
        $rs = query("select * from purchase_order_header where order_number='$ponumber'");
        while($obj=fetch($rs)){
            if($obj->next_revision_series>-1){
                $suffix = '-R'.$obj->next_revision_series.'.'.$obj->purch_revision_no;
            }
        }
        return $suffix;
    }

    function getSupplierString($itemid){
        $data = "";
        $arr  = array();
        $rs = query("select supplier_name, 
                            item_supplier.item_id 
                     from item_supplier
                     inner join supplier on supplier_id=supplier.id 
                     where item_id='$itemid'");
        while($obj=fetch($rs)){
            array_push($arr, strUtfEncode($obj->supplier_name));
        }

        $data = implode(', ', $arr);
        return $data;


    }

    function getInfo($table,$column,$condition){
        $ret = '';
        $rs = query("select * from ".$table." ".$condition);
        while($obj=fetch($rs)){
            $ret = $obj->$column;
        }
        return $ret;
    }
    function trimString($str){
        $newstr =  str_replace("'", '', $str);
        $newstr =  str_replace('"', '', $newstr);
        return $newstr;
    }
    function getItemInfo($id,$columnname){
        $ret = '';
        $rs = query("select * from item where id='$id'");
        while($obj=fetch($rs)){
            $ret = strUtfEncode($obj->$columnname);
        }
        return $ret;
    }

    function formatDate($date){
        if($date=="0000-00-00 00:00:00"||$date==''){
            $date ='';
        }
        else{
           $date = date('M d, Y', strtotime($date));
        }
        return $date;
    }
    function formatDate2($date){
        if($date=="0000-00-00 00:00:00"||$date==''){
            $date ='';
        }
        else{
           $date = date('M d, Y h:i:s A', strtotime($date));
        }
        return $date;
    }
    function formatDate3($date){
        if($date=="0000-00-00 00:00:00"||$date==''||$date=='0000-00-00'){
            $date ='';
        }
        else{
           $date = date('M d, Y', strtotime($date));
        }
        return $date;
    }
     function formatDate4($date){
        if($date=="0000-00-00 00:00:00"||$date==''||$date=='0000-00-00'){
            $date ='';
        }
        else{
           $date = date('M d, Y h:i:s A', strtotime($date));
        }
        return $date;
    }
    function formatDate5($date){
        if($date=="0000-00-00 00:00:00"||$date==''){
            $date ='';
        }
        else{
           $date = date('m/d/Y', strtotime($date));
        }
        return $date;
    }
    function convertWithDecimal($number,$decimalplaces){
        if($decimalplaces==0&&is_numeric($number) && ((float) $number != (int) $number)){
            $temp = number_format($number,2,'.',',');
        }
        else{
            $temp = number_format($number,$decimalplaces,'.',',');
        }
        /*if($number!=0){
                $num = number_format($number,$decimalplaces,'.',',');
                $num = trim($num,'0');
                if(substr($num, -1)=='.'){
                    $num = $num.'00';
                }
                else if(substr($num, -2,1)=='.'){
                    $num = $num.'0';
                }

                if(substr($num, 0,1)==='.'){
                    $num = '0'.$num;
                }
            
        }
        else{
            $num = number_format($number,$decimalplaces,'.',',');
        }

        if(strpos($temp, '.')===false){
            $num =  number_format($number,$decimalplaces,'.',',');
        }*/
      
        return $temp;
    }
    
	function getUserName($id){//GET NAME OF USER ID
		$fn='';
		$mn='';
		$ln='';
        $str = '';
		$r = query("select * from user where id='$id'");
		while($obj=fetch($r)){
			$fn=$obj->first_name;
			$mn=$obj->middle_name;
			$ln=$obj->last_name;
		}
		$str = $fn.' '.$mn.' '.$ln;
        $str = strUtfEncode($str);
        return $str;
	}

    function getUserEmail($id){
        $mail = '';
        $r = query("select * from user where id='$id'");
        while($obj=fetch($r)){
           $mail = $obj->email_address;
        }
        return $mail;
    }

    function getUserUsername($id){
        $username = '';
        $r = query("select * from user where id='$id'");
        while($obj=fetch($r)){
           $username = $obj->username;
        }
        return $username;
    }

	function getDeptName($id){
		$r = query("select * from user, section_department where section_department_id=section_department.id and user.id='$id'");
		while($obj=fetch($r)){
			$name = $obj->section_department_name;
			return $name;
		}
	}

    function getCurrencyName($id){
        $r = query("select * from currency where id='$id'");
        while($obj=fetch($r)){
            $name = $obj->currency_name;
            return $name;
        }
    }
    function getCurrencyCode($id){
        $r = query("select * from currency where id='$id'");
        while($obj=fetch($r)){
            $code = $obj->currency_code;
            return $code;
        }
    }
    function getPoCurrency($po){
        $query = "select currency_code from purchase_order_header, currency where purchase_order_header.currency=currency.id and order_number='$po'";
        $rs = query($query);
        $c = '';
        while($obj=fetch($rs)){
            $c = $obj->currency_code;
        }
        return $c;
    }

     function getPoDate($po){
        $query = "select order_date from purchase_order_header where order_number='$po'";
        $rs = query($query);
        $date = '';
        while($obj=fetch($rs)){
            $date = $obj->order_date;
            $date = formatDate($date);
        }
        return $date;
    }

    function getPoAssignedTo($po){
        $query = "select posted_by from purchase_order_header where order_number='$po'";
        $rs = query($query);
        $staff = '';
        while($obj=fetch($rs)){
            $staff = $obj->posted_by;
            $staff = getUserName($staff);
        }
        return $staff;
    }



	function getUserDeptId($id){
		$r = query("select * from user where id='$id'");
		while($obj=fetch($r)){
			$name = $obj->section_department_id;
			return $name;
		}
	}

    function getUserDeptCode($id){
        $r = query("select * from user,section_department where section_department_id=section_department.id and user.id='$id'");
        while($obj=fetch($r)){
            $code = $obj->section_department_code;
            return $code;
        }
    }

    function lineBreak($string, $maxwidth){
        $string = trim($string);
        if(strlen($string)<$maxwidth){
            $substr = $string;
        }
        else{
            $substr = preg_replace("/\s+?(\S+)?$/", '', substr($string, 0, $maxwidth));
        }
        return $substr;
        
    }


    function getNextTransactionNumber($transactiontypeId){
        $query = "select * from transaction_type where id='$transactiontypeId'";
        $rs = query($query);
        while($obj=fetch($rs)){
            $prefix = $obj->prefix;
            $nextnum = $obj->next_number_series;
           // $month = $obj->month_flag;
        }
        if($transactiontypeId=='1'){
            $deptcode = getUserDeptCode(USERID);
            $deptId = getInfo("user","section_department_id","where id='".USERID."'");
            $month = getInfo("section_department","month_flag","where id='$deptId'");
            if($month!=date('m')){
                if($month!='12'){
                    $month++;
                }
                else{
                    $month = 1;
                }
                query("update section_department set month_flag='$month', next_number_series='1' where id='$deptId'");
                $nextnum = 1;
            }//////////////////////////
            else{
                $rs = query("select * from section_department where id='$deptId'");
                while($obj=fetch($rs)){
                    $nextnum = $obj->next_number_series;
                }
            }///////////////////////////////////
            $number = $deptcode.'-'.$prefix.date('ym').'-'.str_pad($nextnum, 3,'0',STR_PAD_LEFT);
        }
        else{
            if(trim($prefix)==''||$prefix==NULL){
                 $number = $prefix.str_pad($nextnum, 5,'0',STR_PAD_LEFT);
            }
            else{
                $number = $prefix.'-'.str_pad($nextnum, 5,'0',STR_PAD_LEFT);
            }
        }
        $number = trim($number);
        return $number;
    }

    function getTransactionNumber($transactiontypeId){
        $query = "select * from transaction_type where id='$transactiontypeId'";
        $rs = query($query);
        while($obj=fetch($rs)){
            $prefix = $obj->prefix;
            $nextnum = $obj->next_number_series;
            //$month = $obj->month_flag;
        }
        if($transactiontypeId=='1'){
            $deptcode = getUserDeptCode(USERID);
            $deptId = getInfo("user","section_department_id","where id='".USERID."'");
            $month = getInfo("section_department","month_flag","where id='$deptId'");
            if($month!=date('m')){
                if($month!='12'){
                    $month++;
                    $month = date('m');
                }
                else{
                    $month = 1;
                }
                query("update section_department set month_flag='$month' where id='$deptId'");
                
                $nextnum = 1;
            }//////////////////////////
            else{
                $rs = query("select * from section_department where id='$deptId'");
                while($obj=fetch($rs)){
                    $nextnum = $obj->next_number_series;
                }
                //query("update section_department set next_number_series=next_number_series + 1 where id='$deptId'");
            }///////////////////////////////////
            $number = $deptcode.'-'.$prefix.date('ym').'-'.str_pad($nextnum, 3,'0',STR_PAD_LEFT);
            query("update section_department set next_number_series=next_number_series + 1 where id='$deptId'");
        }
        else{
            $number = $prefix.'-'.str_pad($nextnum, 5,'0',STR_PAD_LEFT);
        }

        
        $query = "update transaction_type set next_number_series = next_number_series + 1 where id='$transactiontypeId'";
        query($query);
        return $number;
    }

    function getPaymentTermCode($id){
         $query = "select * from payment_terms where id='$id'";
         $rs =query($query);
         $code='';
         while ($obj=fetch($rs)) {
             $code = $obj->description;
         }
         return $code;
    }

    function getDateNeeded($po){
        $rs = query("select * from purchase_order_header where order_number='$po'");
        if(getNumRows($rs)>0){
            $obj = fetch($rs);
            $refpr = $obj->reference_requisition_number;

            $rs2 = query("select * from purchase_requisition_header where requisition_number='$refpr'");
            $obj2 = fetch($rs2);
            $dateneeded = formatDate($obj2->date_needed);
        }
        return $dateneeded;
    }

    function getRefPrNumber($po){
        $pr = 'No PR Reference';
        $rs = query("select * from purchase_order_header where order_number='$po'");
        while($obj=fetch($rs)){
            $pr = $obj->reference_requisition_number;
        }
        return $pr;
    }

	//add as of January 31, 2018 by Rona
    function getAllocation($prnumber){
        $allocation = '';
        $rs = query("SELECT 
					(SELECT section_department_name FROM section_department WHERE section_department.id = purchase_requisition_allocation.department_id LIMIT 0,1) as allocation, percentage 
						FROM `purchase_requisition_allocation` 
						WHERE purchase_requisition_allocation.requisition_number = '".$prnumber."'");
        while($obj=fetch($rs)){
            $allocation .= $obj->allocation.' ('.$obj->percentage.' %)<br>';
        }
        return $allocation;
    }
    function getPaymentTermDays($id){
        $query = "select * from payment_terms where id='$id'";
         $rs =query($query);
         $days='';
         while ($obj=fetch($rs)) {
             $days = $obj->days.' Days';
         }
         return $days;
    }

	function getUnitPrice($id){ //PR PRICES
		$query = "select *
				  from  item_price_schedule_details, item_price_schedule_header 
				  where item_price_schedule_header.transaction_number=item_price_schedule_details.transaction_number and
				  		effectivity_date_start<=curdate() and
				  		effectivity_date_end>=curdate() and
				  		item_id='$id'
				  order by item_price_schedule_header.transaction_number desc
				  limit 1";
		$rs = query($query);
		$rsnum = getNumRows($rs);
		if($rsnum>0){
			$obj=fetch($rs);
			$price = $obj->unit_price;
			return $price;
		}
		else{
			$query = "select * from item where id='$id'";
			$rs = query($query);
			$obj=fetch($rs);
			$defaultprice = $obj->unit_price;
			return $defaultprice;
		}

	}

    function getPoGrandTotal($po){
        $query = "select * from purchase_order_details where order_number='$po'";
        $rs = query($query);
        $grandtotal = 0;
        while ($obj=fetch($rs)) {
            $qty = $obj->quantity;
            $price = $obj->unit_price;
            $amount = $qty*$price;
            //10222021
            $grandtotal = $grandtotal + round($amount,2); // orig data
            // $grandtotal = $grandtotal + $amount; //uncomment this to resolve the .1 issue/concern in total amount
        }
        //10222021
        $grandtotal = convertWithDecimal($grandtotal,2); // orig data
        // $grandtotal = bcdiv($grandtotal, 1, 2); //uncomment this to resolve the .1 issue/concern in total amount
        return $grandtotal;
    }
    function getPrGrandTotal($pr){
        $query = "select * from purchase_requisition_details where requisition_number='$pr'";
        $rs = query($query);
        $grandtotal = 0;
        while ($obj=fetch($rs)) {
            $qty = $obj->quantity;
            $price = $obj->unit_price;
            $amount = $qty*$price;
            $grandtotal = $grandtotal + round($amount,2);
        }
        return convertWithDecimal($grandtotal,2);
    }

    function getSupplierName($supplierId){
        $supplierName = '';
        $query = "select * from supplier where id='$supplierId'";
        $rs = query($query);
        while ($obj=fetch($rs)) {
            $supplierName = $obj->supplier_name;
        }
        return strtoupper($supplierName);
    }


    function getPrStatus($pr){
        $query="select requisition_status from purchase_requisition_header where requisition_number='$pr'";
        while ($obj=fetch(query($query))) {
            return $obj->requisition_status;
        }
    }

    function getSupplierContact($supplierId){
        $rs = query("select * from supplier_contact where supplier_id='$supplierId' order by created_date desc limit 1");
        $contact = '';
        while($obj=fetch($rs)){
            $contact = strUtfEncode($obj->contact_person);
        }
        return $contact;
    }

    function getPoApprovalDate($po,$userid){
        $rs = query("select * from purchase_order_approval_history where order_number='$po' and user_id='$userid' order by date desc limit 1");
        $obj = fetch($rs);
        $date = formatDate($obj->date);
        return $date;

    }
    function getLastPoApprover($po){
        $response = '';
        $rs = query("select * from purchase_order_header where order_number='$po'");
        while($obj=fetch($rs)){
            $asstmng = $obj->first_approver;
            $genmng = $obj->second_approver;
            $pr = $obj->third_approver;
            $flag = $obj->flag;
            $stat = $obj->order_status;

        }
        if($stat!='REJECTED'){
            if($flag=='2'){
                $response = "Asst. Manager: ".getUserName($asstmng);
            }
            else if($flag=='3'||($flag=='4'&&$pr==NULL)){
                $response = "Gen. Manager: ".getUserName($genmng);
            }
            else if($flag=='4'){
                $response = "President: ".getUserName($pr);
            }
        }
        return $response;
    }

    function getLastPoApprover2($po){
        $response = '';
        $rs = query("select * from purchase_order_header_revision where order_number='$po'");
        while($obj=fetch($rs)){
            $asstmng = $obj->first_approver;
            $genmng = $obj->second_approver;
            $pr = $obj->third_approver;
            $flag = $obj->flag;
            $stat = $obj->order_status;

        }
        if($stat!='REJECTED'){
            if($flag=='2'){
                $response = "Asst. Manager: ".getUserName($asstmng);
            }
            else if($flag=='3'||($flag=='4'&&$pr==NULL)){
                $response = "Gen. Manager: ".getUserName($genmng);
            }
            else if($flag=='4'){
                $response = "President: ".getUserName($pr);
            }
        }
        return $response;


    }

    function getPrApprovers($prnumber){
        $data = array();
        $rs = query("select * from purchase_requisition_header where requisition_number='$prnumber'");
        while($obj=fetch($rs)){
            $sh1 = $obj->section_head1;
            $sh1done = $obj->sh_approved;
            if($sh1!=NULL&&$sh1done==1){
                array_push($data, $sh1);
            }

            $sh2 = $obj->section_head2;
            $sh2done = $obj->sh2_approved;
            if($sh2!=NULL&&$sh2done==1){
                array_push($data, $sh2);
            }

            $conf = $obj->conformance;
            $confdone = $obj->conf_approved;
            if($conf!=NULL&&$confdone==1){
                array_push($data, $conf);
            }

            $gm = $obj->general_manager;
            $gmdone = $obj->gm_approved;
            if($gm!=NULL&&$gmdone==1){
                array_push($data, $gm);
            }

            $avp = $obj->assistant_vice_president;
            $avpdone = $obj->avp_approved;
            if($avp!=NULL&&$avpdone==1){
                array_push($data, $avp);
            }

            $svp = $obj->senior_vice_president;
            $svpdone = $obj->svp_approved;
            if($svp!=NULL&&$svpdone==1){
                array_push($data, $svp);
            }

            $pr = $obj->president;
            $prdone = $obj->pr_approved;
            if($pr!=NULL&&$prdone==1){
                array_push($data, $pr);
            }
        }

        return $data;

    }

    function getPoApprovers($ponumber){
        $data = array();
        $rs = query("select * from purchase_order_header where order_number='$ponumber'");
        while($obj=fetch($rs)){
            $flag = $obj->flag;

            $firstapprover = $obj->first_approver;
            if($firstapprover!=NULL&&$flag==2){
                array_push($data, $firstapprover);
            }

            $secondapprover = $obj->second_approver;
            if($secondapprover!=NULL&&$flag==3){
                array_push($data, $secondapprover);
            }

            $thirdapprover = $obj->third_approver;
            if($thirdapprover!=NULL&&$flag==4){
                array_push($data, $thirdapprover);
            }

            
        }

        return $data;

    }

    function getRcvApprovers($rcvnumber){
        $data = array();
        $rs = query("select first_approver,second_approver  from receiving_header where receiving_number='$rcvnumber'");
        while($obj=fetch($rs)){
            $flag = $obj->flag;

            $firstapprover = $obj->first_approver;
            if($firstapprover!=NULL&&$flag==2){
                array_push($data, $firstapprover);
            }

            $secondapprover = $obj->second_approver;
            if($secondapprover!=NULL&&$flag==3){
                array_push($data, $secondapprover);
            }
        }

        return $data;

    }

    function getPrApprovalHistoryTimestamp($pr,$approvertype,$revflag){
        $str = '';
        $rs = query("select * from purchase_requisition_approval_history 
                     where requisition_number='$pr' and
                           pr_approval_flag='$approvertype' and
                           revision_flag='$revflag' order by date desc limit 1");

        
        while($obj=fetch($rs)){
            $action = $obj->action;
            $remarks = strUtfEncode($obj->pr_approval_remarks);
            $date = formatDate4($obj->date);
            if($action=='APPROVED'){
                $action = '<span style="color:green; font-weight:bold">'.$action.'</span>';
            }
            else{
                 $action = '<span style="color:#bd2a2a; font-weight:bold">'.$action.'</span>';
            }
            $str = '<br>' .$action.'<br>'.$date.'<br>'.$remarks;
        }
        return $str;
    }

    function getRcvApprovalHistoryTimestamp($rcvNo,$approvertype,$revflag){
        $str = '';
        $rs = query("select action, rcv_approval_remarks,date from receiving_approval_history 
                     where receiving_number='$rcvNo' and
                           rcv_approval_flag='$approvertype' and
                           revision_flag='$revflag' order by date desc limit 1");

        
        if($obj=fetch($rs)){
            $action = $obj->action;
            $remarks = strUtfEncode($obj->rcv_approval_remarks);
            $date = formatDate4($obj->date);
            if($action=='ACCEPTED'){
                $action = '<span style="color:green; font-weight:bold">'.$action.'</span>';
            }
            else{
                 $action = '<span style="color:#bd2a2a; font-weight:bold">'.$action.'</span>';
            }
            $str = '<br>' .$action.'<br>'.$date.'<br>'.$remarks;
        }
        return $str;
    }

    function getPoApprovalHistoryTimestamp($po,$approvertype,$revflag){
        $str = '';
        $rs = query("select * from purchase_order_approval_history 
                     where order_number='$po' and
                           po_approval_flag='$approvertype' and
                           revision_flag='$revflag' order by date desc limit 1");

        
        while($obj=fetch($rs)){
            $action = $obj->action;
            $remarks = strUtfEncode($obj->po_approval_remarks);
            $date = formatDate4($obj->date);
            if($action=='APPROVED'){
                $action = '<span style="color:green; font-weight:bold">'.$action.'</span>';
            }
            else{
                 $action = '<span style="color:#bd2a2a; font-weight:bold">'.$action.'</span>';
            }
            $str = '<br>' .$action.'<br>'.$date.'<br>'.$remarks;
        }
        return $str;
    }


    function getPrApprovalTimestamp($prnumber,$userid,$prapprovallevel){
        $timestamp = '';
        $remarks = '';
        /*$rs = query("select * 
                            from purchase_requisition_header 
                            where ((section_head1='$userid' and sh_approved='1') or
                                   (section_head2='$userid' and sh2_approved='1') or
                                   (conformance='$userid' and conf_approved='1') or
                                   (general_manager='$userid' and gm_approved='1') or
                                   (assistant_vice_president='$userid' and avp_approved='1') or
                                   (senior_vice_president='$userid' and svp_approved='1') or
                                   (president='$userid' and pr_approved='1')) and 
                                    requisition_number='$prnumber'");*/
        switch ($prapprovallevel) {
            case '0':
                    $rs = query("select * 
                            from purchase_requisition_header 
                            where ((section_head1='$userid' and sh_approved='1') and requisition_number='$prnumber')");
            break;
            case '1':
                    $rs = query("select * 
                            from purchase_requisition_header 
                            where ((section_head2='$userid' and sh2_approved='1') and requisition_number='$prnumber')");
            break;
            case '2':
                    $rs = query("select * 
                            from purchase_requisition_header 
                            where ((conformance='$userid' and conf_approved='1') and requisition_number='$prnumber')");
            break;
            case '3':
                    $rs = query("select * 
                            from purchase_requisition_header 
                            where ((general_manager='$userid' and gm_approved='1') and requisition_number='$prnumber')");
            break;
            case '4':
                    $rs = query("select * 
                            from purchase_requisition_header 
                            where ((assistant_vice_president='$userid' and avp_approved='1') and requisition_number='$prnumber')");
            break;
            case '5':
                    $rs = query("select * 
                            from purchase_requisition_header 
                            where ((senior_vice_president='$userid' and svp_approved='1') and requisition_number='$prnumber')");
            break;
            case '6':
                    $rs = query("select * 
                            from purchase_requisition_header 
                            where ((president='$userid' and pr_approved='1') and requisition_number='$prnumber')");
            break;
            default:
                $rs = query("select * 
                            from purchase_requisition_header 
                            where ((president='abc' and pr_approved='abc') and requisition_number='$prnumber')");
            break;
        }
        if(getNumRows($rs)>0){
            $obj = fetch($rs);
            if($obj->flag>0){
                $rs = query("select * from purchase_requisition_approval_history 
                             where requisition_number='$prnumber' and user_id='$userid' and pr_approval_flag='$prapprovallevel'
                             order by pr_approval_id desc limit 1");
                while($obj=fetch($rs)){
                    $remarks = '<br>'.strUtfEncode($obj->pr_approval_remarks);
                    $timestamp = '<br><span style="color:green; font-weight:bold">APPROVED</span><br>'.formatDate4($obj->date);
                }
            }
        }
        return $timestamp.$remarks;
    }

    function getPrApprovalTimestampWithRev($prnumber,$userid,$prapprovallevel,$revno){
        $timestamp = '';
        $remarks = '';
        /*$rs = query("select * 
                            from purchase_requisition_header 
                            where ((section_head1='$userid' and sh_approved='1') or
                                   (section_head2='$userid' and sh2_approved='1') or
                                   (conformance='$userid' and conf_approved='1') or
                                   (general_manager='$userid' and gm_approved='1') or
                                   (assistant_vice_president='$userid' and avp_approved='1') or
                                   (senior_vice_president='$userid' and svp_approved='1') or
                                   (president='$userid' and pr_approved='1')) and 
                                    requisition_number='$prnumber'");*/
        switch ($prapprovallevel) {
            case '0':
                    $rs = query("select * 
                            from purchase_requisition_header 
                            where ((section_head1='$userid' and sh_approved='1') and requisition_number='$prnumber' and next_revision_series='$revno')");
            break;
            case '1':
                    $rs = query("select * 
                            from purchase_requisition_header 
                            where ((section_head2='$userid' and sh2_approved='1') and requisition_number='$prnumber' and next_revision_series='$revno')");
            break;
            case '2':
                    $rs = query("select * 
                            from purchase_requisition_header 
                            where ((conformance='$userid' and conf_approved='1') and requisition_number='$prnumber' and next_revision_series='$revno')");
            break;
            case '3':
                    $rs = query("select * 
                            from purchase_requisition_header 
                            where ((general_manager='$userid' and gm_approved='1') and requisition_number='$prnumber' and next_revision_series='$revno')");
            break;
            case '4':
                    $rs = query("select * 
                            from purchase_requisition_header 
                            where ((assistant_vice_president='$userid' and avp_approved='1') and requisition_number='$prnumber' and next_revision_series='$revno')");
            break;
            case '5':
                    $rs = query("select * 
                            from purchase_requisition_header 
                            where ((senior_vice_president='$userid' and svp_approved='1') and requisition_number='$prnumber' and next_revision_series='$revno')");
            break;
            case '6':
                    $rs = query("select * 
                            from purchase_requisition_header 
                            where ((president='$userid' and pr_approved='1') and requisition_number='$prnumber' and next_revision_series='$revno')");
            break;
            default:
                $rs = query("select * 
                            from purchase_requisition_header 
                            where ((president='abc' and pr_approved='abc') and requisition_number='$prnumber' and next_revision_series='$revno')");
            break;
        }
        if(getNumRows($rs)>0){
            $obj = fetch($rs);
            if($obj->flag>0){
                $rs = query("select * from purchase_requisition_approval_history 
                             where requisition_number='$prnumber' and user_id='$userid' and pr_approval_flag='$prapprovallevel' and revision_flag ='$revno'
                             order by pr_approval_id desc limit 1");
                while($obj=fetch($rs)){
                    $remarks = '<br>'.strUtfEncode($obj->pr_approval_remarks);
                    $timestamp = '<br><span style="color:green; font-weight:bold">APPROVED</span><br>'.formatDate4($obj->date);
                }
            }
        }
        return $timestamp.$remarks;
    }

    function getPrApprovalTimestamp2($prnumber,$revision,$userid,$prapprovallevel){
        $timestamp = '';
        $remarks = '';
        $pr = $prnumber.'-R'.$revision;
        switch ($prapprovallevel) {
            case '0':
                    $rs = query("select * 
                            from purchase_requisition_header_revision 
                            where ((section_head1='$userid' and sh_approved='1') and requisition_number='$pr')");
            break;
            case '1':
                    $rs = query("select * 
                            from purchase_requisition_header_revision 
                            where ((section_head2='$userid' and sh2_approved='1') and requisition_number='$pr')");
            break;
            case '2':
                    $rs = query("select * 
                            from purchase_requisition_header_revision 
                            where ((conformance='$userid' and conf_approved='1') and requisition_number='$pr')");
            break;
            case '3':
                    $rs = query("select * 
                            from purchase_requisition_header_revision 
                            where ((general_manager='$userid' and gm_approved='1') and requisition_number='$pr')");
            break;
            case '4':
                    $rs = query("select * 
                            from purchase_requisition_header_revision 
                            where ((assistant_vice_president='$userid' and avp_approved='1') and requisition_number='$pr')");
            break;
            case '5':
                    $rs = query("select * 
                            from purchase_requisition_header_revision 
                            where ((senior_vice_president='$userid' and svp_approved='1') and requisition_number='$pr')");
            break;
            case '6':
                    $rs = query("select * 
                            from purchase_requisition_header_revision 
                            where ((president='$userid' and pr_approved='1') and requisition_number='$pr')");
            break;
            default:
                $rs = query("select * 
                            from purchase_requisition_header_revision 
                            where ((president='abc' and pr_approved='abc') and requisition_number='$pr')");
            break;
        }
        if(getNumRows($rs)>0){
            $obj = fetch($rs);
            if($obj->flag>0){
                $rs = query("select * from purchase_requisition_approval_history 
                             where requisition_number='$prnumber' and user_id='$userid' and pr_approval_flag='$prapprovallevel' and revision_flag='$revision'
                             order by pr_approval_id desc limit 1");
                while($obj=fetch($rs)){
                    $remarks = '<br>'.strUtfEncode($obj->pr_approval_remarks);
                    $timestamp = '<br><span style="color:green; font-weight:bold">APPROVED</span><br>'.formatDate4($obj->date);
                }
            }
        }
        return $timestamp.$remarks;
    }

    function getPoApprovalTimestamp($po,$userid,$approvallevel){
        $timestamp = '';
        $remarks = '';
        /*$rs = query("select * 
                     from purchase_order_header
                     where
                           
                             ((first_approver='$userid' and purchase_order_header.flag>1) or 
                             (second_approver='$userid' and purchase_order_header.flag>2) or 
                             (third_approver='$userid' and purchase_order_header.flag>3))
                             and order_number='$po'
                            
                   ");*/
        switch ($approvallevel) {
            case '1':
                    $rs = query("select * 
                                 from purchase_order_header
                                 where ((first_approver='$userid' and purchase_order_header.flag>1) and order_number='$po')");
            break;
            case '2':
                    $rs = query("select * 
                                 from purchase_order_header
                                 where ((second_approver='$userid' and purchase_order_header.flag>2) and order_number='$po')");
            break;
            case '3':
                    $rs = query("select * 
                                 from purchase_order_header
                                 where ((third_approver='$userid' and purchase_order_header.flag>3) and order_number='$po')");
            break;
            default:
                     $rs = query("select * 
                                 from purchase_order_header
                                 where ((third_approver='sdfs' and purchase_order_header.flag>3) and order_number='$po')");
            break;

        }


        if(getNumRows($rs)>0){
                $rs = query("select * from purchase_order_approval_history 
                            where order_number='$po' and user_id='$userid' and po_approval_flag='$approvallevel'
                            order by po_approval_id desc limit 1");
                while($obj=fetch($rs)){
                    $remarks = '<br>'.strUtfEncode($obj->po_approval_remarks);
                    $timestamp = '<br><span style="color:green; font-weight:bold">APPROVED</span><br>'.formatDate4($obj->date);
                }
        }
        return $timestamp.' '.$remarks;
    }

    function getLastPrApprover($pr,$revno){
		$query = "select user_id,pr_approval_flag from purchase_requisition_approval_history where requisition_number='$pr' and revision_flag='$revno' order by pr_approval_id desc";
		$rs = query($query);
		if($obj=fetch($rs)) {
			$user_id = $obj->user_id;
			$pr_approval_flag = $obj->pr_approval_flag;
			switch ($pr_approval_flag) {
				case 0:
					return 'Section Head (1): '.getUserName($user_id);
					break;
				case 1:
					return 'Section Head (2): '.getUserName($user_id);
					break;
				case 2:
					return 'Conformance: '.getUserName($user_id);
					break;
				case 3:
					return 'General Manager: '.getUserName($user_id);
					break;
				case 4:
					return 'Assistant VP: '.getUserName($user_id);
					break;

				case 5:
					return 'Senior VP: '.getUserName($user_id);
					break;
				case 6:
					return 'President: '.getUserName($user_id);
					break;
			}
		}
        // $approvers = array();
		// if(isset($revno)) {
			// $query = "select * from purchase_requisition_header where requisition_number='$pr' and next_revision_series='$revno'";
		// } else {
			// $query = "select * from purchase_requisition_header where requisition_number='$pr'";
		// }
        // $rs = query($query);
        // if(getNumRows($rs)>0){
            // while($obj=fetch($rs)){
                // $sh1 = $obj->sh_approved;
                // $sh2 = $obj->sh2_approved;
                // $conf = $obj->conf_approved;
                // $gm = $obj->gm_approved;
                // $avp = $obj->avp_approved;
                // $svp = $obj->svp_approved;
                // $pr=$obj->pr_approved;
                // $stat = $obj->requisition_status;

                // $shappr1 = $obj->section_head1;
                // $shappr2 = $obj->section_head2;
                // $confappr=$obj->conformance;
                // $gmappr=$obj->general_manager;
                // $avpappr = $obj->assistant_vice_president;
                // $svpappr = $obj->senior_vice_president;
                // $prappr = $obj->president;
                // array_push($approvers,$sh1,$sh2,$conf,$gm,$avp,$svp,$pr);
                // $flag = $obj->flag;
             
                
            // }
            // if($sh1!=NULL){
                // $x =array_pop(array_keys($approvers,1));
                // if($stat=='APPROVED'||$stat=='IN REVIEW'||$stat=='CANCELLED'){
                    // switch ($x) {
                        // case 0:
                            // return 'Section Head (1): '.getUserName($shappr1);
                            // break;
                        // case 1:
                            // return 'Section Head (2): '.getUserName($shappr2);
                            // break;
                        // case 2:
                            // return 'Conformance: '.getUserName($confappr);
                            // break;
                        // case 3:
                            // return 'General Manager: '.getUserName($gmappr);
                            // break;
                        // case 4:
                            // return 'Assistant VP: '.getUserName($avpappr);
                            // break;

                        // case 5:
                            // return 'Senior VP: '.getUserName($svpappr);
                            // break;
                        // case 6:
                            // return 'President: '.getUserName($prappr);
                            // break;
                    // }
                // }
            // }
        // }   
    }

    function getNextApprover($pr){
        $rs = query("select * from purchase_requisition_header where requisition_number='$pr'");
        if(getNumRows($rs)>0){
             while($obj=fetch($rs)){
                $sh1 = $obj->sh_approved;
                $sh2 = $obj->sh2_approved;
                $conf = $obj->conf_approved;
                $gm = $obj->gm_approved;
                $avp = $obj->avp_approved;
                $svp = $obj->svp_approved;
                $pr=$obj->pr_approved;
                $stat = $obj->requisition_status;

                $shappr1 = $obj->section_head1;
                $shappr2 = $obj->section_head2;
                $confappr=$obj->conformance;
                $gmappr=$obj->general_manager;
                $avpappr = $obj->assistant_vice_president;
                $svpappr = $obj->senior_vice_president;
                $prappr = $obj->president;
                $flag = $obj->flag;
                if($flag==0){
                    return $shappr1;
                }
                else if($flag==1){
                    return $shappr2;
                }
                else if($flag==2){
                    return $confappr;
                }
                else if($flag==3){
                    return $gmappr;
                }
                else if($flag==4){
                    return $avpappr;
                }
                else if($flag==5){
                    return $svpappr;
                }
                else if($flag==6){
                    return $prappr;
                }
            }
        }

    }
    function getLastPrApprover2($pr){
        $approvers = array();
        $query = "select * from purchase_requisition_header_revision where requisition_number='$pr'";
        $rs = query($query);
        if(getNumRows($rs)>0){
            while($obj=fetch($rs)){
                $sh1 = $obj->sh_approved;
                $sh2 = $obj->sh2_approved;
                $conf = $obj->conf_approved;
                $gm = $obj->gm_approved;
                $avp = $obj->avp_approved;
                $svp = $obj->svp_approved;
                $pr=$obj->pr_approved;
                $stat = $obj->requisition_status;

                $shappr1 = $obj->section_head1;
                $shappr2 = $obj->section_head2;
                $confappr=$obj->conformance;
                $gmappr=$obj->general_manager;
                $avpappr = $obj->assistant_vice_president;
                $svpappr = $obj->senior_vice_president;
                $prappr = $obj->president;
                array_push($approvers,$sh1,$sh2,$conf,$gm,$avp,$svp,$pr);
                $flag = $obj->flag;
             
                
            }
            if($sh1!=NULL){
                $x =array_pop(array_keys($approvers,1));
                if($stat=='APPROVED'||$stat=='IN REVIEW'){
                    switch ($x) {
                        case 0:
                            return 'Section Head (1): '.getUserName($shappr1);
                            break;
                        case 1:
                            return 'Section Head (2): '.getUserName($shappr2);
                            break;
                        case 2:
                            return 'Conformance: '.getUserName($confappr);
                            break;
                        case 3:
                            return 'General Manager: '.getUserName($gmappr);
                            break;
                        case 4:
                            return 'Assistant VP: '.getUserName($avpappr);
                            break;

                        case 5:
                            return 'Senior VP: '.getUserName($svpappr);
                            break;
                        case 6:
                            return 'President: '.getUserName($prappr);
                            break;
                    }
                }
            }
        }   
    }

    function getPoNumber($pr,$itemid){//get po number of item in approved pr
        $query = "select * from purchase_order_header, purchase_order_details 
                  where purchase_order_header.order_number=purchase_order_details.order_number and
                        reference_requisition_number='$pr' and
                        item_id='$itemid'";
        while($obj=fetch(query($query))){
            return $obj->order_number;
        }
    }

    function getPoStatus($po){
        $rs = query("select * from purchase_order_header where order_number='$po'");
        $stat ='';
        while($obj=fetch($rs)){
            $stat = $obj->order_status;
        }
        return $stat;
    }

    function updatePoStatus($ponumber,$status){
        $query = "update purchase_order_header set order_status='$status', status_before_revision='$status' where order_number='$ponumber'";
        $rs = query($query);
        if(!$rs){
            echo error();
        }
    }

    function getQtyReceived_2($ponumber,$itemId,$price,$shortdesc,$longdesc){
        $qtyreceived = 0;

        // $query = "select item_id, sum(quantity_received) as qtyreceived
                  // from receiving_header
                  // left join receiving_details
                  // on receiving_header.receiving_number = receiving_details.receiving_number 
                  // where 
						// reference_po_number='$ponumber' and item_name = '".escapeString($shortdesc)."' and long_description = '".escapeString($longdesc)."' and unit_price = '".$price."'
                  // group by reference_po_number, item_name, long_description, unit_price";
				  
		
        $query = "select item_id, sum(quantity_received) as qtyreceived
                  from receiving_header
                  left join receiving_details
                  on receiving_header.receiving_number = receiving_details.receiving_number 
                  where 
						reference_po_number='$ponumber' and item_id = '".$itemId."'
                  group by reference_po_number, item_id";
				  
		$rs = query($query);
        while($obj=fetch($rs)){
            $qtyreceived = $obj->qtyreceived;
        }
        return $qtyreceived;
    }

    function getQtyReceived_3($ponumber,$itemId,$price,$shortdesc,$longdesc,$ctrx){
        $qtyreceived = 0;

        $query = "select sum(quantity_received) as qtyreceived
                  from receiving_header
                  left join receiving_details
                  on receiving_header.receiving_number = receiving_details.receiving_number 
                  where 
                        reference_po_number='$ponumber' and item_id = '".$itemId."'
                  group by item_id order by qtyreceived";

        
        $rs = query($query);
        if($obj=fetch($rs)){
            $qtyreceived = $obj->qtyreceived; 
        }
        return $qtyreceived;
    }

    function getQtyReceivedByPR($prnumber,$itemId,$price){
        $rs = query("select (SELECT order_number FROM purchase_order_details WHERE purchase_order_header.order_number = purchase_order_details.order_number AND purchase_order_details.item_id = '$itemId' and unit_price='$price' LIMIT 0,1) as ponumber from purchase_order_header where reference_requisition_number='$prnumber' and discrepancy_flag like '%PRICE VARIANCE%'");
        $variance = 0;
        if(getNumRows($rs)>0){
            $variance = 1;
        }

        if($variance>0){
            $query = "select item_id, sum(quantity_received) as qtyreceived
                      from receiving_header, receiving_details 
                      where receiving_header.receiving_number=receiving_details.receiving_number and
                            reference_po_number='$ponumber' and 
                            item_id='$itemId'
                      group by item_id";
            $rs = query($query);
            $qtyreceived = 0;
            while($obj=fetch($rs)){
                $qtyreceived = $obj->qtyreceived;
            }
            return $qtyreceived;

        }
        else{
            $query = "select item_id, sum(quantity_received) as qtyreceived
                      from receiving_header, receiving_details 
                      where receiving_header.receiving_number=receiving_details.receiving_number and
                            reference_po_number='$ponumber' and 
                            item_id='$itemId' and unit_price='$price'
                      group by item_id";
            $rs = query($query);
            $qtyreceived = 0;
            while($obj=fetch($rs)){
                $qtyreceived = $obj->qtyreceived;
            }
            return $qtyreceived;
        }
    }

    function getModeOfShipment($modeOfShipmentID){
        $query = "select * from mode_of_shipment where id='$modeOfShipmentID'";
        $rs = query($query);
        $desc='';
        while($obj=fetch($rs)){
            $desc=$obj->description;
        }
        return $desc;
    }

    function getPaymentTerms($termsID){
        $query = "select * from payment_terms where id='$termsID'";
        $rs = query($query);
        $desc='';
        while($obj=fetch($rs)){
            $desc=$obj->description;
        }
        return $desc;
    }

    function getRevisionSources($number,$columnname){
        $sourcescount = 0;
        $sourcescount = getNumRows(query("select * from purchase_requisition_revision_sources where $columnname='$number'"));
        return $sourcescount;

    }
    function checkIfDiscrepancyExist($po){
        $rs = query("select * from purchase_order_variance_details where order_number='$po' and flag='1'");
        $exist = false;
        if(getNumRows($rs)>0){
            $exist = true;
        }
        return $exist;
    }
     function checkIfDiscrepancyExistPR($pr){
        $rs = query("select * from purchase_order_variance_details, purchase_requisition_revision_sources 
                     where purchase_requisition_revision_sources.order_number=purchase_order_variance_details.order_number and 
                           purchase_requisition_revision_sources.requisition_number='$pr' and
                           flag='1'");
        $exist = false;
        if(getNumRows($rs)>0){
            $exist = true;
        }
        return $exist;
    }

    function getNumOfItemsReceived($rcvnum){
        $query = "select count(receiving_number) as rcvnum
                  from receiving_details 
                  where receiving_number='$rcvnum'
                  group by receiving_number";
        $rs = query($query);
        $countf = 0;
        while($obj=fetch($rs)){
            $countf = $obj->rcvnum;
        }

        $counts = 0;
        $query = "select count(receiving_number) as rcvnum
                  from receiving_details_non_catalog 
                  where receiving_number='$rcvnum'
                  group by receiving_number";
        $rs = query($query);
        while($obj=fetch($rs)){
            $counts = $obj->rcvnum;
        }
        $newcount = $counts+$countf;
        return $newcount;
    }

    function getPrDescription($prnumber){
        $desc = array();
        $query = "select item_name from purchase_requisition_details, item where item.id=item_id and requisition_number='$prnumber'";
        $rs = query($query);
        $item='';
        while($obj=fetch($rs)){
            $item = $obj->item_name.'...';
        }
        return $item;
    }

    function checkIfForRevision($prnumber){
         $query = "select * from purchase_requisition_revision_sources where requisition_number='$prnumber'";
         $rs=query($query);
         $rev = NULL;

         while($obj=fetch($rs)){
            $rev = 'NOT NULL';
         }
         return $rev;
    }

    function setRevisionStatOfPR($prnumber){
        $query = "delete from purchase_requisition_revision_sources where requisition_number='$prnumber'";
         $rs=query($query);
         if($rs){
            return true;
         }
         else{
            return false;
         }
    }

    function getStatusRemarks($tablename,$condition,$orderby,$retrievefieldname){
        $remarks='';
        $query = "select * from $tablename where $condition order by $orderby desc limit 1";
        $result = query($query);
        while($obj=fetch($result)){
            $remarks = $obj->$retrievefieldname;

        }   
        return $remarks;    
    }

    function rejectedBy($tablename,$condition,$orderby,$retrievefieldname){
        $by='';
        $query = "select * from $tablename where $condition order by $orderby desc limit 1";
        $result = query($query);
        while($obj=fetch($result)){
            $by = getUserName($obj->user_id);
        }   
        return $by;    
    }

    function checkIfPoExist($ponumber){
        $query = "select * from purchase_order_header where order_number='$ponumber'";
        $rs = query($query);
        if(getNumRows($rs)==1){
            return true;
        }
        else{
            return false;
        }
    }
    //////////////////////////////////////// PURCHASE ORDER CONFIGURATION ////////////////////////////////////////////////////
    function getPoConfigData($fielddata){
        $retrieveddata='';
        $query = "select * from purchase_order_configuration where id='1'";
        $rs = query($query);
        while($obj=fetch($rs)){
            $retrieveddata=$obj->$fielddata;
            if($retrieveddata==NULL){
                $retrieveddata='NULL';
            }
        }
        return $retrieveddata;
    }




	//////////////////////////////////////// FUNCTION FOR PR APPROVAL/////////////////////////////////////////////////////////////
	function approvePr($prnumber, $userid, $emailClass){
        $queryrs = query("select * from purchase_requisition_header where requisition_number='$prnumber'");
        while($obj=fetch($queryrs)){
            $sh2 = $obj->section_head2;
            $conf = $obj->conformance;
            $gm = $obj->general_manager;
            $avp = $obj->assistant_vice_president;
            $svp = $obj->senior_vice_president;
            $pr = $obj->president;
        }
        $now = date('Y-m-d H:i:s');

        $query = "select * from purchase_requisition_header where requisition_number='$prnumber' and section_head1='$userid' and sh_approved is null and flag='0' and requisition_status='IN REVIEW'";
        $rs = query($query);
        $count = getNumRows($rs);
        if($count==1){
            $nextflag = (($sh2!=NULL&&$sh2!='1')?'1':(($conf!=NULL&&$conf!='1')?'2':(($gm!=NULL&&$gm!='1')?'3':(($avp!=NULL&&$avp!='1')?'4':(($svp!=NULL&&$svp!='1')?'5':(($pr!=NULL&&$pr!='1')?'6':'7'))))));
            $prstat = ($nextflag=='7')?'APPROVED':'IN REVIEW';
            $nextapprover = (($nextflag=='1')?$sh2:(($nextflag=='2')?$conf:(($nextflag=='3')?$gm:(($nextflag=='4')?$avp:(($nextflag=='5')?$svp:(($nextflag=='6')?$pr:''))))));
            $query = "update purchase_requisition_header set sh_approved='1', flag='$nextflag', last_made_changes='$now', requisition_status='$prstat' where requisition_number='$prnumber'";
            $r = query($query);
            
            if(!$r){
                echo error();
            }
            else{
                $stat = getPrStatus($prnumber);
            }

            $response = $stat.'@#$'.$nextflag.'@#$'.$prnumber.'@#$'.getUserEmail($nextapprover).'@#$'.getUserName($nextapprover).'@#$'.$userid;
            return $response;
        }

        $query = "select * from purchase_requisition_header where requisition_number='$prnumber' and section_head2='$userid' and sh2_approved is null and flag='1' and requisition_status='IN REVIEW'";
        $rs = query($query);
        $count = getNumRows($rs);
        if($count==1){
            $nextflag = (($conf!=NULL&&$conf!='1')?'2':(($gm!=NULL&&$gm!='1')?'3':(($avp!=NULL&&$avp!='1')?'4':(($svp!=NULL&&$svp!='1')?'5':(($pr!=NULL&&$pr!='1')?'6':'7')))));
            $prstat = ($nextflag=='7')?'APPROVED':'IN REVIEW';
            $nextapprover = (($nextflag=='1')?$sh2:(($nextflag=='2')?$conf:(($nextflag=='3')?$gm:(($nextflag=='4')?$avp:(($nextflag=='5')?$svp:(($nextflag=='6')?$pr:''))))));
            $query = "update purchase_requisition_header set sh2_approved='1', flag='$nextflag', last_made_changes='$now', requisition_status='$prstat' where requisition_number='$prnumber'";
            $r = query($query);
            
            if(!$r){
                echo error();
            }
            else{
                $stat = getPrStatus($prnumber);
            }

            $response = $stat.'@#$'.$nextflag.'@#$'.$prnumber.'@#$'.getUserEmail($nextapprover).'@#$'.getUserName($nextapprover);
            return $response;
        }

        $query = "select * from purchase_requisition_header where requisition_number='$prnumber' and conformance='$userid' and conf_approved is null and flag='2' and requisition_status='IN REVIEW'";
        $rs = query($query);
        $count = getNumRows($rs);
        if($count==1){
            $nextflag =(($gm!=NULL&&$gm!='1')?'3':(($avp!=NULL&&$avp!='1')?'4':(($svp!=NULL&&$svp!='1')?'5':(($pr!=NULL&&$pr!='1')?'6':'7'))));
            $prstat = ($nextflag=='7')?'APPROVED':'IN REVIEW';
            $nextapprover = (($nextflag=='1')?$sh2:(($nextflag=='2')?$conf:(($nextflag=='3')?$gm:(($nextflag=='4')?$avp:(($nextflag=='5')?$svp:(($nextflag=='6')?$pr:''))))));
            $query = "update purchase_requisition_header set conf_approved='1', flag='$nextflag', last_made_changes='$now', requisition_status='$prstat' where requisition_number='$prnumber'";
            $r = query($query);
            
            if(!$r){
                echo error();
            }
           else{
                $stat = getPrStatus($prnumber);
            }

            $response = $stat.'@#$'.$nextflag.'@#$'.$prnumber.'@#$'.getUserEmail($nextapprover).'@#$'.getUserName($nextapprover);
            return $response;

        }

    	

    	$query = "select * from purchase_requisition_header where requisition_number='$prnumber' and general_manager='$userid' and gm_approved is null and flag='3' and requisition_status='IN REVIEW'";
    	$rs = query($query);
    	$count = getNumRows($rs);
    	if($count==1){
    		$nextflag = (($avp!=NULL&&$avp!='1')?'4':(($svp!=NULL&&$svp!='1')?'5':(($pr!=NULL&&$pr!='1')?'6':'7')));
            $prstat = ($nextflag=='7')?'APPROVED':'IN REVIEW';
            $nextapprover = (($nextflag=='1')?$sh2:(($nextflag=='2')?$conf:(($nextflag=='3')?$gm:(($nextflag=='4')?$avp:(($nextflag=='5')?$svp:(($nextflag=='6')?$pr:''))))));
            $query = "update purchase_requisition_header set gm_approved='1', flag='$nextflag', last_made_changes='$now', requisition_status='$prstat' where requisition_number='$prnumber'";
            $r = query($query);
            
            if(!$r){
                echo error();
            }
            else{
                $stat = getPrStatus($prnumber);
            }

            $response = $stat.'@#$'.$nextflag.'@#$'.$prnumber.'@#$'.getUserEmail($nextapprover).'@#$'.getUserName($nextapprover);
            return $response;
    	}
    	$query = "select * from purchase_requisition_header where requisition_number='$prnumber' and assistant_vice_president='$userid' and avp_approved is null and flag='4' and requisition_status='IN REVIEW'";
    	$rs = query($query);
    	$count = getNumRows($rs);
    	if($count==1){
    		$nextflag = (($svp!=NULL&&$svp!='1')?'5':(($pr!=NULL&&$pr!='1')?'6':'7'));
            $prstat = ($nextflag=='7')?'APPROVED':'IN REVIEW';
             $nextapprover = (($nextflag=='1')?$sh2:(($nextflag=='2')?$conf:(($nextflag=='3')?$gm:(($nextflag=='4')?$avp:(($nextflag=='5')?$svp:(($nextflag=='6')?$pr:''))))));
            $query = "update purchase_requisition_header set avp_approved='1', flag='$nextflag', last_made_changes='$now', requisition_status='$prstat' where requisition_number='$prnumber'";
            $r = query($query);
            
            if(!$r){
                echo error();
            }
           else{
                $stat = getPrStatus($prnumber);
            }

            $response = $stat.'@#$'.$nextflag.'@#$'.$prnumber.'@#$'.getUserEmail($nextapprover).'@#$'.getUserName($nextapprover);
            return $response;
    	}

    	$query = "select * from purchase_requisition_header where requisition_number='$prnumber' and senior_vice_president='$userid' and svp_approved is null and flag='5' and requisition_status='IN REVIEW'";
    	$rs = query($query);
    	$count = getNumRows($rs);
    	if($count==1){
    		$nextflag = ($pr!=NULL&&$pr!='1')?'6':'7';
            $prstat = ($nextflag=='7')?'APPROVED':'IN REVIEW';
             $nextapprover = (($nextflag=='1')?$sh2:(($nextflag=='2')?$conf:(($nextflag=='3')?$gm:(($nextflag=='4')?$avp:(($nextflag=='5')?$svp:(($nextflag=='6')?$pr:''))))));
            $query = "update purchase_requisition_header set svp_approved='1', flag='$nextflag', last_made_changes='$now', requisition_status='$prstat' where requisition_number='$prnumber'";
            $r = query($query);
            
            if(!$r){
                echo error();
            }
            else{
                $stat = getPrStatus($prnumber);
            }

            $response = $stat.'@#$'.$nextflag.'@#$'.$prnumber.'@#$'.getUserEmail($nextapprover).'@#$'.getUserName($nextapprover);
            return $response;
    	}


    	$query = "select * from purchase_requisition_header where requisition_number='$prnumber' and president='$userid' and pr_approved is null and flag='6' and requisition_status='IN REVIEW'";
    	$rs = query($query);
    	$count = getNumRows($rs);
    	if($count==1){
    		$nextflag ='7';
            $prstat = ($nextflag=='7')?'APPROVED':'IN REVIEW';
             $nextapprover = (($nextflag=='1')?$sh2:(($nextflag=='2')?$conf:(($nextflag=='3')?$gm:(($nextflag=='4')?$avp:(($nextflag=='5')?$svp:(($nextflag=='6')?$pr:''))))));
            $query = "update purchase_requisition_header set pr_approved='1', flag='$nextflag', last_made_changes='$now', requisition_status='$prstat' where requisition_number='$prnumber'";
            $r = query($query);
            
            if(!$r){
                echo error();
            }
            else{
                $stat = getPrStatus($prnumber);
            }

            $response = $stat.'@#$'.$nextflag.'@#$'.$prnumber.'@#$'.getUserEmail($nextapprover).'@#$'.getUserName($nextapprover);
            return $response;
    	}
    }


    function rejectPr($prnumber){
        $query = "select * from purchase_requisition_header where requisition_number='$prnumber' and conformance is null";
        $rs = mysql_query($query);
        $rscount = mysql_num_rows($rs);
        if($rscount=='1'){
            $query = "update purchase_requisition_header set requisition_status='REJECTED', flag=0, sh_approved=NULL, sh2_approved=NULL, conf_approved=NULL, gm_approved=NULL, avp_approved=NULL, svp_approved=NULL, pr_approved=NULL where requisition_number='$prnumber'";
            $rs = mysql_query($query);
            if(!$rs){
                return mysql_error();
            }
            else{
                return "ok";
            }

        }
        else{
            $query = "update purchase_requisition_header set requisition_status='REJECTED', flag=0, sh_approved=NULL, conf_approved=NULL, gm_approved=NULL, avp_approved=NULL, svp_approved=NULL, pr_approved=NULL where requisition_number='$prnumber'";
            $rs = mysql_query($query);
            if(!$rs){
                return mysql_error();
            }
            else{
                return "ok";
            }
        }
    }
    /////////////////////////////////////// FUNCTION FOR PO APPROVAL ///////////////////////////////////////////////////////////
    function approvePo($ponumber, $userid,$emailClass){
        $date = date('Y-m-d H:i:s');
        $query = "select * from purchase_order_header where order_number='$ponumber' and flag='1' and first_approver='$userid' and order_status='IN REVIEW'";
        $rs = query($query);
        $count = getNumRows($rs);
        if($count==1){
            $obj = fetch($rs);
            $nextapprover = $obj->second_approver;

             /** GET CC **/
            $ccprchstaff = getUserEmail($obj->posted_by);
            $ccprreq = getUserEmail(getInfo("purchase_requisition_header","requisitioner","where requisition_number='".$obj->reference_requisition_number."'"));
            $cc = array();
            array_push($cc, $ccprchstaff, $ccprreq);
            /** GET CC **/


            $nextflag = '2';
            $query = "update purchase_order_header set flag='$nextflag', date_flag='$date' where order_number='$ponumber'";
            $r = query($query);
            if(!$r){
                echo error();
            }
            else{
                $emailsuccess = $emailClass->sendNotification(getUserEmail($nextapprover),getUserName($nextapprover),'EPRPO - Purchase Order Approval',poMessage($ponumber),poMessage($ponumber),$cc);
            }
            return $r;
        }

        $query = "select * from purchase_order_header where order_number='$ponumber' and flag='2' and second_approver='$userid' and order_status='IN REVIEW'";
        $rs = query($query);
        $count = getNumRows($rs);
        if($count==1){
            $obj = fetch($rs);
            /** GET CC **/
            $ccprchstaff = getUserEmail($obj->posted_by);
            $ccprreq = getUserEmail(getInfo("purchase_requisition_header","requisitioner","where requisition_number='".$obj->reference_requisition_number."'"));
            $ccprchhead = getUserEmail(getInfo("purchase_order_configuration","purchasing_head","where id='1'"));
            $cc1 = array();
            array_push($cc1, $ccprchhead, $ccprreq);
            $cc = array();
            array_push($cc, $ccprchstaff, $ccprreq);
            /** GET CC **/

            $nextapproverexist = $obj->third_approver;
            $statbeforerevision = ($obj->status_before_revision!='PARTIAL DELIVERY'&&$obj->status_before_revision!='SERVED')?'UNSERVED':$obj->status_before_revision;
            if($nextapproverexist==NULL){
                $nextflag='4';
                $revised = getNumRows(query("select * from purchase_requisition_revision_sources where order_number='$ponumber'"));
                if($revised>0){
                    $withorderdate = '';
                    $c = query("select * from purchase_order_header where order_number='$ponumber' and order_date!=NULL");
                    if(getNumRows($c)>0){
                     $query = "update purchase_order_header set discrepancy_flag=NULL, flag='$nextflag', order_status='$statbeforerevision', date_flag='$date', order_date='$date' where order_number='$ponumber'";
                    }
                    else{
                     $query = "update purchase_order_header set discrepancy_flag=NULL, flag='$nextflag', order_status='$statbeforerevision', date_flag='$date', order_date='$date' where order_number='$ponumber'";
                     //$query = "update purchase_order_header set discrepancy_flag=NULL, flag='$nextflag', order_status='$statbeforerevision', date_flag='$date' where order_number='$ponumber'";
                    }
                     query("delete from purchase_requisition_revision_sources where order_number='$ponumber'");
                     query("update purchase_order_variance_details set flag='0' where order_number='$ponumber'");
                }
                else{
                     $query = "update purchase_order_header set flag='$nextflag', order_status='$statbeforerevision', order_date='$date', date_flag='$date' where order_number='$ponumber'";
                    //$query = "update purchase_order_header set flag='$nextflag', order_status='$statbeforerevision', date_flag='$date' where order_number='$ponumber'";
                }
            }
            else{
                $nextflag = '3';
                $query = "update purchase_order_header set flag='$nextflag', date_flag='$date'  where order_number='$ponumber'";
            }
            $r = query($query);
            if(!$r){
                echo error();
            }
            else{
                if($nextflag!='4'){
                    $emailsuccess = $emailClass->sendNotification(getUserEmail($nextapproverexist),getUserName($nextapproverexist),'EPRPO - Purchase Order Approval',poMessage($ponumber),poMessage($ponumber),$cc);
                }
                else{
                     $emailsuccess = $emailClass->sendNotification(getUserEmail($obj->posted_by),getUserName($obj->posted_by),'EPRPO - Purchase Order Approval',poApprovedMessage($ponumber),poApprovedMessage($ponumber),$cc1);
                }
            }
            return $r;
        }

        $query = "select * from purchase_order_header where order_number='$ponumber' and flag='3' and third_approver='$userid' and order_status='IN REVIEW'";
        $rs = query($query);
        $count = getNumRows($rs);
        if($count==1){
            $obj = fetch($rs);
            /** GET CC **/
            $ccprchstaff = getUserEmail($obj->posted_by);
            $ccprreq = getUserEmail(getInfo("purchase_requisition_header","requisitioner","where requisition_number='".$obj->reference_requisition_number."'"));
            $ccprchhead = getUserEmail(getInfo("purchase_order_configuration","purchasing_head","where id='1'"));
            $cc1 = array();
            array_push($cc1, $ccprchhead, $ccprreq);
            /** GET CC **/
            $statbeforerevision = ($obj->status_before_revision!='PARTIAL DELIVERY'&&$obj->status_before_revision!='SERVED')?'UNSERVED':$obj->status_before_revision;
                $nextflag = '4';
                $revised = getNumRows(query("select * from purchase_requisition_revision_sources where order_number='$ponumber'"));
                if($revised>0){
                     $query = "update purchase_order_header set discrepancy_flag=NULL, flag='$nextflag',order_status='$statbeforerevision', date_flag='$date', order_date='$date' where order_number='$ponumber'";
                     query("delete from purchase_requisition_revision_sources where order_number='$ponumber'");
                     query("update purchase_order_variance_details set flag='0' where order_number='$ponumber'");
                }
                else{
                     $query = "update purchase_order_header set flag='$nextflag',order_status='$statbeforerevision', order_date='$date', date_flag='$date' where order_number='$ponumber'";
                     //$query = "update purchase_order_header set flag='$nextflag',order_status='$statbeforerevision', date_flag='$date' where order_number='$ponumber'";
                }
                $emailsuccess = $emailClass->sendNotification(getUserEmail($obj->posted_by),getUserName($obj->posted_by),'EPRPO - Purchase Order Approval',poApprovedMessage($ponumber),poApprovedMessage($ponumber),$cc1);

            //
            $r = query($query);
            if(!$r){
                echo error();
            }
            return $r;
        }

    }


    /////////////////////////////////////// FUNCTION FOR RCV APPROVAL ///////////////////////////////////////////////////////////
    function approveReceiving($rcvnumber, $userid,$emailClass){
        $date = date('Y-m-d H:i:s');
        $query = "select * from receiving_header where receiving_number='$rcvnumber' and flag='1' and first_approver='$userid' and receiving_status='IN REVIEW'";
        $rs = query($query);
        $count = getNumRows($rs);
        if($count==1){
            $obj = fetch($rs);
            $nextapprover = $obj->second_approver;

             /** GET CC **/
            $ccreceived_by = getUserEmail($obj->received_by);
            $cc = array();
            array_push($cc, $ccreceived_by);
            /** GET CC **/

            $nextflag = '2';
            $query = "update receiving_header set flag='$nextflag', date_flag='$date' where receiving_number='$rcvnumber'";
            $r = query($query);
            if(!$r){
                echo error();
            }
            else{
                $emailsuccess = $emailClass->sendNotification(getUserEmail($nextapprover),getUserName($nextapprover),'EPRPO - Receiving  Approval',rcvMessage($rcvnumber),rcvMessage($rcvnumber),$cc);
            }
            return $r;
        }

        $query = "select * from receiving_header where receiving_number='$rcvnumber' and flag='2' and second_approver='$userid' and receiving_status='IN REVIEW'";
        $rs = query($query);
        $count = getNumRows($rs);
        if($count==1){
            $obj = fetch($rs);
            /** GET CC **/
			$received_by = $obj->received_by;
            $ccreceived_by = getUserEmail($obj->received_by);
            $cc = array();
            array_push($cc, $ccreceived_by);
            /** GET CC **/

            $nextflag = '3';
			$receiving_status = 'ACCEPTED';
			$query = "update receiving_header set flag='$nextflag', receiving_status='$receiving_status', date_flag='$date' where receiving_number='$rcvnumber'";
            $r = query($query);
            if(!$r){
                echo error();
            }
            else{				
				$query = "SELECT * FROM `receiving_details` WHERE `quantity_received`=0 AND `qty_received_for_approval` > 0 and receiving_number = '$rcvnumber'";
				$rs2 = query($query);
				while($obj2=fetch($rs2)){
					$quantity_received = $obj2->qty_received_for_approval;
					$item_id = $obj2->item_id;
					$r = query("update receiving_details set quantity_received='$quantity_received' where receiving_number='$rcvnumber' and item_id='$item_id'");
				}
				
				//Update PO status
				$isPOServed = false;
				$rs3 = query("SELECT `item_id`, `item_name`, `long_description`, `quantity`, `unit_price` FROM `purchase_order_details` WHERE order_number = '".$obj->reference_po_number."'");
				while($obj3=fetch($rs3)) {
					$poQty = $obj3->quantity;
					$rcvQty = getQtyReceived_2($obj->reference_po_number,$obj3->item_id,$obj3->unit_price,$obj3->item_name,$obj3->long_description);
					
					if($poQty == $rcvQty) {
						$isPOServed = true;
					}
				}
				
				if($isPOServed) {
					updatePoStatus($obj->reference_po_number,'SERVED');
				} else {
					updatePoStatus($obj->reference_po_number,'PARTIAL DELIVERY');
				}
								
                $emailsuccess = $emailClass->sendNotification(getUserEmail($received_by),getUserName($received_by),'EPRPO - Receiving  Approval',rcvMessage($rcvnumber),rcvMessage($rcvnumber),$cc);
            }
            return $r;
        }
    }


    function createPurchaseOrder($prnumber){
        $date = date('Y-m-d H:i:s');
        $query = "select * from purchase_requisition_header where requisition_number='$prnumber'";
        $rs = query($query);
        while ($obj=fetch($rs)) {
            $dateneeded = $obj->date_needed;
            $currency = $obj->currency;
            $prnotes = escapeString(strUtfEncode($obj->pr_notes));
        }
        $suppliers = array();
        $query = "select supplier_id, count(item_id) as itemcount from purchase_requisition_details where requisition_number='$prnumber' group by supplier_id";
        $rs = query($query);
        while($obj=fetch($rs)){
            $id = $obj->supplier_id;
            array_push($suppliers, $id);
        }
       

        $len = count($suppliers);
        for($i=0;$i<$len;$i++){
            $ponumber = getTransactionNumber(2);
            $supplierId = $suppliers[$i];
            $supplierAddr = getInfo("supplier","address","where id='".$supplierId."'");
            $supplierAddr = escapeString(strUtfEncode($supplierAddr));
            $attn = escapeString(getSupplierContact($supplierId));
            $now = date('Y-m-d H:i:s');
            $query = "insert into purchase_order_header(
                                                        order_number,
                                                        order_status,
                                                        supplier_id,
                                                        supplier_address,
                                                        expected_date,
                                                        currency,
                                                        reference_requisition_number,
                                                        date_flag,
                                                        supplier_contact,
                                                        po_remarks
                                                       ) 
                                                values('$ponumber',
                                                        'NEW',
                                                        '$supplierId',
                                                        '$supplierAddr',
                                                        '$dateneeded',
                                                        '$currency',
                                                        '$prnumber',
                                                        '$date',
                                                        '$attn',
                                                        '$prnotes'
                                                        )";
            $rs = query($query);
            if(!$rs){
                return error();
            }
            $query = "select * from purchase_requisition_details where requisition_number='$prnumber' and supplier_id='$supplierId'";
            $rs = query($query);
            if(!$rs){
                return error();
            }
            while ($obj=fetch($rs)) {
                $itemId = $obj->item_id;
                $qty = $obj->quantity;
                $price=$obj->unit_price;
                $classfn = escapeString($obj->classification_code);
				//get item name and description then save to database
				$det = "select * from item where id='$itemId'";
				$details = query($det);
				if ($obj2=fetch($details)) {
					$itemName = escapeString($obj2->item_name);
					$itemDesc = escapeString($obj2->long_description);
				}			

                $query = "insert into purchase_order_details values('$ponumber','$itemId','$itemName','$itemDesc','$qty','$price','$classfn')";
                query($query);
            }
        }
    }

    function updatePurchaseOrder($prnumber){
        $now = date('Y-m-d H:i:s');
        $query = "select * from purchase_requisition_header where requisition_number='$prnumber'";
        $rs = query($query);
        while ($obj=fetch($rs)) {
            $dateneeded = $obj->date_needed;
            $currency = $obj->currency;
            $prnotes = escapeString(strUtfEncode($obj->pr_notes));
            //$revisionsource=$obj->revision_flag;
        }
        $suppliers = array();
        $query = "select supplier_id, count(item_id) as itemcount from purchase_requisition_details where requisition_number='$prnumber' group by supplier_id";
        $rs = query($query);
        while($obj=fetch($rs)){
            $id = $obj->supplier_id;
            array_push($suppliers, $id);
        }
        $len = count($suppliers);

        ///////
        $query ="select * from purchase_order_header where reference_requisition_number='$prnumber'";
        $rs = query($query);
        while($obj=fetch($rs)){
                $ordernum = $obj->order_number;
                $query = "delete from purchase_order_details where order_number='$ordernum'";
                $rs3 = query($query);
        }



        // 
        $sourcesrs = query("select * from purchase_requisition_revision_sources where requisition_number='$prnumber'");
        $sourcescount = getNumRows($sourcesrs);
        $sources_array = array();
        while($obj=fetch($sourcesrs)){
            array_push($sources_array, $obj->order_number);
        }
        for($i=0;$i<$sourcescount;$i++){
            $posource = $sources_array[$i];
            query("update purchase_order_header 
                    set order_status='REVISED', 
                        expected_date='$dateneeded', 
                        currency='$currency',
                        discrepancy_flag=NULL,
                        po_remarks='$prnotes',
                        date_flag='$now'
                    where reference_requisition_number='$prnumber' and 
                          order_number='$posource'");
        }
        /////

        //////////
        for($i=0;$i<$len;$i++){
            $supplierId = $suppliers[$i];
            /*
            $query = "update purchase_order_header set order_status='REVISED', 
                             expected_date='$dateneeded', 
                             currency='$currency' 
                    where reference_requisition_number='$prnumber' and 
                          supplier_id='$supplierId' and 
                          order_number='$revisionsource'";
            $rs = query($query);
            if(!$rs){
                return error();
            }*/

            $query = "select * from purchase_requisition_details where requisition_number='$prnumber' and supplier_id='$supplierId'";
            $r = query($query);
            while ($obj=fetch($r)) {
                $itemId = $obj->item_id;
                $qty = $obj->quantity;
                $price=$obj->unit_price;
                $classfn = escapeString($obj->classification_code);
                    $query ="select * from purchase_order_header where reference_requisition_number='$prnumber' and supplier_id='$supplierId'";
                    $getpoquery = query($query);
                    $fl = false;
                    while($obj=fetch($getpoquery)){
                        $ordernum = $obj->order_number;
                        $fl = true;
                    }
                    if($fl==true){//
						//get item name and description then save to database
						$det = "select * from item where id='$itemId'";
						$details = query($det);
						if ($obj2=fetch($details)) {
							$itemName = escapeString($obj2->item_name);
							$itemDesc = escapeString($obj2->long_description);
						}						
					
                         $insertnewlyadded = "insert into purchase_order_details values('$ordernum','$itemId','$itemName','$itemDesc','$qty','$price','$classfn')";
                         $r2 = query($insertnewlyadded);
                         if(!$r2){
                            echo error();
                         }
                    }
                if(!$rs){
                    echo error();
                }

            }

        }
    }


    ///////////////////////////////////////////////////////////////////// EMAIL NOTIFICATION /////////////////////////////////////////////////////////
    //PR APPROVAL
    function prMessage($prNumber,$mRequestor,$mDate,$mType,$mJustification,$mHierarchy){
        return "Good day!<br><br>
                This is to inform you that ".$prNumber." is waiting for your approval.<br>
                Please log on to your systemone account for details.<br>
                Go to <a href='http://systemone/eprpo'>http://systemone/eprpo</a>.<br>
                <br>
                DETAILS OF REQUEST<br>
                Requestor: ".$mRequestor."<br>
                Control No.: ".$prNumber."<br>
                Date: ".$mDate."<br>
                Request type: ".$mType."<br>
                Justification: ".$mJustification."<br><br>
                Approval Hierarchy: ".$mHierarchy."<br><br>
                This is automatically generated by EPRPO System. Please do not reply.";
    }

    function prRejectMessage($prNumber, $rejectedby, $reason){
        return "Purchase Requisition with PR Number <b>".$prNumber."</b> has been <b>rejected</b> by ".$rejectedby."<br><br>
                Reason:<br>
                ".$reason."<br><br><br>
               Please log on to your systemone account for details.<br>
                Go to <a href='http://systemone/eprpo'>http://systemone/eprpo</a>.<br>
                <br>
                This is automatically generated by EPRPO System. Please do not reply.";
    }

    function dateneededUpdateNotification($pr, $po, $origdateneeded, $newdateneeded){
        return "Date Needed in ".$po." has been changed from ".$origdateneeded." to ".$newdateneeded."<br><br>

                Reference Purchase Requisition: ".$pr."<br>
                Purchase Order: ".$po."<br><br>
               
                Please log on to your systemone account for details.<br>
                Go to <a href='http://systemone/eprpo'>http://systemone/eprpo</a>.<br>
                <br>
                This is automatically generated by EPRPO System. Please do not reply.";
    }

    function poMessage($poNumber){
        return "Good day!<br><br>
                This is to inform you that ".$poNumber." is waiting for your approval.<br>
                Please log on to your systemone account for details.<br>
                Go to <a href='http://systemone/eprpo'>http://systemone/eprpo</a>.<br>
                <br>
                This is automatically generated by EPRPO System. Please do not reply.";
    }

    function poRejectMessage($poNumber, $rejectedby, $reason){
        return "Good day!<br><br>
                Purchase Order <b>".$poNumber."</b> has been <b>rejected</b> by ".$rejectedby."<br><br>
                Reason:<br>
                ".$reason."<br><br><br>
                Please log on to your systemone account for details.<br>
                Go to <a href='http://systemone/eprpo'>http://systemone/eprpo</a>.<br>
                <br>
                This is automatically generated by EPRPO System. Please do not reply.";
    }

	function rcvMessage($rcvNumber){
        return "Good day!<br><br>
                This is to inform you that ".$rcvNumber." is waiting for your approval.<br>
                Please log on to your systemone account for details.<br>
                Go to <a href='http://systemone/eprpo'>http://systemone/eprpo</a>.<br>
                <br>
                This is automatically generated by EPRPO System. Please do not reply.";
    }

    function rcvRejectMessage($rcvNumber, $rejectedby, $reason){
        return "Good day!<br><br>
                Receiving request <b>".$rcvNumber."</b> has been <b>rejected</b> by ".$rejectedby."<br><br>
                Reason:<br>
                ".$reason."<br><br><br>
                Please log on to your systemone account for details.<br>
                Go to <a href='http://systemone/eprpo'>http://systemone/eprpo</a>.<br>
                <br>
                This is automatically generated by EPRPO System. Please do not reply.";
    }

    function poDiscrepancyMessage($poNumber){
        return "Good day!<br><br>
                This is to inform you that there has been a discrepancy in ".$poNumber.".<br><br><br>
                Please log on to your systemone account for details.<br>
                Go to <a href='http://systemone/eprpo'>http://systemone/eprpo</a>.<br>
                <br>
                This is automatically generated by EPRPO System. Please do not reply.";
    }

    function userPasswordMessage($username, $name, $password){
        return '****** This is an automatically generated email, please do not reply ******<br><br> Hi '.$name.'! <br>Your username is <b>'.$username.'</b> and password is <b>'.$password.'</b>.';
    }
    function resetPasswordMessage($name, $password){
        return '****** This is an automatically generated email, please do not reply ******<br><br> Hi '.$name.'! <br>Your password has been reset. New password is <b>'.$password.'</b>.';
    }

    function receivingMessage($po){
        $prnumber = getInfo("purchase_order_header","reference_requisition_number","where order_number='".$po."'");
        $requisitioner = getUserName(getInfo("purchase_requisition_header","requisitioner","where requisition_number='".$prnumber."'"));
        return "Good day!<br><br> 
                This is to inform you that item(s) in ".$po." have been received.<br><br>
                Refererence PR Number: ".$prnumber."<br>
                Requisitioner: ".$requisitioner."<br><br>
                Please log on to your systemone account for details.<br>
                Go to <a href='http://systemone/eprpo'>http://systemone/eprpo</a>.<br>
                <br>
                This is automatically generated by EPRPO System. Please do not reply.";
    }

    function poAssgnMessage($po){
        return "Good day!<br><br>".$po." has been assigned to you.<br><br>
                Please log on to your systemone account for details.<br>
                Go to <a href='http://systemone/eprpo'>http://systemone/eprpo</a>.<br>
                <br>
                This is automatically generated by EPRPO System. Please do not reply.";
    }

    function followupMessage($pr,$msg,$requestor,$reqdate,$type,$justification){
        return "Good day!<br><br>
                This is to remind you that ".$pr." is waiting for your approval.<br><br>

                DETAILS OF REQUEST<br>
                Requestor: ".$requestor."<br>
                Control No.: ".$pr."<br>
                Requisition Date: ".$reqdate."<br>
                Request type: ".$type."<br>
                Justification: ".$justification."<br>
                Follow-up Message: ".$msg."<br><br>
                Please log on to your systemone account for details.<br>
                Go to <a href='http://systemone/eprpo'>http://systemone/eprpo</a>.<br>
                <br>
                This is automatically generated by EPRPO System. Please do not reply.";
    }


    function poApprovedMessage($po){
         return "Good day!<br><br>
                Purchase Order with number ".$po." has been approved.<br><br>
                Please log on to your systemone account for details.<br>
                Go to <a href='http://systemone/eprpo'>http://systemone/eprpo</a>.<br>
                <br>
                This is automatically generated by EPRPO System. Please do not reply.";
    }

    function poGenMessage($pr){
         return "Good day!<br><br>
                The system has generated purchase order(s) for pr number ".$pr.".<br><br>
                Please log on to your systemone account for details.<br>
                Go to <a href='http://systemone/eprpo'>http://systemone/eprpo</a>.<br>
                <br>
                This is automatically generated by EPRPO System. Please do not reply.";
    }

    function poRevisedMessage($pr){
        $reqnums = array();
        $rs = query("select * from purchase_requisition_revision_sources where requisition_number='$pr'");
        while($obj=fetch($rs)){
            array_push($reqnums, $obj->order_number);
        }
        $prnum = implode(',', $reqnums);
         return "Good day!<br><br>
                The following purchase order(s) has been revised:<br>
                ".$prnum." <br><br>
                Please log on to your systemone account for details.<br>
                Go to <a href='http://systemone/eprpo'>http://systemone/eprpo</a>.<br>
                <br>
                This is automatically generated by EPRPO System. Please do not reply.";
    }

    function prCancellationMessage($pr,$reason,$cancelledby,$requestor,$date){
        return "Good day!<br><br>
               <b>".$pr."</b> has been cancelled by ".$cancelledby.".<br><br>

                DETAILS OF CANCELLATION<br>
                Cancelled Date: ".$date."<br>
                Cancelled By: ".$cancelledby."<br>
                Requestor: ".$requestor."<br>
                Control No.: ".$pr."<br>
                Reason: ".$reason."<br>
                
                <br>
                This is automatically generated by EPRPO System. Please do not reply.";
    }


    function prForCancellationMessage($pr){
        return "Good day!<br><br>
                All purchase order(s) of ".$pr." have been cancelled.<br><br>
                <b>Please cancel ".$pr.".</b><br><br><br>


                This is automatically generated by EPRPO System. Please do not reply.";

            
                
    }

    function poCancellationMessage($pr,$details,$cancelledby){
        $msg = "Good day!<br><br>

                Cancellation Request by: ".$cancelledby."<br>
                Refererence PR Number: ".$pr."<br>
                Following purchase order(s) for cancellation: <br>";

        $len = count($details);
        for($i=0;$i<$len;$i++){
            $msg = $msg.$details[$i]."<br>";
        }

        $msg = $msg."<br><br><br>This is automatically generated by EPRPO System. Please do not reply.";

        return $msg;

                
    }

     function holdCancellationMessage($po,$reason,$cancelledbyname,$date){
        return "Good day!<br><br>
               Hold <b>".$po."</b> has been cancelled by ".$cancelledbyname.".<br><br>

                DETAILS OF CANCELLATION<br>
                Cancelled Date: ".$date."<br>
                Reason: ".$reason."<br>
                <br>
                This is automatically generated by EPRPO System. Please do not reply.";
    }

    function HoldPOMessage($po,$reason,$holdby,$now){
        return "Good day!<br><br>
               ".$po."</b> has been hold by ".$holdby.".<br><br>

                Hold Date: ".$now."<br>
                Reason: ".$reason."<br>
                
                <br>
                This is automatically generated by EPRPO System. Please do not reply.";
    }

    function ForApprovalItemMessage($code,$shortdesc,$price,$uploadby,$date){
            return "Good day!<br><br>
               You have an Item for approval, uploaded by ".$uploadby.".<br><br>

                Uploaded Date: ".$date."<br>
                Item Code: ".$code."<br>
                Item Name: ".$shortdesc."<br>
                Price: ".$price."<br>
                
                <br>
                This is automatically generated by EPRPO System. Please do not reply.";
    }

    function ApprovedItemMessage($item_name,$reason,$approvedby,$date){
            return "Good day!<br><br>
               Item ".$item_name." has been approved by ".$approvedby.".<br><br>

                Approved Date: ".$date."<br>
                Remarks: ".$reason."<br>
                
                <br>
                This is automatically generated by EPRPO System. Please do not reply.";
    }

    function DisapprovedItemMessage($item_name,$reason,$approvedby,$date){
            return "Good day!<br><br>
               Item  ".$item_name." has been disapproved by ".$approvedby.".<br><br>

                Approved Date: ".$date."<br>
                Reason: ".$reason."<br>
                
                <br>
                This is automatically generated by EPRPO System. Please do not reply.";
    }

    function prRevisionMessage($pr){
        $reqnums = array();
        $ponums = array();
        $rs = query("select * from purchase_order_header where reference_requisition_number='$pr'");
        while($obj=fetch($rs)){
            array_push($reqnums, $obj->reference_requisition_number);
            array_push($ponums, $obj->order_number);
        }
        $prnum = implode(',', $reqnums);
        $ponum = implode(',', $ponums);
         return "Good day!<br><br>
                The following details is for REVISION:<br>
                PR Num: ".$prnum." <br>
                PO Num reference: ".$ponum." <br><br>
                Please log on to your systemone account for details.<br>
                Go to <a href='http://systemone/eprpo'>http://systemone/eprpo</a>.<br>
                <br>
                This is automatically generated by EPRPO System. Please do not reply.";
    }

	function returnNumRows($result){
		$count = mysql_num_rows($result);
		return $count;
	}
	
	function return_ordinal($number) {
		$ends = array('th','st','nd','rd','th','th','th','th','th','th');
		if ((($number % 100) >= 11) && (($number%100) <= 13))
			return $number. 'th';
		else
			return $number. $ends[$number % 10];
	}

    function Approved_UpdateItemAppStatus($id){
        $query = "update item set app_status='2' where id='$id'";
        $rs = query($query);
        if(!$rs){
            echo error();
        }
    }

    function Disapproved_UpdateItemAppStatus($id){
        $query = "update item set app_status='3' where id='$id'";
        $rs = query($query);
        if(!$rs){
            echo error();
        }
    }


?>