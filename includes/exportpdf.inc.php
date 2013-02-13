<?php
require('fpdf/fpdf.php');

function gen_selectview(&$pdf,$posx,$ln,$boxname,$addtext='',$select1='',$select2='',$select3='')
{
//  global $pdf;
  $query = "SELECT * FROM f_".$boxname." ORDER BY ID ASC";
  //mysql_query('set character set utf8;');
  $result = mysql_query($query);
  $num = mysql_num_rows($result);
  for ($i=0; $i < $num; $i++){
    $row = mysql_fetch_array($result);
    if ($row['ID']<10){$id_nr="0".$row['ID'];} else {$id_nr=$row['ID'];}
    if ($posx!=0){$pdf->SetX($posx);}
    $output=$row['option'];
    if ($row['ID']==$select1 or $row['ID']==$select2 or $row['ID']==$select3){
      $pdf->SetFillColor(0,0,0);
      $pdf->SetTextColor(255,255,255);
      $pdf->Cell(8,5,"(".$id_nr.")",0,0,L,1);
      $pdf->SetTextColor(0,0,0);
      if ($addtext!=''){$output.=": ".$addtext;}
      $pdf->Write($ln,utf8_decode($output."\n"));
    } else {
      $pdf->Cell(8,5,"(".$id_nr.")",0);
      $pdf->Write($ln,utf8_decode($output."\n"));
    }
  }
  mysql_free_result($result);
}

function exportIDsPDF($ids=array())
{
  $fontsize=10;
  $fontsize2=11;
  $fontsize3=8;
  $ln=5;

  $pdf=new FPDF('P','mm','A4');
  $pdf->SetAutoPageBreak(true,1);
  $pdf->SetTopMargin($ln);

  foreach ($ids as $id) {
    $query = "SELECT * FROM `fall` WHERE `ID`='".$id."'";
    $result = mysql_query($query);
    $row=mysql_fetch_object($result);
    //$updatequery = "UPDATE `fall` SET `pdfed`='1' WHERE `ID`='".$id."'";
    //if ($updateresult = mysql_query($updatequery)){
    $badoid=$row->badoid;
// Kopf Seite 1
    $pdf->AddPage();
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,$ln,'(C) 2o1o, xxxxxxxxxxxx',0,0,'L');
    $pdf->Cell(0,$ln,'Seite 1/2',0,0,'R');
    $pdf->Ln($ln);
    $pdf->SetFont('','B');
    $pdf->Cell(0,$ln,'Bado ID: '.$row->badoid,0,0,'L');
    $pdf->Line(10,18,200,18);
    $pdf->Ln($ln);
    $pdf->Ln($ln);
// Kopf Ende
// Persoenl. Daten
    $pdf->SetFont('Arial','',$fontsize);
    $pdf->Write($ln,"\n");
    if ($row->geschlecht=="1"){$gender="m";} else {$gender="w";}
    $pdf->Write($ln,utf8_decode("Name: ".$row->familienname.", ".$row->vorname." (".$gender.")\n"));
    //$dummy=split("-",$row->geburtsdatum);
	$dummy = explode("-", $row->geburtsdatum);
    if ($row-geburtsdatum == ""){
      $pdf->Write($ln,"Geburtsdatum: unbekannt\n");
    } else {
      $pdf->Write($ln,"Geburtsdatum: ".$row->geburtsdatum."\n");
    }
    $pdf->Write($ln,"Aufnahmenummer: ".$row->aufnahmenummer."\n\n");
// Wohnort bei Aufnahme
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,"Wohnort\n");
    $pdf->SetFont('Arial','',$fontsize);
    gen_selectview($pdf,0,$ln,"wohnort","",$row->wohnort_a);
    $pdf->Write($ln,"\n");
// Migrationshintergrund
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,"Migrationshintergrund\n");
    $pdf->SetFont('Arial','',$fontsize);
    gen_selectview($pdf,0,$ln,"migration",$row->migration_anderer,$row->migration);
    $pdf->Write($ln,"\n");
// Familienstand
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,"Familienstand\n");
    $pdf->SetFont('Arial','',$fontsize);
    gen_selectview($pdf,0,$ln,"familienstand","",$row->familienstand);
    $pdf->Write($ln,"\n");
// Berufsbildung
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,"Berufsbildung\n");
    $pdf->SetFont('Arial','',$fontsize);
    gen_selectview($pdf,0,$ln,"berufsbildung","",$row->berufsbildung);
    $pdf->Write($ln,"\n");
// Einkuenfte
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,utf8_decode("Einkünfte\n"));
    $pdf->SetFont('Arial','',$fontsize);
    gen_selectview($pdf,0,$ln,"einkuenfte","",$row->einkuenfte);
    $pdf->Write($ln,"\n");
// Wohnsituation
    $pdf->SetY(26);
    $pdf->SetX(120);
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,"Wohnsituation\n");
    $pdf->SetFont('Arial','',$fontsize);
    gen_selectview($pdf,120,$ln,"wohnsituation","",$row->wohnsituation_a);
    $pdf->Write($ln,"\n");
// Einweisung,Verlegung,Weiterleitung
    $pdf->SetX(120);
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,"Einweisung / Verlegung / Weiterleitung\n");
    $pdf->SetFont('Arial','',$fontsize);
    gen_selectview($pdf,120,$ln,"einweisung",idtostr($row->einweisung_evb,"f_kliniken_evb","kuerzel"),$row->einweisung);
    $pdf->Write($ln,"\n");
// Begleitung, Transport
    $pdf->SetX(120);
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,"Begleitung / Transport durch\n");
    $pdf->SetX(120);
    $pdf->SetFont('Arial','',$fontsize3);
    $pdf->Write($ln,utf8_decode("(bis zu 2 Angaben möglich)\n"));
    $pdf->SetFont('Arial','',$fontsize);
    gen_selectview($pdf,120,$ln,"begleitung","",$row->begleitung1,$row->begleitung2);
    $pdf->Write($ln,"\n");
// Aufnahmemodus
    $pdf->SetX(120);
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,"Aufnahmemodus\n");
    $pdf->SetFont('Arial','',$fontsize);
    gen_selectview($pdf,120,$ln,"amodus","",$row->modus_a);
    $pdf->Write($ln,"\n");
// Aufnahme ins ZPPP
    $pdf->SetX(120);
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,"Aufnahme in das ZPP&P\n");
    $pdf->SetFont('Arial','',$fontsize);
    $pdf->SetX(120);
    $pdf->Write($ln,"am ".$row->aufnahmedatum."\n");
    $pdf->SetX(120);
    $pdf->Write($ln,"auf Station ".idtostr($row->station_a,"f_psy_stationen")." / ".utf8_decode(idtostr($row->aufenthalt_art,"f_aufenthalt_art"))."\n");
    $pdf->SetX(120);
    $pdf->Write($ln,idtostr($row->atag_art,"f_atag_art")."\n");
    $pdf->Write($ln,"\n");
// Uhrzeit / Schicht der Aufnahme
    $pdf->SetX(120);
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,"Uhrzeit der Aufnahme im ZPP&P\n");
    $pdf->SetFont('Arial','',$fontsize);
    gen_selectview($pdf,120,$ln,"auhrzeit_schicht","",idtostr($row->auhrzeit_schicht,"f_auhrzeit_schicht"),$row->auhrzeit_schicht);

// Kopf Seite 2
    $pdf->AddPage();
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,$ln,'(C) 2o1o, xxxxxxxxxxxxxxxxxxx',0,0,'L');
    $pdf->Cell(0,$ln,'Seite 2/2',0,0,'R');
    $pdf->Ln($ln);
    $pdf->SetFont('','B');
    $pdf->Cell(0,$ln,'Bado ID: '.$row->badoid,0,0,'L');
    $pdf->Line(10,18,200,18);
    $pdf->Ln($ln);
    $pdf->Ln($ln);
// Kopf Ende
// Rechtsstatus
    $posy=$pdf->GetY();
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,"Rechtsstatus\n");
    $pdf->SetFont('Arial','',$fontsize);
    gen_selectview($pdf,0,$ln,"rechtsstatus","",$row->rechtsstatus);
    $pdf->Write($ln,"\n");
// Unterbringungsdauer
    $pdf->SetXY(60,$posy);
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,"Unterbringungsdauer\n");
    $pdf->SetFont('Arial','',$fontsize);
    gen_selectview($pdf,60,$ln,"unterbringungsdauer","",$row->unterbringungsdauer);
    $pdf->Write($ln,"\n");
// Entlassungsmodus
    $pdf->SetXY(112,$posy);
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,"Entlassungsmodus\n");
    $pdf->SetFont('Arial','',$fontsize);
    gen_selectview($pdf,112,$ln,"emodus","",$row->modus_e);
    $pdf->Write($ln,"\n");
    $pdf->Write($ln,"\n");
// Entlassungs aus ZPPP
    $pdf->SetY(60);
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,"Entlassung aus dem ZPP&P\n");
    $pdf->SetFont('Arial','',$fontsize);
    $pdf->Write($ln,"am ".$row->entlassungsdatum."\n");
    $pdf->Write($ln,"von Station ".idtostr($row->station_e,"f_psy_stationen")."\n");
    $pdf->Write($ln,"\n");
// Wohnsituation bei E
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,"Wohnsituation bei Entlassung\n");
    $pdf->SetFont('Arial','',$fontsize);
    gen_selectview($pdf,0,$ln,"wohnsituation","",$row->wohnsituation_e);
    $pdf->Write($ln,"\n");
// Wohnort bei Entlassung
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,"Wohnort bei Entlassung\n");
    $pdf->SetFont('Arial','',$fontsize);
    gen_selectview($pdf,0,$ln,"wohnort","",$row->wohnort_e);
    $pdf->Write($ln,"\n");
// Weiterbehandlung
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,"Weiterbehandlung / -betreuung\n");
    $pdf->SetFont('Arial','',$fontsize3);
    $pdf->Write($ln,utf8_decode("(bis zu 3 Angaben möglich)\n"));
    $pdf->SetFont('Arial','',$fontsize);
    gen_selectview($pdf,0,$ln,"weiterbehandlung","",$row->weiterbehandlung1,$row->weiterbehandlung2,$row->weiterbehandlung3);
    $pdf->Write($ln,"\n");
// Suizidalitaet
    $pdf->SetXY(112,60);
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,utf8_decode("Suizidalität & Selbstverletzung\n"));
    $pdf->SetFont('Arial','',$fontsize);
    gen_selectview($pdf,112,$ln,"suizid_sv","",$row->suizid_sv);
    $pdf->Write($ln,"\n");
// PSY Diag
    $pdf->SetX(112);
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,"Psychiatrische Diagnosen\n");
    $pdf->SetFont('Arial','',$fontsize);
    $pdf->SetX(112);
    $pdf->Write($ln,$row->psydiag1."   ".$row->psydiag2."\n");
    $pdf->Write($ln,"\n");
// SOM Diag
    $pdf->SetX(112);
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,"Somatische Diagnosen\n");
    $pdf->SetFont('Arial','',$fontsize);
    $pdf->SetX(112);
    $pdf->Write($ln,$row->somdiag1."    ".$row->somdiag2."\n");
    $pdf->Write($ln,"\n");
// Behandler
    $pdf->SetX(112);
    $pdf->SetFont('Arial','B',$fontsize2);
    $pdf->Write($ln,utf8_decode("Ärztin/Arzt  Psychologin/Psychologe\n"));
    $pdf->SetFont('Arial','',$fontsize);
    $pdf->SetX(112);
    $pdf->Write($ln,utf8_decode(idtostr($row->behandler,"user","username"))."\n");
    //$pdf->Write($ln,utf8_decode(idtostr($row->behandler,"user","realname"))." (".
    //                utf8_decode(idtostr($row->behandler,"user","username")).")\n");
    $pdf->Write($ln,"\n");
// Notizen
    $pdf->SetX(112);
    $pdf->Write($ln,"Notizen:\n");
    $pdf->SetX(112);
    $pdf->MultiCell(0,$ln,utf8_decode($row->msg_log),1);
  }
  mysql_free_result($result);

  return $pdf->Output('','S');
}
?>
