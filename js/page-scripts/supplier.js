$(document).off('click','.supplier-modal-savebtn').on('click','.supplier-modal-savebtn',function(){
		$(this).attr('disabled',true);
		var modal, action, id, code, name, address, tin, stat, paymentterms, leadtime, contact, email, mobile, tel, fax, remarks, position, contactid, ifnewcontact;
		id='';
		stat='';
		ifnewcontact='';
		contactid='';
		modal = $(this).attr('id')=='suppliercreate-savebtn'?'#addsuppliermodal':'#editsuppliermodal';
		action=(modal=='#addsuppliermodal')?'add':'edit';

		code = $(modal+' .suppliercode').val();
		name = $(modal+' .suppliername').val();
		address = $(modal+' .address').val();
		tin = $(modal+' .suppliertin').val();
		paymentterms = $(modal+' .paymentterms').val();
		contact = $(modal+' .contactperson').val();
		position = $(modal+' .position').val();
		email = $(modal+' .email').val();
		mobile = $(modal+' .mobile').val();
		tel = $(modal+' .tel').val();
		fax = $(modal+' .fax').val();
		remarks = $(modal+' .remarks').val();
		


		if(action=='edit'){
			id=$(modal+' .supplierid').val();
			stat = $(modal+' .activeflag').val();
			contactid = $(modal+' .contactperson').attr('contactid');
			ifnewcontact=$(modal+' .checkifnewcontact').prop('checked');
		}
		$('#loading-img').removeClass('hidden');
		$.post(server+'supplier.php',{action:action,id:id,code:code,name:name,address:address,tin:tin,terms:paymentterms,contact:contact,contactid:contactid,email:email,mobile:mobile,tel:tel,fax:fax,remarks:remarks,stat:stat,position:position,ifnewcontact:ifnewcontact},function(data){
				if(data.trim()=='success'){
					successful(modal,'Maintenance/supplier.php');
					$('#loading-img').addClass('hidden');
				}
				else{
					$('#loading-img').addClass('hidden');
					alert(data);
					$(this).removeAttr('disabled');
				}
				$(this).removeAttr('disabled');
		});
});

$(document).off('click','.editsupplierbtn').on('click','.editsupplierbtn',function(){
	$('#editsuppliermodal .supplierid').val($(this).attr('id'));
	$('#editsuppliermodal .suppliercode').val($(this).attr('code'));
	$('#editsuppliermodal .suppliername').val($(this).attr('name'));
	$('#editsuppliermodal .address').val($(this).attr('addr'));
	$('#editsuppliermodal .suppliertin').val($(this).attr('tin'));
	$('#editsuppliermodal .paymentterms').val($(this).attr('term'));
	$('#editsuppliermodal .contactperson').val($(this).attr('contact'));
	$('#editsuppliermodal .activeflag').val($(this).attr('stat'));
	$('#editsuppliermodal .email').val($(this).attr('email'));
	$('#editsuppliermodal .mobile').val($(this).attr('mob'));
	$('#editsuppliermodal .tel').val($(this).attr('tel'));
	$('#editsuppliermodal .fax').val($(this).attr('fax'));
	$('#editsuppliermodal .remarks').val($(this).attr('remarks'));
	$('#editsuppliermodal .position').val($(this).attr('position'));
	$('#editsuppliermodal .contactperson').attr('contactid',$(this).attr('contactid'));

	$('.chosen-select').trigger('chosen:updated');
	
});

$(document).off('click','#deletesupplierbtn').on('click','#deletesupplierbtn',function(){
		var button = $(this);
		button.attr('disabled',true);
		var id, response;

		$.confirm({
				animation: 'bottom', 
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce:1,
				title: 'Confirmation',
				content: 'Are you sure you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',	
				confirmButtonClass: 'btn-oceanblue', 
				cancelButtonClass: 'btn-royalblue', 
				theme: 'white', 

				confirm: function(){
					id = [];
					$('.itemcheckbox:checked').each(function(){
						id.push($(this).attr('rowid'));
					});
					if(id.length>0){
						$('#loading-img').removeClass('hidden');
						$.post(server+'supplier.php',{deleteselected:'',id:id},function(data){
							if(data.trim()!='success'){
								$('#loading-img').addClass('hidden');
								alert(data);
								button.removeAttr('disabled');
							}
							else{
								$('.content').load('Maintenance/supplier.php',function(){
									setTimeout(function(){
										$('#loading-img').addClass('hidden');
									},300);
								});
							}
						});
					}
					button.removeAttr('disabled');
				},
				cancel:function(){ 
					button.removeAttr('disabled');
				}

		});



});

$(document).off('click','#uploadsupplierbtn').on('click','#uploadsupplierbtn',function(){
	$('#loading-img').removeClass('hidden');
	$('.content').load('Other/supplier-upload-file.php',function(){

		setTimeout(function(){
			$.getScript(pagescripts+'supplier-upload-file.js');
			$('.content').animate({scrollTop:0},200);
			$('#loading-img').addClass('hidden');
		},400);

	});
	
});