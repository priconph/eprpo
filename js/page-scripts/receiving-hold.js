$(document).off('click','.viewreceivingpo').on('click','.viewreceivingpo',function(){
	$('#loading-img').removeClass('hidden');

 	var ponumber = $(this).attr('ponum');
 	var supplier = $(this).closest('tr').find('td:eq(2)').text();
	// alert(ponumber +' '+ supplier);

	$('.content').load("Transaction/rcv-transaction-po.php?JKeao4ihslk="+encodeURIComponent(supplier)+"&skrbOIrhlk="+encodeURIComponent(ponumber),function(){
						setTimeout(function(){
							$('#loading-img').addClass('hidden');
						},300);
					});
	$.getScript(pagescripts+'rcv-transaction-po.js');
	$('.content').animate({scrollTop:0},200);
	
});

$(document).off('click', '.viewreceivingpo_omrs').on('click', '.viewreceivingpo_omrs', function () {
	$('#loading-img').removeClass('hidden');

	var ponumber = $(this).attr('ponum_omrs');
	var supplier = $(this).closest('tr').find('td:eq(2)').text();
	console.log(ponumber +' '+ supplier);

	$('.content').load("Transaction/rcv-transaction-po.php?JKeao4ihslk=" + encodeURIComponent(supplier) + "&skrbOIrhlk=" + encodeURIComponent(ponumber), function () {
		setTimeout(function () {
			$('#loading-img').addClass('hidden');
		}, 300);
	});
	$.getScript(pagescripts + 'rcv-transaction-po.js');
	$('.content').animate({ scrollTop: 0 }, 200);

});

$(document).off('click', '.cancelholdbtn').on('click', '.cancelholdbtn', function () {
	// $(document).off('click', '.pageContent .cancelholdbtn').on('click', '.pageContent .cancelholdbtn', function () { //old
	var modal = "#holdcancellationmodal",
		ponumber = $(this).attr('ponum');
	// alert(ponumber);

	$(modal+' .holdcancellationmodal-ponumber').val(ponumber);
	$(modal+' #holdcancellationmodal-ponumber').val(ponumber);


});

$(document).off('click', '.cancelholdbtn_omrs').on('click', '.cancelholdbtn_omrs', function () {
	var modal = "#holdcancellationmodal",
		ponumber_omrs = $(this).attr('ponum_omrs');
	// alert(ponumber_omrs);

	$(modal + ' .holdcancellationmodal-ponumber').val(ponumber_omrs);
	$(modal + ' #holdcancellationmodal-ponumber').val(ponumber_omrs);

});

$(document).off('click','#holdcancellationmodal-confirmbtn:not(".disabled")').on('click','#holdcancellationmodal-confirmbtn:not(".disabled")', function(){
	var modal = "#holdcancellationmodal",
		ponumber = $(modal+' #holdcancellationmodal-ponumber').val(),
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
			title: 'Hold Suppliers Invoice Cancellation',
			content: 'This will cancel hold suppliers invoice - <b>'+ponumber+'</b>.<br> Do you want to continue?',
			confirmButton: 'Yes',
			cancelButton: 'No',	
			confirmButtonClass: 'btn-oceanblue', 
			cancelButtonClass: 'btn-royalblue', 
			theme: 'white', 

			confirm: function(){
				$('#loading-img').removeClass('hidden');
				$.post(server+'receiving-hold.php',{HoldCancellation:'Fkjhs4lkh4s!o3Iah$Uh#O@i2aoa4ho@',ponumber:ponumber,reason:reason},function(data){

					if(data.trim()=='success'){
						$(modal).modal('hide');
						$(modal).on('hidden.bs.modal',function(){	
							$(modal).off('hidden.bs.modal');	
							$('.content').load('Transaction/receiving-hold.php',function(){
								$.getScript(pagescripts+'receiving-hold.js');
								setTimeout(function(){
									$('#loading-img').addClass('hidden');
									say('Hold Suppliers Invoice with '+ponumber+' has been cancelled.');
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



