<?php


 require_once('global.php');

require_once($_SERVER['DOCUMENT_ROOT'].$SUB."/exec-registration.php");
//$obj=new model;


if(isset($_REQUEST['id']))
{
    $sel =new model;
    $sel->GetUserData();
}
if(isset($_REQUEST['insert']))
{
    $ins =new model;
    $new_reg=$ins->insert();
    if($new_reg)
    {
        $myactivite = new Activites();
        $Titile=$myactivite->Titile = 'New registrion has been done';	
        $myactivite->commit_acitve($Titile);
    }
}
if(isset($_REQUEST['update']))
{
    $ins =new model;
    $ins->update();
}
?>