$(document).off('click','.poappr-pohistorytoggle').on('click','.poappr-pohistorytoggle', function(){

	window.open('Other/purchase-order-history-window.php?JndeLIe93o0jn49NDLiu3n='+$('.ponumber').attr('ponumber'));
});

$(document).off('click','#poapproval-cancelbtn').on('click','#poapproval-cancelbtn', function(){
	$('#loading-img').removeClass('hidden');
	$.getScript(pagescripts+'dashboard.js');
	$('.content').animate({scrollTop:0},200);
	$('.content').load("dashboard.php",function(){
		setTimeout(function(){
			$('#loading-img').addClass('hidden');
		},300);
	});
});	


$(document).off('click','#poapproval-approvebtn').on('click','#poapproval-approvebtn',function(){
	var po = $(this).attr('ponumber');
	var poqty = $(this).attr('poqty');
	var prqty = $(this).attr('prqty');
	$('#poapprovedmodal .poapprovedponumber').val(po);
	$('#poapprovedmodal .poqty').val(poqty);
	$('#poapprovedmodal .prqty').val(prqty);
});

$(document).off('click','#poapprovedmodal-approvebtn:not([disabled])').on('click','#poapprovedmodal-approvebtn:not([disabled])', function(){
	var button = $(this),
		modal = '#'+$(this).closest('.modal').attr('id'),
		remarks = $(modal+' .poapprovedremarks').val(),
		ponumber = $(modal+' .poapprovedponumber').val();
		po_qty = $(modal+' .poqty').val();
		pr_qty = $(modal+' .prqty').val();
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

			/* VALIDATION IF PR QTY IS NOT TALLY SA PO QTY*/
			// start else
			// if (pr_qty > po_qty){
			// 	alert('Somethings Wrong, PR Qty ('+pr_qty+') is not tally on PO Qty ('+po_qty+')!');
			// 	$('#loading-img').addClass('hidden');
			// 	$(modal).modal('hide');
			// 	button.removeAttr('disabled');
			// }else{

				$.post(server+'purchase-order-approval.php',{sdfnrworthdf:ponumber,remarks:remarks},function(data){
				
					if(data.trim()=='success'){
						$(modal).modal('hide');
						$(modal).on('hidden.bs.modal',function(){
							$('.content').animate({scrollTop:0},200);
							$('.content').load("dashboard.php",function(){
										$.getScript(pagescripts+'dashboard.js');
										setTimeout(function(){
											$('#loading-img').addClass('hidden');
											notify("You have <b>approved</b> a purchase order", "success","fa fa-check-circle");
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

			// } // end else
			

		},
		cancel:function(){ 
			button.removeAttr('disabled');
		}

	});

});	


$(document).off('click','#poapprovalmodal-rejectbtn:not([disabled])').on('click','#poapprovalmodal-rejectbtn:not([disabled])',function(){
	var button = $(this);
	button.attr('disabled',true);
	var reason, ponumber;
	ponumber = $('.ponumber').attr('ponumber');
	reason = $('#rejectionmodal .rejectreason').val();
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
					$.post(server+'purchase-order-approval.php',{rKdneJcTlsj:'KneLdkeoirR',rejectreason:reason, ponum:ponumber},function(data){
						if(data.trim()=='success'){
							$('.modal').modal('hide');
							$('.modal').on('hidden.bs.modal',function(){
								$.getScript(pagescripts+'dashboard.js');
								$('.content').animate({scrollTop:0},200);
								$('.content').load('dashboard.php',function(){
									setTimeout(function(){
										$('#loading-img').addClass('hidden');
									},300);
									notify("You have <b>rejected</b> a purchase order", "danger","fa fa-exclamation-triangle");
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