	
	var id_po_no 	= '';

	display_po_list();
	function display_po_list(){
		
		var data = {
			'action'	: 'display_po_list'
		}
		
		data = $.param(data);		
		$.ajax({
			
			data	: data,
			type	: 'post',
			dataType: 'json',
			url		: '../handler/handler.php',
			beforeSend: function(data){
				$('#div_loading').prop("hidden",false);
			},	
			success	: function(data){
				
				$('#div_loading').prop("hidden",true);
				var ctr = data['ctr'];
				
				$('#container_po_list').empty();
				
				var content  = '<table id="tbl_po_list" class="table table-striped table-hover" style="padding: 20px; margin-right: 20px; font-size: 12px;">';
					content += '	<thead>';
					content += '		<tr>';
					content += '			<th><center>PO NO.</center></th>';
					content += '			<th><center>STATUS</center></th>';
					content += '			<th><center>SUPPLIER</center></th>';
					content += '			<th><center>REQUISITION DATE</center></th>';
					content += '			<th><center>DATE NEEDED</center></th>';
					content += '			<th><center>LAST MODIFIED</center></th>';
					content += '			<th><center>VIEW</center></th>';
					content += '		</tr>';
					content += '	</thead>';
					content += '	<tbody>';
					content += '	</tbody>';
					content += '</table>';
					
				$('#container_po_list').append(content);
				
				
				for(i=0;i<ctr;i++){
						
					if ( data['order_status'][i] == 'SERVED'){
						var order_status_lbl = '<font color="GREEN">SERVED</font>';
					}else if ( data['order_status'][i] == 'CANCELLED'){
						var order_status_lbl = '<font color="RED">CANCELLED</font>';
					}else if ( data['order_status'][i] == 'FOR CANCELLATION'){
						var order_status_lbl = '<font color="RED">CANCELLED</font>';
					}else if ( data['order_status'][i] == 'FOR REVISION'){
						var order_status_lbl = '<font color="BLUE">FOR REVISION</font>';
					}else if ( data['order_status'][i] == 'PARTIAL DELIVERY'){
						var order_status_lbl = '<font color="FUCHSIA">PARTIAL DELIVERY</font>';
					}else if ( data['order_status'][i] == 'UNSERVED'){
						var order_status_lbl = '<font color="PURPLE">UNSERVED</font>';
					}else if ( data['order_status'][i] == 'REVISED'){
						var order_status_lbl = '<font color="BLUE">REVISED</font>';
					}	
					
					var view_po_history 	= '<button class="btn btn-link" id="btn_view_po_history" style="border-radius: 0px;" title= "Edit asset category" value='+data['order_number'][i]+'> <i class="fa fa-eye" style="color:#0D2DEA"></i> </button>';
										
					var values  = '<tr id='+data['order_number'][i]+'>';
						values += '		<td><b>'+data['order_number'][i]+'</b></td>';
						values += '		<td>'+order_status_lbl+'</td>';
						values += '		<td>'+data['supplier_name'][i]+'</td>';
						values += '		<td><center>'+data['requisition_date'][i]+'</center></td>';
						values += '		<td><center>'+data['date_needed'][i]+'</center></td>';
						values += '		<td><center>'+data['date_flag'][i]+'<center></td>';
						values += '		<td><center>'+view_po_history+'<center></td>';
						values += '</tr>';
						
						$('#tbl_po_list tbody').append(values);
				}
				
				$("#tbl_po_list tbody").on('click','tr #btn_view_po_history', function(e){
					e.preventDefault();
					view_po_history_details($(this).val());	
				});
				
				$('#tbl_po_list').DataTable({ 
					paginate: true, 
					aaSorting: [],
				 });
				 				
			},
			error	: function(data){
				console.log(data);
			}
		});
		
	}
	
	function view_po_history_details(id_po_no) {
		var data = {
			"action"		 : "get_po_history_details",
			"order_number"	 : id_po_no
		}
		
		data = $.param(data);
		$.ajax({
			data	: data,
			type	: 'post',
			dataType: 'json',
			url		: '../handler/handler.php',
			success		: function (data) {
				
				$('#id_po_number').val(data['order_number']);
				$('#po_no').text(data['order_number']);
				$('#id_hidden_po_no').val(id_po_no);
				
				
				var ctr = data['ctr'];
				$('#container_tbl_po_details').empty();
				
				var content  = '<table id="tbl_po_history_details" class="table table-striped table-hover" style="padding: 20px; margin-right: 20px; font-size: 12px;">';
					content += '	<thead>';
					content += '		<tr>';
					content += '			<th><center>REVISION NO.</center></th>';
					content += '			<th><center>REMARKS</center></th>';
					content += '		</tr>';
					content += '	</thead>';
					content += '	<tbody>';
					content += '	</tbody>';
					content += '</table>';
					
				$('#container_tbl_po_details').append(content);
				
				
				for(i=0;i<ctr;i++){												
					var values  = '<tr>';
						values += '		<td><center>'+data['revision_number'][i]+'</center></td>';
						values += '		<td>'+data['remarks'][i]+'</td>';
						values += '</tr>';
						$('#tbl_po_history_details tbody').append(values);
				}
				
				$("#id_po_history_modal_show").modal();		

			}, error	: function (data) {
			alert('ERROR: '+data);
			}
		});
	}
	
