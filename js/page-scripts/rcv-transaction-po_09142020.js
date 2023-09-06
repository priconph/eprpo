$(document).off('click','#rcvpo-submitbtn').on('click','#rcvpo-submitbtn',function(){
	var button = $(this);
	button.attr('disabled',true);
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
					var currency, qtyvarnotes='', pricevarnotes='', actualdate='', underdelnotes='', underdelivery=false, supplierId, ifqtyvalid=true, qtyrcv, ifpricevalid=true, ordered, qtyvariance=false, pricevariance=false, rcvd, rowprice, tobercvd, lineitemid=[], lineitemprice=[], lineitemqty=[], lineitemqtytemp=[], justifications=[], corrective_actions=[], carrier, ponumber, remarks, otherref, ifcomplete=true, hasoldpo=false, incompletejusti=false, bal=0;
					ponumber = $('.referenceponumber').val();
					otherref = $('.otherreference').val();
					actualdate = $('.actualdatedelivered').val();
					carrier = $('.carrier').val();
					remarks = $('.remarks').val();
					supplierId = $('.supplier').attr('supplierId');
					currency = $('.currency').attr('currencyId');

					$('#polineitems tr').each(function(){
						ordered = parseFloat($(this).children('.orderedqty').text().replace(/,/g,''));
						rcvd = parseFloat($(this).children('.receivedqty').text().replace(/,/g,''));
						tobercvd = parseFloat($(this).find('.qtytobereceived').val());
						bal = parseFloat($(this).find('td:eq(11) input').attr('max'));
						
						if(parseFloat(tobercvd)>0){
							//rowprice = parseFloat($(this).find('.priceinput').val());
							if(tobercvd!=''&&tobercvd<=0){
								$(this).find('.qtytobereceived').css({backgroundColor:'#FFC9C9'});
								ifqtyvalid=false;
							}
							else{
								$(this).find('.qtytobereceived').css({backgroundColor:'#fff'});
							}	
							if(tobercvd > bal) {
								$(this).find('.qtytobereceived').css({backgroundColor:'#FFC9C9'});
								ifqtyvalid=false;
							}
							else{
								$(this).find('.qtytobereceived').css({backgroundColor:'#fff'});
							}
							/*if(tobercvd>0&&(rowprice<=0||isNaN(rowprice)==true)){
								$(this).find('.priceinput').css({backgroundColor:'#FFC9C9'});
								ifpricevalid=false;
							}
							else{
								$(this).find('.priceinput').css({backgroundColor:'#fff'});
							}*/
							//check if after this submission po is complete
							if(isNaN(tobercvd)==true){
								qtyrcv = 0;
							}
							else{
								qtyrcv = tobercvd;
							}
							
							if(typeof($(this).find('.has_open_po').val()) != 'undefined') {
								hasoldpo = true;
								if($(this).find('.justification').val() == '' || $(this).find('.corrective_action').val()== '' ) {
									incompletejusti = true;
								}
							}

						}
					});
					if(otherref.trim()==''){
						$('.errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a <b>reference</b>.</b></div></div>");
						$('.content').animate({scrollTop:0},200);
						button.removeAttr('disabled');
						$('.otherreference').focus();
					}
					else if(actualdate==''){
						$('.errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide <b>actual date delivered</b>.</div></div>");
						$('.content').animate({scrollTop:0},200);
						button.removeAttr('disabled');
					}
					else if(ifqtyvalid==false){
						$('.errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid <b>item quantity</b></div></div>");
						$('.content').animate({scrollTop:0},200);
						button.removeAttr('disabled');
						$('.qtytobereceived').focus();
					}
					/*else if(ifpricevalid==false){
						$('.errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid <b>Item Price</b>.</div></div>");
						$('.content').animate({scrollTop:0},200);
						button.removeAttr('disabled');
						$('.priceinput').focus();
					}*/
					else if(hasoldpo && incompletejusti) {
						$('.errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>No <b>Justification</b> and <b>Corrective action</b> provided on item/s with open P.O.</div></div>");
						$('.content').animate({scrollTop:0},200);
						button.removeAttr('disabled');
					}
					else{
						var check_old_po = '';
						$('#polineitems tr').each(function(){
							ordered = parseFloat($(this).children('.orderedqty').text().replace(/,/g,''));
							rcvd = parseFloat($(this).children('.receivedqty').text().replace(/,/g,''));
							tobercvd = parseFloat($(this).find('.qtytobereceived').val().replace(/,/g,''));
							rowprice = parseFloat($(this).find('.priceinput').text());
							check_old_po = typeof($(this).find('.has_open_po').val()) === 'undefined' ? 'N/A' : 'OPEN_PO';
							console.debug("$(this).find('.has_open_po').val() "+$(this).find('.has_open_po').val());
							if(tobercvd>0){
								lineitemid.push($(this).attr('porowitemid'));
								lineitemprice.push($(this).children('.priceinput').text().replace(/,/g,''));
								if(check_old_po == 'N/A') {
									lineitemqty.push(tobercvd);
									lineitemqtytemp.push(0);
									justifications.push('-');
									corrective_actions.push('-');
								} else {
									lineitemqty.push(0);
									lineitemqtytemp.push(tobercvd);
									justifications.push($(this).find('.justification').val());
									corrective_actions.push($(this).find('.corrective_action').val());
								}
							}
							
							var sumtemp = parseFloat(rcvd)+parseFloat(tobercvd);
							var u = $(this).children(':last-child').find('.rowunderdelivery').val().trim();

							if(isNaN(tobercvd)==true){
								tobercvd = 0;
							}


							if(u=='YES'&&sumtemp<ordered&&tobercvd>0){
								underdelivery=true;
								underdelnotes = underdelnotes+$(this).children(':nth-child(2)').text()+'  -  (UNDERDELIVERY) Ordered: '+ordered+' Total Received: '+sumtemp+' @&';

							}
							else if((parseFloat(rcvd)+parseFloat(tobercvd))<ordered){
								ifcomplete = false;
							}
							else if(sumtemp>ordered&&tobercvd>0){
								qtyvariance = true;
								qtyvarnotes = qtyvarnotes+$(this).children(':nth-child(2)').text()+'  -  (EXCEEDS QUANTITY ORDERED) Ordered: '+ordered+' Total Received: '+sumtemp+' @&';
							}

							var poprice = parseFloat($(this).children(':nth-child(6)').text().replace(/,/g,''))+0;
							if((tobercvd>0)&&parseFloat(rowprice)!=parseFloat(poprice)){
								pricevariance = true;
								pricevarnotes = pricevarnotes+$(this).children(':nth-child(2)').text()+'  -  (DIFFERENCE IN PRICE) Unit PO Price: '+$(this).children(':nth-child(5)').text().replace(/,/g,'')+' | Delivery Unit Price: '+convertFloat($(this).find('.priceinput').text())+' | Received: '+tobercvd.toLocaleString()+'/'+ordered.toLocaleString()+' @&';
							}


						});
						if(lineitemid.length<1){//check if there's atleast 1 item to be received
							$('.errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide <b> quantity</b> for item(s) to be received.</div></div>");
							$('.content').animate({scrollTop:0},200);
							button.removeAttr('disabled');
						}
						else{
						
				
							$('#loading-img').removeClass('hidden');
							$.post(server+'rcv-transaction-po.php',{KhrOIrlsk34o2hlK:'URhksoi3Hsri',actualdate:actualdate,qtyvarnotes:qtyvarnotes,otherref:otherref,pricevarnotes:pricevarnotes,underdelnotes:underdelnotes,underdelivery:underdelivery,ifcomplete:ifcomplete,qtyvariance:qtyvariance,pricevariance:pricevariance,currency:currency,remarks:remarks,carrier:carrier,ponumber:ponumber,items:lineitemid,prices:lineitemprice,qtys:lineitemqty,tempqtys:lineitemqtytemp,supplier:supplierId,hasoldpo:hasoldpo,justifications:	justifications,corrective_actions:corrective_actions},function(data){
								if(data.trim()=='success' || data.trim()=='success2' ){
									$('.content').animate({scrollTop:0},200);
									$('.content').load('Transaction/receiving.php',function(){
										$.getScript(pagescripts+'receiving.js');
										setTimeout(function(){
											$('#loading-img').addClass('hidden');
										},300);
										if(data.trim()=='success')
											notify("<b>Item(s) received</b>", "success","fa fa-check-circle");
										else
											notify("<b>Item(s) received is subject for approval</b>", "success","fa fa-check-circle");
									});
									
									
								}
								else if(data.trim()=='invalidactualdeldate'){
									$('#loading-img').addClass('hidden');
									notify("<b>Invalid actual delivery date</b>", "success","fa fa-check-circle");
									button.removeAttr('disabled');
								}
								else{
									$('#loading-img').addClass('hidden');
									alert(data);
									button.removeAttr('disabled');
								}
							});

							
						}

					}
				},
				cancel:function(){ 
					button.removeAttr('disabled');
				}

	});





});

$(document).off('click','#rcvpo-cancelbtn').on('click','#rcvpo-cancelbtn',function(){
	$('#loading-img').removeClass('hidden');
	$('.content').animate({scrollTop:0},200);
	$('.content').load('Transaction/receiving.php',function(){
		$.getScript(pagescripts+'receiving.js');
		setTimeout(function(){
						$('#loading-img').addClass('hidden');
		},300);
	});
	
	
});



$(document).off('click','.rcvtrans-prhistorytoggle').on('click','.rcvtrans-prhistorytoggle',function(){
	var prnumber = $(this).attr('prnumber');
	window.open('Other/purchase-requisition-source-window.php?ksjcno0923jsbkyiu94hnsk='+prnumber);
});
