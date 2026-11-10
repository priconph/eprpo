var server = '../scripts/';
var pagescripts = '../js/page-scripts/';
function successful(modal,pagetoload){
		$(modal).modal('hide');
		$(modal).on('hidden.bs.modal', function () {
			$('.content').load(pagetoload,function(){
											setTimeout(function(){
												$('#loading-img').addClass('hidden');
											},300);
										});
		});
}

function loadpage(loadto,tobeloaded){
	$(loadto).load(tobeloaded,function(){
		setTimeout(function(){
			$('#loading-img').addClass('hidden');
		},300);
	});
}

function say(message){
	$.alert({
		title: 'System Message',
		content: message,
		confirmButton: 'Close',
		animation: 'bottom', 
		closeAnimation: 'top',
		confirmButtonClass: 'btn-royalblue', 
		columnClass: 'col-md-offset-3 col-md-6',
		animationSpeed: 1000

	});
}

function notify(msg,type,icon){
		$.alert({
			title: 'System Message',
			content: msg,
			confirmButton: 'Close',
			animation: 'bottom', 
			closeAnimation: 'top',
			confirmButtonClass: 'btn-royalblue', 
			columnClass: 'col-md-offset-3 col-md-6',
			animationSpeed: 1000

		});
			/*$.notify({
				// options
				icon: icon,

				message: msg
			},{
				// settings
				type: type,
				offset: 20,
				spacing: 10,
				z_index: 2031,
				delay: 5000,
				placement: {
					from: "bottom",
					align: "center"
				},
				allow_dismiss:true,
				timer: 1000,
				url_target: '_blank',
				mouse_over: null,
				animate: {
					enter: 'animated fadeInDown',
					exit: 'animated fadeOutUp'
				},
			});*/
	
}

function convertFloat(input){
	var number = parseFloat(Math.round(input * 100) / 100).toFixed(2), temp, start; //decimal round off to 5 decimal places
	number = Number(number).toLocaleString();//5 decimal places
	if(number.indexOf('.')<0){
		number = number+'.00';
	}
	else{
		start = number.indexOf('.');
		temp = number.substring(start);
		if(temp.length<3){
			number = number+'0';
		}
	}

	return number;
}

function convertPrice2(input){
	input = input.replace(/,/g,'');
	var number = parseFloat(Math.round(input * 100000) / 100000).toFixed(5), temp, start; //decimal round off to 5 decimal places
	number = Number(number).toLocaleString(undefined,{minimumFractionDigits: 5});//5 decimal places
	if(number.indexOf('.')<0){
		number = number+'.00';
	}
	else{
		start = number.indexOf('.');
		temp = number.substring(start);
		if(temp.length<3){
			number = number+'0';
		}
	}
	//number = number.replace(/0{0,3}$/, "");
	return number;

}

function convertPrice(input){
	input = input.replace(/,/g,'');
	var number = parseFloat(Math.round(input * 100000) / 100000).toFixed(5), temp, start; //decimal round off to 5 decimal places
	number = Number(number).toLocaleString(undefined,{minimumFractionDigits: 5});//5 decimal places
	if(number.indexOf('.')<0){
		number = number+'.00';
	}
	else{
		start = number.indexOf('.');
		temp = number.substring(start);
		if(temp.length<3){
			number = number+'0';
		}
	}
	number = number.replace(/0{0,3}$/, "");
	return number;
}

function checkEmail(input){
			var result = /^([a-zA-Z0-9\.\_]{2,}([\@]{1}[a-zA-Z0-9]{2,})([\.]{1}[a-zA-Z0-9]{2,})([\.]{1}[a-zA-Z]{2,}){0,})$/.test(input);
			return result;
}

function checkName(input){
	var result = /^([a-zA-Z]{2,})([\s][a-zA-Z]{0,}){0,}$/.test(input);
	return result;
}

function checkDeptName(input){
	var result = /^([a-zA-Z0-9\/\(\)\-]{2,})([\s][a-zA-Z0-9\/\(\)\-]{0,}){0,}$/.test(input);
	return result;
}

function checkAlphanumeric(input){
	var result = /^([a-zA-Z0-9]{3,})([\s][a-zA-Z0-9]{0,}){0,}$/.test(input);
	return result;
}

function checkRemarks(input){
	var result = /^([a-zA-Z0-9]{3,})([\s][a-zA-Z0-9\.\-\%\$\<\>\?\!\&\,\;\:\'\"]{0,}([\.]{0,1})){0,}$/.test(input);
	return result;
}

function checkClassfn(input){
	var result = /^([a-zA-Z0-9]{3,})$/.test(input);
	return result;
}

function checkUsername(input){
	var result = /^([a-zA-Z0-9]{1,})([a-zA-Z0-9\_]{4,})([\_][a-zA-Z0-9]{0,}){0,}$/.test(input);
	return result;
}

function checkNumber(input){
	var result = /^([\+]{0,}([0-9]{2,})([\-\s][0-9]{3,}){0,})([\,][\s]{0,}[\+]{0,}([0-9]{2,})([\-\s][0-9]{3,}){0,}){0,}$/.test(input);
	return result;
}

function checkPrice(input){
	var result = /^([0-9]{0,})([\.][0-9]{1,}){0,1}$/.test(input);
	return result;
}

function checkPassword(input){
	var result = /^([a-zA-Z0-9\!\?]{5,})$/.test(input);
	return result;
}

function checkInt(input){
	var result = /^([1-9]{1})([0-9]{0,})$/.test(input);
	return result;
}

function checkCode(input){
	var result = /^([a-zA-Z0-9]{2,})$/.test(input);
	return result;
}


// TABLES AND PAGINATION
var rowperpage = 10;
function sliceTable(rowperpage, tablename, paginationId){
				    	if($(tablename+' .tablerow').length<1){
				    		$(tablename+' .noresult').removeClass('hidden');
				    	}
				    	$(tablename+' .tablerow').addClass('hidden');
				    	$(tablename+' .tablerow').slice(0,rowperpage).removeClass('hidden');

				    	$(paginationId+' li:first-child').addClass('active');
				    	$(paginationId+' li').addClass('hidden');
				    	$(paginationId+' li').slice(0,5).removeClass('hidden');
}
/////////////////////////

function checkGrandTotal(grandtotal){
			var currency = $('.pageContent .currency').val();
			var reqnum = $('.pageContent .reqnum').val();
			var checkdept = false;

			/* 07052024 Updated by Nessa Removed SRVP except sa last else*/
			if(currency=='1'){//JPY
				if(grandtotal>=228153){
					// $('.srvp').addClass('defaultsign').closest('.form-group').removeClass('hidden');
					$('.president').addClass('defaultsign').closest('.form-group').removeClass('hidden');
				}
				// else if(reqnum.search('FCLTY')>=0||reqnum.search('HRD')>=0){
					// $('.srvp').addClass('defaultsign').closest('.form-group').removeClass('hidden');
					// $('.president').removeClass('defaultsign').val('NULL').closest('.form-group').addClass('hidden');
				// }
				// else{
					// $('.srvp').removeClass('defaultsign').val('NULL').closest('.form-group').addClass('hidden');
					// $('.president').removeClass('defaultsign').val('NULL').closest('.form-group').addClass('hidden');
				// }
			}
			else if(currency=='2'){//php
				if(grandtotal>=100000){
					// $('.srvp').addClass('defaultsign').closest('.form-group').removeClass('hidden');
					$('.president').addClass('defaultsign').closest('.form-group').removeClass('hidden');
				}
				// else if(reqnum.search('FCLTY')>=0||reqnum.search('HRD')>=0){
					// $('.srvp').addClass('defaultsign').closest('.form-group').removeClass('hidden');
					// $('.president').removeClass('defaultsign').val('NULL').closest('.form-group').addClass('hidden');
				// }
				else{
					// $('.srvp').removeClass('defaultsign').val('NULL').closest('.form-group').addClass('hidden');
					$('.president').removeClass('defaultsign').val('NULL').closest('.form-group').addClass('hidden');
				}
			}
			else if(currency=='3'){//usd
				if(grandtotal>=2500){
					// $('.srvp').addClass('defaultsign').closest('.form-group').removeClass('hidden');
					$('.president').addClass('defaultsign').closest('.form-group').removeClass('hidden');
				}
				// else if(reqnum.search('FCLTY')>=0||reqnum.search('HRD')>=0){
					// $('.srvp').addClass('defaultsign').closest('.form-group').removeClass('hidden');
					// $('.president').removeClass('defaultsign').val('NULL').closest('.form-group').addClass('hidden');
				// }
				else{
					// $('.srvp').removeClass('defaultsign').val('NULL').closest('.form-group').addClass('hidden');
					$('.president').removeClass('defaultsign').val('NULL').closest('.form-group').addClass('hidden');
				}
			}
			else{
				$('.srvp').removeClass('defaultsign').val('NULL').closest('.form-group').addClass('hidden');
				$('.president').removeClass('defaultsign').val('NULL').closest('.form-group').addClass('hidden');
			}
}

/*function checkAllocation(){
			var itemcode, totalpercperitem, perc, ifallocisvalid=true;
			$('#pritems tr').each(function(){
				totalpercperitem = 0;
				itemcode = $(this).children(':nth-child(3)').text();
				$('#allocationsummary tr').each(function(){
					if($(this).text().search(new RegExp(itemcode,"i"))>=0){
						perc = parseFloat($(this).children(':nth-child(4)').text());
						totalpercperitem = parseFloat(totalpercperitem) + perc;
					}
				});
				if(totalpercperitem<99){
					ifallocisvalid = false;
				}
			});
			return ifallocisvalid;
}*/
function checkAllocation(){
			var totalperc=0, perc, ifallocisvalid=true;
			var row_count = $('#allocationsummary tr').length;
			var ctr = 0;
				$('#allocationsummary tr').each(function(){
						perc = parseFloat($(this).children('.allocationpercentage').text());
						totalperc = parseFloat(totalperc) + perc;
						ctr++;
						
						if(row_count == ctr) {
							if(totalperc<99.99){
								ifallocisvalid = false;
							}
						} 
				});
			return ifallocisvalid;
}

function userAccess(){
	$.post(server+'user-access.php',{DkkIroh3Lro93hdlk:'sdfei3opod30napri'},function(data){
		var fetchdata = data.split(','),
			count = fetchdata.length;
		for (var i = 0; i < count; i++) {
			$(fetchdata[i]).removeClass('hidden').removeAttr('disabled');
		};

	});
}

function userAccessMobile(){
	$.post(server+'user-access.php',{DkkIroh3Lro93hdlk:'sdfei3opod30napri'},function(data){
		var fetchdata = data.split(','),
			count = fetchdata.length;
		for (var i = 0; i < count; i++) {
			$("li[data-id='"+fetchdata[i].replace(/[\.\#]/g,'')+"']").removeClass('hidden').removeAttr('disabled');
		};

	});
}

