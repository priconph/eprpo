$(document).ready(function(){

		//navtabs & tab pane js
		$(document).on('click','.nav-tabs li',function(){
			var pane = $(this).data('pane');
			$(this).addClass('active').siblings().removeClass('active');
			$(pane).addClass('active').siblings().removeClass('active');
		});

		//table js
		$(document).on('change','.selectall',function(){
			var ifchecked = $(this).prop('checked');
			var checkbox = $(this);
			if(ifchecked==true){
				checkbox.closest('.table-responsive').find(".dataTables_scrollBody input[type='checkbox']").prop('checked',true);
			}
			else{
				checkbox.closest('.table-responsive').find(".dataTables_scrollBody input[type='checkbox']").prop('checked',false);
			}

		});
		//cancelbtn in modal
		$(document).on('click','.modal-cancelbtn',function(){
			$('.modal').modal('hide');
		});

		//searching 
		 $(document).on('keyup','.searchinput',function(e){
		 	var text = $(this).val();
		 	if(e.which=='13'||e.keycode=='13'){
		    	//var text = $(this).val();
		    	$(this).closest('.panel').find('.tablerow').addClass('hidden');
		    	$(this).closest('.panel').find('.tablerow').removeClass('found');

		    	$(this).closest('.panel').find('.tablerow').each(function(){
		    		if($(this).text().search(new RegExp(text,"i"))>=0) {
		    			$(this).removeClass('hidden').addClass('found');
		    		}
		    	}); 

		    	if($(this).closest('.panel').find('.found').length<1){
		    		$(this).closest('.panel').find('.noresult').removeClass('hidden');
		    	}
		    	else{
		    		$(this).closest('.panel').find('.noresult').addClass('hidden');
		    	}

		    	////////////////////////////////search table text//////////////////////
				    var foundrows = $(this).closest('.panel').find('.tablerow.found').length;
				    $(this).closest('.panel').find('.table-text-search').text(foundrows+' result(s) found');
				    //hide "showing a-z of n entries"//
				    $(this).closest('.panel').find('.table-text-div').addClass('hidden');
				    //
				 //////////////////////////////////////////////////////////////////////

		    	if(text==""){
		    		$(this).closest('.panel').find('.tablerow').addClass('hidden');
		    		var actv = $('.pages .active').attr('value');
		    		var too = actv*rowperpage;
		    		var fromm = too-rowperpage;
		    		$(this).closest('.panel').find('.tablerow').slice(fromm,too).removeClass('hidden');

		    		///////////////////////////////
		    		$(this).closest('.panel').find('.table-text-search').text('');
		    		//show showing
		    		 $(this).closest('.panel').find('.table-text-div').removeClass('hidden');
		    		 //
		    		///////////////////////////
		    	}
		    }
		    else if(text==""){
			    	$(this).closest('.panel').find('.noresult').addClass('hidden');
			    	

		    		$(this).closest('.panel').find('.tablerow').addClass('hidden');
		    		var actv = $('.pages .active').attr('value');
		    		var too = actv*rowperpage;
		    		var fromm = too-rowperpage;
		    		$(this).closest('.panel').find('.tablerow').slice(fromm,too).removeClass('hidden');

		    		///////////////////////////////
		    		$(this).closest('.panel').find('.table-text-search').text('');
		    		//show showing
		    		 $(this).closest('.panel').find('.table-text-div').removeClass('hidden');
		    		 //
		    		///////////////////////////
		    }


		});
		 //pagination
		 $(document).on('click','.pages li', function(){

	    		$(this).closest('.tablewithpagination').children('.table-wrapper').find('.tablerow').addClass('hidden');
	    		$(this).addClass('active').siblings('li').removeClass('active');
	    		var x = parseInt($(this).attr('value'));
	    		var to = x*rowperpage;
	    		var from = to-rowperpage;
	    		$(this).closest('.tablewithpagination').children('.table-wrapper').find('.tablerow').slice(from,to).removeClass('hidden');
	    		//var paginationlength = $('.pages li').length;
	    		var paginationlength = $(this).closest('.pages').children('li').length;

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
	    		//slice pagination
	    		$(this).closest('.pages').children('li').addClass('hidden');
				$(this).closest('.pages').children('li').slice(visiblepagination,visiblepaginationto).removeClass('hidden');
				//slice pagination - end
				
				//////////////////table-text (<showing 1-10 of entries>..)
				$(this).closest('.panel').find('.table-text-search').text('');
				$(this).closest('.panel').find('.searchinput, .searchforitems').val('');
				$(this).closest('.panel').find('.table-text-div').removeClass('hidden');

				$(this).closest('.modal').find('.table-text-search').text('');
				$(this).closest('.modal').find('.lookupsearchinput, .searchforitems').val('');
				$(this).closest('.modal').find('.table-text-div').removeClass('hidden');


				var total = $(this).closest('.row').find('.table-text').children('.table-text-total').text(),
					fromtext = (to-rowperpage)+1,
					ceil= Math.ceil(total/10),
					floor = Math.floor(total/10),
					totext = rowperpage*x;
				if(x==ceil){
					totext=total;
				}
				$(this).closest('.row').find('.table-text').children('.table-text-from').text(fromtext);
				$(this).closest('.row').find('.table-text').children('.table-text-to').text(totext);

				//hide noresult row
				if($(this).closest('.panel').find('table .tablerow').length>0){
					$(this).closest('.panel').find('table .noresult').addClass('hidden');
				}
				if($(this).closest('.modal').find('table .tablerow').length>0){
					$(this).closest('.modal').find('table .noresult').addClass('hidden');
				}
				//////////////////////////////////////////


		});


		 //message alert
		 $(document).on('click','.closemessage',function(){
		 	$(this).closest('.message').remove();
		 	$(this).closest('.message-success').remove();
		 });

		 /*
		//datepicker
		$('.dateinput').datepicker({
				inline: true,
				showOn: "button",
				buttonImage: "../resources/set1/Calender-Month.png",
				buttonImageOnly: true,
		});*/

		$(document).on('hidden.bs.modal','.modal',function(){
			$('.modal .errordiv').empty();
		});

		











});