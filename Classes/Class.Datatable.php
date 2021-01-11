<?php
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');



function DT_OrderBy($orders){

if($orders){

	$order_string = " order by ";
	$order_array = [];

	foreach ($orders as $key => $order) {
		$columnIndex = $order['column']; 
		$columnSortOrder = $order['dir'];
		$columnNames = $_REQUEST['columns'][$columnIndex]['data'];

		if($_REQUEST['columns'][$columnIndex]['orderable']){

			if(is_array($columnNames)){
				foreach ($columnNames as $key => $columnName) {
					array_push($order_array, " $columnName $columnSortOrder "); 
				} 
			}else{
				$columnName = $columnNames;
				array_push($order_array, " $columnName $columnSortOrder "); 
			}
		}
	}

	$order_string.= implode(',', $order_array);
}else{
	$order_string = " ";
}


return $order_string;
}


function DT_Search($search){

if($search){

	$order_array = [];

	foreach ($_REQUEST['columns'] as $key => $column) {

		if($column['searchable']){

		$columndata = $column['data'];

			if(is_array($columndata)){
				foreach ($columndata as $key => $data) {
					array_push($order_array, " $data like '%$search%' "); 
				} 
			}else{
				$data = $columndata;
					array_push($order_array, " $data like '%$search%' "); 
			}

		}

	}

	$search_string = implode(' or ', $order_array);
	$search_string = " ( $search_string ) ";


}else{
	$search_string = " ";
}


return $search_string;
}


function DT_SQL($Query,$BindData=[],$SearchString='',$Limit=''){
	 $db= new db();

    if(is_array($BindData) && count($BindData)>0){

    	// $BindData = [':id'=>$id]; //Example

    	foreach ($BindData as $key => $value) {
    		$Query = str_replace_all($key, $value, $Query);
    	}
    }

    $sql = $db->prepare($Query);
    $sql->execute();
    $totalRecords = $sql->rowCount();

    $totalRecordwithFilter = $totalRecords;


	$Query.=" $SearchString ";
    $sql = $db->prepare($Query);
    $sql->execute();
    $totalRecordwithFilter = $sql->rowCount();
    $resultNoLimit = $sql->fetchAll();



	$Query.=" $Limit ";
    $sql = $db->prepare($Query);
    $sql->execute();
    $resultWithLimit = $sql->fetchAll();



    $response = array(
      // "draw" => intval($_POST['draw']),
      "iTotalRecords" => $totalRecords,
      "iTotalDisplayRecords" => $totalRecordwithFilter,
      "aaData" => $resultWithLimit,
      "aaDataNoLimit" => $resultNoLimit
  );


   return json_encode($response); 

}



?>