<?php

ini_set("display_errors", "1");
error_reporting(E_ALL);

// (c) Xavier Nicolay
// Exemple de génération de devis/facture PDF

require('invoice.php');
require_once('function.php'); 

// if(isset($_REQUEST['Orderid']))
// {
     $Orderid=54; 
    // $Orderid=$_POST['Orderid']; 
    $eidtserivce = $db->prepare("select * from `OrderServic` JOIN clients ON OrderServic.Cid=clients.id JOIN Service ON Service.id=OrderServic.SeriveId WHERE OrderServic.OrderId=:Orderid");
    $eidtserivce->bindValue(":Orderid",$Orderid,PDO::PARAM_INT);
    $eidtserivcefile=$eidtserivce->execute();
    $all_serivce=$eidtserivce->fetchAll(PDO::FETCH_ASSOC);

      
     // $Orderid=$_POST['Orderid']; 
    $eidtproduct = $db->prepare("select OrderProduct.OrderId as id, Product.ProductTitle as Title,OrderProduct.ProductPrice as QUANTITE,OrderProduct.ProdcutQuality as PUHT,OrderProduct.ProductDiscountInParentage as MONTANTHT,OrderProduct.ProductFianlPrice as TVA from `OrderProduct` JOIN clients ON OrderProduct.Cid=clients.id JOIN Product ON Product.id=OrderProduct.ProdcutId WHERE OrderProduct.OrderId=:Orderid");
    $eidtproduct->bindValue(":Orderid",$Orderid,PDO::PARAM_INT);
    $eidtproductfile=$eidtproduct->execute();
    $all_prodcut=$eidtproduct->fetchAll(PDO::FETCH_ASSOC);

    // print_r($all_prodcut[0]);
    // die();

    
    // $Orderid=$_POST['Orderid']; 
    $eidtmembership = $db->prepare("select * from `OrderMembership` JOIN clients ON OrderMembership.Cid=clients.id JOIN MemberPackage ON MemberPackage.id=OrderMembership.MembershipId WHERE OrderMembership.OrderId=:Orderid");
    $eidtmembership->bindValue(":Orderid",$Orderid,PDO::PARAM_INT);
    $eidtmembershipfile=$eidtmembership->execute();
    $all_membership=$eidtmembership->fetchAll(PDO::FETCH_ASSOC);
    
    // $Orderid=$_POST['Orderid']; 
    $order = $db->prepare("select * from `OrderMaster` JOIN clients ON OrderMaster.Cid=clients.id WHERE OrderMaster.id=:Orderid");
    $order->bindValue(":Orderid",$Orderid,PDO::PARAM_INT);
    $orderfile=$order->execute();
    $all_order=$order->fetch(PDO::FETCH_ASSOC);
    
    // if($orderfile)
    // {
    //     echo  json_encode(["resonse_serive"=>$all_serivce,"resonse_product"=>$all_prodcut,"resonse_membership"=>$all_membership,"resonse_order"=>$all_order]);die;
    // }
// }



$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
$pdf->AddPage();
$pdf->Image('mysunless_logo.png',10,6,30);
// $pdf->addSociete( "MaSociete",
//                   "MonAdresse\n" .
//                   "75000 PARIS\n".
//                   "R.C.S. PARIS B 000 000 007\n" .
//                   "Capital : 18000 " . EURO );
$pdf->fact_dev( "", "" );
$pdf->temporaire( "MY SUNLESS" );
$pdf->addDate( "03/12/2003");
// $pdf->addClient("CL01");
$pdf->addPageNumber("1");
$pdf->addClientAdresse("Ste\nM. XXXX\n3ème étage\n33, rue d'ailleurs\n75000 PARIS");
$pdf->addReglement("Chèque à réception de facture");
$pdf->addEcheance("03/12/2003");
$pdf->addNumTVA("FR888777666");
// $pdf->addReference("Devis ... du ....");
$cols=array( "id"    => 15,
             "Title"  => 78,
             "QUANTITE"     => 22,
             "PUHT"      => 26,
             "MONTANTHT" => 30,
             "TVA"          => 11 );
$pdf->addCols( $cols);

$cols=array( "id"    => "L",
             "Title"  => "L",
             "QUANTITE"     => "C",
             "PUHT"      => "R",
             "MONTANTHT" => "R",
             "TVA"          => "C" );
$pdf->addLineFormat( $cols);
$pdf->addLineFormat($cols);

$y    = 109;
 // $line = array( "REFERENCE"    => "REF1",
 //                "DESIGNATION"  => "Carte Mère MSI 6378\n" .
 //                                  "Processeur AMD 1Ghz\n" .
 //                                  "128Mo SDRAM, 30 Go Disque, CD-ROM, Floppy, Carte vidéo",
 //                "QUANTITE"     => "1",
 //                "PUHT"      => "600.00",
 //                "MONTANTHT" => "600.00",
 //                "TVA"          => "1" );
$size = $pdf->addLine($y,$all_prodcut[0]);
$y   += $size + 2;


// $line = array( "REFERENCE"    => "REF2",
//                "DESIGNATION"  => "Câble RS232",
//                "QUANTITE"     => "1",
//                "PUHT"      => "10.00",
//                "MONTANTHT" => "60.00",
//                "TVA"          => "1" );

// $size = $pdf->addLine($y,$all_prodcut);
// $y   += $size + 2;

$pdf->addCadreTVAs();
        
// invoice = array( "px_unit" => value,
//                  "qte"     => qte,
//                  "tva"     => code_tva );
// tab_tva = array( "1"       => 19.6,
//                  "2"       => 5.5, ... );
// params  = array( "RemiseGlobale" => [0|1],
//                      "remise_tva"     => [1|2...],  // {la remise s'applique sur ce code TVA}
//                      "remise"         => value,     // {montant de la remise}
//                      "remise_percent" => percent,   // {pourcentage de remise sur ce montant de TVA}
//                  "FraisPort"     => [0|1],
//                      "portTTC"        => value,     // montant des frais de ports TTC
//                                                     // par defaut la TVA = 19.6 %
//                      "portHT"         => value,     // montant des frais de ports HT
//                      "portTVA"        => tva_value, // valeur de la TVA a appliquer sur le montant HT
//                  "AccompteExige" => [0|1],
//                      "accompte"         => value    // montant de l'acompte (TTC)
//                      "accompte_percent" => percent  // pourcentage d'acompte (TTC)
//                  "Remarque" => "texte"              // texte
$tot_prods = array( array ( "px_unit" => 600, "qte" => 1, "tva" => 1 ),
                    array ( "px_unit" =>  10, "qte" => 1, "tva" => 1 ));
$tab_tva = array( "1"       => 19.6,
                  "2"       => 5.5);
$params  = array( "RemiseGlobale" => 1,
                      "remise_tva"     => 1,       // {la remise s'applique sur ce code TVA}
                      "remise"         => 0,       // {montant de la remise}
                      "remise_percent" => 10,      // {pourcentage de remise sur ce montant de TVA}
                  "FraisPort"     => 1,
                      "portTTC"        => 10,      // montant des frais de ports TTC
                                                   // par defaut la TVA = 19.6 %
                      "portHT"         => 0,       // montant des frais de ports HT
                      "portTVA"        => 19.6,    // valeur de la TVA a appliquer sur le montant HT
                  "AccompteExige" => 1,
                      "accompte"         => 0,     // montant de l'acompte (TTC)
                      "accompte_percent" => 15,    // pourcentage d'acompte (TTC)
                  "Remarque" => "Avec un acompte, svp..." );

$pdf->addTVAs( $params, $tab_tva, $tot_prods);
$pdf->addCadreEurosFrancs();
$pdf->Output();
?>
