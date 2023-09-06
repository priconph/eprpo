$(document).ready(function(){
	$(document).off('click','.modal-usergroup-savebtn').on('click','.modal-usergroup-savebtn',function(){
		$(this).attr('disabled',true);
		var id, groupname, modal, action;
		id='';
		modal = $(this).attr('id')=='usergroupcreate-savebtn'?'#addusergroupmodal':'#editusergroupmodal';
		action=(modal=='#addusergroupmodal')?'add':'edit';
		groupname = $(modal+' .usergroupname').val();
		if(groupname==''){
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please specify a <b>Group Name</b>.</div></div>");
			$(this).removeAttr('disabled');
		}
		else if(checkAlphanumeric(groupname)==false){
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span><b>Invalid Group Name.</b><br> It should be atleast 3 characters long.<br>It should only contain alphanumeric characters.</div></div>");
			$(this).removeAttr('disabled');
		}
		else{
			if(action=='edit'){
				id = $(modal+' .usergroupid').val();
			}
			$('#loading-img').removeClass('hidden');
			$.post(server+'user-group.php',{action:action,groupname:groupname,id:id},function(data){
					if(data.trim()=='success'){
						successful(modal,'System/user-group.php');
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
	
	$(document).off('click','.editusergroupbtn').on('click','.editusergroupbtn',function(){
		var id, groupname;
		id=$(this).attr('id');
		groupname = $(this).attr('name');
		$('#editusergroupmodal .usergroupid').val(id);
		$('#editusergroupmodal .usergroupname').val(groupname);
	});

	/***** DELETE USER GROUP BTN *****/
	$(document).off('click','#deleteusergroupbtn').on('click','#deleteusergroupbtn',function(){
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
							$.post(server+'user-group.php',{deleteselected:'',id:id},function(data){
								if(data.trim()!='success'){
									$('#loading-img').addClass('hidden');
									alert(data);
									button.removeAttr('disabled');
								}
								else{
									$('.content').load('System/user-group.php',function(){
										setTimeout(function(){
											$('#loading-img').addClass('hidden');
										},300);
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
});