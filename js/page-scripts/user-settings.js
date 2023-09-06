$(document).off('click','#changepasswordmodal-confirmbtn').on('click','#changepasswordmodal-confirmbtn',function(){
	$(this).attr('disabled',true);
	var current, newpass, retypenew;
	current = $('#changepasswordmodal .currentpassword').val();
	newpass = $('#changepasswordmodal .newpassword').val();
	retypenew = $('#changepasswordmodal .retypenewpassword').val();
	if(current==''||newpass==''){
		$('#changepasswordmodal .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Current and new password should not be blank.</div></div>");
		$(this).removeAttr('disabled');
	}
	else if(newpass.toLowerCase()!=retypenew.toLowerCase()){
		$('#changepasswordmodal .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>New Password must be entered twice.</div></div>");
		$(this).removeAttr('disabled');
	}
	else if(newpass.trim()==''){
		$('#changepasswordmodal .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>New password must atleast be 5 characters long.<br>It should only contain numerical,alphabetical, and special characters such as '?,!'</div></div>");
		$(this).removeAttr('disabled');
	}
	else{
		$.post(server+'user-settings.php',{jdsfhBfroi:'dsfer43@3',current:current,newpass:newpass,retype:retypenew},function(data){
			if(data.trim()=='success'){
				$('#changepasswordmodal').modal('hide');
				$('#changepasswordmodal').on('hidden.bs.modal',function(){
					$('#user-settings-panel .errordiv').html("<div class='message-success'><div class='message-content'><span class='closemessage'>&times;</span>Password updated.</div></div>");
					$('#changepasswordmodal-confirmbtn').removeAttr('disabled');
					notify("Password has been <b>updated</b>", "success","fa fa-check-circle");
				});	
			}
			else{
				$('#changepasswordmodal .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>"+data+"</div></div>");
				$('#changepasswordmodal-confirmbtn').removeAttr('disabled');
			}
		});
	}
	
});