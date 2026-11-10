
const timestamp = new Date().getTime();

$(document).off('click','#prpendingapprovaltable .tablerow').on('click','#prpendingapprovaltable .tablerow', function(){
	$('#loading-img').removeClass('hidden');
	var prnumber = $(this).attr('id');

	$.getScript(pagescripts + 'purchase-requisition-approval.js?v=' + timestamp);
	$('.content').load("Approvals/purchase-requisition-approval.php?LdekFprJdlURd="+prnumber,function(){
						setTimeout(function(){
							$('#loading-img').addClass('hidden');
						},300);
					});
	
	$('.content').animate({scrollTop:0},200);
	
});

$(document).off('click','#popendingapprovaltable .tablerow').on('click','#popendingapprovaltable .tablerow', function(){
	$('#loading-img').removeClass('hidden');
	var ponumber = $(this).attr('po');
	$('.content').load("Approvals/purchase-order-approval.php?Ksfrlwowp%kspej%Dhor="+ponumber,function(){
						setTimeout(function(){
							$('#loading-img').addClass('hidden');
						},300);
					});
	$.getScript(pagescripts + 'purchase-order-approval.js?v=' + timestamp);
	$('.content').animate({scrollTop:0},200);
	
});

$(document).off('click','#rcvpendingapprovaltable .tablerow').on('click','#rcvpendingapprovaltable .tablerow', function(){
	$('#loading-img').removeClass('hidden');
	var receiving_number = $(this).attr('rcv');
	$('.content').load("Approvals/receiving-approval.php?Gde*$jddwRx="+receiving_number,function(){
						setTimeout(function(){
							$('#loading-img').addClass('hidden');
						},300);
					});
	$.getScript(pagescripts + 'receiving-approval.js?v=' + timestamp);
	$('.content').animate({scrollTop:0},200);
	
});

$(document).off('click','#prhistorypagination li').on('click','#prhistorypagination li',function(){
	var index = $(this).attr('value');
	$('#prhistory').load('Other/dashboard-pr-approval-history.php?Kdjroi34hl5k4oajDLeo='+index);
});

$(document).off('click','#pohistorypagination li').on('click','#pohistorypagination li',function(){
	var index = $(this).attr('value');
	$('#pohistory').load('Other/dashboard-po-approval-history.php?Kdjoeiuwp3io3ipNdKlp='+index);
});

$(document).off('click','#dashsettingsbtn').on('click','#dashsettingsbtn',function(){
	$('#loading-img').removeClass('hidden');
	$('#dash').removeClass('active');
	$('.content').load("user-settings.php",function(){
						setTimeout(function(){
							$('#loading-img').addClass('hidden');
						},300);
					});
	$.getScript(pagescripts + 'user-settings.js?v=' + timestamp);
	$('.content').animate({scrollTop:0},200);
});


$(document).off('click','#dashlogoutbtn').on('click','#dashlogoutbtn',function(){
	window.location.href='logout.php';
});

$(document).off('click','.viewdetailslink').on('click','.viewdetailslink', function(){
	var prnumber = $(this).attr('prnumber');
	window.open('Other/purchase-requisition-source-window.php?ksjcno0923jsbkyiu94hnsk='+prnumber);
});

$(document).off('click','.prrowlog').on('click','.prrowlog', function(){
	var revflag = $(this).attr('prflag'),
		prnumber = $(this).attr('prnumber');
	window.open('Other/purchase-requisition-approval-log.php?ksjcno0923jsbkyiu94hnsk='+prnumber+'&rev='+revflag);
});

$(document).off('click','.porowlog').on('click','.porowlog', function(){
	var revflag = $(this).attr('poflag'),
		ponumber = $(this).attr('ponumber');
	window.open('Other/purchase-order-approval-log.php?ksjcno0923jsbkyiu94hnsk='+ponumber+'&rev='+revflag);
});

$(document).off('click','.rcvrowlog').on('click','.rcvrowlog', function(){
	var revflag = $(this).attr('rcvflag'),
		rcvnumber = $(this).attr('rcvnumber');
	window.open('Other/receiving-approval-log.php?dasdJGsbdfuwn1d='+rcvnumber+'&rev='+revflag);
});


$(document).off('click','.ponotificationrow').on('click','.ponotificationrow', function(){
	$('#poblowup').modal('show');
	var withdiscrepancy, dateneeded, lastapprovedby, forrevision, ponumber, postedby, assignedto, attn, prnumber, status, supplier, supplierAddr, expdelivery, orderdate, remarks, grandtotal, terms, shipvia, currency, mode;
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
	mode = $(this).closest('tr').attr('mode');
	attn = $(this).closest('tr').attr('attn');
	assignedto = $(this).closest('tr').attr('assignedto');
	lastapprovedby = $(this).closest('tr').attr('lastapprovedby');
	postedby = $(this).closest('tr').attr('postedby');
	forrevision = $(this).closest('tr').attr('forrevision');
	dateneeded = $(this).closest('tr').attr('dateneeded');


	$('#poitems').load('Transaction/purchase-order-items.php?Oinsldfru='+ponumber);
	$('#poitemsreceived').load('Transaction/purchase-order-itemsreceived.php?KJeslkrioj43lk='+ponumber);
	$('#ponumber').text(ponumber);
	$('#prnumber').html(prnumber+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class='viewdetailslink pointer' prnumber='"+prnumber+"'style='font-style:italic;color:blue;font-size:11px'>View details</span>");
	$('#orderstatus').text(status);
	$('#vendor').text(supplier);
	$('#supplierAddress').text(supplierAddr);
	$('.poblowupcurrencyaddon').text(currency);
	//$('#expdelivery').text(expdelivery);
	$('#orderdate').text(orderdate);
	$('#remarks').text(remarks);
	$('#grandtotal').val(grandtotal);
	$('#paymentterms').text(terms);
	$('#shipvia').text(shipvia);
	$('#pocurrency').text(currency);
	$('#shipmentmode').text(mode);
	$('#lastapprovedby').text(lastapprovedby);
	$('#attn').text(attn);
	$('#assignedto').text(assignedto);
	$('#postedby').text(postedby);
	$('#dateneeded').text(dateneeded);


});


var quicklinks = "#dashboardquicklinks";
$(document).off('click',quicklinks+' .qlprapproval').on('click',quicklinks+' .qlprapproval', function(){
	$('#purchase-requisition-row .nav-tabs li[data-pane="#dashboard-pr-approval-pane"]').addClass('active').siblings().removeClass('active');
	$('#purchase-requisition-row #dashboard-pr-approval-pane').addClass('active').siblings().removeClass('active');
	$('.content').animate({scrollTop:240},200);
});

$(document).off('click',quicklinks+' .qlprhistory').on('click',quicklinks+' .qlprhistory', function(){
	$('.content').animate({scrollTop:240},200);
	$('#purchase-requisition-row .nav-tabs li[data-pane="#dashboard-pr-history-pane"]').addClass('active').siblings().removeClass('active');
	$('#purchase-requisition-row #dashboard-pr-history-pane').addClass('active').siblings().removeClass('active');
});

$(document).off('click',quicklinks+' .qlpoapproval').on('click',quicklinks+' .qlpoapproval', function(){
	$('.content').animate({scrollTop:580},200);
	$('#purchase-order-row .nav-tabs li[data-pane="#dashboard-po-approval-pane"]').addClass('active').siblings().removeClass('active');
	$('#purchase-order-row #dashboard-po-approval-pane').addClass('active').siblings().removeClass('active');
});

$(document).off('click',quicklinks+' .qlpohistory').on('click',quicklinks+' .qlpohistory', function(){
	$('.content').animate({scrollTop:580},200);
	$('#purchase-order-row .nav-tabs li[data-pane="#dashboard-po-history-pane"]').addClass('active').siblings().removeClass('active');
	$('#purchase-order-row #dashboard-po-history-pane').addClass('active').siblings().removeClass('active');
});

$(document).off('click',quicklinks+' .qlholdinvoice').on('click',quicklinks+' .qlholdinvoice', function(){
	$('#receiving-row .nav-tabs li[data-pane="#dashboard-hold-supplier-pane"]').addClass('active').siblings().removeClass('active');
	$('#receiving-row #dashboard-hold-supplier-pane').addClass('active').siblings().removeClass('active');
	$('.content').animate({scrollTop:920},200);
});

$(document).off('click',quicklinks+' .qlrcvapproval').on('click',quicklinks+' .qlrcvapproval', function(){
	$('#receiving-row .nav-tabs li[data-pane="#dashboard-item-approval-pane"]').addClass('active').siblings().removeClass('active');
	$('#receiving-row #dashboard-item-approval-pane').addClass('active').siblings().removeClass('active');
	$('.content').animate({scrollTop:920},200);
});

$(document).off('click',quicklinks+' .qlrcvhistory').on('click',quicklinks+' .qlrcvhistory', function(){
	$('.content').animate({scrollTop:920},200);
	$('#receiving-row .nav-tabs li[data-pane="#dashboard-rcv-history-pane"]').addClass('active').siblings().removeClass('active');
	$('#receiving-row #dashboard-rcv-history-pane').addClass('active').siblings().removeClass('active');
});

$(document).off('click',quicklinks+' .qlappstatus').on('click',quicklinks+' .qlappstatus', function(){
	$('#item-approval-row .nav-tabs li[data-pane="#dashboard-rcv-approval-pane"]').addClass('active').siblings().removeClass('active');
	$('#item-approval-row #dashboard-rcv-approval-pane').addClass('active').siblings().removeClass('active');
	$('.content').animate({scrollTop:920},200);
});

$(document).off('click',quicklinks+' .qlrcvhistory').on('click',quicklinks+' .qlrcvhistory', function(){
	$('.content').animate({scrollTop:920},200);
	$('#receiving-row .nav-tabs li[data-pane="#dashboard-rcv-history-pane"]').addClass('active').siblings().removeClass('active');
	$('#receiving-row #dashboard-rcv-history-pane').addClass('active').siblings().removeClass('active');
});


$(document).off('click','.qlpoassignment, .actql-assignpo, .actql-viewpo').on('click','.qlpoassignment, .actql-assignpo, .actql-viewpo', function(){
	$('.content').animate({scrollTop:0},200);
	$.getScript(pagescripts + 'purchase-order.js?v=' + timestamp);
	$('.content').load('Transaction/purchase-order.php',function(){
		setTimeout(function(){
			$('#loading-img').addClass('hidden');
		},300);

	});
	$('#dash').removeClass('active');
});

$(document).off('click','.actql-viewpo-history').on('click','.actql-viewpo-history', function(){
	$('.content').animate({scrollTop:0},200);
	// $.getScript(pagescripts+'purchase-order.js?v=' + timestamp);
	$('.content').load('Transaction/po-history.php',function(){
		setTimeout(function(){
			$('#loading-img').addClass('hidden');
		},300);

	});
	$('#dash').removeClass('active');
});


$(document).off('click','#prdetailtbdy .prinforow').on('click','#prdetailtbdy .prinforow', function(){
	var prnumber = $(this).attr('id');
	window.open('Other/approver-purchase-requisition-view.php?fkzjewum0rbmwaoisualoweiu='+prnumber);
});

$(document).off('click','.actql-viewpr').on('click','.actql-viewpr', function(){
	$('.content').animate({scrollTop:0},200);
	$.getScript(pagescripts + 'purchase-requisition.js?v=' + timestamp);
	$('.content').load('Transaction/purchase-requisition.php',function(){
		setTimeout(function(){
			$('#loading-img').addClass('hidden');
		},300);

	});
	$('#dash').removeClass('active');
});

$(document).off('click','.actql-newpr').on('click','.actql-newpr', function(){
	$('.content').animate({scrollTop:0},200);
	$.getScript(pagescripts + 'pr-transaction.js?v=' + timestamp);
	$('.content').load('Transaction/pr-transaction.php',function(){
		setTimeout(function(){
			$('#loading-img').addClass('hidden');
		},300);

	});
	$('#dash').removeClass('active');
});

$(document).off('click','.actql-changepwd').on('click','.actql-changepwd', function(){
	$('.content').animate({scrollTop:0},200);
	$.getScript(pagescripts + 'user-settings.js?v=' + timestamp);
	$('.content').load('user-settings.php',function(){
		setTimeout(function(){
			$('#loading-img').addClass('hidden');
		},300);

	});
	$('#dash').removeClass('active');
});

$(document).off('click','.itemapprovalrow').on('click','.itemapprovalrow', function(){
	$('.content').animate({scrollTop:0},200);
	$.getScript(pagescripts + 'item.js?v=' + timestamp);
	$('.content').load('Maintenance/items.php',function(){
		setTimeout(function(){
			$('#loading-img').addClass('hidden');
		},300);

	});
	$('#dash').removeClass('active');

});