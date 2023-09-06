//LINE ITEMS BUTTONS
$(document).off('click','#removefromcart-removebtn').on('click','#removefromcart-removebtn',function(){
	$('#cartitems .itemcheckbox:checked').each(function(){
		var code = $(this).closest('tr').children(':nth-child(3)').text();
		$('#itemlistmodal tr').each(function(){
			if($(this).text().search(new RegExp(code,"i"))>=0){
				$(this).children(':first-child').children('.itemcheckbox').prop('checked',false);
				$(this).children(':nth-child(2)').children('input').val('');
				$(this).children(':last-child').children('input').val('');

			}
		});
		$(this).closest('tr').remove();
	});
});


//PRICE SCHEDULE BUTTONS
$(document).off('click','#priceschedule-savebtn').on('click','#priceschedule-savebtn',function(){ //saving item price schedule
	$(this).attr('disabled',true);
	var tnum, supplier, rnum, datecreated, datestart, dateend,remarks, itemid, unitprice, itemremarks;
	itemid=[];
	unitprice=[];
	itemremarks=[];
	tnum = $("#generalinformationpanel .transactionnumber").val();
	supplier = $("#generalinformationpanel .supplier").attr('suppid');
	rnum = $("#generalinformationpanel .referencenum").val();
	datecreated = $("#generalinformationpanel .datecreated").val();
	datestart = $("#generalinformationpanel .datestart").val();
	dateend = $("#generalinformationpanel .dateend").val();
	remarks = $("#generalinformationpanel .remarks").val();

	$('#cartitems tr').each(function(){
		itemid.push($(this).attr('cartrowid'));
		unitprice.push($(this).children('.cartprice').text());
		itemremarks.push($(this).children('.cartremarks').text());
	});
	if(itemid.length==0){
		alert('Please add an item');
	}
	else{
		$.post(server+'item-price-schedule.php',{action:'add',transactionnum:tnum,supplierid:supplier,refnum:rnum,datecreated:datecreated,datestart:datestart,dateend:dateend,remarks:remarks,items:itemid,prices:unitprice,itemremarks:itemremarks},function(data){
			if(data=='success'){
				$('.content').load('Transaction/item-price-schedule.php');
				$.getScript(pagescripts+'item-price-schedule.js');
			}
			else{
				alert(data);
				$('#priceschedule-savebtn').removeAttr('disabled');
			}
		});
	}
});


$(document).off('click','#priceschedule-cancelbtn').on('click','#priceschedule-cancelbtn',function(){
	$('.content').load('Transaction/item-price-schedule.php');
	$.getScript(pagescripts+'item-price-schedule.js');
});


// ITEM LIST MODAL BUTTONS
$(document).off('click','#itemlist-addbtn').on('click','#itemlist-addbtn',function(){// adding items to item price schedule items table
	var checkeditem, id, code, description, price, count, remarks, flag=false;
	count = 1;
	checkeditem = $('#itemlistmodal .itemcheckbox:checked');



	if(checkeditem.length>0){
			checkeditem.each(function(){
				if($(this).parent().next().children(':first-child').val()==''){
					flag=true;
				}
			});

			if(flag==false){
				$('#cartitems').empty();
				checkeditem.each(function(){
					id = $(this).attr('itemid');
					code=$(this).attr('code');
					description=$(this).attr('desc');
					price = $(this).parent().next().children(':first-child').val();
					remarks = $(this).parent().siblings(':last').children(':first-child').val();

						/*if($('#cartitems tr').text().search(new RegExp(code,"i"))>=0){
							$('#'+code+'price').text(price);
						}
						else{*/
							$('#cartitems').append("<tr cartrowid='"+id+"'><td><input type='checkbox' class='itemcheckbox'></td><td class='text-center'>"+count+"</td><td>"+code+"</td><td>"+description+"</td><td id='"+code+"price' class='cartprice'>"+price+"</td><td class='cartremarks'>"+remarks+"</td></tr>")
							count++;
						//}
						//$(this).prop('checked',false);
						//$(this).parent().next().children(':first-child').val('');
						
				});
				$('#itemlistmodal').modal('hide');
			}
			else{
				$('#itemlistmodal .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide <b>Unit Price</b> for each selected item.</div></div>");
			}
	}
	else{
		$('#itemlistmodal .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>You have not selected any item.</div></div>");
	}
	
});

$(document).off('click','#itemlist-cancelbtn').on('click','#itemlist-cancelbtn',function(){
	$('#itemlistmodal').modal('hide');
});