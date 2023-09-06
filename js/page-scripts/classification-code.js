$(document).off('click','.classificationcode-modal-savebtn').on('click','.classificationcode-modal-savebtn',function(){
		$(this).attr('disabled',true);
		var id, acct_code, acct_name, modal, action,button,status=0;
		id='';
		button = $(this);
		modal = $(this).attr('id')=='classcodecreate-savebtn'?'#addclasscodemodal':'#editclasscodemodal';
		action=(modal=='#addclasscodemodal')?'add':'edit';
		acct_name = $(modal+' .acct_name').val();
		acct_code = $(modal+' .acct_code').val().replace(/\s/g,'');
		status   = $(modal+' .status').val();
		
		if(acct_code==''){
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please enter the <b>Classification Code</b>.</div></div>");
			$(this).removeAttr('disabled');
		}
		else if(acct_name==''){
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please specify a <b>Account Name</b>.</div></div>");
			$(this).removeAttr('disabled');
		}
		else{
			if(action=='edit'){
				id = $(modal+' .classcodeid').val();
			}

			$('#loading-img').removeClass('hidden');
			$.post(server+'classification-code.php',{action:action,code:acct_code,acct_name:acct_name,id:id,status:status},function(data){
					if(data.trim()=='success'){
						successful(modal,'Maintenance/classification-code.php');
						$('#loading-img').addClass('hidden');
					}
					else{
						$('#loading-img').addClass('hidden');
						$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span><b>Classification Code</b> already in use.</div></div>");
						button.removeAttr('disabled');
		
					}
			});
		}
});

$(document).off('click','.editclasscodebtn').on('click','.editclasscodebtn',function(){
	var id, acct_name,code,status;
	id = $(this).attr('id');
	code = $(this).attr('code');
	acct_name = $(this).attr('name');
	status = $(this).attr('status');
	$('#editclasscodemodal .classcodeid').val(id);
	$('#editclasscodemodal .acct_name').val(acct_name);
	$('#editclasscodemodal .acct_code').val(code);
	$('#editclasscodemodal .status').val(status);
});

/***** DELETE CLASSIFICATION CODE BTN ****/
$(document).off('click','#deleteclasscodebtn').on('click','#deleteclasscodebtn',function(){
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
						$.post(server+'classification-code.php',{deleteselected:'',id:id},function(data){
							if(data.trim()!='success'){
								$('#loading-img').addClass('hidden');
								alert(data);
								button.removeAttr('disabled');
							}
							else{
								$.getScript(pagescripts+'classification-code.js');
								$('.content').load('Maintenance/classification-code.php',function(){
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
