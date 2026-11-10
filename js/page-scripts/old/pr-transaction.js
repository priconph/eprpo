var filestobedeleted =[];
/*var attachmentsize = 0;
$('.fileattachment').on('change', function(evt) {
	for (var i = 0; i < $('.fileattachment').length; i++) {
		if($('.fileattachment')[i].files[0]){
			attachmentsize = attachmentsize+parseInt($('.fileattachment')[i].files[0].size);
		}
	};
    console.log(attachmentsize);
   
 });*/
//attachment btn - add more file

$(document).off('click','#prcreate-additembtn').on('click','#prcreate-additembtn', function(){
	if($('.currency').val()!='NULL'){
		$('#addpritemmodal').modal('show');
	}
	else{
		say('Please select a <b>currency</b>');
	}
});

$(document).off('click','#fileattachment-addbtn').on('click','#fileattachment-addbtn', function(){
	$('#attachmentstable').append("<tr><td><input type='checkbox' class='itemcheckbox'></td><td><input class='fileattachment' type='file' name='file[]'></td><td class='text-center'><select class='form-input form-control filetype' name='filetype[]'><option value=''>-</option><option value='Part Description/Specification'>Part Description/Specification</option><option value='Sample Unit'>Sample Unit</option><option value='Drawing Layout'>Drawing Layout</option><option value='Computation'>Computation</option><option value='NULL'>Other Description</option></select></td><td class='text-center'><input type='text' class='form-input form-control disabled otherfiletype' name='otherfiletype[]' readonly></td></tr>");
});
$(document).off('click','#fileattachment-deletebtn').on('click','#fileattachment-deletebtn', function(){
	$('#attachmentstable .itemcheckbox:checked').closest('tr').remove();
});

$(document).off('change','.filetype').on('change','.filetype',function(){
	if($(this).val()=='NULL'){
		$(this).parent().next().children(':first-child').removeClass('disabled').removeAttr('readonly');
	}
	else{
		$(this).parent().next().children(':first-child').addClass('disabled').attr('readonly','').val('');
	}
}); 

//cancelbtn
$(document).off('click','#prcreate-cancelbtn').on('click','#prcreate-cancelbtn', function(){
	$('.content').animate({scrollTop:0},200);
	$('#loading-img').removeClass('hidden');
	$('.content').load('Transaction/purchase-requisition.php',function(){
		$.getScript(pagescripts+'purchase-requisition.js');
		setTimeout(function(){
			$('#loading-img').addClass('hidden');
		},300);
	});
	
});

//delete selected in pritemstable
$(document).off('click','#prcreate-removeitembtn').on('click','#prcreate-removeitembtn', function(){
	if($('#pritems .itemcheckbox:checked').length>0){
		var code, grandtotal=0, conformanceneeded=false;
		$('#pritems .itemcheckbox:checked').each(function(){
			$(this).closest('tr').remove();
		});
		$('#pritems tr').each(function(){
			if($(this).attr('conformance')=='1'){
				conformanceneeded = true;
			}
			grandtotal = parseFloat(grandtotal)+parseFloat($(this).children('.amount').attr('amount'));
		});
		if(conformanceneeded==false){//check if hides conformance if not needed
			$('.confhead').removeClass('defaultsign').val('NULL').closest('.form-group').addClass('hidden');
		}
		$('#totalpramount').val(convertFloat(grandtotal)).attr('grandtotal',grandtotal);
		checkGrandTotal(grandtotal);
		var lnitem = 1;
		$('.rowlineitem').each(function(){
				$(this).text(lnitem);
				lnitem++;
		});
	}
});

//delete alocation btn
$(document).off('click','#prcreate-removeallocationbtn').on('click','#prcreate-removeallocationbtn', function(){
	$('#allocationsummary .itemcheckbox:checked').each(function(){
		$(this).closest('tr').remove();
	});
});


/////////////////////
$(document).off('change','.currency').on('change','.currency',function(){
	$('#lineitems tbody').empty();
	var currency = $(this).val();
	var currencycode = $(this).find('option:selected').text();
	var total = $('#totalpramount').val().replace(/,/g,'');
	checkGrandTotal(total);
	if(currency=='NULL'||currency==''){
		 $('#newpr-itemlisttable').DataTable().ajax.url( 'Other/ajax/itemlist.php' ).search('').load();
	}
	else{
		$('#newpr-itemlisttable').DataTable().ajax.url( 'Other/ajax/itemlist.php?currency='+currency).search('').load();
	}
	$('.searchforitems').val('');
	$('.totalcurrencycode').html('<b>'+currencycode+'</b>');

	var grandtotal = 0;
	var g = convertFloat(grandtotal);
	$('#totalpramount').val(g).attr('grandtotal',grandtotal);
	checkGrandTotal(grandtotal);
});


$(document).off('click','#allocationmodal-addbtn').on('click','#allocationmodal-addbtn', function(){
	var deptid, department, percentage, current, newperc, exist=false, totalpercperitem=0, temp;
	deptid = $('#allocationmodal .department').val();
	department = $('#allocationmodal .department').find("option:selected").text();
	percentage = $('#allocationmodal .percentage').val();
	remarks = $('#allocationmodal .allocationremarks').val();
	$('#allocationsummary tr').each(function(){
		totalpercperitem = parseFloat(totalpercperitem) + parseFloat($(this).children('.allocationpercentage').text());
	});
	totalpercperitem = parseFloat(totalpercperitem)+parseFloat(percentage);
	
	if(deptid==''){
		$('#allocationmodal .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a <b>department.</b></div></div>");
	}
	else if(percentage<.01){
		$('#allocationmodal .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span><b>Invalid Percentage Input</b><br>Please provide <b>percentage</b> for selected department.</div></div>");
	}
	else if(totalpercperitem>100){
		$('#allocationmodal .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span><b>Invalid Percentage Input</b><br>It exceeds the maximum percentage.</div></div>");
	}
	else{
		$('#allocationsummary tr').each(function(){
			if($(this).children('.allocationdeptid').text().search(new RegExp(department,"i"))>=0){
					current = $(this).children('.allocationpercentage').text();
					newperc = parseFloat(current) + parseFloat(percentage);
					$(this).children('.allocationpercentage').html(newperc);
					$(this).children('.allocationnotes').html(remarks);
					exist=true;
				}

		});
		if(exist==false){
			$('#allocationsummary').append("<tr ><td><input type='checkbox' class='itemcheckbox'></td><td class='allocationdeptid' allocdeptid='"+deptid+"'>"+department+"</td><td class='allocationpercentage text-center'>"+percentage+"</td><td class='allocationnotes'>"+remarks+"</td></tr>");
		}
		$('#allocationmodal .allocationremarks').val('');
		$('#allocationmodal .department').val('').trigger('change');
		$('#allocationmodal .percentage').val('');
		$('.modal').modal('hide');

	}
});

$(document).off('click','#addpr-itemlistmodal .itemlookuprow').on('click','#addpr-itemlistmodal .itemlookuprow', function(){
	$('#addpritemmodal .itemcode').val($(this).attr('code')).attr('itemid',$(this).attr('itemid')).attr('conformance',$(this).attr('conformance')).attr('uom',$(this).attr('uomcode'));
	$('#addpritemmodal .description').val($(this).attr('name'));
	$('#addpritemmodal .itemdesc').val($(this).attr('desc'));
	$('#addpritemmodal .unitprice').val($(this).attr('price'));

	$('#addpritemmodal .supplier').val($(this).attr('dftsuppname')).attr('supplierid',$(this).attr('dftsuppid'));
	$('#addpr-itemlistmodal').modal('hide');
	$('#addpritemmodal .classfn').focus();

	$('#addpr-supplierlistmodal #suppliertable').DataTable().ajax.url( 'Other/ajax/supplierlist.php?itemid='+$(this).attr('itemid')).search('').load();

});


$(document).off('click','#addpr-supplierlistmodal .supplierlookuprow').on('click','#addpr-supplierlistmodal .supplierlookuprow', function(){
		$('#addpritemmodal .supplier').val($(this).children('.suppliername').text()).attr('supplierid',$(this).attr('supplierid'));
		$('#addpr-supplierlistmodal').modal('hide');
		$('#addpritemmodal .quantity').focus();
});

$(document).off('click','#addpr-classcodemodal .classcodelookuprow').on('click','#addpr-classcodemodal .classcodelookuprow', function(){
		$('#addpritemmodal .classfn').val($(this).attr('acct_code'));
		$('#addpr-classcodemodal').modal('hide');
		$('#addpritemmodal .quantity').focus();
});

$(document).off('click','#addpritemmodal-addbtn').on('click','#addpritemmodal-addbtn', function(){
	var itemid, itemcode, conf, description, uom, classfn, supplierid, supplier, qty, unitprice, rowitemexist=false, conformance=false, modal, grandtotal=0, linecount, amount, floatamount, oldqty, newqty, oldamount, newamount, longdesc,rowtrim,rowexist=false,desctrim,itemdesctrim,classfntrim, supptrim, uptrim;
	modal = '#addpritemmodal';
	itemid = $(modal+' .itemcode').attr('itemid');
	itemcode = $(modal+' .itemcode').val();
	conf = $(modal+' .itemcode').attr('conformance');
	uom = $(modal+' .itemcode').attr('uom');
	longdesc = $(modal+' .itemdesc').val();
	description = $(modal+' .description').val();
	classfn = $(modal+' .classfn').val().toUpperCase();
	supplierid = $(modal+' .supplier').attr('supplierid');
	supplier = $(modal+' .supplier').val();
	qty = $(modal+' .quantity').val();
	unitprice = $(modal+' .unitprice').val();
	floatamount = parseFloat(qty)*parseFloat(unitprice);//.toFixed(5);
	//floatamount = Number(qty)*Number(unitprice);

	linecount = $('#pritems tr').length + 1;

	if(itemcode==''){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select an <b>item</b>.</div></div>");
	}
	else if(classfn==''){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please enter <b>classification code</b>.</div></div>");
	}
	else if(supplier==''){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a <b>supplier</b>.</div></div>");
	}
	else if(qty==''||qty<=0){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please enter <b>valid quantity</b>.</div></div>");
	}
	else if(unitprice==''||checkPrice(unitprice)==false){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please enter <b>valid unit price</b>.</div></div>");
	}
	else{
		amount = convertFloat(floatamount);
		desctrim = description.replace(/(\r\n|\n|\r|\s)/gm,'').toLowerCase();
		itemdesctrim = longdesc.replace(/(\r\n|\n|\r|\s)/gm,'').toLowerCase();
		classfntrim = classfn.replace(/(\r\n|\n|\r|\s)/gm,'').toLowerCase();
		supptrim = supplier.replace(/(\r\n|\n|\r|\s)/gm,'').toLowerCase();
		uptrim = convertPrice2(unitprice).replace(/(\r\n|\n|\r|\s)/gm,'').toLowerCase();
		
		//unitprice = convertFloat(unitprice);
		$('#pritems tr').each(function(){
			rowtrim = $(this).text().replace(/(\r\n|\n|\r|\s)/gm,'').toLowerCase();
			if($(this).text().search(new RegExp(itemcode,"i"))>=0){
				if(parseFloat($(this).children('.rowprice').text().replace(/,/g,''))==parseFloat(unitprice)){
					if(supplier==$(this).children('.rowsupplier').text()){
						rowitemexist = true;
						oldqty = $(this).children('.rowqty').text().replace(/,/g,'');
						newqty = parseFloat(oldqty) + parseFloat(qty);
						$(this).children('.rowclassfn').text(classfn);
						$(this).children('.rowprice').text(convertPrice2(unitprice));
						$(this).children('.rowsupplier').text(supplier);
						 $(this).children('.rowqty').text(Number(newqty).toLocaleString());


						newamount = parseFloat(newqty)*parseFloat(unitprice.replace(/,/g,'')).toFixed(5);
						var newamt = convertFloat(newamount);
						 $(this).children('.amount').text(newamt);
						 $(this).children('.amount').attr('amount',newamount);
					}
				}
			}
			//Check if item has same Item name, description, unit of measure, supplier, unit price, classification code and currency
			else {
				if(rowtrim.search(new RegExp(desctrim,"i"))>=0 && rowtrim.search(new RegExp(itemdesctrim,"i"))>=0 && rowtrim.search(new RegExp(classfntrim,"i"))>=0 && rowtrim.search(new RegExp(supptrim,"i"))>=0 && rowtrim.search(new RegExp(uptrim,"i"))>=0){			
					rowexist = true;
				}
			}
			
		});
		
		if(rowitemexist==false){
			if($('#pritems tr').length<6){
				if(rowexist==true){
					say('Item cannot be added. Item has the same name, description, classification code, supplier and unit price.<br>Please advice Purchasing for duplicate item.');
				} else {
					$('#pritems').append("<tr prrowid='"+itemid+"' conformance='"+conf+"'><td><input type='checkbox' class='itemcheckbox'></td><td class='text-center rowclick rowlineitem'>"+linecount+"</td><td class='rowclick'>"+itemcode+"</td><td class='rowclick'>"+description+"</td><td class='rowclick'>"+longdesc+"</td><td class='rowclassfn text-center rowclick'>"+classfn+"</td><td id='"+itemcode+"qty' class='rowqty rowclick text-right'>"+Number(qty).toLocaleString()+"</td><td class='rowclick text-center'>"+uom+"</td><td class='rowsupplier rowclick' supplierid='"+supplierid+"'>"+supplier+"</td><td class='rowprice text-right rowclick'>"+convertPrice2(unitprice)+"</td><td id='"+itemcode+"amount' class='amount text-right rowclick' amount='"+floatamount+"'>"+amount+"</td></tr>");
					var lnitem = 1;
					$('.rowlineitem').each(function(){
						$(this).text(lnitem);
						lnitem++;
					});
				}
			}
			else{
				say('Item cannot be added. Exceeds maximum number(6) of line items.');
			}
		}
		
		

		$('#pritems tr').each(function(){
			grandtotal = parseFloat(grandtotal) + parseFloat($(this).children('.amount').attr('amount'));
			if($(this).attr('conformance')=='1'){
				conformance = true;
			}
		});
		if(conformance==true){//shows conformance if needed
			$('.confhead').addClass('defaultsign').closest('.form-group').removeClass('hidden');
		}
		else{
			$('.confhead').removeClass('defaultsign').val('NULL').closest('.form-group').addClass('hidden');
		}
		var g = convertFloat(grandtotal);
		$('#totalpramount').val(g).attr('grandtotal',grandtotal);
		checkGrandTotal(grandtotal);
		$(modal).modal('hide');
		$('#addpritemmodal').on('hidden.bs.modal',function(){
			$(this).find('input').val('');
			$(this).find('textarea').text('').val('');

			$('#addpr-supplierlistmodal #suppliertable').DataTable().ajax.url( 'Other/ajax/supplierlist.php' ).search('').load();

			/*commentout10/19/16$('#addpr-supplierlistrows').load('Other/supplierlist-all.php',function(){
				$('#addpr-supplierlistrows .supplierlookuprow').slice(0,10).removeClass('hidden');
			});
			$('#addpr-supplierpagination').load('Other/supplierlist-pagination.php',function(){
				$('#addpr-supplierpagination li').slice(0,5).removeClass('hidden');
			});*/
			
		});
	}
});



//searching 
$(document).on('keyup','.lookupsearchinput',function(e){
		var text = $(this).val();
		if(e.which=='13'||e.keycode=='13'){
	    	//var text = $(this).val();
	    	$(this).closest('.lookupmodal').find('.tablerow').addClass('hidden');
	    	$(this).closest('.lookupmodal').find('.tablerow').removeClass('found');

	    	$(this).closest('.lookupmodal').find('.tablerow').each(function(){
	    		if($(this).text().search(new RegExp(text,"i"))>=0) {
	    			$(this).removeClass('hidden').addClass('found');
	    		}
	    	}); 

	    	if($(this).closest('.lookupmodal').find('.found').length<1){
	    		$(this).closest('.lookupmodal').find('.noresult').removeClass('hidden');
	    	}
	    	else{
	    		$(this).closest('.lookupmodal').find('.noresult').addClass('hidden');
	    	}

	    	////////////////////////////////search table text//////////////////////
				    var foundrows = $(this).closest('.modal').find('.tablerow.found').length;
				    $(this).closest('.modal').find('.table-text-search').text(foundrows+' result(s) found');
				    //hide "showing a-z of n entries"//
				    $(this).closest('.modal').find('.table-text-div').addClass('hidden');
				    //
				 //////////////////////////////////////////////////////////////////////

	    	if(text==""){
	    		$(this).closest('.lookupmodal').find('.tablerow').addClass('hidden');
	    		var actv = $('.pages .active').attr('value');
	    		var too = actv*rowperpage;
	    		var fromm = too-rowperpage;
	    		$(this).closest('.lookupmodal').find('.tablerow').slice(fromm,too).removeClass('hidden');

	    		///////////////////////////////
		    		$(this).closest('.modal').find('.table-text-search').text('');
		    		//show showing
		    		 $(this).closest('.modal').find('.table-text-div').removeClass('hidden');
		    		 //
		    		///////////////////////////
	    	}
	    }
	    else if(text==""){


		    		$(this).closest('.lookupmodal').find('.noresult').addClass('hidden');
		    	
	    		$(this).closest('.lookupmodal').find('.tablerow').addClass('hidden');
	    		var actv = $('.pages .active').attr('value');
	    		var too = actv*rowperpage;
	    		var fromm = too-rowperpage;
	    		$(this).closest('.lookupmodal').find('.tablerow').slice(fromm,too).removeClass('hidden');

	    		///////////////////////////////
		    		$(this).closest('.modal').find('.table-text-search').text('');
		    		//show showing
		    		 $(this).closest('.modal').find('.table-text-div').removeClass('hidden');
		    		 //
		    		///////////////////////////
	    }
});

//EDIT QTY ITEM
$(document).off('click','#pritems .rowclick').on('click','#pritems .rowclick', function(){
	var modal = '#editpritemmodal';
	$(modal+' .itemcode').val($(this).closest('tr').children('.rowlineitem').text());
	$(modal+' .quantity').val($(this).closest('tr').children('.rowqty').text().replace(/,/g,''));
	$(modal).modal('show');
	
});
$(document).off('click','#editpritemmodal-confirmbtn').on('click','#editpritemmodal-confirmbtn',function(){
		var modal = '#editpritemmodal';
		var itemcode = $(modal+' .itemcode').val();
		var qty = $(modal+' .quantity').val();
		var grandtotal = 0;
		if(qty!=''){
			$('#pritems tr').each(function(){
					//if($(this).text().search(new RegExp(itemcode,"i"))>=0){
					if(parseInt($(this).find('.rowlineitem').text())==parseInt(itemcode)){
						var newqty = parseInt(qty);
						//$('#'+itemcode+'qty').text(newqty.toLocaleString());
						$(this).children('.rowqty').text(newqty.toLocaleString());
						var newamount = parseInt(newqty)*parseFloat($(this).children('.rowprice').text().replace(/,/g,'')).toFixed(5);
						var newamt = convertFloat(newamount);
						$(this).children('.amount').text(newamt);
						$(this).children('.amount').attr('amount',newamount);
					}
					grandtotal = parseFloat(grandtotal) + parseFloat($(this).children('.amount').attr('amount').replace(/,/g,''));
					var gtotal = convertFloat(grandtotal);
					$('#totalpramount').val(gtotal).attr('grandtotal',grandtotal);
			});
			checkGrandTotal(grandtotal);
			$('#editpritemmodal').modal('hide');
		}
		else{
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please enter item <b>quantity</b>.</div></div>");
		}
});
//EDIT QTY ITEM - END
/*$('#addpritemmodal').on('hidden.bs.modal',function(){
			$(this).find('input').val('');
			$(this).find('textarea').text('').val('');
			alert('sdfsdf');
});*/

//SAVE/SUBMIT PR
$(document).off('click','.prsavesubmit:not(".disabled")').on('click','.prsavesubmit:not(".disabled")', function(){
	var button = $(this);
	button.addClass('disabled');
	var file, filetype, fileotherdesc, prnotes, allocvalid, currency, type, dateneeded, justification, sh1, sh2, dh, gm, avp, svp, p, conf, attachmentcount=0, defaultsign=false, items=[], qty=[], prices=[], suppliers=[], classfns=[], attachmenterr=false, allocremarks=[], allocdept=[], allocperc=[];
	currency = $('.pageContent .currency').val();
	type = $('.pageContent .reqtype').val();
	dateneeded = $('.pageContent .dateneeded').val();
	justification = $('.pageContent .justification').val();
	sh1 = $('.pageContent .sectionhead1').val();
	sh2 = $('.pageContent .sectionhead2').val();
	dh = $('.pageContent .depthead').val();
	gm = $('.pageContent .genman').val();
	avp = $('.pageContent .asstvp').val();
	svp = $('.pageContent .srvp').val();
	p = $('.pageContent .president').val();
	conf = $('.pageContent .confhead').val();
	prnotes = $('.pageContent .prnotes').val();
	$('.defaultsign').each(function(){
		if($(this).val()=='NULL'){
			defaultsign=true;
		}
	});
	//attachment validation
	$('#attachmentstable tr').each(function(){
			file = $(this).children(':nth-child(2)').children('.fileattachment').val();
			filetype = $(this).children(':nth-child(3)').children('.filetype').val();
			fileotherdesc = $(this).children(':nth-child(4)').children('.otherfiletype').val();
			if(file!=''){
				if(filetype!=''){
					if(filetype=='NULL'){
						if(fileotherdesc.trim()==''){
							attachmenterr=true;
						}
					}
				}
				else{
					attachmenterr=true;				
				}
			}
			else{
				$(this).remove();
			}
	});
	attachmentcount = $('#attachmentstable tr').length;
	allocvalid = checkAllocation();
	
	$('#pritems tr').each(function(){
				items.push($(this).attr('prrowid'));
			});
			$('#pritems .rowclassfn').each(function(){
				classfns.push($(this).text());
			});
			$('#pritems .rowqty').each(function(){
				qty.push($(this).text().replace(/,/g,''));
			});
			$('#pritems .rowprice').each(function(){
				prices.push($(this).text().replace(/,/g,''));
			});
			$('#pritems .rowsupplier').each(function(){
				suppliers.push($(this).attr('supplierid'));
			});
			$('#allocationsummary tr').each(function(){
				//allocitem.push($(this).children(':nth-child(2)').attr('allocitemid'));
				allocdept.push($(this).children('.allocationdeptid').attr('allocdeptid'));
				allocperc.push($(this).children('.allocationpercentage').text());
				allocremarks.push($(this).children('.allocationnotes').text());
	});


	//check attachment size
	var attachmentsize = 0;
	for (var i = 0; i < $('.fileattachment').length; i++) {
		if($('.fileattachment')[i].files[0]){
			attachmentsize = attachmentsize+parseInt($('.fileattachment')[i].files[0].size);
		}
	};
    console.log(attachmentsize);
	//////////////////////////

	if($(this).attr('id')=='prcreate-savebtn'){
			
			if(attachmenterr==true){
				$('.pageContent .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span><b>Attachment Error.</b><br>Please select attachment type or specify appropriate attachment description.</div></div>");
				$('#attachments').addClass('active').siblings().removeClass('active');
				$('.content').animate({scrollTop:0},200);
				button.removeClass('disabled');
			}
			else if(parseInt(attachmentsize)>2000000){
				$('.pageContent .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>File attachment(s) should not <b>exceed 2 mb</b>.</div></div>");
				$('#attachments').addClass('active').siblings().removeClass('active');
				$('.content').animate({scrollTop:0},200);
				button.removeClass('disabled');
			}
			else{	
					$('#source-button').val('savebtn');
					$('#loading-img').removeClass('hidden');
					$.post(server+'purchase-requisition.php',{SdjfrlFBroigjRi:'',prnotes:prnotes,reqtype:type,dateneeded:dateneeded,remarks:justification,sh1:sh1,sh2:sh2,gm:gm,avp:avp,svp:svp,pr:p,conf:conf,items:items,qty:qty,prices:prices,classfns:classfns,suppliers:suppliers,allocremarks:allocremarks,allocdept:allocdept,allocperc:allocperc,curr:currency},function(data){
						data = data.trim();
						$('#prnum').val(data);
						if(attachmentcount>0){
							$('#attachmentsform').submit();
							$('#loading-img').addClass('hidden');
							
						}
						else{
							$('.content').load('Transaction/purchase-requisition.php',function(){
									setTimeout(function(){
											$('#loading-img').addClass('hidden');
											notify("Purchase Requisition has been <b>saved</b>", "success","fa fa-check-circle");
									},300);
												
							});
							$.getScript(pagescripts+'purchase-requisition.js');
						} 
					});
			}
	}
	else{
		

		if($('#pritems tr').length<1){
			$('.pageContent .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>You haven't added any <b>item.</b></div></div>");
			$('#content').animate({scrollTop:0},200);
			button.removeClass('disabled');
		}
		// else if(allocvalid==false){
		else if(allocdept==false || allocvalid == false){
			$('.pageContent .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span><b>Purchase Requisition</b> is not properly allocated.<br>Please check allocation summary.</div></div>");
			$('#content').animate({scrollTop:0},200);
			$(this).removeClass('disabled');
		}
		else if(currency==''){
			$('.pageContent .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select <b>currency.</b></div></div>");
			$('#content').animate({scrollTop:0},200);
			button.removeClass('disabled');

			
		}
		else if(dateneeded==''){
			$('.pageContent .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide <b>date needed.</b></div></div>");
			$('#content').animate({scrollTop:0},200);
			button.removeClass('disabled');
		}
		else if(justification==''){
			$('.pageContent .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide <b>justification.</b></div></div>");
			$('#content').animate({scrollTop:0},200);
			button.removeClass('disabled');
		}
		else if(currency=='NULL'||currency==''){
			$('.pageContent .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select <b>currency.</b></div></div>");
			$('#attachments').addClass('active').siblings().removeClass('active');
			$('.content').animate({scrollTop:0},200);
			button.removeClass('disabled');
		}
		else if(attachmenterr==true){
			$('.pageContent .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span><b>Attachment Error.</b><br>Please select attachment type.<br>Or specify appropriate attachment description.</div></div>");
			$('#attachments').addClass('active').siblings().removeClass('active');
			$('#content').animate({scrollTop:0},200);
			button.removeClass('disabled');
		}
		else if(parseInt(attachmentsize)>2000000){
				$('.pageContent .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>File attachment(s) should not <b>exceed 2 mb</b>.</div></div>");
				$('#attachments').addClass('active').siblings().removeClass('active');
				$('.content').animate({scrollTop:0},200);
				button.removeClass('disabled');
		}
		else if(defaultsign==true){
			$('.pageContent .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select <b>required approvers.</b></div></div>");
			$('#content').animate({scrollTop:0},200);
			button.removeClass('disabled');
		}
		else if(sh1==sh2&&sh1!='1'&&sh2!='1'){
			$('.pageContent .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a different user<b> for section head(2) approver.</b></div></div>");
			$('#content').animate({scrollTop:0},200);
			button.removeClass('disabled');
		}
		else{
			$('#source-button').val('submitbtn');
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
										$('#loading-img').removeClass('hidden');
										$.post(server+'purchase-requisition.php',{SKdHeKorUowAnPl:'',prnotes:prnotes,reqtype:type,dateneeded:dateneeded,remarks:justification,sh1:sh1,sh2:sh2,gm:gm,avp:avp,svp:svp,pr:p,conf:conf,items:items,qty:qty,prices:prices,classfns:classfns,allocremarks:allocremarks,allocdept:allocdept,allocperc:allocperc,curr:currency,suppliers:suppliers},function(data){
											data = data.trim();
											//if(data.length==14){
												var dt = data.split('@#$');
												var response = dt[0];
												var reqnumber = dt[1];
												var emailto = dt[2];
												var emailtoname = dt[3];
												$('#prnum').val(reqnumber);
												//
												$('#email-to').val(emailto);
												$('#email-toname').val(emailtoname);

												//
												if(response=='success'){
													if(attachmentcount>0){
														
														$('#attachmentsform').submit();
														$('#loading-img').addClass('hidden');
													}
													else{
														$('.content').load('Transaction/purchase-requisition.php',function(){
															setTimeout(function(){
																$.getScript(pagescripts+'purchase-requisition.js');
																$('#loading-img').addClass('hidden');
																notify("Purchase Requisition has been <b>submitted</b>", "success","fa fa-check-circle");
															},300);
															
															
															
															
														});
														setTimeout(function(){
															$.post(server+'purchase-requisition.php',{emailnotification:'Nsl3Ep203N0amlOepan10@#0',reqnum:reqnumber,emailto:emailto,emailtoname:emailtoname},function(data){
																	if(data.trim()!=1||data.trim()!=true){
																		alert(data);
																	}
															});
														},1000);
													}
												}
												else{
													alert(response);
													button.removeClass('disabled');
													$('#loading-img').addClass('hidden');

												}


											
											
										});
										
									},
									cancel:function(){ 
										button.removeClass('disabled');
									}
									
			});

		}

		$('#generalinfosection>ul').children(':first-child').addClass('active').siblings().removeClass('active');
		$('.tab-panes').children(':first-child').addClass('active').siblings().removeClass('active');
	}
});


//////////////////////////// PAGINATION SLICE AND SEARCH ////////////////////
$(document).off('click','#itempagination li').on('click','#itempagination li',function(){
	var currency = $('.currency').val();
	var index = $(this).attr('value');
	//$('#addpr-itemlistmodal tbody').load('Other/itemlist.php?oichf%jsl24='+index);
	if(currency=='NULL'||currency==''){
		$('#addpr-itemlistmodal tbody').load('Other/itemlist.php?oichf%jsl24='+index);
	}
	else{
		$('#addpr-itemlistmodal tbody').load('Other/itemlist.php?oichf%jsl24='+index+'&currency='+currency);
	}
});

$(document).on('keyup','.newpr-searchforitems',function(e){
	var key = e.which||e.keycode;
	e.preventDefault();

	var input = $(this).val();
	var index = $('#itempagination li.active').attr('value');
	var currency = $('.currency').val();

	if(input==''){	
			//$('#addpr-itemlistmodal tbody').load('Other/itemlist.php?oichf%jsl24='+index);
			if(currency=='NULL'||currency==''){
				$('#addpr-itemlistmodal tbody').load('Other/itemlist.php?oichf%jsl24='+index);
			}
			else{
				$('#addpr-itemlistmodal tbody').load('Other/itemlist.php?oichf%jsl24='+index+'&currency='+currency);
			}	

			///////////////////////////////
			$(this).closest('.modal').find('.table-text-search').text('');
			//show showing
			$(this).closest('.modal').find('.table-text-div').removeClass('hidden');
			//
			///////////////////////////
	}
	else if(key==13){
			input=input.replace(/\s/g,'%20');

			if(currency=='NULL'||currency==''){
				$('#addpr-itemlistmodal tbody').load('Other/itemlist-search.php?oichf%jsl24='+input,function(){
					////////////////////////////////search table text//////////////////////
					var foundrows = $(this).closest('.modal').find('.tablerow').length;
					$(this).closest('.modal').find('.table-text-search').text(foundrows+' result(s) found');
					//hide "showing a-z of n entries"//
					$(this).closest('.modal').find('.table-text-div').addClass('hidden');
					 //
					//////////////////////////////////////////////////////////////////////
				});
			}
			else{
				$('#addpr-itemlistmodal tbody').load('Other/itemlist-search.php?oichf%jsl24='+input+'&currency='+currency,function(){
					////////////////////////////////search table text//////////////////////
					var foundrows = $(this).closest('.modal').find('.tablerow').length;
					$(this).closest('.modal').find('.table-text-search').text(foundrows+' result(s) found');
					//hide "showing a-z of n entries"//
					$(this).closest('.modal').find('.table-text-div').addClass('hidden');
					 //
					//////////////////////////////////////////////////////////////////////
				});
				
			}

			/*
			$('#addpr-itemlistmodal tbody').load('Other/itemlist-search.php?oichf%jsl24='+input,function(){
				////////////////////////////////search table text//////////////////////
				var foundrows = $(this).closest('.modal').find('.tablerow').length;
				$(this).closest('.modal').find('.table-text-search').text(foundrows+' result(s) found');
				//hide "showing a-z of n entries"//
				$(this).closest('.modal').find('.table-text-div').addClass('hidden');
				 //
				//////////////////////////////////////////////////////////////////////
			});*/
	}
});
		///////////////////////////////////////////////////////////////////////////////////






$(document).on('keyup','.addbylot-lotnumber',function(e){
	var key = e.which||e.keycode;
	e.preventDefault();

	var input = $(this).val();

	if(key==13&&input!=''){
			input=input.replace(/\s/g,'%20');
			$('#addprlotitemmodal tbody').load('Other/itemlist-lot.php?DksncooEpo30sjdoi='+input);
	}
});


$(document).on('keyup','.lotitem-classfncode, .lotitem-quantity',function(e){
	$(this).closest('tr').children(':first-child').children(':first-child').prop('checked',true);
});



$(document).off('click','#addprlotitemmodal-addbtn').on('click','#addprlotitemmodal-addbtn',function(){
	var error = false;
	$('#addprlotitemmodal tbody tr').each(function(){
		$(this).find('input, .chosen-single').css({borderColor:'#ddd'});

	});
	$('#addprlotitemmodal .lotitem-checkbox:checked').each(function(){
		var classfn = $(this).closest('tr').children('.col-classfn').children('.lotitem-classfncode');
		var qty = $(this).closest('tr').children('.col-qty').children('.lotitem-quantity');
		var supplier = $(this).closest('tr').children('.col-supplier').children('.lotitem-supplier');
		var supp = $(this).closest('tr').children('.col-supplier').find('.chosen-single');
		if(classfn.val()==''||qty.val()==''||checkInt(qty.val())==false||supplier.val()==''||parseInt(qty.val())<1){
			error = true;
			if(classfn.val()==''){
				classfn.css({borderColor:'red'});
			}
			if(qty.val()==''||parseInt(qty.val())<1||checkInt(qty.val())==false){
				qty.css({borderColor:'red'});
			}
			if(supplier.val()==''){
				supp.css({borderColor:'red'});
			}

		}
	});
	if(error==false){
		var linecount, rowitemexist=false, conformance=false, grandtotal = 0;
		$('#addprlotitemmodal .lotitem-checkbox:checked').each(function(){
			var linecount = parseInt($('#pritems tr').length)+1;
			var itemid = $(this).closest('tr').attr('itemid');
			var conf = $(this).closest('tr').attr('conformance');
			var itemcode = $(this).closest('tr').children(':nth-child(5)').text();
			var description = $(this).closest('tr').children(':nth-child(6)').text();//itemname
			var longdesc = $(this).closest('tr').children(':nth-child(7)').text();
			var classfn = $(this).closest('tr').children('.col-classfn').children('.lotitem-classfncode').val().toUpperCase();
			var qty = $(this).closest('tr').children('.col-qty').children('.lotitem-quantity').val();
			var unitprice = $(this).closest('tr').attr('price');
			var uom = $(this).closest('tr').attr('uomcode');
			var supplierid = $(this).closest('tr').children('.col-supplier').children('.lotitem-supplier').val();
			var supplier = $(this).closest('tr').children('.col-supplier').children('.lotitem-supplier').find(' option:selected').text();
			var floatamount = qty*unitprice;
			unitprice = convertPrice2(unitprice);
			var amount = convertFloat(floatamount);



			$('#pritems tr').each(function(){
				if($(this).text().search(new RegExp(itemcode,"i"))>=0){
					rowitemexist = true;
					var oldqty = $(this).children('.rowqty').text().replace(/,/g,'');
					var newqty = parseInt(oldqty) + parseInt(qty);
					$(this).children('.rowclassfn').text(classfn);
					$(this).children('.rowprice').text(unitprice);
					$(this).children('.rowsupplier').text(supplier);
					 $(this).children('.rowqty').text(Number(newqty).toLocaleString());


					var newamount = parseInt(newqty)*parseFloat(unitprice.replace(/,/g,''));
					var newamt = convertFloat(newamount);
					 $(this).children('.amount').text(newamt);
					 $(this).children('.amount').attr('amount',newamount);
				}
			});
			if(rowitemexist==false){
				$('#pritems').append("<tr prrowid='"+itemid+"' conformance='"+conf+"'><td><input type='checkbox' class='itemcheckbox'></td><td class='text-center rowclick'>"+linecount+"</td><td class='rowclick'>"+itemcode+"</td><td class='rowclick'>"+description+"</td><td class='rowclick'>"+longdesc+"</td><td class='rowclassfn text-center rowclick'>"+classfn+"</td><td id='"+itemcode+"qty' class='rowqty rowclick text-right'>"+Number(qty).toLocaleString()+"</td><td class='rowclick text-center'>"+uom+"</td><td class='rowsupplier rowclick' supplierid='"+supplierid+"'>"+supplier+"</td><td class='rowprice text-right rowclick'>"+convertPrice2(unitprice)+"</td><td id='"+itemcode+"amount' class='amount text-right rowclick' amount='"+floatamount+"'>"+amount+"</td></tr>");
			}
			rowitemexist = false;
		});

		$('#pritems tr').each(function(){
			grandtotal = parseInt(grandtotal) + parseFloat($(this).children('.amount').attr('amount'));
			if($(this).attr('conformance')=='1'){
				conformance = true;
			}
		});
		if(conformance==true){//shows conformance if needed
			$('.confhead').addClass('defaultsign').closest('.form-group').removeClass('hidden');
		}
		else{
			$('.confhead').removeClass('defaultsign').val('NULL').closest('.form-group').addClass('hidden');
		}
		var g = convertFloat(grandtotal);
		$('#totalpramount').val(g).attr('grandtotal',grandtotal);
		checkGrandTotal(grandtotal);
		$('#addprlotitemmodal').modal('hide');
		//$('#addprlotitemmodal tbody').html("<tr><td colspan='20' class='noresult'>No Items Found</td></tr>");
		//$('.addbylot-lotnumber').val('');

	}



	
});

