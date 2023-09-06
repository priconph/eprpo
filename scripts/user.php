<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include('email-class.php');
	if(isset($_POST['action'])){
		$data = $_POST['dataarray'];
		$i = ($_POST['action']=='edit')?2:0;
		$fname = ucwords(strtolower(escapeString($data[$i+0])));
		$mname = ucwords(strtolower(escapeString($data[$i+1])));
		$lname = ucwords(strtolower(escapeString($data[$i+2])));
		$dept = escapeString($data[$i+3]);
		$email = strtolower(escapeString($data[$i+4]));
		$username = strtolower(escapeString($data[$i+5])); 
		$usergroup = escapeString($data[$i+6]);
		$pwd = randomPassword();
		$password = md5($pwd); //sample default password
		$loginuser = $userid;
		$date = date('Y-m-d H:i:s');

		$id='';

		if($_POST['action']=='edit'){
			$id = escapeString($data[0]);
			$query = "select * from user where username='$username' and not id='$id'";
		}else{
			$query = "select * from user where username='$username'";
		}
		$rs=query($query);
		$count = getNumRows($rs);
		if($count>=1){
			echo 'usernameexist';
		}
		else{
			if($_POST['action']=='edit'){
				$id = escapeString($data[0]);
				$stat = escapeString($data[1]);
				$query = "update user set active_flag='$stat', first_name='$fname', middle_name='$mname', last_name='$lname', section_department_id='$dept', user_group_id=$usergroup, email_address='$email', username='$username', updated_by_user_id='$loginuser', updated_date='$date' where id='$id'";
				$rs = query($query);
				//set approver stat of this user 
				$q = "update approver set active_flag='$stat' where user_id='$id'";
				query($q);
				//
			}
			else{
				$query = "insert into user(id, active_flag, password, first_name, middle_name, last_name, section_department_id, email_address, username, user_group_id, created_by_user_id, created_date, updated_by_user_id, updated_date) values(null,'1','$password','$fname','$mname','$lname','$dept','$email','$username',$usergroup,'$loginuser','$date',NULL,NULL)";
				$rs = query($query);
				$id = mysql_insert_id();

				////////// EMAIL NOTIFICATION ////////////
				$emailClass->sendNotification(getUserEmail($id),getUserName($id),'EPRPO - User Password',userPasswordMessage($username, getUserName($id),$pwd),userPasswordMessage($username, getUserName($id),$pwd),NULL);

				/////////////////////////////////////////
			}

			
			
			////////// USER RIGHTS ///////////////////
			query("delete from user_rights where user_id='$id'");
			@$useraccess = $_POST['useraccess'];
			$accesscount = count($useraccess);
			$userrights = array();
			for($i=0;$i<$accesscount;$i++){
				$access = escapeString($useraccess[$i]);
				$rights = "('".$id."','".$access."')";
				array_push($userrights, $rights);
				//$query = "insert into user_rights(user_id, menu_id) values('$id','$access')";
				//$rs = query($query);
			}
			$rightsvalues = implode(', ', $userrights);
			$rs = query("insert into user_rights(user_id, menu_id) values".$rightsvalues);
			//////////////////////////////////////
			$response=($rs==true)?"success":mysql_error();
			echo $response;
		}
	}

	if(isset($_POST['deleteselected'])){
		$idarray = $_POST['id'];
		foreach ($idarray as $id) {
			$delete = escapeString($id);
			$query = "delete from user where id='$delete'";
			$rs = query($query);
			if(!$rs){
					echo mysql_error();
			}
		}
		if(mysql_error()==''){
			echo "success";
		}
	}


	if(isset($_POST['ki45lKJn3idlky'])){//reset user password
		if($_POST['ki45lKJn3idlky']=='3kh$klkddj4%l'){
			$id = escapeString($_POST['id']);
			$pwd = randomPassword();
			$resetpword=md5($pwd);
			
				$query = "update user set password='$resetpword' where id='$id'";
				$rs=query($query);

				if($rs){
					////////// EMAIL NOTIFICATION ////////////
					$res = $emailClass->sendNotification(getUserEmail($id),getUserName($id),'EPRPO - User Password Reset',resetPasswordMessage(getUserName($id),$pwd),resetPasswordMessage(getUserName($id),$pwd),NULL);
					/////////////////////////////////////////
					
					echo "success";
				}

			

		}
	}


	if(isset($_POST['DLnslrii3hl34p03ddnfo4i'])){//edit user permission - checks exisiting user access
    	if($_POST['DLnslrii3hl34p03ddnfo4i']=='sdfei3opod30napri'){
    		$access = array();
    		$id = escapeString($_POST['id']);
    		$query = "select * from user_rights where user_id='$id'";
    		$rs = query($query);
    		while($obj=fetch($rs)){
    			array_push($access, $obj->menu_id);
    		}
    		echo implode(',', $access);
    	}
    }

	


?>