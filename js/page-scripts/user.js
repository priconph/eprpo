$(document).off('click','.modal-savebtn').on('click','.modal-savebtn',function(){
	var button = $(this);

	button.attr('disabled',true);
	var modal, action, id, status, fname, mname, lname, dept, email, username, usergroup, array, 
		useraccess=[];
	array=[];
	modal = $(this).attr('id')=='usercreate-savebtn'?'#addusermodal':'#editusermodal';
	action=(modal=='#addusermodal')?'add':'edit';
	fname=$(modal+' .firstname');
	mname=$(modal+' .middlename');
	lname=$(modal+' .lastname');
	dept=$(modal+' .department');
	email=$(modal+' .email');
	username=$(modal+' .username');
	usergroup=$(modal+' .usergroup');
	$(modal+' .form-input').each(function(){
			array.push($(this).val());
	});
	/////////////
	$(modal+' .useraccess').each(function(){
		if($(this).prop('checked')==true){
			useraccess.push($(this).val());
		}
	});

	
	///////////////////
	if(fname==''||mname==''||lname==''||dept==''||email==''||username==''||usergroup==''){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide <b>all the information.</b></div></div>");
		button.removeAttr('disabled');
	}
	else if(fname.val().trim()==''){
		fname.focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span><b>Invalid First Name</b> <br>It should only contain alphabetical characters.<br>It should be atleast 2 characters long.</div></div>");
		button.removeAttr('disabled');
	}
	else if(lname.val().trim()==''){
		lname.focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span><b>Invalid Last Name</b> <br>It should only contain alphabetical characters.<br>It should be atleast 2 characters long.</div></div>");
		button.removeAttr('disabled');
	}
	else if(dept.val()==''){
		dept.focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span><b>Invalid Department</b> <br>Please select a department.</div></div>");
		button.removeAttr('disabled');
	}
	else if(checkEmail(email.val())==false){
		email.focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span><b>Invalid Email Address</b> <br>Please enter a valid email address.</div></div>");
		button.removeAttr('disabled');
	}
	else if(checkUsername(username.val())==false){
		username.focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span><b>Invalid Username</b> <br>It should only contain alphabetical and numerical characters, and underscores.<br>It should be atleast 5 characters long.</div></div>");
		button.removeAttr('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		$.post(server+'user.php',{action:action,dataarray:array,useraccess:useraccess},function(data){
			if(data.trim()=='success'){
				successful(modal,'System/user.php');
				$('#loading-img').addClass('hidden');
			}
			else if(data.trim()=='usernameexist'){
				alert('Username already exist');
				$('#loading-img').addClass('hidden');
				button.removeAttr('disabled');
			}
			else{
				$('#loading-img').addClass('hidden');
				alert(data);
				button.removeAttr('disabled');
			}
		});
	}
});

$(document).off('click','.edituserbtn').on('click','.edituserbtn',function(){
		var id, stat, firstname, middlename, lastname, department, email, username, usergroup;
		id = $(this).attr('id');
		stat = $(this).attr('stat');
		firstname = $(this).attr('fname');
		middlename = $(this).attr('mname');
		lastname = $(this).attr('lname');
		department = $(this).attr('dept');
		email = $(this).attr('email');
		username = $(this).attr('username');
		usergroup = $(this).attr('usergroup');
		$('#editusermodal .userid').val(id);
		$('#editusermodal .firstname').val(firstname);
		$('#editusermodal .middlename').val(middlename);
		$('#editusermodal .lastname').val(lastname);
		$('#editusermodal .department').val(department);
		$('#editusermodal .email').val(email);
		$('#editusermodal .username').val(username);
		$('#editusermodal .usergroup').val(usergroup);
		$('#editusermodal .stat').val(stat);


		$.post(server+'user.php',{DLnslrii3hl34p03ddnfo4i:'sdfei3opod30napri',id:id},function(data){
			var fetchdata = data.trim().split(','),
				count = fetchdata.length;
			$("#editusermodal .table-useraccess input[value!='#dash']").prop('checked',false);
			for (var i = 0;i<count; i++) {
				$("#editusermodal input[value='"+fetchdata[i]+"']").prop('checked',true);
			};

		});

		$('.chosen-select').trigger('chosen:updated');
});

$('#editusermodal').on('hidden.bs.modal',function(){
	$("#editusermodal input[type='checkbox']").prop('checked',false);
});


$(document).off('click','#deleteuserbtn').on('click','#deleteuserbtn',function(){
		var button = $(this);
		button.attr('disabled',true);
		var id, response;

		$.confirm({
					animation: 'bottom', 
					closeAnimation: 'top',
					animationSpeed: 1000,
					animationBounce:1,
					title: 'Confirmation',
					content: 'Are you sure you want to continue?',
					confirmButton: 'Confirm',
					cancelButton: 'Cancel',	
					confirmButtonClass: 'btn-oceanblue', 
					cancelButtonClass: 'btn-royalblue', 
					theme: 'white', 

					confirm: function(){
						id = [];
						$('.itemcheckbox:checked').each(function(){
							id.push($(this).attr('rowid'));
						});
						if(id.length>0){
							$('#loading-img').removeClass('hidden');
							$.post(server+'user.php',{deleteselected:'',id:id},function(data){
								if(data.trim()!='success'){
									$('#loading-img').addClass('hidden');
									alert(data);
									button.removeAttr('disabled');
								}
								else{
									$('.content').load('System/user.php',function(){
										setTimeout(function(){
											$('#loading-img').addClass('hidden');
										});
									});
									
								}
							});
						}
						else{
							button.removeAttr('disabled');
						}
					},
					cancel:function(){ 
						button.removeAttr('disabled');
					}

		});

});

$(document).off('click','.resetpassword').on('click','.resetpassword',function(){
	var button = $(this);
	button.attr('disabled',true);


	$.confirm({
					animation: 'bottom', 
					closeAnimation: 'top',
					animationSpeed: 1000,
					animationBounce:1,
					title: 'Confirmation',
					content: 'Are you sure you want to continue?',
					confirmButton: 'Confirm',
					cancelButton: 'Cancel',	
					confirmButtonClass: 'btn-oceanblue', 
					cancelButtonClass: 'btn-royalblue', 
					theme: 'white', 

					confirm: function(){
						var id = button.attr('id');
						$('#loading-img').removeClass('hidden');
						$.post(server+'user.php',{ki45lKJn3idlky:'3kh$klkddj4%l',id:id},function(data){
								if(data.trim()=='success'){
									$('.content').load('System/user.php',function(){
										setTimeout(function(){
											$('#loading-img').addClass('hidden');
											notify("Password Reset Successful", "success","fa fa-check-circle");
										},300);
									});
								}
								else{
									$('#loading-img').addClass('hidden');
									alert(data);
									button.removeAttr('disabled');
								}
						});
					},
					cancel:function(){ 
						button.removeAttr('disabled');
					}

		});


});

$(document).off('change','.section').on('change','.section',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		sectionname = $(this).data('section');

	if($(this).prop('checked')==true){
		$(modal+' .'+sectionname+' .useraccess').prop('checked',true);
	}
	else{
		$(modal+' .'+sectionname+' .useraccess').prop('checked',false);
	}
	
});

$(document).off('change','.view').on('change','.view',function(){
	if($(this).prop('checked')==true){
		$(this).closest('td').siblings('td:not([rowspan])').children('input').prop('checked',true);
	}
	else{
		$(this).closest('td').siblings('td:not([rowspan])').children('input').prop('checked',false);
	}
	
});