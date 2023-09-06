$(document).off('click','.shipviamodal-savebtn').on('click','.shipviamodal-savebtn',function(){
		$(this).attr('disabled',true);
		var desc, modal, action;
		id='';

		modal = '#'+$(this).closest('.modal').attr('id');
		desc = $(modal+' .description').val()
		action=(modal=='#addshipviamodal')?'add':'edit';
		if(desc==''){
			$(modal+' .description').focus();
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a description.</div></div>");
			$(this).removeAttr('disabled');
		}
		else{
			if(action=='edit'){
				id = $(modal+' .shipviaid').val();
			}
			$('#loading-img').removeClass('hidden');
			$.post(server+'ship-via.php',{action:action,desc:desc,id:id},function(data){
					if(data.trim()=='success'){
						successful(modal,'Maintenance/ship-via.php');
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


$(document).off('click','.editshipviabtn').on('click','.editshipviabtn',function(){
	$(this).attr('disabled',true);
	var id, code, desc, days;
	id= $(this).attr('id');
	desc = $(this).attr('desc');
	$('#editshipviamodal .shipviaid').val(id);
	$('#editshipviamodal .description').val(desc);
	$(this).removeAttr('disabled');
});

$(document).off('click','#deleteshipviabtn').on('click','#deleteshipviabtn', function(){
		var button = $(this);
		button.attr('disabled',true);
		var id, response, deletedtotal;
		id = [];
		$('.itemcheckbox:checked').each(function(){
			id.push($(this).attr('rowid'));
		});
		deletedtotal = id.length;

		if(deletedtotal>0){
			$.confirm({
						animation: 'bottom', 
						closeAnimation: 'top',
						animationSpeed: 1000,
						animationBounce:1,
						title: 'Delete',
						content: 'Are you sure you want to continue?',
						confirmButton: 'Delete',
						cancelButton: 'Cancel',	
						confirmButtonClass: 'btn-oceanblue', 
						cancelButtonClass: 'btn-royalblue', 
						theme: 'white', 

						confirm: function(){ 
							
								$('#loading-img').removeClass('hidden');
								$.post(server+'ship-via.php',{deleteselected:'Kjbdoirnajeli93jw0j20',id:id},function(data){
									if(data.trim()!='success'){
										if(data.trim()=='1451'){
											var msg = "There are row(s) that cannot be deleted. Please see System Administrator."
											alert(msg);
											loadpage('.content','Maintenance/ship-via.php');
										}
										else{
											alert(data);
											loadpage('.content','Maintenance/ship-via.php');
										}
										button.removeAttr('disabled');
									}
									else{
										loadpage('.content','Maintenance/ship-via.php');
									}
								});
							
							button.removeAttr('disabled');
							//notify("<b>"+deletedtotal+" rows(s)</b> deleted", "success","");
						},
						cancel:function(){ 
							button.removeAttr('disabled');
						}
						
			});	
		}
		else{
			//notify("Please select rows to be deleted", "danger","");
			button.removeAttr('disabled');
		}

});

