<?php
include('bes_config.php');
include('bes_initdb.php');
include('includes/functions.php');
include('bes_initvars.php');

require('fpdf/fpdf.php');

function gen_selectview($posx,$ln,$boxname)
{
  global $pdf;
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

$fontsize=10;
$fontsize2=11;
$fontsize3=8;
$ln=5;

$pdf=new FPDF('P','mm','A4');
$pdf->SetAutoPageBreak(true,1);
$pdf->SetTopMargin($ln);

  $pdf->AddPage();
  // Kopf
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,$ln,'(C) 2o1o, xxxxxxx',0,0,'L');
    $pdf->Cell(0,$ln,'Seite 1/2',0,0,'R');
    $pdf->Ln($ln);
    $pdf->SetFont('','B');
    $pdf->Write($ln,"Bado ID:  ");
    $pdf->Cell(10,$ln*2,"",1,0,'L');
    $pdf->Cell(10,$ln*2,"",1,0,'L');
    $pdf->Cell(10,$ln*2,"",1,0,'L');
    $pdf->Cell(10,$ln*2,"",1,0,'L');
    $pdf->Cell(10,$ln*2,"",1,0,'L');
    $pdf->Cell(10,$ln*2,"",1,0,'L');
    $pdf->Line(10,23,200,23);
    $pdf->Ln($ln);
    $pdf->Ln($ln);
    $pdf->Ln($ln);
  // Kopf Ende

  $pdf->SetFont('Arial','',$fontsize);
  $pdf->Cell(80,$ln*7,"Patientenaufkleber",1,0,'C');
  $pdf->Ln($ln);$pdf->Ln($ln);$pdf->Ln($ln);$pdf->Ln($ln);$pdf->Ln($ln);$pdf->Ln($ln);$pdf->Ln($ln);$pdf->Ln($ln);

  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,"Wohnort\n");
  $pdf->SetFont('Arial','',$fontsize);
  gen_selectview(0,$ln,"wohnort");
  $pdf->Write($ln,"\n");

  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,"Migrationshintergrund\n");
  $pdf->SetFont('Arial','',$fontsize);
  gen_selectview(0,$ln,"migration");
  $pdf->Write($ln,"\n");

  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,"Familienstand\n");
  $pdf->SetFont('Arial','',$fontsize);
  gen_selectview(0,$ln,"familienstand");
  $pdf->Write($ln,"\n");

  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,"Berufsbildung\n");
  $pdf->SetFont('Arial','',$fontsize);
  gen_selectview(0,$ln,"berufsbildung");
  $pdf->Write($ln,"\n");

  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,utf8_decode("Einkünfte\n"));
  $pdf->SetFont('Arial','',$fontsize);
  gen_selectview(0,$ln,"einkuenfte");
  $pdf->Write($ln,"\n");

  $pdf->SetY(26);
  $pdf->SetX(120);
  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,"Wohnsituation\n");
  $pdf->SetFont('Arial','',$fontsize);
  gen_selectview(120,$ln,"wohnsituation");
  $pdf->Write($ln,"\n");

  $pdf->SetX(120);
  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,"Einweisung / Verlegung / Weiterleitung\n");
  $pdf->SetFont('Arial','',$fontsize);
  gen_selectview(120,$ln,"einweisung");
  $pdf->Write($ln,"\n");

  $pdf->SetX(120);
  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,"Begleitung / Transport durch\n");
  $pdf->SetX(120);
  $pdf->SetFont('Arial','',$fontsize3);
  $pdf->Write($ln,utf8_decode("(bis zu 2 Angaben möglich)\n"));
  $pdf->SetFont('Arial','',$fontsize);
  gen_selectview(120,$ln,"begleitung");
  $pdf->Write($ln,"\n");

  $pdf->SetX(120);
  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,"Aufnahmemodus\n");
  $pdf->SetFont('Arial','',$fontsize);
  gen_selectview(120,$ln,"amodus");
  $pdf->Write($ln,"\n");

  $pdf->SetX(120);
  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,"Aufnahme in das ZPP&P\n");
  $pdf->SetFont('Arial','',$fontsize);
  $pdf->SetX(120);
  $pdf->Write(8,"am  ");
  $pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');
  $pdf->Write(8," .  ");
  $pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');
  $pdf->Write(8,".2010\n");
  $pdf->Ln(1);
  $pdf->SetX(120);
  $pdf->Write(8,"auf Station  ");
  $pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');
  $pdf->Write(8,"  TS  ");$pdf->Cell(8,8,"",1,0,'L');
  $pdf->Ln(10);
  $pdf->SetX(120);
  $pdf->Cell(4,4,"",1,0,'L');
  $pdf->Write($ln,"Wochentag    ");
  $pdf->Cell(4,4,"",1,0,'L');
  $pdf->Write($ln,"Festtag    ");
  $pdf->Cell(4,4,"",1,0,'L');
  $pdf->Write($ln,"Samstag/Sonntag\n");
  $pdf->Ln($ln);
  $pdf->SetX(120);
  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,"Uhrzeit der Aufnahme im ZPP&P\n");
  $pdf->SetFont('Arial','',$fontsize);
  gen_selectview(120,$ln,"auhrzeit_schicht");

  // andere Klinik Kaestchen
  $pdf->SetXY(165,116);
  $pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');
  $pdf->Line(33,135,90,135);

  $pdf->AddPage();
  // Kopf
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,$ln,'(C) 2o1o, Zentrum PP&P Klinikum EvB Potsdam',0,0,'L');
    $pdf->Cell(0,$ln,'Seite 2/2',0,0,'R');
    $pdf->Ln($ln);
    $pdf->Line(10,18,200,18);
    $pdf->Ln($ln);
    $pdf->Ln($ln);
  // Kopf Ende
  $posy=$pdf->GetY();
  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,"Rechtsstatus\n");
  $pdf->SetFont('Arial','',$fontsize);
  gen_selectview(0,$ln,"rechtsstatus");
  $pdf->Write($ln,"\n");

  $pdf->SetXY(60,$posy);
  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,"Unterbringungsdauer\n");
  $pdf->SetFont('Arial','',$fontsize);
  gen_selectview(60,$ln,"unterbringungsdauer");
  $pdf->Write($ln,"\n");

  $pdf->SetXY(112,$posy);
  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,"Entlassungsmodus\n");
  $pdf->SetFont('Arial','',$fontsize);
  gen_selectview(112,$ln,"emodus");
  $pdf->Write($ln,"\n");
  $pdf->Write($ln,"\n");

  $pdf->SetY(60);
  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,"Entlassung aus dem ZPP&P\n");
  $pdf->SetFont('Arial','',$fontsize);

  $pdf->Write(8,"am  ");
  $pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');
  $pdf->Write(8," .  ");
  $pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');
  $pdf->Write(8,".2010\n");
  $pdf->Ln(1);
  $pdf->Write(8,"von Station  ");
  $pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');
  $pdf->Write($ln,"\n");
  $pdf->Write($ln,"\n");
  $pdf->Write($ln,"\n");

  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,"Wohnsituation bei Entlassung\n");
  $pdf->SetFont('Arial','',$fontsize);
  gen_selectview(0,$ln,"wohnsituation");
  $pdf->Write($ln,"\n");

  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,"Wohnort bei Entlassung\n");
  $pdf->SetFont('Arial','',$fontsize);
  gen_selectview(0,$ln,"wohnort");
  $pdf->Write($ln,"\n");

  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,"Weiterbehandlung / -betreuung\n");
  $pdf->SetFont('Arial','',$fontsize3);
  $pdf->Write($ln,utf8_decode("(bis zu 3 Angaben möglich)\n"));
  $pdf->SetFont('Arial','',$fontsize);
  gen_selectview(0,$ln,"weiterbehandlung");
  $pdf->Write($ln,"\n");

  $pdf->SetXY(112,60);
  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,utf8_decode("Suizidalität & Selbstverletzung\n"));
  $pdf->SetFont('Arial','',$fontsize);
  gen_selectview(112,$ln,"suizid_sv");
  $pdf->Write($ln,"\n");

  $pdf->SetX(112);
  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,"Psychiatrische Diagnosen (ICD-10)\n");
  $pdf->SetFont('Arial','',$fontsize2);
  $pdf->SetX(112);
  $pdf->Write(8,"F  ");
  $pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');
  $pdf->Write(8," .  ");
  $pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');
  $pdf->Write(8,"    F  ");
  $pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');
  $pdf->Write(8," .  ");
  $pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');
  $pdf->Write($ln,"\n\n\n");
  $pdf->SetX(112);
  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,"Somatische Diagnosen(ICD-10)\n");
  $pdf->SetFont('Arial','',$fontsize2);
  $pdf->SetX(112);

  $pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');
  $pdf->Write(8," .  ");
  $pdf->Cell(8,8,"",1,0,'L');
  $pdf->Write(8,"           ");
  $pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');
  $pdf->Write(8," .  ");
  $pdf->Cell(8,8,"",1,0,'L');

  $pdf->Write($ln,"\n\n\n");
  $pdf->SetX(112);
  $pdf->SetFont('Arial','B',$fontsize2);
  $pdf->Write($ln,utf8_decode("Ärztin/Arzt  Psychologin/Psychologe\n"));
  $pdf->SetFont('Arial','',$fontsize);
  $pdf->SetX(112);
  $pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');
  $pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');
  $pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');$pdf->Cell(8,8,"",1,0,'L');

$pdf->Output('bado_empty','I');
?>
