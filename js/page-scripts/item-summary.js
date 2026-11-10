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


$(document).off('click', '#rcvsummary-submitbtn, #itemsummary-exporttoexcel').on('click','#itemsummary-exporttoexcel', function(){
	// var summaryby, supplier, supplierid, reference, itemid, item, from, to, ponumber;
	// summaryby = $('.summaryby').val();
	// from = $('.datefrom').val();
	// to = $('.dateto').val();

    // window.open('Printouts/excel/item-summary.php?ItemSummaryExcelFormat=orderdatefrom=' + from + '&orderdateto=' + to);
    window.open('Printouts/excel/item-summary.php?ItemSummaryExcelFormat');

	
});