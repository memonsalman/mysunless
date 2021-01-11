<?php

// logout.php



// Calls "destroy_tokens_in_store" to destroy the tokens in the token store, forcing a re-login next time.

require_once "../src/functions.inc.php";
require_once $_SERVER['DOCUMENT_ROOT']."/crm/global.php";


if (skydrive_tokenstore::destroy_tokens_in_store()) {

	$url1="https://mysunless.com".$SUB."/MyBackup.php";

    header('location:'.$url1);exit;

} else {

	echo "Error";

}

?>