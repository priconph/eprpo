$(document).off('click','#prsummary-submitbtn, #prsummary-exporttoexcel').on('click','#prsummary-submitbtn, #prsummary-exporttoexcel', function(){
	var department, requester, supplier, reqfrom, reqto, neededfrom, neededto, qtymin, qtymax, pricemin, pricemax, prnumber, prstatus;
	prnumber = $('.prnumber').val();
	prstatus = $('.prstatus').val();
	department = $('.department').val();
	requester = $('.requestedby').val();
	supplier = $('.supplier').val();
	reqfrom = $('.reqfrom').val();
	reqto = $('.reqto').val();
	qtymin = $('.itemqtymin').val();
	qtymax = $('.itemqtymax').val();
	neededfrom = $('.neededfrom').val();
	neededto = $('.neededto').val();
	pricemin = $('.unitpricemin').val();
	pricemax = $('.unitpricemax').val();
	if($(this).attr('id')=='prsummary-submitbtn'){
		window.open('Printouts/pr-summary.php?KJdjh4lJlekNfkj3Iur3H2=prsummary&prstatus='+prstatus+'&prnumber='+prnumber+'&department='+department+'&requester='+requester+'&supplier='+supplier+'&reqfrom='+reqfrom+'&reqto='+reqto+'&neededfrom='+neededfrom+'&neededto='+neededto+'&qtymin='+qtymin+'&qtymax='+qtymax+'&pricemin='+pricemin+'&pricemax='+pricemax);
	}
	else{
		window.open('Printouts/excel/pr-summary.php?prSummaryExcelFormat01128167839=prsummary&prstatus='+prstatus+'&prnumber='+prnumber+'&department='+department+'&requester='+requester+'&supplier='+supplier+'&reqfrom='+reqfrom+'&reqto='+reqto+'&neededfrom='+neededfrom+'&neededto='+neededto+'&qtymin='+qtymin+'&qtymax='+qtymax+'&pricemin='+pricemin+'&pricemax='+pricemax);
	}
});

$(document).off('change','.department').on('change','.department', function(){
	var dept = $(this).val();

	$.post(server+'post-functions.php',{getDepartmentUsers:'sk@O#$@lai$juosn%Liso%snl',dept:dept},function(data){
		$('.requestedby').html(data).attr("data-placeholder", "Any").trigger("chosen:updated");
	});

});
