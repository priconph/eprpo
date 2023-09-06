$(document).off('click','#pr-approval-approvebtn').on('click','#pr-approval-approvebtn',function(){
	var pr = $(this).attr('prnumber');
	$('#prapprovedmodal .prapprovedprnumber').val(pr);
});

$(document).off('click','#prapprovedmodal-approvebtn:not([disabled])').on('click','#prapprovedmodal-approvebtn:not([disabled])',function(){
	var button = $(this),
		modal = '#'+$(this).closest('.modal').attr('id'),
		remarks = $(modal+' .prapprovedremarks').val(),
		pr = $(modal+' .prapprovedprnumber').val();
	button.attr('disabled',true);

	$.confirm({
				animation: 'bottom', 
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce:1,
				title: 'Confirmation',
				content: 'You are about to approve this form. Are you sure you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',	
				confirmButtonClass: 'btn-oceanblue', 
				cancelButtonClass: 'btn-royalblue', 
				theme: 'white', 

				confirm: function(){
					$('#loading-img').removeClass('hidden');
					$.post(server+'purchase-requisition-approval.php',{approveKUrywBoedlkn:'fOskenDLfhir',prnumber:pr,remarks:remarks},function(data){
						data = data.trim();
						var fetched = data.split('@#$');
						var status = fetched[1];
						var nextflag = fetched[2];
						var reqnum = fetched[3];
						var emailto = fetched[4];
						var emailtoname = fetched[5];

						if(fetched[0]=='success'){

							
							$(modal).modal('hide');
							$(modal).on('hidden.bs.modal',function(){
								$('.content').load("dashboard.php",function(){
									setTimeout(function(){
										$('#loading-img').addClass('hidden');
										notify("You have <b>approved</b> a purchase requisition", "success","fa fa-check-circle");
									},400);
									$('.content').animate({scrollTop:0},200);
									
									

								});
								
								setTimeout(function(){
									$.post(server+'purchase-requisition-approval.php',{emailnotification:'Nsl3Ep203N0amlOepan10@#0',status:status,nextflag:nextflag,reqnum:reqnum,emailto:emailto,emailtoname:emailtoname},function(data){
										if(data.trim()!=1||data.trim()!=true){
											alert(data);
										}
									});
								},1000);
								$(modal).off('hidden.bs.modal');
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
$(document).off('click','.prappr-prhistorytoggle').on('click','.prappr-prhistorytoggle', function(){
			window.open('Other/purchase-requisition-history-window.php?JndeLIe93o0jn49NDLiu3n='+$('.reqnum').attr('prnumber'));
});

$(document).off('click','#pr-approval-cancelbtn').on('click','#pr-approval-cancelbtn',function(){
	
	$('#loading-img').removeClass('hidden');
	$('.content').load("dashboard.php",function(){
		$.getScript(pagescripts+'dashboard.js');
		setTimeout(function(){
			$('#loading-img').addClass('hidden');
		},300);
		$('.content').animate({scrollTop:0},200);
	});
	
	
});	

$(document).off('click','#prmodal-rejectbtn:not([disabled])').on('click','#prmodal-rejectbtn:not([disabled])',function(){
	var button = $(this);
	button.attr('disabled',true);
	var reason, prnumber;
	prnumber = $('.reqnum').attr('prnumber');
	reason = $('#rejectionmodal .rejectreason').val();
	if(reason==''){
		$('#rejectionmodal .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide valid <b>reason for rejection.</b></div></div>");
		button.removeAttr('disabled');
	}
	else{
		$.confirm({
					animation: 'bottom', 
					closeAnimation: 'top',
					animationSpeed: 1000,
					animationBounce:1,
					title: 'Confirmation',
					content: 'You are about to reject this form. Do you wish to continue?',
					confirmButton: 'Confirm',
					cancelButton: 'Cancel',	
					confirmButtonClass: 'btn-oceanblue', 
					cancelButtonClass: 'btn-royalblue', 
					theme: 'white', 

					confirm: function(){
						
						
							$('#loading-img').removeClass('hidden');
							$.post(server+'purchase-requisition-approval.php',{rKdneJcTlsj:'KneLdkeoirR',rejectreason:reason, prnum:prnumber},function(data){
								if(data.trim()=='success'){
									$('.modal').modal('hide');
									$('.modal').on('hidden.bs.modal',function(){
										
										
										$('.content').load('dashboard.php',function(){
											$.getScript(pagescripts+'dashboard.js');
											setTimeout(function(){
												$('#loading-img').addClass('hidden');
												notify("You have <b>rejected</b> a purchase requisition", "danger","fa fa-exclamation-triangle");
											},400);
											
											$('.content').animate({scrollTop:0},200);
										});
									
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
	}



});
