$(document).off('click','.modemodal-savebtn').on('click','.modemodal-savebtn',function(){
		var button = $(this);
		button.attr('disabled',true);

		var modal = '#'+$(this).closest('.modal').attr('id'),
			shipvia = $(modal+' .shipvia').val(),
			modeofshipment = $(modal+' .shipmentmode').val(),
			id='',
			action=(modal=='#addmodeofshipmentmodal')?'add':'edit';

		if(shipvia==''){
			$(modal+' .shipvia').focus();
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span><b>Ship Via</b> is a required field</div></div>");
			button.removeAttr('disabled');
		}
		else if(modeofshipment==''){
			$(modal+' .modeofshipment').focus();
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span><b>Mode of Shipment</b> is a required field</div></div>");
			button.removeAttr('disabled');
		}
		else{
			if(action=='edit'){
				id = $(modal+' .modeofshipmentid').val();
			}
			$('#loading-img').removeClass('hidden');
			$.post(server+'mode-of-shipment.php',{action:action,shipvia:shipvia,modeofshipment:modeofshipment,id:id},function(data){
					if(data.trim()=='success'){
						successful(modal,'Maintenance/mode-of-shipment.php');
						$('#loading-img').addClass('hidden');
					}
					else{
						$('#loading-img').addClass('hidden');
						alert(data);
						button.removeAttr('disabled');
					}
			});
		}
});

$(document).off('click','.editmodeofshipmentbtn').on('click','.editmodeofshipmentbtn',function(){
	$(this).attr('disabled',true);
	var modal = '#editmodeofshipmentmodal',
		id= $(this).attr('id'),
	    mode = $(this).attr('mode'),
	    via=$(this).attr('via');
	$(modal+' .modeofshipmentid').val(id);
	$(modal+' .shipvia').val(via);
	$(modal+' .shipmentmode').val(mode);
	$(this).removeAttr('disabled');
});

$(document).off('click','#deletemodeofshipmentbtn').on('click','#deletemodeofshipmentbtn', function(){
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
								$.post(server+'mode-of-shipment.php',{deleteselected:'Kjbdoirnajeli93jw0j20',id:id},function(data){
									if(data.trim()!='success'){
										if(data.trim()=='1451'){
											var msg = "There are row(s) that cannot be deleted. Please see System Administrator."
											alert(msg);
											loadpage('.content','Maintenance/mode-of-shipment.php');
										}
										else{
											alert(data);
											loadpage('.content','Maintenance/mode-of-shipment.php');
										}
										button.removeAttr('disabled');
									}
									else{
										loadpage('.content','Maintenance/mode-of-shipment.php');
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
