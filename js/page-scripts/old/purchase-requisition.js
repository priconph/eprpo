//FOLLOW-UP APPROVER//
$(document).off('click','#prtable .notifyapproverbtn').on('click','#prtable .notifyapproverbtn', function(){
	$('#followup-prnumber').val($(this).attr('prnum'));
});
$(document).off('click','#followup-confirmbtn').on('click','#followup-confirmbtn', function(){
	var btn = $(this);
		btn.attr('disabled',true);
	var modal = $(this).closest('.modal');
	var msg = $(modal).find('.followupmessage').val();
	var prnumber = $(modal).find('#followup-prnumber').val();
	if(msg==''){
		$(modal).find('.errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a <b>message.</b></div></div>");
		btn.removeAttr('disabled');
	}
	else{
		$.confirm({
					animation: 'bottom', 
					closeAnimation: 'top',
					animationSpeed: 1000,
					animationBounce:1,
					title: 'Confirmation',
					content: 'Do you want to continue?',
					confirmButton: 'Confirm',
					cancelButton: 'Cancel',	
					confirmButtonClass: 'btn-oceanblue', 
					cancelButtonClass: 'btn-royalblue', 
					theme: 'white', 

					confirm: function(){
						
						
							$('#loading-img').removeClass('hidden');
							$.post(server+'purchase-requisition.php',{followupOnApproval:'DHlei#N2ak2oiuha3b@K!JoEh',msg:msg, prnum:prnumber},function(data){
								if(data.trim()=='success'){
									$('.modal').modal('hide');
									$('.modal').on('hidden.bs.modal',function(){
										
										
										$('.content').load('Transaction/purchase-requisition.php',function(){
											$.getScript(pagescripts+'purchase-requisition.js');
											setTimeout(function(){
												$('#loading-img').addClass('hidden');
												notify("Follow-up request sent", "danger","fa fa-exclamation-triangle");
											},400);
										});
									
									});
								}
								else{
									$('#loading-img').addClass('hidden');
									alert(data);
									btn.removeAttr('disabled');
								}
							});
						
					},
					cancel:function(){ 
						btn.removeAttr('disabled');
					}

		});
	
	}

});

//ADD NEW PR
$(document).off('click','#addpr').on('click','#addpr', function(){
	$('#loading-img').removeClass('hidden');
	$('.content').load('Transaction/pr-transaction.php',function(){
		$.getScript(pagescripts+'pr-transaction.js');
		setTimeout(function(){
			$('#loading-img').addClass('hidden');
		},300);
	});
	
});

//DELETE PR



//GETDETAILS PR
$(document).off('click','#prtable .clickable').on('click','#prtable .clickable', function(){
	$('#prblowup').modal('show');
	var prnumber, row;
	row=$(this).closest('.tablerow');
	prnumber = $(this).closest('.tablerow').attr('id');
	$('#cartitems').load('Transaction/purchase-requisition-items.php?LkNoNslOeiHdo%pdD='+prnumber);
	$('#attachmentstable').load('Transaction/purchase-requisition-attachments.php?KdkhDNlROiu%pdD='+prnumber);
	$('#allocationsummary').load('Transaction/purchase-requisition-allocation.php?Dkrojfknnkeh%pdD='+prnumber);

	$('#prnumber').html(row.attr('id'));
	$('#reqstat').html(row.attr('stat'));
	$('#dept').html(row.attr('dept'));
	$('#requisitioner').html(row.attr('requisitioner'));
	$('#justification').html(row.attr('remarks'));
	$('#reqcurrency').html(row.attr('currency'));
	$('#prblowupcurrencysymbol').html('<b>'+row.attr('currency')+'</b>');
	$('#revprnumber').html(row.attr('revnumber'));
	$('#reqtype').removeAttr('class');
	$('#reasonforrejection').html(row.attr('rejectionreason'));
	$('#rejectedby').val(row.attr('rejectedby'));
	$('#prnotes').html(row.attr('prnotes'));
	$('#cancellationreason').html(row.attr('reasonforcancellation'));
	if(row.attr('type').trim()=='REGULAR'){
		$('#reqtype').addClass('label').addClass('label-primary').html(row.attr('type'));
	}
	else{
		$('#reqtype').addClass('label').addClass('label-danger').html(row.attr('type'));
	}
	
	$('#reqdate').html(row.attr('reqdate'));
	$('#reqdateneeded').html(row.attr('dateneeded'));
	$('#lastmodified').html(row.attr('lastmodified'));
	$('#grandtotal').val(row.attr('prtotal'));
	$('#datecanceled').html(row.attr('canceleddate'));
	$('#pr-approverspane .lastapprovedby').text('').html(row.attr('lastapprovedby'))
	$('#pr-approverspane .sectionhead1').html(row.attr('sh1'));
	$('#pr-approverspane .sectionhead2').html(row.attr('sh2'));
	$('#pr-approverspane .genman').html(row.attr('gm'));
	$('#pr-approverspane .asstvp').html(row.attr('avp'));

	$('#pr-approverspane .confhead').html(row.attr('conf'));
	$('#pr-approverspane .srvp').html(row.attr('svp'));
	$('#pr-approverspane .president').html(row.attr('pr'));

	var forrevision = row.attr('forrevision'),
		withdiscrepancy = row.attr('withdiscrepancy');

	if(withdiscrepancy==1&&forrevision>0){
		$('#pr-discrepancytab').removeClass('hidden');
		$('#pr-discrepancy').removeClass('hidden').load('Other/purchase-requisition-discrepancy-details.php?Kbdkldriiopirn389yd9830='+prnumber);
	}
	else{
		$('#pr-discrepancytab').addClass('hidden').removeClass('active');
		$('#pr-discrepancy').addClass('hidden').removeClass('active');
		$('#pr-geninfotab').addClass('active').siblings().removeClass('active');
		$('#pr-geninfo').addClass('active').siblings().removeClass('active');
	}
	
	if(forrevision>0&&row.attr('stat')=='FOR REVISION'){
		$('#pr-revisionremarkstab').removeClass('hidden');
		$('#pr-revisionremarks').removeClass('hidden').load('Other/purchase-requisition-revision-remarks.php?Kbdkldriiopirn389yd9830='+prnumber);
	}
	else{
		$('#pr-revisionremarkstab').addClass('hidden').removeClass('active');
		$('#pr-revisionremarks').addClass('hidden').removeClass('active');
		$('#pr-geninfotab').addClass('active').siblings().removeClass('active');
		$('#pr-geninfo').addClass('active').siblings().removeClass('active');
	}

	if(row.attr('stat')=='REJECTED'){
		$('#pr-rejectionremarkstab').removeClass('hidden');
		$('#pr-rejectionremarks').removeClass('hidden');
	}
	else{
		$('#pr-rejectionremarkstab').addClass('hidden').removeClass('active');
		$('#pr-rejectionremarks').addClass('hidden').removeClass('active');
		$('#geninfotab').addClass('active').siblings().removeClass('active');
		$('#po-geninfo').addClass('active').siblings().removeClass('active');
	}


});


$(document).off('click','.editpurchaserequisition').on('click','.editpurchaserequisition', function(){
	var prnumber = $(this).attr('prnum');
	$('#loading-img').removeClass('hidden');
	$('.content').load('Transaction/pr-transaction-edit.php?KdUeobMFHDpo='+prnumber,function(){
		$.getScript(pagescripts+'pr-transaction-edit.js');
		setTimeout(function(){
			$('#loading-img').addClass('hidden');
		},300);
	});
	
});

$(document).off('click','#deletepr').on('click','#deletepr', function(){
	$.post(server+'purchase-requisition.php',{sdfsdfsd:''},function(data){
		alert(data);
	});
});



$(document).off('click','.pageContent .cancelprbtn').on('click','.pageContent .cancelprbtn', function(){
	var modal = "#prcancellationmodal",
		prnumber = $(this).attr('prnum');


	$(modal+' .prcancellationmodal-prnumber').val(prnumber);
	$(modal+' #prcancellationmodal-prnumber').val(prnumber);


});

$(document).off('click','#prcancellationmodal-confirmbtn:not(".disabled")').on('click','#prcancellationmodal-confirmbtn:not(".disabled")', function(){
	var modal = "#prcancellationmodal",
		prnumber = $(modal+' #prcancellationmodal-prnumber').val(),
		reason = $(modal+' .reasonofcancellation').val();
		button = $(this);
	button.addClass('disabled');
	if(reason==''){
		say("Please provide reason for cancellation.");
		button.removeClass('disabled');

	}
	else{
		$.confirm({
			animation: 'bottom', 
			closeAnimation: 'top',
			animationSpeed: 1000,
			animationBounce:1,
			title: 'PR Cancellation',
			content: 'This will cancel <b>'+prnumber+'</b>.<br> Do you want to continue?',
			confirmButton: 'Yes',
			cancelButton: 'No',	
			confirmButtonClass: 'btn-oceanblue', 
			cancelButtonClass: 'btn-royalblue', 
			theme: 'white', 

			confirm: function(){
				$('#loading-img').removeClass('hidden');
				$.post(server+'purchase-requisition.php',{prCancellationNoPo:'Fkjhs4lkh4s!o3Iah$Uh#O@i2aoa4ho@',prnumber:prnumber,reason:reason},function(data){

					if(data.trim()=='success'){
						$(modal).modal('hide');
						$(modal).on('hidden.bs.modal',function(){	
							$(modal).off('hidden.bs.modal');	
							$('.content').load('Transaction/purchase-requisition.php',function(){
								$.getScript(pagescripts+'purchase-requisition.js');
								setTimeout(function(){
									$('#loading-img').addClass('hidden');
									say(prnumber+' has been cancelled.');
								},400);
							});
									
						});
					}
					else{
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
						alert(data);
					}
				});	
			},
			cancel:function(){ 
				button.removeClass('disabled');
			}

		});
	}


});



