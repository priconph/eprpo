$(document).off('click','#transactiontypeedit-savebtn').on('click','#transactiontypeedit-savebtn',function(){
		$(this).attr('disabled',true);
		var id, code, desc, prefix, numseries, modal;
		modal = '#'+$(this).closest('.modal').attr('id');
		id=$(modal+' .transactiontypeid').val();
		code=$(modal+' .code').val();
		desc=$(modal+' .desc').val();
		prefix=$(modal+' .prefix').val();
		numseries=$(modal+' .numseries').val();
		$('#loading-img').removeClass('hidden');
		$.post(server+'transaction-type.php',{action:'edit',id:id,code:code,desc:desc,prefix:prefix,numseries:numseries},function(data){
				if(data.trim()=='success'){
					successful(modal,'System/transaction-type.php');
					$('#loading-img').addClass('hidden');
				}
				else{
					$('#loading-img').addClass('hidden');
					alert(data);
					$(this).removeAttr('disabled');
				}
		});	
});
$(document).off('click','.edittranstypebtn').on('click','.edittranstypebtn',function(){
	$('#edittransactiontypemodal .transactiontypeid').val($(this).attr('id'));
	$('#edittransactiontypemodal .code').val($(this).attr('code'));
	$('#edittransactiontypemodal .desc').val($(this).attr('desc'));
	$('#edittransactiontypemodal .prefix').val($(this).attr('prefix'));
	$('#edittransactiontypemodal .numseries').val($(this).attr('numseries'));
});