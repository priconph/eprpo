<?php
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

/* IF Query comes from DataTables do the following */
if (!empty($_POST) ) {

    
	// $qry = "select item.id, 
							 // item_code,
							 // item_name,
							 // item.lot_tags,
							 // long_description,
							 // item.default_supplier_id,
							 // item.unit_of_measure_id,
							 // unit_price,
							 // currency.id as currencyid,
							 // currency_code,
							 // item.remarks,
							 // item.active_flag,
							 // conformance_flag,
							 // unit_of_measure_code,
							 // item.delivery_lead_time,
							 // item.created_by_user_id,
							 // item.created_date,
							 // item.updated_by_user_id,
							 // item.updated_date,
							 // created_by,
							 // updated_by
					  // from item
					  // left join (select id, concat(COALESCE(first_name,''),' ',COALESCE(last_name,'')) as created_by from user) as a on a.id=item.created_by_user_id
					  // left join (select id, concat(COALESCE(first_name,''),' ',COALESCE(last_name,'')) as updated_by from user) as b on b.id=item.updated_by_user_id
					  // inner join unit_of_measure on item.unit_of_measure_id=unit_of_measure.id
					  // inner join currency on default_currency=currency.id";

	// $qry = "
 //            SELECT vw_item_with_app.*
 //            	(SELECT item_approval_history.pkid FROM item_approval_history 
	// 			WHERE item_approval_history.fkid_item=vw_item_with_app.id  
	// 			LIMIT 0,1) as approval_id,
	// 		(SELECT item_approval_history.reason_for_rejection FROM item_approval_history 
	// 			WHERE item_approval_history.fkid_item=vw_item_with_app.id  
	// 			LIMIT 0,1) as approval_history,
	// 		(SELECT item_approval_history.date FROM item_approval_history 
	// 			WHERE item_approval_history.fkid_item=vw_item_with_app.id
	// 			ORDER BY item_approval_history.date DESC
	// 			LIMIT 0,1) as approval_date
				
 //            	FROM  vw_item_with_app
 //            	WHERE vw_item_with_app.app_status <> 0
 //            	-- AND vw_item_with_app.active_flag <> 0 
 //    ";

    $qry = "SELECT vw_item_with_app.* FROM  vw_item_with_app";

                
	
	function getData2($sql){
    	global $connection ;//we use connection already opened
        $query = mysql_query($sql) OR DIE ("Can't get Data from DB , check your SQL Query " );
        $data = array();

        $hidebtn = (userAccess(USERID,'.edititembtn')==false)?'hidden':'';
        $hidebtn_app = (userAccess(USERID,'.approvalItem_btn')==false)?'hidden':'';

		while($obj=mysql_fetch_object($query)){
            $id = $obj->id;
			$itemcode = $obj->item_code;
			$shortdesc = strUtfEncode($obj->item_name);
			$longdesc = strUtfEncode($obj->long_description);
			$supp_name = strUtfEncode($obj->supplier_name);
			$uomid = $obj->unit_of_measure_id;
			$uom = $obj->unit_of_measure_code;
			$price = $obj->unit_price;
			$price2 = convertWithDecimal($price,5);
			$currencyid = ($obj->currencyid==''||$obj->currencyid==NULL)?'NULL':$obj->currencyid;
			$currency = $obj->currency_code;
			$supplierid = getSuppliers($id);
			$lead = $obj->delivery_lead_time;
			$remarks = strUtfEncode($obj->remarks);
			$stat = $obj->active_flag;
			$conf = $obj->conformance_flag;
			$conformance = ($conf==0)?"NO":"YES";
			$status = ($stat==0)?"INACTIVE":"ACTIVE";
			$createdby = strUtfEncode($obj->created_by);
			$createddate = formatDate4($obj->created_date);
			$updatedby = strUtfEncode($obj->updated_by);
			$updateddate = formatDate4($obj->updated_date);
			$tags = $obj->lot_tags;
			$app_stat = $obj->app_status;
			$approval_history = $obj->approval_history;
			$approval_date = $obj->approval_date;
			
			if ($approval_history == ''){
				$approval_history = '';
			}

			if ($app_stat == '1'){
				$app_status = '<span style="color:blue; font-weight:bold">FOR APPROVAL</span>';
				$app_button = "<img id='$id' class='nopadding approvalItem_btn $hidebtn_app' src='../resources/img/tick_16.png' title='Item Approval' data-toggle='modal' href='#approvalitemmodal'>";
			}else if ($app_stat == '2'){
				$app_status = '<span style="color:green; font-weight:bold">APPROVED</span>';
				// <br>'.$approval_date.'<br>'.$approval_history;
				$app_button = "";
			}else if ($app_stat == '3'){
				$app_status = '<span style="color:red; font-weight:bold">DISAPPROVED</span>';
				// <br>'.$approval_date.'<br>'.$approval_history;
				$app_button = "";
			}else{
				$app_status = '---';
				$app_button = "";
			}

			$arraydata =  array(
								//"DT_RowAttr"=>array(),	//to add attributes in tr e.g. "DT_RowAttr"=>array("attr1name"=>'attrvalue',"attr2name"=>'attrvalue')
								'CHECKBOX'=>"<input type='checkbox' class='itemcheckbox' rowid='$id'>",
								'ACTION'=>"<img id='$id' supplier='$supplierid' tags='$tags' app_status='$app_status' code='$itemcode' currency='$currencyid' shortdesc='$shortdesc' longdesc='$longdesc' supp_name='$supp_name' uom='$uomid' price='$price' leadtime='$lead' remarks='$remarks' stat='$stat' conformance='$conf' class='nopadding edititembtn $hidebtn' src='../resources/img/File_Edit.png' title='Edit Item' data-toggle='modal' href='#edititemmodal'>".$app_button,

								'app_status'=>$app_status, 
								'id'=>$id, 
								'active_flag'=>$status, 
								'item_code'=>$itemcode, 
								'item_name'=>$shortdesc,
								'long_description'=>$longdesc,
								'supplier_name'=>$supp_name,
								'unit_price'=>$price2,
								'currency_code'=>$currency,
								'unit_of_measure_code'=>$uom,
								'conformance_flag'=>$conformance,
								'created_date'=>$createddate,
								'created_by'=>$createdby,
								'updated_date'=>$updateddate,
								'updated_by'=>$updatedby,
							);
			array_push($data, $arraydata);
        }

        return $data;
    	
    }

   $searchColumns = array( 'item_code',
    					   'item_name',
    					   'long_description',
    					   'supplier_name',
    					   'unit_price',
    					   'currency_code',
    					   'unit_of_measure_code');




    /* Useful $_POST Variables coming from the plugin */
    $draw = $_POST["draw"];//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
    $orderByColumnIndex  = $_POST['order'][0]['column'];// index of the sorting column (0 index based - i.e. 0 is the first record)
    $orderBy = $_POST['columns'][$orderByColumnIndex]['data'];//Get name of the sorting column from its index
    $orderType = $_POST['order'][0]['dir']; // ASC or DESC
    $start  = $_POST["start"];//Paging first record indicator.
    $length = $_POST['length'];//Number of records that the table can display in the current draw
    /* END of POST variables */



    $recordsTotal = mysql_num_rows(mysql_query($qry));

    /* SEARCH CASE : Filtered data */
    if(!empty($_POST['search']['value'])){

        /* WHERE Clause for searching */
        for($i=3 ; $i<count($_POST['columns']);$i++){
            $column = $_POST['columns'][$i]['data'];//we get the name of each column using its index from POST request
            if($column=='created_date'||$column=='updated_date'){
            	$where[]="$column like '%".date('Y-m-d', strtotime(escapeString($_POST['search']['value'])))."%'";
            }
            else{
            	$where[]="trim($column) like '%".escapeString($_POST['search']['value'])."%'";
            }

        }

        /*for($i=0; $i<count($searchColumns); $i++){
        	$column = $searchColumns[$i];//we get the name of each column using its index from POST request
            $where[]="$column like '%".utf8_decode($_POST['search']['value'])."%'";
        });*/

        $where = "WHERE ".implode(" OR " , $where);// id like '%searchValue%' or name like '%searchValue%' ....
        /* End WHERE */

       
        $sql = sprintf("%s %s", $qry , $where);//Search query without limit clause (No pagination)

        $recordsFiltered = mysql_num_rows(mysql_query($sql));//Count of search result

        /* SQL Query for search with limit and orderBy clauses*/
        $sql = sprintf("%s %s ORDER BY %s %s limit %d , %d ", $qry , $where ,$orderBy, $orderType ,$start,$length  );
        $data = getData2($sql);
    }
    /* END SEARCH */
    else {
        $sql = sprintf("%s ORDER BY %s %s limit %d , %d ", $qry ,$orderBy,$orderType ,$start , $length);
        $data = getData2($sql);

        $recordsFiltered = $recordsTotal;
    }

    /* Response to client before JSON encoding */
    $response = array(
        "draw" => intval($draw),
        "recordsTotal" => $recordsTotal,
        "recordsFiltered" => $recordsFiltered,
        "data" => $data
    );

    echo json_encode($response);

} else {
    echo "NO POST Query from DataTable";
}
?>