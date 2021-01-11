<?php session_start(); ?>
<?php 
    require_once('Exec_Config.php');        

require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
$db= new db();
// Very important to set the page number first.
if (!(isset($_POST['start']))) { 
    $pagenum = 1; 
} else {
    $pagenum = intval($_POST['start']);    
}
//Number of results displayed per page  by default its 10.
@$page_limit =  10;
// Get the total number of rows in the table
try {
    $stmt = $db->prepare("SELECT count(*) as count FROM `faqs`");
    $stmt->execute();
    $result1 = $stmt->fetchAll();
} catch (Exception $ex) {
    echo($ex->getMessage());
}
$cnt = $result1[0]["count"];
//Calculate the last page based on total number of rows and rows per page. 
$last = ceil($cnt/$page_limit); 
//this makes sure the page number isn't below one, or more than our maximum pages 
if ($pagenum < 1) { 
    $pagenum = 1; 
} elseif ($pagenum > $last)  { 
    $pagenum = $last; 
}
@$lower_limit = ($pagenum - 1) * $page_limit;
$sql2 = " SELECT * FROM `faqs` limit ".$lower_limit." ,  ".$page_limit. " ";
try {
    $stmt = $db->prepare($sql2);
    $stmt->execute();
    @$result_faqs = $stmt->fetchAll();
} catch (Exception $ex) {
    echo($ex->getMessage());
}
?>
<div id= 'faqs_content'>
    <?php
foreach ($result_faqs as $row) 
{
    echo " <div class='card m-b-0'>
<div class='card-header ' role='tab' id='headingOne1'>
<h5 class='mb-0'>
<a class='link collapsed' data-toggle='collapse' data-parent='#accordion1' href='#".$row['id']."' aria-expanded='false' aria-controls='collapseOne'>
Q.&nbsp".$row['faqTitle']."
</a>
</h5>
</div>
<div id='".$row['id']."' class='collapse' role='tabpanel' aria-labelledby='headingOne1' style=''>
<div class='card-body'>
".$row['faqDesc']."
</div>
</div>
</div>";
}
    ?>      
    <br>
</div>
<div class="height30">
</div>
<table width="50%" border="0" cellspacing="0" cellpadding="2" >
    <tr>
        <td valign="top" >
            <?php
if ( ($pagenum-1) > 0) {
            ?>    
            <a href="javascript:void(0);" class="links" onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo 1; ?>')">First</a>
            <a href="javascript:void(0);" class="links"  onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $pagenum-1; ?>')">Previous</a>
            <?php
}
//Show page links
for($i=1; $i<=$last; $i++) {
    if ($i == $pagenum ) {
            ?>
            <a href="javascript:void(0);" class="links selected" ><?php echo $i ?></a>
            <?php
    } else {  
            ?>
            <a href="javascript:void(0);" class="links"  onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $i; ?>')" ><?php echo $i ?></a>
            <?php 
    }
} 
if ( ($pagenum+1) <= $last) {
            ?>
            <a href="javascript:void(0);" onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $pagenum+1; ?>')" class="links">Next</a>
            <?php 
}
if ( ($pagenum) != $last) 
{ 
            ?>    
            <a href="javascript:void(0);" onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $last; ?>')" class="links" >Last</a>
            <?php
} 
            ?>
        </td>
    </tr>
</table>