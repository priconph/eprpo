/////////////////ITEM LIST //////////////////////////////
$(document).off('click','#itempagination li').on('click','#itempagination li',function(){
	var index = $(this).attr('value');
	$('#itemlist-line-data').load('Other/itemlist.php?oichf%jsl24='+index);
});

$(document).off('click','.itemlookuprow').on('click','.itemlookuprow', function(){
	$('.itemcode').val($(this).children(':nth-child(2)').text()).attr('itemid',$(this).attr('itemid'));
	$('#itemlistmodal').modal('hide');
});

$(document).on('keyup','.searchforitems',function(e){
			var key = e.which||e.keycode;
			e.preventDefault();

			var input = $(this).val();
			var index = $('#itempagination li.active').attr('value');

			if(input==''){
				$('#itemlistmodal tbody').load('Other/itemlist.php?oichf%jsl24='+index);
			}
			else if(key==13){
				input=input.replace(/\s/g,'%20');
				$('#itemlistmodal tbody').load('Other/itemlist-search.php?oichf%jsl24='+input);
			}
});
//////////////// ITEM LIST END ///////////////////////////



$(document).on('change','.summaryby',function(){
	$('.reference').val('');
	$('.itemcode').val('');
	var summaryby = $(this).val();

	if(summaryby=='po'||summaryby=='nonpo'){
		$('#referencegroup').removeClass('hidden');
		$('#itemgroup').addClass('hidden');
		$('#suppliergroup').addClass('hidden');
		$('#noncatalogitemgroup').addClass('hidden');
		if(summaryby=='po'){
			$('#ponumbergroup').removeClass('hidden');
		}
		else{
			$('#ponumbergroup').addClass('hidden');
		}
	}
	else if(summaryby=='catalogitem'){
		$('#referencegroup').addClass('hidden');
		$('#itemgroup').removeClass('hidden');
		$('#suppliergroup').addClass('hidden');
		$('#noncatalogitemgroup').addClass('hidden');
		$('#ponumbergroup').addClass('hidden');
	}
	else if(summaryby=='noncatalogitem'){
		$('#referencegroup').addClass('hidden');
		$('#itemgroup').addClass('hidden');
		$('#suppliergroup').addClass('hidden');
		$('#noncatalogitemgroup').removeClass('hidden');
		$('#ponumbergroup').addClass('hidden');
	}
	else if(summaryby=='supplier'){
		$('#referencegroup').addClass('hidden');
		$('#itemgroup').addClass('hidden');
		$('#suppliergroup').removeClass('hidden');
		$('#noncatalogitemgroup').addClass('hidden');
		$('#ponumbergroup').addClass('hidden');
	}
	else if(summaryby=='any'){
		$('#referencegroup').addClass('hidden');
		$('#itemgroup').addClass('hidden');
		$('#suppliergroup').addClass('hidden');
		$('#noncatalogitemgroup').addClass('hidden');
		$('#ponumbergroup').addClass('hidden');
	}
});


$(document).off('click','#rcvsummary-submitbtn, #rcvsummary-exporttoexcel').on('click','#rcvsummary-submitbtn, #rcvsummary-exporttoexcel', function(){
	var summaryby, supplier, supplierid, reference, itemid, item, from, to, ponumber;
	summaryby = $('.summaryby').val();
	from = $('.datefrom').val();
	to = $('.dateto').val();

	if(summaryby=='po'){
		reference = $('.reference').val();
		ponumber = $('.ponumber').val();
		if($(this).attr('id')=='rcvsummary-submitbtn'){
			window.open('Printouts/rcv-summary-reference.php?DlenjF34KJddlkeornfk349n=rcvsummary&by='+summaryby+'&reference='+reference+'&orderdatefrom='+from+'&orderdateto='+to+'&ponumber='+ponumber);
		}
		else{
			window.open('Printouts/excel/rcv-summary-reference.php?rcvSummaryReferenceExcelFormat1231003435=rcvsummary&by='+summaryby+'&reference='+reference+'&orderdatefrom='+from+'&orderdateto='+to+'&ponumber='+ponumber);
		}
	}
	else if(summaryby=='nonpo'){
		reference = $('.reference').val();
		if($(this).attr('id')=='rcvsummary-submitbtn'){
			window.open('Printouts/rcv-summary-nonpo.php?DlenjF34KJddlkeornfk349n=rcvsummary&by='+summaryby+'&reference='+reference+'&orderdatefrom='+from+'&orderdateto='+to);
		}
		else{
			window.open('Printouts/excel/rcv-summary-nonpo.php?rcvSummaryNonPoExcelFormat1231003435=rcvsummary&by='+summaryby+'&reference='+reference+'&orderdatefrom='+from+'&orderdateto='+to);
		}
	}
	else if(summaryby=='catalogitem'){
		item = $('.itemcode').val();
		itemid = $('.itemcode').attr('itemid');
		if($(this).attr('id')=='rcvsummary-submitbtn'){
			window.open('Printouts/rcv-summary-catalogitem.php?DlenjF34KJddlkeornfk349n=rcvsummary&by='+summaryby+'&itemcode='+item+'&itemid='+itemid+'&orderdatefrom='+from+'&orderdateto='+to);
		}
		else{
			window.open('Printouts/excel/rcv-summary-catalogitem.php?rcvSummaryCatalogItemExcelFormat1231003435=rcvsummary&by='+summaryby+'&itemcode='+item+'&itemid='+itemid+'&orderdatefrom='+from+'&orderdateto='+to);
		}
	}
	else if(summaryby=='noncatalogitem'){
		item = $('.noncatalogitem').val();
		if($(this).attr('id')=='rcvsummary-submitbtn'){
			window.open('Printouts/rcv-summary-noncatalogitem.php?DlenjF34KJddlkeornfk349n=rcvsummary&by='+summaryby+'&itemcode='+item+'&orderdatefrom='+from+'&orderdateto='+to);
		}
		else{
			window.open('Printouts/excel/rcv-summary-noncatalogitem.php?rcvSummaryNonCatalogItemExcelFormat1231003435=rcvsummary&by='+summaryby+'&itemcode='+item+'&orderdatefrom='+from+'&orderdateto='+to);
		}
	}
	else if(summaryby=='supplier'){
		supplierid=$('.supplier').val();
		supplier = $('.supplier option:selected').text();
		if($(this).attr('id')=='rcvsummary-submitbtn'){
			window.open('Printouts/rcv-summary-supplier.php?DlenjF34KJddlkeornfk349n=rcvsummary&by='+summaryby+'&supplier='+supplier+'&suppId='+supplierid+'&orderdatefrom='+from+'&orderdateto='+to);
		}
		else{
			window.open('Printouts/excel/rcv-summary-supplier.php?rcvSummarySupplierExcelFormat1231003435=rcvsummary&by='+summaryby+'&supplier='+supplier+'&suppId='+supplierid+'&orderdatefrom='+from+'&orderdateto='+to);
		}
	}
	else if(summaryby=='any'){
		if($(this).attr('id')=='rcvsummary-submitbtn'){
			window.open('Printouts/rcv-summary.php?DlenjF34KJddlkeornfk349n=rcvsummary&by='+summaryby+'&orderdatefrom='+from+'&orderdateto='+to);
		}
		else{
			window.open('Printouts/excel/rcv-summary.php?rcvSummaryExcelFormat1231003435=rcvsummary&by='+summaryby+'&orderdatefrom='+from+'&orderdateto='+to);
		}
	}
	
});