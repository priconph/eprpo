$(document).off('change','.modal-transactiontype').on('change','.modal-transactiontype',function(){
	if($(this).val()=='Purchase Order'){
		$('#ponumberformgroup').removeClass('hidden').children('select').val('');
		$('#supplierformgroup').removeClass('hidden').children('select').val('');
	}
	else if($(this).val()=='Generic'){
		$('#supplierformgroup').removeClass('hidden').children('select').val('');
		$('#ponumberformgroup').addClass('hidden').children('select').val('');
	}
	else{
		$('#ponumberformgroup, #supplierformgroup').addClass('hidden').children('select').val('');
	}
	
});


$(document).off('change','.modal-supplier').on('change','.modal-supplier',function(){
	var supplierId = $(this).val();
	$.post(server+'receiving.php',{OIrlksfho:'Kh4Iiou&3hdk#k',supplierId:supplierId},function(data){
		$('#podropdownlist').empty();
		$('#podropdownlist').append("<option value=''>-</option>");
		if(data.trim()!=''){
			var fetcheddata = data.split('%');
			for(i=0;i<fetcheddata.length;i++){
				var po = fetcheddata[i].trim();
				$('#podropdownlist').append("<option value='"+po+"'>"+po+"</option>");
			}
		}
	});
});

$(document).off('click','#rcvmodal-nextbtn').on('click','#rcvmodal-nextbtn',function(){
 	var transaction, supplier, ponumber;
 	transaction = $('.modal-transactiontype').val();
 	supplier = $('.modal-supplier').val();
 	ponumber = $('.modal-ponumber').val();

 	if(transaction=='Purchase Order'){
 		if(supplier==''){
 			$('#newrcvmodal .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a <b>supplier</b>.</div></div>");
 		}
 		else if(ponumber==''){
 			$('#newrcvmodal .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select an <b>order number</b>.</div></div>");
 		}
 		else{
 			$('#newrcvmodal').modal('hide');
 			$('#newrcvmodal').on('hidden.bs.modal',function(){
 				$('#loading-img').removeClass('hidden');
 				$('.content').load('Transaction/rcv-transaction-po.php?JKeao4ihslk='+supplier+"&skrbOIrhlk="+ponumber,function(){
 					$.getScript(pagescripts+'rcv-transaction-po.js');
 					setTimeout(function(){
						$('#loading-img').addClass('hidden');
					},300);
 				});
				$('.content').animate({scrollTop:0},200);
 			});
 		}
 	}
 	else if(transaction=='Generic'){
 		if(supplier==''){
 			$('#newrcvmodal .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a <b>supplier</b>.</div></div>");
 		}
 		else{
 			$('#newrcvmodal').modal('hide');
 			$('#newrcvmodal').on('hidden.bs.modal',function(){
 				$('#loading-img').removeClass('hidden');
 				$('.content').load('Transaction/rcv-transaction.php?kdekdfoKJhej2='+supplier,function(){
 					$.getScript(pagescripts+'rcv-transaction.js');
 					setTimeout(function(){
						$('#loading-img').addClass('hidden');
					},300);
 				});
				
				$('.content').animate({scrollTop:0},200);
 			});
 			
 		}
 	}
 	else{
 		$('#newrcvmodal .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a <b>transaction type</b>.</div></div>");
 	}
});

$(document).off('click','.rcvrow').on('click','.rcvrow',function(){
	var modal = '#rcvblowup';
	$('#rcvblowup').modal('show');
	var reference;
	$('#rcvnum').text($(this).attr('rcvnum'));
	/*if($(this).attr('po')==''){
		reference = $(this).attr('otherref');
	}
	else{
		reference = $(this).attr('po');
	}*/
	$(modal+' #ponumber').text($(this).attr('po'));
	$(modal+' #reference').text($(this).attr('otherref'));
	$(modal+' #prreference').text($(this).attr('prref')+$(this).attr('prsuffix')).attr('prnumber',$(this).attr('prref'));
	$(modal+' #suppliername').text($(this).attr('supplier'));
	$(modal+' #actualdatedelivered').text($(this).attr('actualdate'));
	$(modal+' #carrier').text($(this).attr('carrier'));
	$(modal+' #receiveddate').text($(this).attr('date'));
	$(modal+' #receivedby').text($(this).attr('by'));
	$(modal+' #currency').text($(this).attr('currency'));
	$(modal+' #remarks').text($(this).attr('remarks'));
	$(modal+' #hold_remarks').text($(this).attr('hold_remarks'));
	$(modal+' .asstmngr').html($(this).attr('asstmngr'));
	$(modal+' .genmngr').html($(this).attr('genmngr'));
	

	if($(this).attr('rcv_stat') == 'N/A' || $(this).attr('rcv_stat') == '') {
		$(modal+' #div_rcv_status').hide();
	} else {
		$(modal+' #div_rcv_status').show();
		$(modal+' #receiving_status').text($(this).attr('rcv_stat'));
	}
	if($(this).attr('prref').toUpperCase()=='NO PR REFERENCE'){
		$('.rcvviewtrans-prhistorytoggle').addClass('hidden');
	}
	else{
		$('.rcvviewtrans-prhistorytoggle').removeClass('hidden');
	}

	$('#cartitems').load('Transaction/receiving-items.php?KJeslkrioj43lk='+$(this).attr('rcvnum'));
});

$(document).off('click','.rcvviewtrans-prhistorytoggle').on('click','.rcvviewtrans-prhistorytoggle',function(){
	var prnumber = $('#prreference').attr('prnumber');
	window.open('Other/purchase-requisition-source-window.php?ksjcno0923jsbkyiu94hnsk='+prnumber);
});

$(document).off('click','#rejectedrcvtable tbody tr').on('click','#rejectedrcvtable tbody tr',function(){
	var supplier, ponumber, rcvnumber;
  	supplier = $(this).find('td:eq(7)').text();
	ponumber = $(this).find('td:eq(1)').text();
	rcvnumber = $(this).find('td:eq(0)').text();
	$('#rejectrcvmodal').modal('hide');
	$('#rejectrcvmodal').on('hidden.bs.modal',function(){
		$('#loading-img').removeClass('hidden');
		$('.content').load('Transaction/rcv-rejecttransaction-po.php?mJsbdues8d='+supplier+"&aLsrbOsIrhlk="+ponumber+"&7asd9DAca="+rcvnumber,function(){
			$.getScript(pagescripts+'rcv-rejecttransaction-po.js');
			setTimeout(function(){
				$('#loading-img').addClass('hidden');
			},300);
		});
		$('.content').animate({scrollTop:0},200);
	});

});

// 