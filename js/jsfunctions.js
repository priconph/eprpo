function newVariant(){
            var targetDiv = document.getElementById("product-variant-div");
            targetDiv.innerHTML += '<br><hr><div class="form-group"><label for="nameInput" class="col-md-4 control-label">Initial Stock Level</label><div class="col-md-8"><input type="text" class="form-control input-sm" id="nameInput" placeholder="Product Name"></div></div><div class="form-group"><label for="nameInput" class="col-md-4 control-label">SKU</label><div class="col-md-8"><input type="text" class="form-control input-sm" id="nameInput" placeholder="Product Name"></div></div><div class="form-group"><label for="nameInput" class="col-md-4 control-label">Cost</label><div class="col-md-8"><input type="number" class="form-control input-sm" id="nameInput" placeholder="Product Name"></div></div><div class="form-group"><label for="nameInput" class="col-md-4 control-label">Wholesale Price</label><div class="col-md-8"><input type="number" class="form-control input-sm" id="nameInput" placeholder="Product Name"></div></div><div class="form-group"><label for="nameInput" class="col-md-4 control-label">Retail Price</label><div class="col-md-8"><input type="number" class="form-control input-sm" id="nameInput" placeholder="Product Name"></div></div>';                                         
}

function loaded(){
				alert("SDfsdfsdf");
				//var loadscreen = document.getElementById('load-screen');
				//document.getElementById('content').removeChild(loadscreen);
}






function validate_user(formname){

					var fname = document.getElementById(formname).elements[0].value;
		            var mname = document.getElementById(formname).elements[1].value;
		            var lname = document.getElementById(formname).elements[2].value;
		            var role = document.getElementById(formname).elements[3].value;
		            var username = document.getElementById(formname).elements[4].value;
		            var uinifexist = document.getElementById(formname).elements[6].value;

		            var alertdiv = document.getElementById('alertdiv');
		            var msg = 'New User Successfully Added';
		            if (formname=='edituserform') {
		            	alertdiv = document.getElementById('alertdiv2');
		            	msg = 'Saved changes!';
		            	var currentusername = document.getElementById('currentusername').value;
		            	if(username==currentusername){
		            		uinifexist = 'notexist';
		            		
		            	}
		            }

		            var num=document.getElementById(formname).elements.length-1;
		            var fieldswitherrors = new Array();
		            
		                     
		            // regular expression --->  /^([a-z0-9]{5,})$/ ---> meaning from a to z 1 to 9 and must atleast be 5 characters
		            //alert(/^([a-z]{1,})$/.test('abcdef')); 
		                                                                        
		                                                                        
		            if(fname==''||mname==''||lname==''||role==''||username==''){
		                      alertdiv.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Oops!</strong> Please fill out all the information.</div>";
		                      return false;
		            }
		            else{
		            	for(x=0;x<3;x++){
			            	var text = document.getElementById(formname).elements[x].value;
			            	var result = /^([a-zA-Z]{1,})$/.test(text);
			            	if(result==false){
			            		fieldswitherrors.push(x);
			            		break;
			            	}
			            }
			            var checkusername = /^([a-z0-9A-Z_]{5,})$/.test(username);
			            if(fieldswitherrors.length>0){
			            	alertdiv.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>First, Middle, & Last Name</strong> should only contain alphabetical characters.</div>";
			            	return false;
			            }
			            else if(!checkusername){
			            		alertdiv.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Username</strong> should only contain <strong>alphabetical, numerical characters or underscores.</strong> It must be <strong>at least 5 characters long</strong>. </div>";
		                     	return false;
			            	
			            }
			            else if(uinifexist=='exist'){
			            		alertdiv.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>USERNAME</strong> already exists. </div>";
		                     	return false;
			            }
			            else{
			            	alert(msg);
			            }
		                
		            }
	     
		            																			
}




/*function validate_client(formname){
	var alertdiv = document.getElementById('alertdiv');
	if(formname=='editclientform'){
		alertdiv = document.getElementById('alertdiv2');
	}

	var inputcount = document.getElementById(formname).elements.length-1;
	for(i=0;i<inputcount;i++){
		var textinput = document.getElementById(formname).elements[i].value;
		if(textinput==''){
			alertdiv.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Oops!</strong> Please fill out all the information.</div>";
			return false;
		}
	}
	
	for(i=2;i<4;i++){
		var input = document.getElementById(formname).elements[i].value;
		var test = /^([0-9]{7,})$/.test(input);
		if(test==false){
			alertdiv.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Landline and Mobile</strong> should only contain numerical characters or characters such as +()-</div>";
			return false;
		}
	}
	

}*/





function validate_location(formname){
	var msg = 'New Location Added!';
	var alertdiv = document.getElementById('alertdiv');
	if(formname=='editlocationform'){
		alertdiv = document.getElementById('alertdiv2');
		msg = 'Location Description Updated';
	}

	var desc = document.getElementById(formname).elements[0].value;
	var checkdesc = /^([a-zA-Z0-9\s\-]{5,})$/.test(desc);
	if(checkdesc==false){
		alertdiv.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><b>Location Description</b> must atleast be <b>5 characters</b> long. It should only contain <b>alphabetical or numerical characters</b> or a <b>dash line.</b>  </div>";
		return false;
	}
	alert(msg);
}

function validate_lot(formname){
	var msg = 'New Lot Added!';
	var alertdiv = document.getElementById('alertdiv');
	if(formname=='editlotform'){
		alertdiv = document.getElementById('alertdiv2');
		msg = 'Lot Description Updated';
	}

	var desc = document.getElementById(formname).elements[0].value;
	var checkdesc = /^([a-zA-Z0-9\s\-]{2,})$/.test(desc);
	if(checkdesc==false){
		alertdiv.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><b>Location Description</b> must atleast be <b>5 characters</b> long. It should only contain <b>alphabetical or numerical characters</b> or a <b>dash line.</b>  </div>";
		return false;
	}
	alert(msg);
}

function validate_rack(formname){
	var msg = 'New Rack Added!';
	var alertdiv = document.getElementById('alertdiv');
	if(formname=='editrackform'){
		alertdiv = document.getElementById('alertdiv2');
		msg = 'Rack Description Updated';
	}

	var desc = document.getElementById(formname).elements[0].value;
	var checkdesc = /^([a-zA-Z0-9\s\-]{3,})$/.test(desc);
	if(checkdesc==false){
		alertdiv.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><b>Location Description</b> must atleast be <b>5 characters</b> long. It should only contain <b>alphabetical or numerical characters</b> or a <b>dash line.</b>  </div>";
		return false;
	}
	alert(msg);
}

function validate_level(formname){
	var msg = 'New Level Added!';
	var alertdiv = document.getElementById('alertdiv');
	if(formname=='editlevelform'){
		alertdiv = document.getElementById('alertdiv2');
		msg = 'Level Description Updated';
	}

	var desc = document.getElementById(formname).elements[0].value;
	var checkdesc = /^([a-zA-Z0-9\s\-]{3,})$/.test(desc);
	if(checkdesc==false){
		alertdiv.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><b>Location Description</b> must atleast be <b>5 characters</b> long. It should only contain <b>alphabetical or numerical characters</b> or a <b>dash line.</b>  </div>";
		return false;
	}
	alert(msg);
}



function validate_role(formname){
	var msg = 'New Role Added!';
	var alertdiv = document.getElementById('alertdiv');
	if(formname=='editroleform'){
		alertdiv = document.getElementById('alertdiv2');
		msg = 'Role Updated';
	}

	var role = document.getElementById(formname).elements[0].value;
	var checkdesc = /^([a-zA-Z\s]{5,})$/.test(role);
	if(checkdesc==false){
		alertdiv.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><b>Role</b> must atleast be <b>5 characters</b> long. <br>It should only contain <b>alphabetical characters.</b></div>";
		return false;
	}
	alert(msg);

}

function validate_size(formname){
	var msg = 'New Size Added!';
	var alertdiv = document.getElementById('alertdiv');
	if(formname=='editsizeform'){
		alertdiv = document.getElementById('alertdiv2');
		msg = 'Size Updated';
	}

	var size = document.getElementById(formname).elements[0].value;
	var checkdesc = /^([a-zA-Z\s0-9\-]{3,})$/.test(size);
	if(checkdesc==false){
		alertdiv.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><b>Size Description</b> must atleast be <b>3 characters</b> long. <br>It should only contain <b>alphabetical and numerical characters.</b></div>";
		return false;
	}
	alert(msg);

}


function validate_category(formname){
	var msg = 'New Category Added!';
	var alertdiv = document.getElementById('alertdiv');
	if(formname=='editcategoryform'){
		alertdiv = document.getElementById('alertdiv2');
		msg = 'Category Updated';
	}

	var category = document.getElementById(formname).elements[0].value;
	var checkdesc = /^([a-zA-Z\s]{3,})$/.test(category);
	if(checkdesc==false){
		alertdiv.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><b>Category Name</b> must atleast be <b>3 characters</b> long. <br>It should only contain <b>alphabetical characters.</b></div>";
		return false;
	}
	alert(msg);

}

function validate_category2(formname){
	var msg = 'New Subcategory Added!';
	var alertdiv = document.getElementById('alertdiv');
	if(formname=='editcategory2form'){
		alertdiv = document.getElementById('alertdiv2');
		msg = 'Subcategory Updated';
	}

	var category = document.getElementById(formname).elements[0].value;
	var checkdesc = /^([a-zA-Z\s0-9]{3,})$/.test(category);
	if(checkdesc==false){
		alertdiv.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><b>Subcategory Name</b> must atleast be <b>3 characters</b> long. <br>It should only contain <b>alphabetical and numerical characters.</b></div>";
		return false;
	}
	alert(msg);

}

function validate_type(formname){
	var msg = 'New Type Added!';
	var alertdiv = document.getElementById('alertdiv');
	if(formname=='edittypeform'){
		alertdiv = document.getElementById('alertdiv2');
		msg = 'Type Updated';
	}

	var typeName = document.getElementById(formname).elements[0].value;
	var checktype = /^([a-zA-Z\s0-9]{3,})$/.test(typeName);
	if(checktype==false){
		alertdiv.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><b>Type Name</b> must atleast be <b>3 characters</b> long. <br>It should only contain <b>alphabetical and numerical characters.</b></div>";
		return false;
	}
	alert(msg);

}


function validate_subtype(formname){
	var msg = 'New Subtype Added!';
	var alertdiv = document.getElementById('alertdiv');
	if(formname=='editsubtypeform'){
		alertdiv = document.getElementById('alertdiv2');
		msg = 'Subtype Updated';
	}

	var subtypeName = document.getElementById(formname).elements[0].value;
	var checktype = /^([a-zA-Z\s0-9\-]{3,})$/.test(subtypeName);
	if(checktype==false){
		alertdiv.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><b>Subtype Name</b> must atleast be <b>3 characters</b> long. <br>It should only contain <b>alphabetical and numerical characters.</b></div>";
		return false;
	}
	alert(msg);

}




function validate_uom(formname){
	var msg = 'New Unit of Measure Added!';
	var alertdiv = document.getElementById('alertdiv');
	if(formname=='edituomform'){
		alertdiv = document.getElementById('alertdiv2');
		msg = 'Unit of Measure Updated';
	}

	var uomDesc = document.getElementById(formname).elements[0].value;
	var checkdesc = /^([a-zA-Z0-9\s]{2,})$/.test(uomDesc);
	if(checkdesc==false){
		alertdiv.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><b>UOM Description</b> must atleast be <b>2 characters</b> long. <br>It should only contain <b>alphabetical and numerical characters.</b></div>";
		return false;
	}
	alert(msg);

}


function validate_product(formname){
	var msg = 'New Product Added!';
	var alertdiv = document.getElementById('alertdiv');
	if(formname=='editproductform'){
		alertdiv = document.getElementById('alertdiv2');
		msg = 'Product Updated';
	}

	var sku = document.getElementById(formname).elements[0].value;
	var name = document.getElementById(formname).elements[1].value;
	var category = document.getElementById(formname).elements[2].value;
	var subcategory = document.getElementById(formname).elements[3].value;
	var size = document.getElementById(formname).elements[4].value;
	var type = document.getElementById(formname).elements[5].value;
	var subtype = document.getElementById(formname).elements[6].value;
	var brand = document.getElementById(formname).elements[7].value;
	var price = document.getElementById(formname).elements[8].value;
	var uom = document.getElementById(formname).elements[9].value;
	var serial = document.getElementById(formname).elements[10].value;
	var minstock = document.getElementById(formname).elements[11].value;
	var disccode = document.getElementById(formname).elements[12].value;
	var loc = document.getElementById(formname).elements[13].value;

	var ifskuexist = document.getElementById(formname).elements[15].value;


	for(i=0;i<14;i++){
		var ifempty=document.getElementById(formname).elements[i].value;
		if(ifempty==''){
			alertdiv.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Please provide all the information.</div>";
			return false;
		}
	}


	var checkprodname = /^([a-zA-Z0-9\s\-\(\)\,\/\.]{3,})$/.test(name);
	//var checksuppliername = /^([a-zA-Z0-9\s]{3,})$/.test(brand);

	if(checkprodname==false){
		alertdiv.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><b>Product Name and Supplier</b> should only contain alphabetical or numerical characters. They should <b>atleast be 3 characters</b> long.</div>";
		return false;
	}
	if(ifskuexist=='exist'){
		alertdiv.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><b>SKU</b> already exists.</div>";
		return false;
	}


	alert(msg);
}




function validate_brand(formname){
	var msg = 'New Brand Added!';
	var alertdiv = document.getElementById('alertdiv');
	if(formname=='editbrandform'){
		alertdiv = document.getElementById('alertdiv2');
		msg = 'Brand Updated';
	}

	var brand = document.getElementById(formname).elements[0].value;
	var checkdesc = /^([a-zA-Z\s0-9]{2,})$/.test(brand);
	if(checkdesc==false){
		alertdiv.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><b>Brand Name</b> must atleast be <b>2 characters</b> long. <br>It should only contain <b>alphabetical and numerical characters.</b></div>";
		return false;
	}
	alert(msg);

}






/*function validate_editaccount(){
	var oldpword, newpword, retypepword, alert, checkoldpword;
	checkoldpword = document.getElementById('checkoldpword').value;
	oldpword = document.getElementById('oldpwordinput').value;
	newpword = document.getElementById('newpwordinput').value;
	retypepword = document.getElementById('retypepwordinput').value;
	alert = document.getElementById('alertdiv');
	//alert(checkoldpword);
	if(oldpword==''||newpword==''||retypepword==''){
		alert.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Please fill out all the information.</div>";
		return false;
	}
	else if(newpword!=retypepword){
		alert.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>New Password does not match.</div>";
		return false;
	}
	else if(checkoldpword=='notexist'){
		alert.innerHTML="<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><b>Old Password</b> incorrect.</div>";
		return false;

	}
	return false;
}*/






