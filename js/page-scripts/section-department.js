$(document).off('click','.sectiondepartment-modal-savebtn').on('click','.sectiondepartment-modal-savebtn',function(){
		$(this).attr('disabled',true);
		var id, deptcode, deptname, modal, action,button,nextnum='',status=0;
		id='';
		button = $(this);
		modal = $(this).attr('id')=='departmentcreate-savebtn'?'#adddepartmentmodal':'#editdepartmentmodal';
		action=(modal=='#adddepartmentmodal')?'add':'edit';
		deptname = $(modal+' .deptname').val();
		deptcode = $(modal+' .deptcode').val().replace(/\s/g,'');
		status   = $(modal+' .status').val();
		
		if(deptcode==''){
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please enter <b>Department Code</b>.</div></div>");
			$(this).removeAttr('disabled');
		}
		else if(deptname==''){
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please specify a <b>Department Name</b>.</div></div>");
			$(this).removeAttr('disabled');
		}
		else if(checkDeptName(deptname)==false){
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span><b>Invalid Department Name.</b><br> It should be atleast 2 characters long.<br>It should only contain alphabetical and numerical characters.</div></div>");
			$(this).removeAttr('disabled');
		}
		else{
			if(action=='edit'){
				id = $(modal+' .deptid').val();
				nextnum = $(modal+' .nextnumberseries').val().trim();
			}

			if((checkInt(nextnum)==false||nextnum=='')&&action=='edit'){
				$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please enter valid number series. It must be an integer.</div></div>");
				$(this).removeAttr('disabled');
			}
			else{
				$('#loading-img').removeClass('hidden');
				$.post(server+'section-department.php',{action:action,code:deptcode,deptname:deptname,nextnum:nextnum,id:id,status:status},function(data){
						if(data.trim()=='success'){
							successful(modal,'Maintenance/section-department.php');
							$('#loading-img').addClass('hidden');
						}
						else{
							$('#loading-img').addClass('hidden');
							$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span><b>Department Code</b> already in use.</div></div>");
							button.removeAttr('disabled');
			
						}
				});
			}
		}
});

$(document).off('click','.editdepartmentbtn').on('click','.editdepartmentbtn',function(){
	var id, deptname,code,nextnum,status;
	id=$(this).attr('id');
	code = $(this).attr('code');
	deptname = $(this).attr('name');
	nextnum = $(this).attr('nextnum');
	status = $(this).attr('status');
	$('#editdepartmentmodal .deptid').val(id);
	$('#editdepartmentmodal .deptname').val(deptname);
	$('#editdepartmentmodal .deptcode').val(code);
	$('#editdepartmentmodal .nextnumberseries').val(nextnum);
	$('#editdepartmentmodal .status').val(status);
});

/***** DELETE DEPARTMENT BTN ****/
$(document).off('click','#deletesectionbtn').on('click','#deletesectionbtn',function(){
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
						$.post(server+'section-department.php',{deleteselected:'',id:id},function(data){
							if(data.trim()!='success'){
								$('#loading-img').addClass('hidden');
								alert(data);
								button.removeAttr('disabled');
							}
							else{
								$.getScript(pagescripts+'section-department.js');
								$('.content').load('Maintenance/section-department.php',function(){
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
