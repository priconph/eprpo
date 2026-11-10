<?php
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

    $crncy = isset($_GET['currency'])?"and default_currency='".$_GET['currency']."'":'';
/* IF Query comes from DataTables do the following */

	if (!empty($_POST) ) {

		$qry = "select  a.id,
				        a.item_name,
						a.item_code,
						a.long_description,
						a.unit_price,
						a.conformance_flag,
						a.uom,
						a.uomcode,
						a.created_date,
						a.updated_date,
						a.currency_code,
						a.created_by,
						a.updated_by,
						a.supplier_name,
						a.supp_status
				from
				(select item.id,
						item_name,
						item_code,
						long_description,
						item.unit_price,
						item.conformance_flag,
						unit_of_measure.id as uom,
						unit_of_measure_code as uomcode,
						item.created_date,
						item.updated_date,
						currency_code,
						created_by,
						updated_by,
						group_concat(supplier_name separator ', ') as supplier_name,
						supplier.active_flag as supp_status
						
						from item
						left join unit_of_measure on  unit_of_measure_id=unit_of_measure.id
						left join currency on currency.id=item.default_currency
						left join (select id, concat(COALESCE(first_name,''),' ',COALESCE(last_name,'')) as created_by from user) as a on a.id=item.created_by_user_id
						left join (select id, concat(COALESCE(first_name,''),' ',COALESCE(last_name,'')) as updated_by from user) as b on b.id=item.updated_by_user_id
						left join item_supplier on item_supplier.item_id=item.id and item_supplier.item_id=item.id
						left join supplier on supplier.id=item_supplier.supplier_id
						where item.active_flag=1 and (item.app_status=2 or item.app_status=4) and supplier.active_flag=1
						$crncy
						group by item.id) as a";
						// where item.active_flag=1 and (item.app_status=2 or item.app_status=4) // old 07052024 by Nessa
						

		
		
		function getData2($sql){
	    	global $connection ;//we use connection already opened
	        $query = mysql_query($sql) OR DIE ("Can't get Data from DB , check your SQL Query " );
	        $data = array();

			while($obj=mysql_fetch_object($query)){
	            	$id = $obj->id;
					$suppliers = $obj->supp_status == 1 ? strUtfEncode($obj->supplier_name) : '';
					$itemcode = $obj->item_code;
					$name = strUtfEncode($obj->item_name);
					$longdesc = strUtfEncode($obj->long_description);
					$datecreated = formatDate2($obj->created_date);
					$createdby = strUtfEncode($obj->created_by);
					$updateddate = formatDate2($obj->updated_date);
					$updatedby = strUtfEncode($obj->updated_by);
					$conf = $obj->conformance_flag;
					//$dftsupplierid = $obj->defaultsupplierid;
					//$dftsuppliername = strUtfEncode($obj->defaultsupplier);
					$dftsupplierid = $obj->supp_status == 1 ? explode('#@$', getSuppliers($id)) : 0;
					$dftsupplierid = $dftsupplierid == 0 ? 0 : $dftsupplierid[0];
					$dftsuppliername = strUtfEncode(getInfo('supplier','supplier_name',"where id='$dftsupplierid'"));
					$uom = $obj->uom;
					$uomcode = $obj->uomcode;
					$price=$obj->unit_price;
					$prc = convertWithDecimal($price,5);
					$priceamount = number_format($price,5,'.','');
					$curr = $obj->currency_code;

				$arraydata =  array(
									"DT_RowClass"=>"tablerow itemlookuprow pointer",
									"DT_RowAttr"=>array(
															"dftsuppid"=>singleQuotesReverse($dftsupplierid),
															"dftsuppname"=>singleQuotesReverse($dftsuppliername),
															"itemid"=>$id,
															"conformance"=>$conf,
															"uom"=>$uom,
															"uomcode"=>$uomcode,
															"price"=>$priceamount,
															"code"=>$itemcode,
															"name"=>singleQuotesReverse($name),
															"desc"=>singleQuotesReverse($longdesc)
														),
									'item_code'=>$itemcode, 
									'item_name'=>$name,
									'long_description'=>$longdesc,
									'supplier_name'=>$suppliers,
									'unit_price'=>$prc,
									'currency_code'=>$curr,
									'created_date'=>$datecreated,
									'created_by'=>$createdby,
									'updated_date'=>$updateddate,
									'updated_by'=>$updatedby
								);
				array_push($data, $arraydata);
	        }

	        return $data;
	    	
	    }





	    /* Useful $_POST Variables coming from the plugin */
	    $draw = $_POST["draw"];//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
	    $orderByColumnIndex  = $_POST['order'][0]['column'];// index of the sorting column (0 index based - i.e. 0 is the first record)
	    $orderBy = $_POST['columns'][$orderByColumnIndex]['data'];//Get name of the sorting column from its index
	    $orderType = $_POST['order'][0]['dir']; // ASC or DESC
	    $start  = $_POST["start"];//Paging first record indicator.
	    $length = $_POST['length'];//Number of records that the table can display in the current draw
	    /* END of POST variables */

	    $columnSearch = array('item_code','item_name','long_description','supplier_name');


	    $recordsTotal = mysql_num_rows(mysql_query($qry));

	    /* SEARCH CASE : Filtered data */
	    if(!empty($_POST['search']['value'])){

	        /* WHERE Clause for searching */
	        for($i=0 ; $i<count($_POST['columns']);$i++){
	            $column = $_POST['columns'][$i]['data'];//we get the name of each column using its index from POST request
	            if($column=='created_date'||$column=='updated_date'){
	            	$where[]="$column like '%".date('Y-m-d', strtotime($_POST['search']['value']))."%'";
	            }
	            else{
	            	$where[]="$column like '%".mysql_real_escape_string(utf8_decode($_POST['search']['value']))."%'";
	           }

	        }

	        /*for($i=0 ; $i<count($columnSearch);$i++){
	        	$column = $columnSearch[$i];//we get the name of each column using its index from POST request
	            $where[]="$column like '%".utf8_decode($_POST['search']['value'])."%'";

	        }*/



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