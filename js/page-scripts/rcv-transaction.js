
/////////////////ITEM LIST //////////////////////////////
$(document).off('click','#itempagination li').on('click','#itempagination li',function(){
	var currency = $('.currency').val();
	var index = $(this).attr('value');
	if(currency=='NULL'||currency==''){
		$('#itemlistmodal tbody').load('Other/itemlist.php?oichf%jsl24='+index);
	}
	else{
		$('#itemlistmodal tbody').load('Other/itemlist.php?oichf%jsl24='+index+'&currency='+currency);
	}
});
$(document).on('keyup','.searchforitems',function(e){
			var key = e.which||e.keycode;
	e.preventDefault();

	var input = $(this).val();
	var index = $('#itempagination li.active').attr('value');
	var currency = $('.currency').val();

	if(input==''){	
			if(currency=='NULL'||currency==''){
				$('#itemlist-line-data').load('Other/itemlist.php?oichf%jsl24='+index);
			}
			else{
				$('#itemlist-line-data').load('Other/itemlist.php?oichf%jsl24='+index+'&currency='+currency);
			}	
			$(this).closest('.modal').find('.table-text-search').text('');
			$(this).closest('.modal').find('.table-text-div').removeClass('hidden');
	}
	else if(key==13){
			input=input.replace(/\s/g,'%20');

			if(currency=='NULL'||currency==''){
				$('#itemlist-line-data').load('Other/itemlist-search.php?oichf%jsl24='+input,function(){
					var foundrows = $(this).closest('.modal').find('.tablerow').length;
					$(this).closest('.modal').find('.table-text-search').text(foundrows+' result(s) found');
					$(this).closest('.modal').find('.table-text-div').addClass('hidden');
				});
			}
			else{
				$('#itemlist-line-data').load('Other/itemlist-search.php?oichf%jsl24='+input+'&currency='+currency,function(){
					var foundrows = $(this).closest('.modal').find('.tablerow').length;
					$(this).closest('.modal').find('.table-text-search').text(foundrows+' result(s) found');
					$(this).closest('.modal').find('.table-text-div').addClass('hidden');
				});
			}
	}
});
$(document).on('keyup','.lookupsearchinput',function(){
	var input = $(this).val();
	var index = $('#itempagination li.active').attr('value');
	if(input!=''&&input.length>2){
		$('#itemlist-line-data').load('Other/itemlist-search.php?oichf%jsl24='+input);
		///////////////////////////////
				$(this).closest('.modal').find('.table-text-search').text('');
				//show showing
				$(this).closest('.modal').find('.table-text-div').removeClass('hidden');
				//
				///////////////////////////
	}
	else{
		$('#itemlist-line-data').load('Other/itemlist.php?oichf%jsl24='+index,function(){
			////////////////////////////////search table text//////////////////////
					var foundrows = $(this).closest('.modal').find('.tablerow').length;
					$(this).closest('.modal').find('.table-text-search').text(foundrows+' result(s) found');
					//hide "showing a-z of n entries"//
					$(this).closest('.modal').find('.table-text-div').addClass('hidden');
					 //
					//////////////////////////////////////////////////////////////////////
		});
	}
});
//////////////// ITEM LIST END ///////////////////////////

$(document).off('click','.itemlookuprow').on('click','.itemlookuprow', function(){
	$('#additemmodal .itemcode').val($(this).children(':nth-child(2)').text()).attr('itemid',$(this).attr('itemid')).attr('disabled',true).addClass('disabled');
	$('#additemmodal .itemname').val($(this).children(':nth-child(3)').text()).attr('disabled',true).addClass('disabled');
	$('#additemmodal .itemdesc').val($(this).children(':nth-child(4)').text()).attr('disabled',true).addClass('disabled');
	$('#additemmodal .uom').val($(this).attr('uom')).attr('disabled',true).addClass('disabled');
	$('#additemmodal .unitprice').val($(this).attr('price'));
	$('#itemlistmodal').modal('hide');
});

$('#additemmodal').on('hidden.bs.modal',function(){
	$('#additemmodal .itemcode').val('')
	$('#additemmodal .itemname').val('');
	$('#additemmodal .itemdesc').val('');
	$('#additemmodal .uom').val('NULL');
	$('#additemmodal .itemcode').removeClass('disabled').removeAttr('disabled');
	$('#additemmodal .itemname').removeClass('disabled').removeAttr('disabled');
	$('#additemmodal .itemdesc').removeClass('disabled').removeAttr('disabled');
	$('#additemmodal .uom').removeClass('disabled').removeAttr('disabled');

});

$(document).off('click','#additemmodal-addbtn').on('click','#additemmodal-addbtn', function(){
	var currency, crncy, itemid, itemcode, description, uom, uomid, itemname, qty, unitprice, rowitemexist=false, modal, grandtotal=0, linecount, amount, floatamount, oldqty, newqty, oldamount, newamount;
	modal = '#additemmodal';
	itemid = $(modal+' .itemcode').attr('itemid');
	itemcode = $(modal+' .itemcode').val();
	itemname = $(modal+' .itemname').val();
	uom = $(modal+' .uom option:selected').text();
	uomid = $(modal+' .uom').val();
	description = $(modal+' .itemdesc').val();
	qty = $(modal+' .quantity').val();
	unitprice = $(modal+' .unitprice').val();
	floatamount = parseFloat(qty)*parseFloat(unitprice);






	//currency= $(modal+' .currency').val();////////////
	//crncy = $(modal+' .currency option:selected').text();/////////

	linecount = $('#polineitems tr').length + 1;

	if(itemcode==''){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select an <b>item</b>.</div></div>");
	}
	else if(itemname==''){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide <b>item name</b>.</div></div>");
	}
	else if(uom=='NULL'){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select <b>uit of measure</b>.</div></div>");
	}
	else if(qty==''){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please enter item <b>quantity</b>.</div></div>");
	}
	else if(unitprice==''){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please enter <b>valid unit price</b>.</div></div>");
	}
	//else if(currency==''){////////////
	//	$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select <b>currency</b>.</div></div>");
	//}/////
	else{
		unitprice = convertPrice2(unitprice);
		amount = convertFloat(floatamount);
		$('#polineitems tr').each(function(){
			if($(this).text().search(new RegExp(itemcode,"i"))>=0){
				rowitemexist = true;
				oldqty = $('#'+itemcode+'qty').text();
				newqty = parseFloat(oldqty) + parseFloat(qty);
				$(this).children('.rowprice').text(unitprice);
				$('#'+itemcode+'qty').text(newqty);
				newamount = parseFloat(newqty)*parseFloat(unitprice);
				$('#'+itemcode+'amount').text(newamount.toLocaleString());
				$('#'+itemcode+'amount').attr('amount',newamount);

				$(this).children('.rowcurrency').text(crncy).attr('currencyId',currency);
			}
		});
		if(rowitemexist==false){
			$('#polineitems').append("<tr rowid='"+itemid+"'><td><input type='checkbox' class='itemcheckbox'></td><td class='text-center'>"+linecount+"</td><td class='rowcode text-nowrap'>"+itemcode+"</td><td class='rowname text-nowrap'>"+itemname+"</td><td class='rowdesc'>"+description+"<td id='"+itemcode+"qty' class='rowqty text-right'>"+parseFloat(qty).toLocaleString()+"</td><td class='rowuom text-center' uomid='"+uomid+"'>"+uom+"</td><td class='rowprice text-right'>"+unitprice+"</td><td id='"+itemcode+"amount' class='amount text-right' amount='"+floatamount+"'>"+amount+"</td></tr>");
		}
		$('#polineitems tr').each(function(){
			grandtotal = parseFloat(grandtotal) + parseFloat($(this).children(':nth-child(9)').attr('amount').replace(/,/g,''));
		});

		var gtotal = convertFloat(grandtotal);

		$('#grandtotal').val(gtotal).attr('grandtotal',grandtotal);
		checkGrandTotal(grandtotal);
		$(modal).modal('hide');
		$('#additemmodal').on('hidden.bs.modal',function(){
			$(this).find('.form-control').val('');
		});

		$(modal+' .itemcode').attr('itemid','NEW');
		$('#additemmodal .uom').removeAttr('disabled').removeClass('disabled');
		$('#additemmodal .itemcode').removeAttr('disabled').removeClass('disabled');
		$('#additemmodal .itemname').removeAttr('disabled').removeClass('disabled');
		$('#additemmodal .itemdesc').removeAttr('disabled').removeClass('disabled');
	}
});


$(document).off('click','#rcvnonpo-removeitembtn').on('click','#rcvnonpo-removeitembtn', function(){
	var code, grandtotal=0;
	$('#polineitems .itemcheckbox:checked').each(function(){
		$(this).closest('tr').remove();
	});
	$('#polineitems tr').each(function(){
		grandtotal = grandtotal+parseFloat($(this).children('.amount').attr('amount'));
	});
	$('#grandtotal').val(grandtotal.toLocaleString()).attr('grandtotal',grandtotal);
});

$(document).off('click','#rcvnonpo-cancelbtn').on('click','#rcvnonpo-cancelbtn',function(){
	$('#loading-img').removeClass('hidden');
	$('.content').load('Transaction/receiving.php',function(){
		$.getScript(pagescripts+'receiving.js');
		setTimeout(function(){
						$('#loading-img').addClass('hidden');
		},300);
	});
	
	$('.content').animate({scrollTop:0},200);
});



$(document).off('click','#rcvnonpo-submitbtn').on('click','#rcvnonpo-submitbtn',function(){
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
					var ordered, rcvd, currency, supplierid, tobercvd, actualdate, lineitemid=[], lineitemprice=[], lineitemqty=[], carrier, reference, remarks, codeNonPo=[], nameNonPo=[], descNonPo=[], qtyNonPo=[], priceNonPo=[], uomNonPo=[];
					reference = $('.reference').val();
					actualdate = $('.actualdatedelivered').val();
					carrier = $('.carrier').val();
					remarks = $('.remarks').val();
					supplierid = $('.supplier').attr('suppid');
					currency = $('.currency').val();

					if(reference==''){
						$('.errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a<b> reference</b>.</div></div>");
						$('.content').animate({scrollTop:0},200);
						button.removeAttr('disabled');
					}
					else if(currency==''){
						$('.errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select <b>currency</b>.</div></div>");
						$('.content').animate({scrollTop:0},200);
						button.removeAttr('disabled');
					}
					else if(actualdate==''){
						$('.errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please specify <b>actual date delivered</b>.</div></div>");
						$('.content').animate({scrollTop:0},200);
						button.removeAttr('disabled');
					}
					else if($('#polineitems tr').length<1){
						$('.errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please add an<b> item</b>.</div></div>");
						$('.content').animate({scrollTop:0},200);
						button.removeAttr('disabled');
					}
					else{
						$('#polineitems tr').each(function(){
							if($(this).attr('rowid')=='NEW'){
								codeNonPo.push($(this).children('.rowcode').text());
								nameNonPo.push($(this).children('.rowname').text());
								descNonPo.push($(this).children('.rowdesc').text());
								qtyNonPo.push($(this).children('.rowqty').text().replace(/,/g,''));
								priceNonPo.push($(this).children('.rowprice').text().replace(/,/g,''));
								uomNonPo.push($(this).children('.rowuom').attr('uomid'));
							}else{
								lineitemid.push($(this).attr('rowid'));
								lineitemprice.push($(this).children('.rowprice').text().replace(/,/g,''));
								lineitemqty.push($(this).children('.rowqty').text().replace(/,/g,''));
							}
						});
						$('#loading-img').removeClass('hidden');
						$.post(server+'rcv-transaction.php',{JFKberkjoIfj23jl:'URhksoi3Hsri',supplierid:supplierid,currency:currency,remarks:remarks,carrier:carrier,reference:reference,items:lineitemid,prices:lineitemprice,qtys:lineitemqty,noncode:codeNonPo,nonname:nameNonPo,nondesc:descNonPo,nonqty:qtyNonPo,nonprice:priceNonPo,nonuom:uomNonPo,actualdate:actualdate},function(data){
							if(data.trim()=='success'){
								$.getScript(pagescripts+'receiving.js');
								$('.content').animate({scrollTop:0},200);
								$('.content').load('Transaction/receiving.php',function(){
										setTimeout(function(){
											$('#loading-img').addClass('hidden');
										},300);
										notify("Item(s) have been <b>received</b>", "success","fa fa-check-circle");
								});
								

							}else{
								alert(data);
								button.removeAttr('disabled');
							}
						});
					}

				},
				cancel:function(){ 
					button.removeAttr('disabled');
				}

	});




});

$(document).off('change','#rcv-geninfo .currency').on('change','#rcv-geninfo .currency',function(){
			var currency = $(this).val();
			var total = $('#grandtotal').val().replace(/,/g,'');
			var currencycode = $(this).find('option:selected').text();
			checkGrandTotal(total);
			if(currency=='NULL'||currency==''){
				$('#itemlist-line-data').load('Other/itemlist.php?oichf%jsl24=1');
				$('#rcvnonpopagination').load('Other/itemlist-pagination.php');
			}
			else{
				$('#itemlist-line-data').load('Other/itemlist.php?oichf%jsl24=1&currency='+currency);
				$('#rcvnonpopagination').load('Other/itemlist-pagination.php?currency='+currency);
			}
			$('.searchforitems').val('');
			$('#rcvnonpocurrency').html('<b>'+currencycode+'</b>');
});
