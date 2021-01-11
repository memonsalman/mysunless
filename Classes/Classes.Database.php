<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/crm/function.php');

$query = $db->prepare("Select * from users where usertype='Admin' ");
$query->execute();
$Result = $query->fetchAll();
array_push($Result,$db->queryFetchDatabase());
echo json_encode($Result);

?>