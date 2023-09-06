//searchbutton to trigger userlist modal
$(document).off('click','#reactivateapproverbtn').on('click','#reactivateapproverbtn',function(){
	$('#activationmodal').modal('show');
});

$(document).off('click','#activationmodal-reactivatebtn').on('click','#activationmodal-reactivatebtn',function(){
	var modal = '#'+$(this).closest('.modal').attr('id');
	var approver = $(modal+' .userapprover').val();
	if(approver==''){
		say('Please select an approver.');
	}
	else{
		$.post(server+'approver.php',{approverreactivation:'FKjshOs$ksnOO$93slamli#H@op!',approver:approver},function(data){
			if(data.trim()=='success'){
				$(modal).modal('hide');
				$(modal).on('hidden.bs.modal',function(){
					$('.content').load('System/approver.php',function(){
						say('Approver has been reactivated.');
					});
					$(modal).off('hidden.bs.modal');
				});
				
			}else{
				
				alert(data);
			}
		});
	}
});

var searchsource = '';
$(document).off('click','.searchuserbtn').on('click','.searchuserbtn',function(){
	searchsource = $(this).attr('from');
});
$(document).off('click','.userlistselect').on('click','.userlistselect',function(){
	var id, name, modal;
	id=$(this).attr('id');
	name=$(this).attr('name');
	modal = (searchsource=='add')?'#addapprovermodal':'#editapprovermodal';
	$(modal+' .userinput').attr('userid',id).val(name);
	$('#userlist').modal('hide');

});

$(document).off('click','.modal-savebtn').on('click','.modal-savebtn',function(){
		$(this).attr('disabled',true);
		var id, user, department, approvaltype, modal, action, stat, userinput;
		id='';
		stat='';
		modal = $(this).attr('id')=='approvercreate-savebtn'?'#addapprovermodal':'#editapprovermodal';
		action=(modal=='#addapprovermodal')?'add':'edit';

		user = $(modal+' .userinput').attr('userid');
		userinput = $(modal+' .userinput').val();
		department = $(modal+' .department').val();
		approvaltype = $(modal+' .approvaltype').val();

		if(userinput==''){
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a <b>User</b>.</div></div>");
			$(this).removeAttr('disabled');
		}
		else if(department==''){
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a <b>Department</b>.</div></div>");
			$(this).removeAttr('disabled');
		}
		else if(approvaltype==''){
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select an <b>Approval Type</b>.</div></div>");
			$(this).removeAttr('disabled');
		}
		else{
			if(action=='edit'){
				id=$(modal+' .userinput').attr('apprid');
				stat=$(modal+' .activeflag').val();
			}
			$('#loading-img').removeClass('hidden');
			$.post(server+'approver.php',{action:action,user:user,department:department,approvaltype:approvaltype,stat:stat,id:id},function(data){
					if(data.trim()=='success'){
						successful(modal,'System/approver.php');
						$(this).removeAttr('disabled');
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

$(document).off('click','.editapproverbtn').on('click','.editapproverbtn',function(){
	$('#editapprovermodal .approverid').val($(this).attr('id'));
	$('#editapprovermodal .userinput').val($(this).attr('name')).attr('userid',$(this).attr('userid')).attr('apprid',$(this).attr('id'));
	$('#editapprovermodal .department').val($(this).attr('department'));
	$('#editapprovermodal .approvaltype').val($(this).attr('approval'));
	$('#editapprovermodal .activeflag').val($(this).attr('stat'));

	$('.chosen-select').trigger('chosen:updated');
});

$(document).off('click','#deleteapproverbtn').on('click','#deleteapproverbtn',function(){
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
							$.post(server+'approver.php',{deleteselected:'',id:id},function(data){
								if(data.trim()!='success'){
									$('#loading-img').addClass('hidden');
									alert(data);
									$(this).removeAttr('disabled');
								}
								else{
									$('.content').load('System/approver.php',function(){
										setTimeout(function(){
											$('#loading-img').addClass('hidden');
										},300);
										button.removeAttr('disabled');
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
$(document).on('keyup','.lookupsearchinput',function(e){
			var text = $(this).val();
		 	if(e.which=='13'||e.keycode=='13'){
			    	$(this).closest('.lookupmodal').find('.userlistrow').addClass('hidden');
			    	$(this).closest('.lookupmodal').find('.userlistrow').removeClass('found');

			    	$(this).closest('.lookupmodal').find('.userlistrow').each(function(){
			    		if($(this).text().search(new RegExp(text,"i"))>=0) {
			    			$(this).removeClass('hidden').addClass('found');
			    		}
			    	}); 

			    	if($(this).closest('.lookupmodal').find('.found').length<1){
			    		$(this).closest('.lookupmodal').find('.noresult').removeClass('hidden');
			    	}
			    	else{
			    		$(this).closest('.lookupmodal').find('.noresult').addClass('hidden');
			    	}

			    	////////////////////////////////search table text//////////////////////
				    var foundrows = $(this).closest('.modal').find('.tablerow.found').length;
				    $(this).closest('.modal').find('.table-text-search').text(foundrows+' result(s) found');
				    //hide "showing a-z of n entries"//
				    $(this).closest('.modal').find('.table-text-div').addClass('hidden');
				    //
					//////////////////////////////////////////////////////////////////////

			    	if(text==""){
			    		$(this).closest('.lookupmodal').find('.userlistrow').addClass('hidden');
			    		var actv = $('.pages .active').attr('value');
			    		var too = actv*rowperpage;
			    		var fromm = too-rowperpage;
			    		$(this).closest('.lookupmodal').find('.tablerow').slice(fromm,too).removeClass('hidden');

			    		///////////////////////////////
			    		$(this).closest('.modal').find('.table-text-search').text('');
			    		//show showing
			    		 $(this).closest('.modal').find('.table-text-div').removeClass('hidden');
			    		 //
			    		///////////////////////////
			    	}
			}
			else if(text==''){
				$(this).closest('.lookupmodal').find('.noresult').addClass('hidden');
				$(this).closest('.lookupmodal').find('.userlistrow').addClass('hidden');
			    var actv = $('.pagination .active').attr('value');
			    var too = actv*rowperpage;
			    var fromm = too-rowperpage;
			    $(this).closest('.lookupmodal').find('.userlistrow').slice(fromm,too).removeClass('hidden');

			    ///////////////////////////////
		    		$(this).closest('.modal').find('.table-text-search').text('');
		    		//show showing
		    		 $(this).closest('.modal').find('.table-text-div').removeClass('hidden');
		    		 //
		    		///////////////////////////
			}	
});
