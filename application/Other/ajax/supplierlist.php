<?php
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

    $itemidstr = isset($_GET['itemid'])?"and item_id='".$_GET['itemid']."'":'';
/* IF Query comes from DataTables do the following */
if (!empty($_POST) ) {

	$qry = "select * 
			from
				(
					select id, supplier_name 
					from supplier 
                    left join item_supplier on item_supplier.supplier_id=supplier.id
					where active_flag='1' $itemidstr
                    group by supplier.id
				) as a";

    $rscount = mysql_num_rows(mysql_query($qry));
    if($rscount==0){
        $qry = "select * 
            from
                (
                    select id, supplier_name 
                    from supplier 
                    left join item_supplier on item_supplier.supplier_id=supplier.id
                    where active_flag='1'
                    group by supplier.id
                ) as a";
    }



	
	
	function getData2($sql){
    	global $connection ;//we use connection already opened
        $query = mysql_query($sql) OR DIE ("Can't get Data from DB , check your SQL Query " );
        $data = array();

		while($obj=mysql_fetch_object($query)){
            	$id= $obj->id;
            	$name= strUtfEncode($obj->supplier_name);

			$arraydata =  array(
								"DT_RowClass"=>"tablerow supplierlookuprow pointer",
								"DT_RowAttr"=>array(
														"supplierid"=>$id
													),
								'supplier_name'=>$name
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

    $columnSearch = array('supplier_name');


    $recordsTotal = mysql_num_rows(mysql_query($qry));

    /* SEARCH CASE : Filtered data */
    if(!empty($_POST['search']['value'])){

        /* WHERE Clause for searching */
        for($i=0 ; $i<count($_POST['columns']);$i++){
            $column = $_POST['columns'][$i]['data'];//we get the name of each column using its index from POST request
           	$where[]="$column like '%". strUtfDecode($_POST['search']['value'])."%'";
           

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