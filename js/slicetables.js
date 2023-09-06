/*************** AUTHOR:KAYE BONTIE ***************************/
/*************** TURNINGPOINT INC. ****************************/
$(document).ready(function(){


	    	var rowperpage = 9; 


	    	if($('.tablerow').length<1){
	    		$('.noresult').removeClass('hidden');
	    	}




	    	$('.table .tablerow').addClass('hidden');
	    	$('.table .tablerow').slice(0,rowperpage).removeClass('hidden');

	    	$('.pagination li:first').addClass('active');
	    	$('.pagination li').addClass('hidden');
	    	$('.pagination li').slice(0,5).removeClass('hidden');



	    	$('.pagination li').click(function(){

	    		$('.table .tablerow').addClass('hidden');

	    		$(this).addClass('active').siblings('li').removeClass('active');

	    		var x = $(this).data('value')
	    		var to = x*rowperpage;
	    		var from = to-rowperpage;

	    		$('.table .tablerow').slice(from,to).removeClass('hidden');




	    		var paginationlength = $('.pagination li').length;
	    		if(paginationlength>=5){
	    			var slicenum = 5;
	    		}
	    		else{
	    			var slicenum = paginationlength;
	    		}

	    		if(x>=3){
	    			var visiblepagination = x-3;
	    			if(paginationlength-x>=2){
	    				var visiblepaginationto = x+2;
	    			}
	    			else{
	    				var visiblepaginationto = paginationlength;
	    				var visiblepagination = x-(slicenum-(paginationlength-x));
	    			}
	    		}
	    		else{
	    			var visiblepagination = 0;
	    			var visiblepaginationto = 5;
	    		}

	    		$('.pagination li').addClass('hidden');
	    		$('.pagination li').slice(visiblepagination,visiblepaginationto).removeClass('hidden');

	    	});





	    $('.searchinput').keyup(function(){
	    	var text = $(this).val();

	    	$('tbody tr').addClass('hidden');
	    	$('.tablerow').removeClass('found');


	    	$('.tablerow').each(function(){
	    		if($(this).text().search(new RegExp(text,"i"))>=0) {
	    			$(this).removeClass('hidden').addClass('found');
	    		}
	    	}); 

	    	if($('.found').length<1){
	    		$('.noresult').removeClass('hidden');
	    	}

	    	if(text==""){
	    		$('tbody .tablerow').addClass('hidden');
	    		var actv = $('.pagination .active').data('value');
	    		var too = actv*rowperpage;
	    		var fromm = too-rowperpage;

	    		$('.table .tablerow').slice(fromm,too).removeClass('hidden');
	    	}
	    });













	});