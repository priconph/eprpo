$(document).off('click','.poappr-pohistorytoggle').on('click','.poappr-pohistorytoggle', function(){

	window.open('Other/purchase-order-history-window.php?JndeLIe93o0jn49NDLiu3n='+$('.rcvnumber').attr('rcvnumber'));
});

$(document).off('click','#rcvapproval-cancelbtn').on('click','#rcvapproval-cancelbtn', function(){
	$('#loading-img').removeClass('hidden');
	$.getScript(pagescripts+'dashboard.js');
	$('.content').animate({scrollTop:0},200);
	$('.content').load("dashboard.php",function(){
		setTimeout(function(){
			$('#loading-img').addClass('hidden');
		},300);
	});
});	


$(document).off('click','#rcvapproval-approvebtn').on('click','#rcvapproval-approvebtn',function(){
	var rcv = $(this).attr('rcvnumber');
	$('#rcvapprovedmodal .rcvapprovedrcv').val(rcv);
});
$(document).off('click','#rcvapproval-rejectbtn').on('click','#rcvapproval-rejectbtn',function(){
	var rcv = $(this).attr('rcvnumber');
	$('#rejectionmodal .rcvrejectrcvno').val(rcv);
});

$(document).off('click','#rcvapprovedmodal-approvebtn:not([disabled])').on('click','#rcvapprovedmodal-approvebtn:not([disabled])', function(){
	var button = $(this),
		modal = '#'+$(this).closest('.modal').attr('id'),
		remarks = $(modal+' .rcvapprovedremarks').val(),
		rcvnumber = $(modal+' .rcvapprovedrcv').val();
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
			$.post(server+'receiving-approval.php',{sdurJdSamsn:rcvnumber,remarks:remarks},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						$('.content').animate({scrollTop:0},200);
						$('.content').load("dashboard.php",function(){
									$.getScript(pagescripts+'dashboard.js');
									setTimeout(function(){
										$('#loading-img').addClass('hidden');
										notify("You have <b>approved</b> a receiving request", "success","fa fa-check-circle");
									},300);
						});
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


$(document).off('click','#rcvapprovalmodal-rejectbtn:not([disabled])').on('click','#rcvapprovalmodal-rejectbtn:not([disabled])',function(){
	var button = $(this),
		modal = '#'+$(this).closest('.modal').attr('id'),
		reason = $('#rejectionmodal .rejectreason').val(),
		rcvnumber = $(modal+' .rcvrejectrcvno').val();
	button.attr('disabled',true);
	
	if(reason==''){
		$('#rejectionmodal .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a <b>valid reason</b> for rejection.</div></div>");
		button.removeAttr('disabled');
	}
	else{

		$.confirm({
			animation: 'bottom', 
			closeAnimation: 'top',
			animationSpeed: 1000,
			animationBounce:1,
			title: 'Confirmation',
			content: 'You are about to reject this form. Are you sure you want to continue?',
			confirmButton: 'Confirm',
			cancelButton: 'Cancel',	
			confirmButtonClass: 'btn-oceanblue', 
			cancelButtonClass: 'btn-royalblue', 
			theme: 'white', 

			confirm: function(){
				
					$('#loading-img').removeClass('hidden');
					$.post(server+'receiving-approval.php',{adIssacluck:rcvnumber,rejectreason:reason},function(data){
						if(data.trim()=='success'){
							$('.modal').modal('hide');
							$('.modal').on('hidden.bs.modal',function(){
								$.getScript(pagescripts+'dashboard.js');
								$('.content').animate({scrollTop:0},200);
								$('.content').load('dashboard.php',function(){
									setTimeout(function(){
										$('#loading-img').addClass('hidden');
									},300);
									notify("You have <b>rejected</b> a receiving request", "danger","fa fa-exclamation-triangle");
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

$(document).off('click','.viewdetails').on('click','.viewdetails', function(){
	var prnumber = $(this).attr('prnumber');
	window.open('Other/purchase-requisition-source-window.php?ksjcno0923jsbkyiu94hnsk='+prnumber);
});