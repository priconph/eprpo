<?php
    include('../config/connection.php');
    include('../config/checklogin.php');
    include('../config/checkurlaccess.php');
    include('../config/functions.php');

    if(isset($_POST['JKfoIr40Oibsoi1Ofih'])){
    	if($_POST['JKfoIr40Oibsoi1Ofih']=='Bdmrpodk49d94po3'){
    		$host = escapeString($_POST['host']);
    		$port = escapeString($_POST['port']);
    		$encryption = escapeString($_POST['encryption']);
    		$uname = escapeString($_POST['username']);
    		$pword = escapeString($_POST['password']);
    		$sender = escapeString($_POST['sender']);
    		$emailfrom = escapeString($_POST['emailfrom']);

    		$query = "update email_settings set host='$host', username='$uname', password='$pword', port='$port', email_sent_from='$emailfrom', email_sender='$sender', encryption='$encryption' where id='1'";
    		$rs = query($query);
    		if($rs){
    			echo "success";
    		}

    	}
    }

    if(isset($_POST['Flekfiui42k3b49ksb'])){
    	if($_POST['Flekfiui42k3b49ksb']=='aoirHkieHFi1hoi34i'){
    		$head = escapeString($_POST['head']);
    		$firstapprover = escapeString($_POST['firstapprover']);
    		$secondapprover = escapeString($_POST['secondapprover']);
    		$lastapprover = escapeString($_POST['lastapprover']);

            $asst = escapeString($_POST['asst']);
            $mng = escapeString($_POST['mng']);
            $pres = escapeString($_POST['pr']);

    		$query = "update purchase_order_configuration set purchasing_head=$head, 
                                                              first_approver=$firstapprover, 
                                                              second_approver=$secondapprover, 
                                                              third_approver=$lastapprover,
                                                              assistant_manager=$asst,
                                                              general_manager=$mng,
                                                              president=$pres 
                    where id='1'";


    		$rs = query($query); 
            // AS OF 4/7/2016//////////////////////////
            //query("update purchase_order_header set first_approver='$firstapprover' where order_status='IN REVIEW' and flag='1'");
            //query("update purchase_order_header set second_approver='$secondapprover' where order_status='IN REVIEW' and flag='2'");
            //query("update purchase_order_header set third_approver='$lastapprover' where order_status='IN REVIEW' and flag='3' and  not third_approver=NULL");
            /////////////////////////////////////////
    		if($rs){
    			echo "success";
    		}
    	}
    }

    if(isset($_POST['LdproRjona2kdh49Ndl'])){
        if($_POST['LdproRjona2kdh49Ndl']=='FKnsli4p49JRPo49jn'){
            $approvaltype = escapeString($_POST['approvaltype']);
            $assignedto = escapeString($_POST['assignedto']);
            $oldapprover = escapeString($_POST['oldapprover']);

            switch ($approvaltype) {
                case '1':
                    query("update purchase_requisition_header set section_head1='$assignedto' where requisition_status='IN REVIEW' and section_head1='$oldapprover' and sh_approved is NULL");
                    break;
                case '2':
                    query("update purchase_requisition_header set section_head2='$assignedto' where requisition_status='IN REVIEW' and section_head2='$oldapprover' and sh2_approved is NULL");
                    break;
                case '3':
                    query("update purchase_requisition_header set general_manager='$assignedto' where requisition_status='IN REVIEW' and general_manager='$oldapprover' and gm_approved is NULL");
                    break;
                case '4':
                    query("update purchase_requisition_header set assistant_vice_president='$assignedto' where requisition_status='IN REVIEW' and assistant_vice_president='$oldapprover' and avp_approved is NULL");
                    break;
                case '5':
                    query("update purchase_requisition_header set senior_vice_president='$assignedto' where requisition_status='IN REVIEW' and senior_vice_president='$oldapprover' and svp_approved is NULL");
                    break;
                case '6':
                    query("update purchase_requisition_header set president='$assignedto' where requisition_status='IN REVIEW' and president='$oldapprover' and pr_approved is NULL");
                    break;
                case '7':
                    query("update purchase_requisition_header set conformance='$assignedto' where requisition_status='IN REVIEW' and conformance='$oldapprover' and conf_approved is NULL");
                    break;
            }
            query("update approver set active_flag=0 where user_id='$oldapprover'");
            echo "success";
        }
    }

    if(isset($_POST['KsnclIEnsi309sjDNks'])){
        if($_POST['KsnclIEnsi309sjDNks']=='Ndlkiona4i03nakfnor'){
            $approvaltype = escapeString($_POST['approvaltype']);
            $assignedto = escapeString($_POST['assignedto']);
            $oldapprover = escapeString($_POST['oldapprover']);

            switch ($approvaltype) {
                case '1':
                    query("update purchase_order_header set first_approver='$assignedto' where order_status='IN REVIEW' and first_approver='$oldapprover' and flag<=1");
                    query("update purchase_order_configuration set first_approver='$assignedto' where id='1'");
                    break;
                case '2':
                    query("update purchase_order_header set second_approver='$assignedto' where order_status='IN REVIEW' and second_approver='$oldapprover' and flag<=2");
                     query("update purchase_order_configuration set second_approver='$assignedto' where id='1'");
                    break;
                case '3':
                    query("update purchase_order_header set third_approver='$assignedto' where order_status='IN REVIEW' and third_approver='$oldapprover' and flag<=3");
                     query("update purchase_order_configuration set third_approver='$assignedto' where id='1'");
                    break;
            }
            echo "success";
        }
    }

    if(isset($_POST['poprintingalignment'])&&$_POST['poprintingalignment']=='Dlslorpw#02e4i$l@lnaol!3so'){
        $topmargin = escapeString($_POST['topmargin']);
        $rs = query("update purchase_order_configuration set po_printing_alignment='$topmargin'");
        if($rs){
            echo "success";
        }
    }





?>