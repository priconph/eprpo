$(document).off('click','#fileupload-cancelbtn').on('click','#fileupload-cancelbtn',function(){
	$('#loading-img').removeClass('hidden');
	$('.content').load('Maintenance/items.php',function(){
		setTimeout(function(){
			$.getScript(pagescripts+'item.js');
			$('.content').animate({scrollTop:0},200);
			$('#loading-img').addClass('hidden');
		},400);
	});
	
});

$(document).off('click','#fileupload-uploadbtn').on('click','#fileupload-uploadbtn',function(){
	$('#loading-img').removeClass('hidden');
	$('#uploaditem-form').submit();
});