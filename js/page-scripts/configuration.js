$(document).off('click','#emailnotification-savebtn').on('click','#emailnotification-savebtn', function(){
	$(this).attr('disabled',true);
	var host, encryption, port, username, password, sender, emailfrom, ok=true;
	host = $('.host').val();
	encryption = $('.encryption').val();
	port = $('.tcpport').val();
	username = $('.smtpusername').val();
	password = $('.smtppassword').val();
	sender = $('.emailsender').val();
	emailfrom = $('.emailsentfrom').val();

	$('#email-notification-settings .form-input').each(function(){
		if($(this).val()==''){
			ok=false;
		}
	});

	if(ok==false){
		$('#email-notification-settings .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide all the needed information.</div></div>");
		$('.content').animate({scrollTop:0},200);
		$(this).removeAttr('disabled');
	}
	else{

		$.post(server+'configuration.php',{JKfoIr40Oibsoi1Ofih:'Bdmrpodk49d94po3',host:host,encryption:encryption,port:port,username:username,password:password,sender:sender,emailfrom:emailfrom},function(data){
			if(data.trim()=='success'){

				
				$('#email-notification-settings').modal('hide');
				$('#email-notification-settings').on('hidden.bs.modal',function(){
					$('.content').load('System/configuration.php');
					$.getScript(pagescripts+'configuration.js');
					$('.content').animate({scrollTop:0},200);
					notify("Email Settings has been <b>updated</b>", "success","fa fa-check-circle");
				});
				
			}
			else{
				alert(data);
			}
		});
	}
});

$(document).off('click','#poapprovalsettings-savebtn').on('click','#poapprovalsettings-savebtn', function(){
	var head, first, second, divapp, third, ok=true, asst, mng, pr;
	head = $('.purchasingsectionhead').val();
	first = $('.pofirstapprover').val();
	second = $('.posecondapprover').val();
	divapp = $('.podivapprover').val();
	third = $('.polastapprover').val();

	asst = $('.asstmng').val();
	mng = $('.genmng').val();
	pr = $('.pres').val();


	$('#po-approval-settings .form-input').each(function(){
		if($(this).val()==''){
			ok=false;
		}
	});

	if(ok==false){
		$('#po-approval-settings-settings .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please add an<b> item</b>.</div></div>");
		$('.content').animate({scrollTop:0},200);
		$(this).removeAttr('disabled');
	}
	else{
		$.post(server + 'configuration.php', { Flekfiui42k3b49ksb: 'aoirHkieHFi1hoi34i', asst: asst, mng: mng, pr: pr, head: head, firstapprover: first, secondapprover: second, divapprover: divapp, lastapprover:third},function(data){
			if(data.trim()=='success'){
				
				$('#po-approval-settings').modal('hide');
				$('#po-approval-settings').on('hidden.bs.modal',function(){
					$('.content').load('System/configuration.php');
					$.getScript(pagescripts+'configuration.js');
					$('.content').animate({scrollTop:0},200);
					notify("PO Approval Settings has been <b>updated</b>", "success","fa fa-check-circle");
				});
			}
			else{
				alert(data);
			}
		});
	}

});


$(document).off('click','#prapprovalsettings-savebtn').on('click','#prapprovalsettings-savebtn', function(){
	var approvaltype = $('#pr-approval-assignment .prapprovaltype').val(),
		oldapprover = $('#pr-approval-assignment .prevapprover').val(),
		assignedto = $('#pr-approval-assignment .assignprto').val();

	if(approvaltype=='NULL'){
		$('#pr-approval-assignment .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select <b>approval type.</b></div></div>");
		$('.content').animate({scrollTop:0},200);
	}
	else if(oldapprover=='NULL'){
		$('#pr-approval-assignment .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select an approver to be<b> replaced</b>.</div></div>");
		$('.content').animate({scrollTop:0},200);
	}
	else if(assignedto=='NULL'){
		$('#pr-approval-assignment .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select an approver<b> replacement</b>.</div></div>");
		$('.content').animate({scrollTop:0},200);
	}
	else{
		$.post(server+'configuration.php',{LdproRjona2kdh49Ndl:'FKnsli4p49JRPo49jn',approvaltype:approvaltype,assignedto:assignedto,oldapprover:oldapprover},function(data){
			if(data.trim()=='success'){
				$('#pr-approval-assignment').modal('hide');
				$('#pr-approval-assignment').on('hidden.bs.modal',function(){
					$('.content').load('System/configuration.php');
					$.getScript(pagescripts+'configuration.js');
					$('.content').animate({scrollTop:0},200);
					notify("Assigned <b>successfully</b>", "success","fa fa-check-circle");
				});
			}
			else{
				alert(data);
			}
		});
	}

});


$(document).off('click','#poapprovalassignment-savebtn').on('click','#poapprovalassignment-savebtn', function(){
	var approvaltype = $('#po-approval-assignment .poapprovaltype').val(),
		oldapprover = $('#po-approval-assignment .prevpoapprover').val(),
		assignedto = $('#po-approval-assignment .assignpoto').val();

	if(approvaltype=='NULL'){
		$('#po-approval-assignment .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select <b>approval type.</b></div></div>");
		$('.content').animate({scrollTop:0},200);
	}
	else if(oldapprover=='NULL'){
		$('#po-approval-assignment .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select an approver to be<b> replaced</b>.</div></div>");
		$('.content').animate({scrollTop:0},200);
	}
	else if(assignedto=='NULL'){
		$('#po-approval-assignment .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select an approver<b> replacement</b>.</div></div>");
		$('.content').animate({scrollTop:0},200);
	}
	else{
		$.post(server+'configuration.php',{KsnclIEnsi309sjDNks:'Ndlkiona4i03nakfnor',approvaltype:approvaltype,assignedto:assignedto,oldapprover:oldapprover},function(data){
			if(data.trim()=='success'){
				$('#po-approval-assignment').modal('hide');
				$('#po-approval-assignment').on('hidden.bs.modal',function(){
					$('.content').load('System/configuration.php');
					$.getScript(pagescripts+'configuration.js');
					$('.content').animate({scrollTop:0},200);
					notify("Assigned <b>successfully</b>", "success","fa fa-check-circle");
				});
			}
			else{
				alert(data);
			}
		});
	}

});

$(document).off('click','#poprinting-savebtn').on('click','#poprinting-savebtn', function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		topmargin = $(modal+' .topmargin').val(),
		button = $(this);
	button.attr('disabled',true);

	if(topmargin==''){
		$(modal+' .topmargin').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Margin top cannot be empty.</div></div>");
		button.removeAttr('disabled');
	}
	else if(topmargin<0){
		$(modal+' .topmargin').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Margin top must be an integer greater than or equal to 0.</div></div>");
		button.removeAttr('disabled');
	}
	else{
		$.post(server+'configuration.php',{poprintingalignment:'Dlslorpw#02e4i$l@lnaol!3so',topmargin:topmargin},function(data){
			if(data.trim()=='success'){
				$(modal).modal('hide');
				$(modal).on('hidden.bs.modal',function(){
					$('.content').load('System/configuration.php');
					$.getScript(pagescripts+'configuration.js');
					//notify("Updated Printing Alignment", "success","fa fa-check-circle");
				});
			}
			else{
				alert(data);
			}
		});
	}
});