<?php

    require_once('Exec_Config.php');        
    
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.Faqs.php'); 
$Faqs = new Faqs($_POST["id"]);

$Faqs->id = $_POST["id"];

// echo "<pre>";
// print_r($_POST);
// die;
$Faqs->faqTitle = stripslashes(strip_tags($_POST["faqTitle"])); //$_POST["faqTitle"];
// $faqDesc=$Faqs->faqDesc = stripslashes(strip_tags($_POST["faqDesc"]));
$faqDesc=$Faqs->faqDesc = $_POST["faqDesc"];


if($_POST["faqCategoryNew"] != "")
{
    
    $Faqs->faqCategory = stripslashes(strip_tags(trim($_POST["faqCategoryNew"])," "));
    
}   
else if($_POST["faqCategoryOld"] != "")
{
    
    $Faqs->faqCategory = stripslashes(strip_tags(trim($_POST["faqCategoryOld"])," "));
    
    
}

$Faqs->commit($Faqs->id);


if($Faqs)
{ 
    $myactivite = new Activites();
    if(@$_POST['id']=="new")
    {

        $Titile=$myactivite->Titile = 'Add new FAQ '.$Faqs->faqTitle;	
    }
    else
    {
       
        $Titile=$myactivite->Titile = 'Update FAQ details '.$Faqs->faqTitle;		
    }
    $myactivite->commit_acitve($Titile);
    if($_POST['id']=="new")
    {
        echo  json_encode(["resonse"=>'FAQ has been successfully added']);die;			
    }		
    else
    {
        echo  json_encode(["resonse"=>'FAQ has been successfully updated']);die;			
    }
}
else
{
    echo  json_encode(["error"=>'Something wrong']);die;
}
/*$FaqDis=new DisplayFaqs;
$FaqDis->FaqsDisplay();
*/
?>