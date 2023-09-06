$(document).off('click','#fileupload-uploadbtn').on('click','#fileupload-uploadbtn',function(){
	$('#loading-img').removeClass('hidden');
	$('#uploadsupplier-form').submit();
});

$(document).off('click','#fileupload-cancelbtn').on('click','#fileupload-cancelbtn',function(){
	$('#loading-img').removeClass('hidden');
	$('.content').load('Maintenance/supplier.php',function(){
		setTimeout(function(){
			$.getScript(pagescripts+'supplier.js');
			$('#loading-img').addClass('hidden');
		},400);
	});
});