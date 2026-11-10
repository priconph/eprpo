$(document).off('click','.potrans-prviewdetails').on('click','.potrans-prviewdetails', function(){
	var prnumber = $(this).attr('prnumber');
	window.open('Other/purchase-requisition-source-window.php?ksjcno0923jsbkyiu94hnsk='+prnumber);
});

$(document).off('click','#pocreate-cancelbtn').on('click','#pocreate-cancelbtn', function(){
	$('.content').animate({scrollTop:0},200);
	$('#loading-img').removeClass('hidden');
	$('.content').load('Transaction/purchase-order.php',function(){
		setTimeout(function(){
			$.getScript(pagescripts+'purchase-order.js');
			$('#loading-img').addClass('hidden');
		},300);
	});
		
});

$(document).off('click','.pobuttons:not(".disabled")').on('click','.pobuttons:not(".disabled")', function(){
	var button=$(this);
	button.addClass('disabled');
	var crntdateneeded, dateneeded, prnumber, president, ponumber, attn, supplieraddr, term, shipvia, deliverydate, remarks, poapprover, action, grandtotal, currency, response=false, modeofshipment;
	president = $('.president').val();
	prnumber = $('.refreqnum').val();
	dateneeded = $('#dateneeded').val();
	ponumber = $('.ponumber').val();
	supplieraddr = $('.supplieraddress').val();
	term = $('.paymentterms').val();
	shipvia = $('.shipvia').val();
	//deliverydate = $('.deliverydate').val();
	remarks = $('.remarks').val();
	modeofshipment = $('.modeofshipment').val();
	attn = $('.attn').val();
	crntdateneeded = $('#crntdateneeded').val();

	grandtotal=$('#pograndtotal').val().replace(/,/g,'');
	currency = $('.pocurrency').val();
	if (currency == '1') {
		curr = 'JPY';
	} else if (currency == '2') {
		curr = 'PHP';
	} else if (currency == '3') {
		curr = 'USD';
	} else {
		curr = '---';
	}
	facshipto = $('.facshipto').val();

	if($(this).attr('id')=='pocreate-savebtn'){
		action="SAVE";
	}
	else if($(this).attr('id')=='pocreate-submitbtn'){
		action='SUBMIT';
	}

	if(action!='SAVE'&&term=='NULL'){
		$('.pageContent .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select <b>term of payment.</b></div></div>");
		$('.content').animate({scrollTop:0},200);
		button.removeClass('disabled');
	}
	else if(action!='SAVE'&&shipvia==''){
		$('.pageContent .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select <b>ship via.</b></div></div>");
		$('.content').animate({scrollTop:0},200);
		button.removeClass('disabled');
	}
	else if(action!='SAVE'&&modeofshipment=='NULL'){
		$('.pageContent .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select <b>mode of shipment.</b></div></div>");
		$('.content').animate({scrollTop:0},200);
		button.removeClass('disabled');
	}
	/*else if(action!='SAVE'&&deliverydate==''){
		$('.pageContent .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide <b>expected delivery date.</b></div></div>");
		$('.content').animate({scrollTop:0},200);
		$(this).removeAttr('disabled');
	}*/
	else{
		if(action=='SUBMIT'){
			/* 10222024 Updated */
			if (currency == '1' && grandtotal >= 228153 || currency == '2' && grandtotal >= 100000 || currency == '3' && grandtotal >= 2500) {//JPY
				if (president == null || president == '') {
					$('.pageContent .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>The total amount is <b>" + curr + " " + grandtotal + " </b> and the <b>President</b> was not identified in the PR. This is subject for revision. Thank you!</div></div>");
					$('.content').animate({ scrollTop: 0 }, 200);
					button.removeClass('disabled');
				} else {
					$.confirm({
						animation: 'bottom',
						closeAnimation: 'top',
						animationSpeed: 1000,
						animationBounce: 1,
						title: 'Confirmation',
						content: 'Are you sure you want to continue?',
						confirmButton: 'Confirm',
						cancelButton: 'Cancel',
						confirmButtonClass: 'btn-oceanblue',
						cancelButtonClass: 'btn-royalblue',
						theme: 'white',

						confirm: function () {
							$('#loading-img').removeClass('hidden');
							$.post(server + 'po-transaction.php', { kerbfdsoirp: action, attn: attn, prnumber: prnumber, crntdateneeded: crntdateneeded, dateneeded: dateneeded, grandtotal: grandtotal, mode: modeofshipment, currency: currency, ponumber: ponumber, supplieraddr: supplieraddr, term: term, shipvia: shipvia, remarks: remarks }, function (data) {
								var dt = data.trim().split('#@$');
								var ponumber = dt[1];
								if (dt[0] == 'success') {
									$('.content').animate({ scrollTop: 0 }, 200);
									$('.content').load('Transaction/purchase-order.php', function () {
										$.getScript(pagescripts + 'purchase-order.js');
										setTimeout(function () {
											$('#loading-img').addClass('hidden');
										}, 300);
										notify("Purchase Order has been <b>submitted</b>", "success", "fa fa-check-circle");

										setTimeout(function(){
												$.post(server+'po-transaction.php',{emailnotification:'Nsl3Ep203N0amlOepan10@#0',ponumber:ponumber},function(data){
														if(data.trim()!=1||data.trim()!=true){
															alert(data);
														}
												});
										},500);
									});

								}
								else {
									$('#loading-img').addClass('hidden');
									alert(data);
									button.removeClass('disabled');
								}
							});
						},
						cancel: function () {
							button.removeClass('disabled');
						}

					});
				}
			} else {
				$.confirm({
					animation: 'bottom',
					closeAnimation: 'top',
					animationSpeed: 1000,
					animationBounce: 1,
					title: 'Confirmation',
					content: 'Are you sure you want to continue?',
					confirmButton: 'Confirm',
					cancelButton: 'Cancel',
					confirmButtonClass: 'btn-oceanblue',
					cancelButtonClass: 'btn-royalblue',
					theme: 'white',

					confirm: function () {
						$('#loading-img').removeClass('hidden');
						$.post(server + 'po-transaction.php', { kerbfdsoirp: action, attn: attn, prnumber: prnumber, crntdateneeded: crntdateneeded, dateneeded: dateneeded, grandtotal: grandtotal, mode: modeofshipment, currency: currency, ponumber: ponumber, supplieraddr: supplieraddr, term: term, shipvia: shipvia, remarks: remarks }, function (data) {
							var dt = data.trim().split('#@$');
							var ponumber = dt[1];
							if (dt[0] == 'success') {
								$('.content').animate({ scrollTop: 0 }, 200);
								$('.content').load('Transaction/purchase-order.php', function () {
									$.getScript(pagescripts + 'purchase-order.js');
									setTimeout(function () {
										$('#loading-img').addClass('hidden');
									}, 300);
									notify("Purchase Order has been <b>submitted</b>", "success", "fa fa-check-circle");

									setTimeout(function(){
											$.post(server+'po-transaction.php',{emailnotification:'Nsl3Ep203N0amlOepan10@#0',ponumber:ponumber},function(data){
													if(data.trim()!=1||data.trim()!=true){
														alert(data);
													}
											});
									},500);
								});

							}
							else {
								$('#loading-img').addClass('hidden');
								alert(data);
								button.removeClass('disabled');
							}
						});
					},
					cancel: function () {
						button.removeClass('disabled');
					}

				});
			}


			// $.confirm({
			// 		animation: 'bottom', 
			// 		closeAnimation: 'top',
			// 		animationSpeed: 1000,
			// 		animationBounce:1,
			// 		title: 'Confirmation',
			// 		content: 'Are you sure you want to continue?',
			// 		confirmButton: 'Confirm',
			// 		cancelButton: 'Cancel',	
			// 		confirmButtonClass: 'btn-oceanblue', 
			// 		cancelButtonClass: 'btn-royalblue', 
			// 		theme: 'white', 

			// 		confirm: function(){
			// 				$('#loading-img').removeClass('hidden');
			// 				$.post(server+'po-transaction.php',{kerbfdsoirp:action,attn:attn,prnumber:prnumber,crntdateneeded:crntdateneeded,dateneeded:dateneeded,grandtotal:grandtotal,mode:modeofshipment,currency:currency,ponumber:ponumber,supplieraddr:supplieraddr,term:term,shipvia:shipvia,remarks:remarks},function(data){
			// 						var dt = data.trim().split('#@$');
			// 						var ponumber = dt[1];
			// 						if(dt[0]=='success'){
			// 							$('.content').animate({scrollTop:0},200);
			// 							$('.content').load('Transaction/purchase-order.php',function(){
			// 									$.getScript(pagescripts+'purchase-order.js');
			// 									setTimeout(function(){
			// 										$('#loading-img').addClass('hidden');
			// 									},300);
			// 									notify("Purchase Order has been <b>submitted</b>", "success","fa fa-check-circle");
												
			// 									/*setTimeout(function(){
			// 											$.post(server+'po-transaction.php',{emailnotification:'Nsl3Ep203N0amlOepan10@#0',ponumber:ponumber},function(data){
			// 													if(data.trim()!=1||data.trim()!=true){
			// 														alert(data);
			// 													}
			// 											});
			// 									},500);*/
			// 							});

			// 						}
			// 						else{
			// 							$('#loading-img').addClass('hidden');
			// 							alert(data);
			// 							button.removeClass('disabled');
			// 						}
			// 				});	
			// 		},
			// 		cancel:function(){ 
			// 			button.removeClass('disabled');
			// 		}
					
			// });

		}
		else{
			$('#loading-img').removeClass('hidden');
			$.post(server+'po-transaction.php',{kerbfdsoirp:action,attn:attn,prnumber:prnumber,dateneeded:dateneeded,grandtotal:grandtotal,mode:modeofshipment,currency:currency,ponumber:ponumber,supplieraddr:supplieraddr,term:term,shipvia:shipvia,remarks:remarks},function(data){
				if(data.trim()=='success'){
					$('.content').animate({scrollTop:0},200);
					$('.content').load('Transaction/purchase-order.php',function(){
						$.getScript(pagescripts+'purchase-order.js');
						setTimeout(function(){
							$('#loading-img').addClass('hidden');
						},300);
						notify("Purchase Order <b>saved</b>", "success","fa fa-check-circle");

					});
					button.removeClass('disabled');

				}
				else{
					$('#loading-img').addClass('hidden');
					alert(data);
					button.removeClass('disabled');
				}
			});	
		}

	}
});
$(document).off('click','#revisionmodal-revisebtn:not(".disabled")').on('click','#revisionmodal-revisebtn:not(".disabled")', function(){
	var button = $(this);
	button.addClass('disabled');
	button.attr('disabled',true);

	if($('.reasonforrevision').val()==''){
		$('#revisionmodal .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a valid reason for revision.</div></div>");
		button.removeAttr('disabled');
		button.removeClass('disabled');
	}else{
		
		$.confirm({
					animation: 'bottom', 
					closeAnimation: 'top',
					animationSpeed: 1000,
					animationBounce:1,
					title: 'Confirmation',
					content: 'Are you sure you want to revise purchase order?',
					confirmButton: 'Confirm',
					cancelButton: 'Cancel',	
					confirmButtonClass: 'btn-oceanblue', 
					cancelButtonClass: 'btn-royalblue', 
					theme: 'white', 

					confirm: function(){
						$('#revisionmodal').modal('hide');
						$('#revisionmodal').on('hidden.bs.modal',function(){
							var remarks, ponumber, reqnumber;
							ponumber = $('#ponumberinput').val();
							ponumber = ponumber.split('-R');
							reqnumber = $('#ponumberinput').attr('reference');
							remarks = $('.reasonforrevision').val();
				
							$('#loading-img').removeClass('hidden');
							$.post(server+'purchase-order.php',{KJlrkjosnRh2kjhl34k:'329&4kjhos909dfe',ponumber:ponumber[0],reqnumber:reqnumber,remarks:remarks},function(data){
								if(data.trim()==''){
									$('.content').animate({scrollTop:0},200);
									$('.content').load('Transaction/purchase-order.php',function(){
										$.getScript(pagescripts+'purchase-order.js');
										setTimeout(function(){
											$('#loading-img').addClass('hidden');
										},300);
										notify("Purchase Order has been flagged as <b>for revision</b>", "success","fa fa-check-circle");
									});
								}else{
									$('#loading-img').addClass('hidden');
									alert(data);
									button.removeAttr('disabled');
									button.removeClass('disabled');
								}
							});
						});
					},
					cancel:function(){ 
						button.removeAttr('disabled');
						button.removeClass('disabled');
					}
					
		});

	}
});

$(document).off('click','.potrans-pohistorytoggle').on('click','.potrans-pohistorytoggle', function(){

	window.open('Other/purchase-order-history-window.php?JndeLIe93o0jn49NDLiu3n='+$('.ponumber').val());
});


///////////////////////////////MODE OF SHIPMENT ////////////////////////////////


$(document).off('change','.shipvia').on('change','.shipvia', function(){
	var x, y, via, fetchdata;
	via = $(this).val().toUpperCase();
	$('.modeofshipment').empty();
	$.post(server+'po-transaction.php',{LKrnIRR4oRjiodb:via},function(data){
		$('.modeofshipment').append("<option value='NULL'>-</option>");
		if(data.length>=2){
			fetchdata = data.trim().split('%$@');
			
			x = (fetchdata.length/2);
			for(i=0;i<x;i++){
				y = i*2;
				$('.modeofshipment').append("<option value='"+fetchdata[y]+"'>"+fetchdata[y+1]+"</option>");
			}
		}
		$('.chosen-select').trigger('chosen:updated');
	});

});

$(document).off('change','.paymentterms').on('change','.paymentterms', function(){
	var terms = $('.paymentterms option:selected').text();
	$(this).next('.chosen-container-single').attr('title',terms);

});