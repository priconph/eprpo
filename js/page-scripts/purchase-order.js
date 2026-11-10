$(document).off('click','#addpo').on('click','#addpo', function(){
	$('.content').load('Transaction/po-transaction.php');
	$.getScript(pagescripts+'po-transaction.js');
});
$(document).off('click','.editpurchaseorder').on('click','.editpurchaseorder', function(){
	var ponumber = $(this).attr('ponum');
	$('#loading-img').removeClass('hidden');
	$('.content').load('Transaction/po-transaction.php?Ksfrlwowp%kspej%Dhor='+ponumber,function(){
		setTimeout(function(){
			$.getScript(pagescripts+'po-transaction.js');
			$('#loading-img').addClass('hidden');
		},300);
	});
	
});


$(document).off('click','#findpomodal-loadbtn').on('click','#findpomodal-loadbtn', function(){
	var modal = '#'+$(this).closest('.modal').attr('id');
	var status = $(modal+' #findpomodal-status').val().replace(/\s/g,'%20');
	/*$('#loading-img').removeClass('hidden');
	$('#potable tbody').load('Other/find-purchase-order.php?status='+status,function(){
			userAccess();
			setTimeout(function(){
				$('#loading-img').addClass('hidden');
			},500);
	});*/
	$('#potable').DataTable().ajax.url( 'Other/ajax/purchase-order.php?status='+status).search('').load();
});

//see Service Request #031418-12-42-13 - Start
$(document).off('click','#load_for_printing').on('click','#load_for_printing', function(){
	$('#potable').DataTable().ajax.url( 'Other/ajax/purchase-order.php?status=FOR PRINT').search('').load();
});
//see Service Request #031418-12-42-13 - End

$(document).off('click','#potable .tagpoasprinted').on('click','#potable .tagpoasprinted', function(){
	var ponumber = $(this).attr('ponum');
	$.confirm({
				animation: 'bottom', 
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce:1,
				title: 'Tag as Printed',
				content: 'Are you sure you want to tag '+ponumber+' as printed?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',	
				confirmButtonClass: 'btn-oceanblue', 
				cancelButtonClass: 'btn-royalblue', 
				theme: 'white', 

				confirm: function(){
					$('#loading-img').removeClass('hidden');
					$.post(server+'purchase-order.php',{tagpoasprinted:'DKjhsi$OIhs$K#uh#i2#lfk',ponumber:ponumber},function(data){
						if(data.trim()=='success'){
							$('#loading-img').addClass('hidden');
							$('.content').load('Transaction/purchase-order.php',function(){
								setTimeout(function(){
									$('#loading-img').addClass('hidden');
								},300);
								notify(ponumber+" has been tagged as <b>printed</b>", "success","fa fa-check-circle");
							});
						}
						else{
							alert(data);
							$('#loading-img').addClass('hidden');

						}
					});
				}
			});


});

$(document).off('click','#potable .clickable').on('click','#potable .clickable', function(){
	$('#poblowup').modal('show');
	var withdiscrepancy, dateneeded, rejection, rejectedby, lastapprovedby, forrevision, ponumber, postedby, assignedto, attn, prnumber, status, supplier, supplierAddr, expdelivery, orderdate, remarks, grandtotal, terms, shipvia, currency, facshipto, mode, cancelleddate, cancelledby, cancelledreason, prsuffix;
	ponumber = $(this).closest('tr').attr('ponumber');
	prnumber = $(this).closest('tr').attr('refpr');
	status = $(this).closest('tr').attr('status');
	supplier = $(this).closest('tr').attr('supplier');
	supplierAddr = $(this).closest('tr').attr('supplierAddr');
	//expdelivery = $(this).closest('tr').attr('expdelivery');
	orderdate = $(this).closest('tr').attr('orderdate');
	remarks = $(this).closest('tr').attr('remarks');
	grandtotal = $(this).closest('tr').attr('pototal');
	terms = $(this).closest('tr').attr('terms');
	shipvia = $(this).closest('tr').attr('shipvia');
	currency = $(this).closest('tr').attr('currency');
	facshipto = $(this).closest('tr').attr('facshipto');
	mode = $(this).closest('tr').attr('mode');
	attn = $(this).closest('tr').attr('attn');
	dateassigned = $(this).closest('tr').attr('dateassigned');
	assignedto = $(this).closest('tr').attr('assignedto');
	lastapprovedby = $(this).closest('tr').attr('lastapprovedby');
	postedby = $(this).closest('tr').attr('postedby');
	dateposted = $(this).closest('tr').attr('dateposted');
	withdiscrepancy = $(this).closest('tr').attr('withdiscrepancy');
	forrevision = $(this).closest('tr').attr('forrevision');
	dateneeded = $(this).closest('tr').attr('dateneeded');
	rejection = $(this).closest('tr').attr('rejectionreason');
	rejectedby  = $(this).closest('tr').attr('rejectedby');
	revnum  = $(this).closest('tr').attr('revnum');
	cancelledreason  = $(this).closest('tr').attr('cancelledreason');
	cancelleddate  = $(this).closest('tr').attr('cancelleddate');
	cancelledby  = $(this).closest('tr').attr('cancelledby');
	prsuffix=$(this).closest('tr').attr('prsuffix');



	$('#poitems').load('Transaction/purchase-order-items.php?Oinsldfru='+ponumber);
	$('#poitemsreceived').load('Transaction/purchase-order-itemsreceived.php?KJeslkrioj43lk='+ponumber);
	$('#ponumber').html(ponumber);
	$('#prnumber').html(prnumber+prsuffix+"<br><span class='detailslink pointer' prnumber='"+prnumber+"'style='font-style:italic;color:blue;font-size:11px'>View details</span>");
	$('#orderstatus').html(status);
	$('#vendor').html(supplier);
	$('#supplierAddress').html(supplierAddr);
	//$('#expdelivery').html(expdelivery);
	$('#orderdate').html(orderdate);
	$('#remarks').html(remarks);
	$('#grandtotal').val(grandtotal);
	$('#blowupcurrencysymbol').html('<b>'+currency+'</b>');
	$('#paymentterms').html(terms);
	$('#shipviafield').html(shipvia);
	$('#pocurrency').html(currency);
	$('#facshipto').html(facshipto);
	$('#shipmentmode').html(mode);
	$('#revisionnumber').html(revnum);
	$('#lastapprovedby').html(lastapprovedby);
	$('#attn').html(attn);
	$('#dateassigned').html(dateassigned);
	$('#dateposted').html(dateposted);
	$('#assignedto').html(assignedto);
	$('#postedby').html(postedby);
	$('#dateneeded').html(dateneeded);
	$('#reasonforrejection').val(rejection);
	$('#rejectedby').val(rejectedby);
	$('#po-approverspane .asstmngr').html($(this).closest('tr').attr('firstapprover'));
	$('#po-approverspane .genmngr').html($(this).closest('tr').attr('secondapprover'));
	$('#po-approverspane .divmngr').html($(this).closest('tr').attr('divapprover'));
	$('#po-approverspane .pres').html($(this).closest('tr').attr('thirdapprover'));
	$('#po-approverspane .lastapprovedby').html($(this).closest('tr').attr('lastapprovedby'));
	$('#cancelleddate').html(cancelleddate);
	$('#cancelledby').html(cancelledby);
	$('#cancelledreason').html(cancelledreason);

	if(withdiscrepancy=='true'||withdiscrepancy==1){
		$('#discrepancytab').removeClass('hidden');
		$('#po-discrepancy').removeClass('hidden').load('Other/purchase-order-discrepancy-details.php?Kbdkldriiopirn389yd9830='+ponumber);
	}
	else{
		$('#discrepancytab').addClass('hidden').removeClass('active');
		$('#po-discrepancy').addClass('hidden').removeClass('active');
		$('#geninfotab').addClass('active').siblings().removeClass('active');
		$('#po-geninfo').addClass('active').siblings().removeClass('active');
	}
	
	// if(forrevision>0){
		$('#revisionremarkstab').removeClass('hidden');
		$('#po-revisionremarks').removeClass('hidden').load('Other/purchase-order-revision-remarks.php?Kbdkldriiopirn389yd9830='+ponumber);
	// }
	// else{
		// $('#revisionremarkstab').addClass('hidden').removeClass('active');
		// $('#po-revisionremarks').addClass('hidden').removeClass('active');
		// $('#geninfotab').addClass('active').siblings().removeClass('active');
		// $('#po-geninfo').addClass('active').siblings().removeClass('active');
	// }

	if(status=='REJECTED'){
		$('#rejectionremarkstab').removeClass('hidden');
		$('#po-rejectionremarks').removeClass('hidden');
	}
	else{
		$('#rejectionremarkstab').addClass('hidden').removeClass('active');
		$('#po-rejectionremarks').addClass('hidden').removeClass('active');
		$('#geninfotab').addClass('active').siblings().removeClass('active');
		$('#po-geninfo').addClass('active').siblings().removeClass('active');
	}

});
$(document).off('click','.revisepurchaseorder').on('click','.revisepurchaseorder', function(){
	var ponumber = $(this).attr('ponum');
	$('.reasonforrevision').attr('ponumber',$(this).attr('ponum')).attr('reqnumber',$(this).attr('reqnum'));
	//$('.reasonforrevision').attr('reqnumber',$(this).attr('reqnum'));

	$.post(server+'purchase-order.php',{MdlkeoI230Uj143dl:'NddJy@$l3*p&4kh2',ponumber:ponumber},function(data){
		if(data.trim()!='none'){
			var fetchdata = data.split('@&'),
				notes = 'Variance in Receiving:  '+ponumber+'\n';
			for(var i = 0;i<fetchdata.length;i++){
				notes = notes+'Item Code: '+fetchdata[i]+'\n ';
			}
			$('#revisionmodal .reasonforrevision').val(notes);
			$('#revisionmodal').modal('show');
		}
		else{
			$('#revisionmodal .reasonforrevision').val('');
			$('#revisionmodal').modal('show');
		}

	});
});

$(document).off('click','#poview-revisebtn:not([disabled])').on('click','#poview-revisebtn:not([disabled])', function(){
	var button = $(this);
	button.attr('disabled',true);
	//if(checkRemarks($('.reasonforrevision').val())==false){
	if($('.reasonforrevision').val()==''){
		$('#revisionmodal .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a <b>valid reason</b> for revision.</div></div>");
		$(this).removeAttr('disabled');
	}
	else if($('#revisionmodal .reasonforrevision').val().length>500){
		$('#revisionmodal .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Given remarks exceeds the maximum(500) number of characters.</div></div>");
		$(this).removeAttr('disabled');
	}
	else{
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
						var ponumber, reqnumber, remarks;
						ponumber = $('.reasonforrevision').attr('ponumber');
						reqnumber = $('.reasonforrevision').attr('reqnumber');
						remarks = $('.reasonforrevision').val().trim();
						$('#loading-img').removeClass('hidden');
						$.post(server+'purchase-order.php',{KJlrkjosnRh2kjhl34k:'329&4kjhos909dfe',ponumber:ponumber,reqnumber:reqnumber,remarks:remarks},function(data){
							if(data.trim()==''){

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
							}
						});
					});
				},
				cancel:function(){ 
					button.removeAttr('disabled');
				}

			});
	}	
});


$(document).off('click','.pohistorytoggle').on('click','.pohistorytoggle', function(){

	window.open('Other/purchase-order-history-window.php?JndeLIe93o0jn49NDLiu3n='+$('#poblowup #ponumber').html());
});

$(document).off('click','.printpurchaseorder').on('click','.printpurchaseorder', function(){
	$('#printpomodal #revsuffix').val($(this).attr('revno'));
	$('#printbtn-printingmodal').attr("ponumber",$(this).attr('ponum'));
	$('#printbtn-printingmodal').attr("revno",$(this).attr('revno'));
});



$(document).off('click','#printbtn-printingmodal').on('click','#printbtn-printingmodal', function(){
	var template = $('#printtype').val(),
		revsuffix = $('#revsuffix').val(),
		ponum = $(this).attr("ponumber");

	// if(template=='1'){
	// 	window.open('Printouts/purchase-order-printout.php?jkHIejPoeiwMlkdA='+ponum);
	// }
	// else{
	// 	// window.open('Printouts/purchase-order-printout2.php?jkHIejPoeiwMlkdA='+ponum+'&revsuffix='+revsuffix);
	// 	window.open('Printouts/purchase-order-printout2.php?jkHIejPoeiwMlkdA='+ponum); //di ko na sinama ung rev# sa link para di nila mapaltan :D
	// }
	/* 02012025 by Nessa */
	if (template == '2') {
		window.open('Printouts/purchase-order-printout2.php?jkHIejPoeiwMlkdA=' + ponum);
	} else if (template == '3') {
		window.open('Printouts/purchase-order-printout_bir.php?jkHIejPoeiwMlkdA=' + ponum);
	} else {
		window.open('Printouts/purchase-order-printout.php?jkHIejPoeiwMlkdA=' + ponum);
	}
});

$(document).off('click','.detailslink').on('click','.detailslink', function(){
	var prnumber = $(this).attr('prnumber');
	window.open('Other/purchase-requisition-source-window.php?ksjcno0923jsbkyiu94hnsk='+prnumber);
});


$(document).off('click','.assignpurchaseorder').on('click','.assignpurchaseorder', function(){
	var ponumber = $(this).attr('ponum');
	$('#assignmentmodal-ponumber').val(ponumber);
});

$(document).off('click','#assignmentmodal-confirmbtn').on('click','#assignmentmodal-confirmbtn', function(){
		var ponumber, assignedto, user;
		$(this).attr('disabled',true);
		ponumber = $('#assignmentmodal-ponumber').val();
		assignedto = $('#assignmentmodal-assignedto').val();
		user = $('#assignmentmodal-assignedto option:selected').html();
		if(assignedto==''){
			$('#assignmentmodal .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a user.</div></div>");
			$(this).removeAttr('disabled');
		}
		else{
			$('#loading-img').removeClass('hidden');
			$.post(server+'purchase-order.php',{Kj4lkd8Klei3nfkisdfk:'ekhrPro4j2kHFlkef#lfk',ponumber:ponumber,assignedto:assignedto},function(data){
				if(data.trim()=='success'){
					$('#assignmentmodal').modal('hide');
					$('#assignmentmodal').on('hidden.bs.modal',function(){
							$('.content').animate({scrollTop:0},200);
							$('.content').load('Transaction/purchase-order.php',function(){
								$.getScript(pagescripts+'purchase-order.js');
								setTimeout(function(){
									$('#loading-img').addClass('hidden');
								},300);
								notify(ponumber+" has been assigned to <b>"+user+"</b>", "success","fa fa-check-circle");
							});
							
							
							$(this).removeAttr('disabled');
					});

				}
				else{
					alert(data);
					$(this).removeAttr('disabled');
				}
			});
		}

});

$(document).off('click','#printtemplate').on('click','#printtemplate', function(){

	window.open('Printouts/purchase-order-printout-blank.php');
});


$(document).off('click','.pageContent .cancelpobtn').on('click','.pageContent .cancelpobtn', function(){
	var po = $(this).attr('ponum');
	$('#pocancellationmodal-table tbody').load('Other/load-po-siblings.php?ponumber='+po);
});


$(document).off('click','#pocancellationmodal-confirmbtn:not(".disabled")').on('click','#pocancellationmodal-confirmbtn:not(".disabled")', function(){
	var checkreason = true, 
		reason = '',
		ponumbers = [],
		poreasons = [],
		button = $(this),
		modal = '#'+$(this).closest('.modal').attr('id'),
		prnumber= '';
		button.addClass('disabled');
	$('#pocancellationmodal-table .pocancel-checkbox:checked').each(function(){

		reason = $(this).closest('tr').find('.pocancel-reasonfield').val();
		prnumber = $(this).closest('tr').attr('prnumber');
		ponumbers.push($(this).attr('ponumber'));
		poreasons.push(reason);
		if(reason.trim()==''){
			checkreason = false;
		}
	});

	if(checkreason==false){
		say('Please add reason of cancellation for selected purchase orders.');
		button.removeClass('disabled');
	}
	else{
		$.confirm({
			animation: 'bottom', 
			closeAnimation: 'top',
			animationSpeed: 1000,
			animationBounce:1,
			title: 'PO Cancellation',
			content: 'This will cancel <b>'+ponumbers+'</b>.<br> Do you want to continue?',
			confirmButton: 'Yes',
			cancelButton: 'No',	
			confirmButtonClass: 'btn-oceanblue', 
			cancelButtonClass: 'btn-royalblue', 
			theme: 'white', 

			confirm: function(){
				$('#loading-img').removeClass('hidden');
				$.post(server+'purchase-order.php',{poCancellation:'Fkjhs4lkh4s!o3Iah$Uh#O@i2aoa4ho@',ponumbers:ponumbers,poreasons:poreasons,prnumber:prnumber},function(data){

					if(data.trim()=='success'){
						$(modal).modal('hide');
						$(modal).on('hidden.bs.modal',function(){	
							$(modal).off('hidden.bs.modal');	
							$('.content').load('Transaction/purchase-order.php',function(){
								$.getScript(pagescripts+'purchase-order.js');
								setTimeout(function(){
									$('#loading-img').addClass('hidden');
									say(ponumbers+' has been cancelled.');
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