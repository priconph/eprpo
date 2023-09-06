/***** CREATE NEW ITEM BTN *****/
$(document).off('click','.item-modal-savebtn').on('click','.item-modal-savebtn',function(){
		$(this).attr('disabled',true);
		var id, code, shortdesc, longdesc, conformance, supplier, price, uom, remarks, modal, action, stat, leadtime, currency, tags;
		id='';
		stat='';
		modal = $(this).attr('id')=='itemcreate-savebtn'?'#additemmodal':'#edititemmodal';
		action=(modal=='#additemmodal')?'add':'edit';

		var button = $(this);

		code = $(modal+' .itemcode').val();
		shortdesc = $(modal+' .shortdesc').val();
		longdesc = $(modal+' .longdesc').val();
		conformance = $(modal+' .conformanceflag').val();
		supplier = $(modal+' .defaultsupplier').val();
		leadtime = $(modal+' .leadtime').val();
		price = $(modal+' .unitprice').val();
		uom = $(modal+' .uom').val();
		remarks = $(modal+' .remarks').val();
		currency = $(modal+' .currency').val();
		tags = $(modal+' .lotnumbertags').val();

		if(action=='edit'){
			id=$(modal+' .itemid').val();
			stat=$(modal+' .activeflag').val();
		}

		if(code==''){
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide <b>Item Code.</b></div></div>");
			button.removeAttr('disabled');
		}
		else if(shortdesc==''){
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide <b>Item Name.</b></div></div>");
			button.removeAttr('disabled');
		}
		else if(conformance=='NULL'){
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select if <b>Conformance</b> is needed for the item.</div></div>");
			button.removeAttr('disabled');
		}
		else if(supplier=='NULL'){
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select <b>Default Supplier.</b></div></div>");
			button.removeAttr('disabled');
		}
		else if(checkPrice(price)==false){
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please enter <b>Unit Price.</b></div></div>");
			button.removeAttr('disabled');
		}
		else if(currency=='NULL'){
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select <b>Default Currency.</b></div></div>");
			button.removeAttr('disabled');
		}
		else if(uom=='NULL'){
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select <b>Unit of Measure.</b></div></div>");
			button.removeAttr('disabled');
		}
		else{

			$('#loading-img').removeClass('hidden');
			$.post(server+'item.php',{itemtransaction:action,id:id,tags:tags,stat:stat,code:code,currency:currency,shortdesc:shortdesc,longdesc:longdesc,conformance:conformance,supplier:supplier,lead:leadtime,price:price,uom:uom,remarks:remarks},function(data){

				if(data.trim()=='success'){
						successful(modal,'Maintenance/items.php');
						$(modal).modal('hide');
						$(modal).on('hidden.bs.modal',function(){							
							$('#loading-img').addClass('hidden');
							button.removeAttr('disabled');
							$(modal).off('hidden.bs.modal');
						});
						$('#itemtable').DataTable().ajax.reload();
						
						$('.content').load('Maintenance/items.php',function(){});
					}
					else if(data.trim() == 'exist') {
						$('#loading-img').addClass('hidden');
						//notify("<i>Item already exist with same item name, description, unit price, currency, unit price and default supplier.</i>", "success","fa fa-check-circle");
						$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Item already exist with same item name, description, unit price, currency, unit price and default supplier.</div></div>");
						button.removeAttr('disabled');
					}
					else{
						$('#loading-img').addClass('hidden');
						alert(data);
						button.removeAttr('disabled');
					}
			});	
		}
});

$(document).off('click','.edititembtn').on('click','.edititembtn',function(){
	$('#edititemmodal .itemid').val($(this).attr('id'));
	$('#edititemmodal .itemcode').val($(this).attr('code'));
	$('#edititemmodal .shortdesc').val($(this).attr('shortdesc'));
	$('#edititemmodal .longdesc').val($(this).attr('longdesc'));
	$('#edititemmodal .activeflag').val($(this).attr('stat'));
	$('#edititemmodal .conformanceflag').val($(this).attr('conformance'));
	$('#edititemmodal .defaultsupplier').val($(this).attr('supplier').split('#@$'));
	$('#edititemmodal .leadtime').val($(this).attr('leadtime'));
	$('#edititemmodal .unitprice').val($(this).attr('price'));
	$('#edititemmodal .currency').val($(this).attr('currency'));
	$('#edititemmodal .uom').val($(this).attr('uom'));
	$('#edititemmodal .remarks').val($(this).attr('remarks'));

	$('#smp').html("<input type='text' class='form-control lotnumbertags' value='"+$(this).attr('tags')+"' data-role='tagsinput'>");
	$('#edititemmodal .lotnumbertags').tagsinput('refresh');

	$('.chosen-select').trigger('chosen:updated');
	//alert($(this).attr('tags'));
});

/***** DELETE ITEM BTN *****/
$(document).off('click','#deleteitembtn').on('click','#deleteitembtn',function(){
		var button = $(this);
		button.attr('disabled',true);
		var id, response;

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
						id = [];
						$('.itemcheckbox:checked').each(function(){
							id.push($(this).attr('rowid'));
						});
						if(id.length>0){
							$('#loading-img').removeClass('hidden');
							$.post(server+'item.php',{deleteselected:'',id:id},function(data){
								if(data.trim()=='success'){
									$('.content').load('Maintenance/items.php',function(){
										setTimeout(function(){
											$('#loading-img').addClass('hidden');
										},500);
									});
								}
								else if(data.trim()=='ERROR1451'){
									$('#loading-img').addClass('hidden');
									notify("<i>Item(s) cannot be deleted. It is used in previous PR/PO Transactions.<br>Please contact System Administrator.</i>", "success","fa fa-check-circle");
									button.removeAttr('disabled');
								}
								else{
									alert(data);
									$('#loading-img').addClass('hidden');
									button.removeAttr('disabled');

								}
							});
						}
						button.removeAttr('disabled');
					},
					cancel:function(){ 
						button.removeAttr('disabled');
					}
					
		});


});

$(document).off('click','#uploaditembtn').on('click','#uploaditembtn',function(){
	$('#loading-img').removeClass('hidden');
	$('.content').load('Other/item-upload-file.php',function(){
		setTimeout(function(){
			$.getScript(pagescripts+'item-upload-file.js');
			$('.content').animate({scrollTop:0},200);
			$('#loading-img').addClass('hidden');
		},400);
	});
	
});

//////////////////////////// PAGINATION SLICE AND SEARCH ////////////////////
$(document).off('click','#itempagination li').on('click','#itempagination li',function(){
	var index = $(this).attr('value');
	$('#itemrows').load('Other/item-table-data.php?oichf%jsl24='+index);
});

$(document).on('keyup','.searchforitems',function(e){
	var key = e.which||e.keycode;
	e.preventDefault();

	var input = $(this).val();
		input = input.replace(/\s/g,'%20');
	var index = $('#itempagination li.active').attr('value');

	if(input==''){
		$('#itemrows').load('Other/item-table-data.php?oichf%jsl24='+index);

		///////////////////////////////
		$(this).closest('.panel').find('.table-text-search').text('');
		//show showing
		$(this).closest('.panel').find('.table-text-div').removeClass('hidden');
		//
		///////////////////////////
	}
	else if(key==13){
		$('#itemrows').load('Other/maintenance-itemlist-search.php?oichf%jsl24='+input,function(){
			////////////////////////////////search table text//////////////////////
			var foundrows = $(this).closest('.panel').find('.tablerow').length;
			$(this).closest('.panel').find('.table-text-search').text(foundrows+' result(s) found');
			//hide "showing a-z of n entries"//
			$(this).closest('.panel').find('.table-text-div').addClass('hidden');
			 //
			//////////////////////////////////////////////////////////////////////

		});
			

	}


});



///////////////////////////////////////////////////////////////////////////////////