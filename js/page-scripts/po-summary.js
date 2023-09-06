$(document).off('click','#posummary-submitbtn, #posummary-exporttoexcel').on('click','#posummary-submitbtn, #posummary-exporttoexcel', function(){
	var supplier, status, from, to, dnfrom, dnto, staff;
	staff = $('.popurchasingstaff').val();
	supplier = $('.posupplier').val();
	status = $('.postatus').val();
	pobalance = $('.pobalanceflag').val();
	from = $('.datefrom').val();
	to = $('.dateto').val();
	dnfrom = $('.dateneededfrom').val();
	dnto = $('.dateneededto').val();
	if($(this).attr('id')=='posummary-submitbtn'){
		window.open('Printouts/po-summary.php?KFjskrlkdfjnkalel0iidjlk=posummary&supplierId='+supplier+'&pobalance='+pobalance+'&status='+status+'&orderdatefrom='+from+'&orderdateto='+to+'&dateneededfrom='+dnfrom+'&dateneededto='+dnto+'&staff='+staff);
	}
	else{
		window.open('Printouts/excel/po-summary.php?poSummaryExcelFormat019827@839=posummary&supplierId='+supplier+'&pobalance='+pobalance+'&status='+status+'&orderdatefrom='+from+'&orderdateto='+to+'&dateneededfrom='+dnfrom+'&dateneededto='+dnto+'&staff='+staff);
	}
});
