$(document).off('click','.modal-savebtn').on('click','.modal-savebtn',function(){
		$(this).attr('disabled',true);
		var code, desc, days, modal, action;
		id='';
		modal = $(this).attr('id')=='paymenttermcreate-savebtn'?'#addpaymenttermsmodal':'#editpaymenttermsmodal';
		action=(modal=='#addpaymenttermsmodal')?'add':'edit';
		code = $(modal+' .code').val();
		desc = $(modal+' .description').val();
		days = $(modal+' .days').val();
		if(code==''){
			$(modal+' .code').focus();
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span><b>Invalid Term Code.</b><br>It should only contain alphabetical and numerical characters.<br>It should be atleast 2 characters long.</div></div>");
			$(this).removeAttr('disabled');
		}
		else if(checkInt(days)==false&&days!='0'){
			$(modal+' .days').focus();
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span><b>Invalid Days Input.</b><br> It should not be empty.<br>Input must be an integer.</div></div>");
			$(this).removeAttr('disabled');
		}
		else{
			if(action=='edit'){
				id = $(modal+' .termid').val();
			}
			$('#loading-img').removeClass('hidden');
			$.post(server+'terms-of-payment.php',{action:action,code:code,description:desc,days:days,id:id},function(data){
					if(data.trim()=='success'){
						successful(modal,'Maintenance/terms-of-payment.php');
						$('#loading-img').addClass('hidden');
					}
					else{
						$('#loading-img').addClass('hidden');
						alert(data);
						$(this).removeAttr('disabled');
					}
			});
		}
});


$(document).off('click','.editpaymenttermbtn').on('click','.editpaymenttermbtn',function(){
	$(this).attr('disabled',true);
	var id, code, desc, days;
	id= $(this).attr('id');
	code = $(this).attr('code');
	desc = $(this).attr('desc');
	days = $(this).attr('days');
	$('#editpaymenttermsmodal .termid').val(id);
	$('#editpaymenttermsmodal .code').val(code);
	$('#editpaymenttermsmodal .description').val(desc);
	$('#editpaymenttermsmodal .days').val(days);
	$(this).removeAttr('disabled');
});

/***** DELETE DEPARTMENT BTN *****/
$(document).off('click','#deletepaymenttermbtn').on('click','#deletepaymenttermbtn',function(){
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
						$.post(server+'terms-of-payment.php',{deleteselected:'',id:id},function(data){
							if(data.trim()!='success'){
								$('#loading-img').addClass('hidden');
								alert(data);
								button.removeAttr('disabled');
							}
							else{
								$('.content').load('Maintenance/terms-of-payment.php',function(){
									setTimeout(function(){
										$('#loading-img').addClass('hidden');
									},300);
								});
								
							}
						});
					}
					button.removeAttr('disabled');
				},
				cancel:function(){ 
					button.removeAttr('disabled');
				}

		});




});
