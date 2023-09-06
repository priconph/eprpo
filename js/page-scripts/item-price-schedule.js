// NEW PRICE SHCEDULE
$(document).off('click','#newpriceschedcreate-nextbtn').on('click','#newpriceschedcreate-nextbtn', function(){
	var supplier = $('#addpriceschedulemodal .supplier').val();
	var salt = 'dsfsdfewodjs';
	if(supplier==''){
		$('#addpriceschedulemodal .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a <b>Supplier</b>.</div></div>");
	}
	else{
		$('.modal').modal('hide');
		$('#addpriceschedulemodal').on('hidden.bs.modal',function(){
			$('.content').load('Transaction/price-sched-transaction.php?suppId='+supplier+'&dkjfhskjnkjoi='+salt);
			$.getScript(pagescripts+'price-sched-transaction.js');

		});
		
	}
});



//DELETE ITEM PRICE SCHEDULE
$(document).off('click','#deletepriceschedulebtn').on('click','#deletepriceschedulebtn', function(){
	$(this).attr('disabled',true);
	var id, response;
	response = confirm("Are you sure you want to continue?");
	if(response==true){
		id = [];
		$('.itemcheckbox:checked').each(function(){
			id.push($(this).attr('rowid'));
		});
		if(id.length>0){
			$.post(server+'item-price-schedule.php',{deleteselected:'',id:id},function(data){
				if(data!='success'){
					alert(data);
					$(this).removeAttr('disabled');
				}
				else{
					$('.content').load('Transaction/item-price-schedule.php');
					$(this).removeAttr('disabled');
				}
			});
		}
		else{
			$(this).removeAttr('disabled');
		}
	}
	else{
		$(this).removeAttr('disabled');
	}
});



//EDIT




//GET ITEM DETAILS
$(document).off('click','#priceschedtable .clickable').on('click','#priceschedtable .clickable', function(){
	var transactionnumber = $(this).closest('.tablerow').attr('id');
	$('#cartitems').load('Transaction/item-price-schedule-items.php?KePfoeFkeu%lsfmWL$l2jqp='+transactionnumber);

	$('#transactiondetails #transactionnum').val($(this).closest('.tablerow').attr('id'));
	$('#transactiondetails #supplier').val($(this).closest('.tablerow').attr('supp'));
	$('#transactiondetails #referencenum').val($(this).closest('.tablerow').attr('ref'));
	$('#transactiondetails #startdate').val($(this).closest('.tablerow').attr('start'));
	$('#transactiondetails #enddate').val($(this).closest('.tablerow').attr('end'));
	$('#transactiondetails #createdby').val($(this).closest('.tablerow').attr('cby'));
	$('#transactiondetails #datecreated').val($(this).closest('.tablerow').attr('cdate'));
	$('#transactiondetails #modifiedby').val($(this).closest('.tablerow').attr('uby'));
	$('#transactiondetails #datemodified').val($(this).closest('.tablerow').attr('udate'));
});	