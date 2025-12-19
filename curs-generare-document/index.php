<?php

/// Curs 9: Generarea unui document cu php si fpdf
/// Data: 25 Noiembrie 2025, 21:07-00:22

/// Folosim clasa fpdf
require('../assets/fpdf186/fpdf.php');

/// Dimensiunea imaginea pare sa fie 613x613px (613px is approximately 155.7 mm at 96 PPI")
$pdf = new FPDF('P', 'mm', array(155.7, 155.7));
$pdf->AddPage();
$pdf->SetMargins(7, 7, 7, 7);

/// Adauga font special pentru diacritice
// $pdf->AddFont('RobotoBlack','','./fonts/Roboto-Black.ttf');
// $pdf->AddFont('RobotoBold','','./fonts/Roboto-Bold.ttf');
// $pdf->AddFont('RobotoRegular','','./fonts/Roboto-Regular.ttf');

/// Adauga bordura portocalie
$pdf->setDrawColor(255, 126, 6);
$pdf->setLineWidth(5);
$pdf->Rect(0, 0, 155.7, 155.7, 'D');

/// Zona 1.1 "cod rezervare"
$pdf->SetXY(7, 8);
$pdf->SetFont('Arial','B', 16); /// '' pentru a fi regular, 'B' pentru bold
$pdf->SetTextColor(210, 148, 69);
$pdf->setLineWidth(1);
$pdf->MultiCell(40,7,"COD\nREZERVARE", 0);

/// Zona 1.2 "serie bilet"
$pdf->SetXY(47, 15);
$pdf->SetTextColor(5, 7, 11);
$pdf->SetFont('Arial','', 16);
$pdf->Cell(62, 7, '229991288', 0, 0, 'C'); /// primul 0 inseamna fara bordura, al doilea 0 inseamna urmatoarea celula se duce in dreapta

/// Zona 1.3 "cinema city"
$pdf->setXY(109, 8);
$pdf->SetFont('Arial','B', 24);
$pdf->SetTextColor(245, 135, 27);
$pdf->MultiCell(40, 7, "CINEMA CITY", 0, 'R');
$pdf->Ln();

/// Linia 1
$pdf->SetLineWidth(1);
$pdf->SetDrawColor(196, 151, 118);
$pdf->Line(9, 25, 147, 25);

/// Zona 2.1 "film"
$pdf->SetFont('Arial','B', 16); /// '' pentru a fi regular, 'B' pentru bold
$pdf->SetTextColor(210, 148, 69);
$pdf->setLineWidth(1);
$pdf->cell(30, 7, 'FILM');

/// Zona 2.2 "nume film"
$pdf->SetTextColor(5, 7, 11);
$pdf->SetFont('Arial','', 16);
$pdf->Cell(0, 7, 'Napoleon 2D');

/// Zona 3.1 "ziua saptamanii"
$pdf->SetXY(7, 37);
$pdf->Cell(30, 7, 'miercuri');

/// Zona 3.1 "data si ora"
$pdf->Cell(0, 7, '02/12/2023 18:50');

/// Zona 4.1 "locatia"
$pdf->Ln();
$pdf->Cell(0, 7, 'Cluj Iulius Mall');

/// Linia 2
$pdf->Line(9, 55, 147, 55);

/// Zona 5.1 "sala rand loc"
$pdf->setXY(7, 60);
$pdf->SetFont('Arial','B', 24);
$pdf->SetTextColor(245, 135, 27);
$pdf->MultiCell(40, 9, "SALA\nRAND\nLOC");
$pdf->Ln();

/// Zona 5.2 "9 7 6"
$pdf->setXY(52, 60);
$pdf->SetFont('Arial','', 24);
$pdf->SetTextColor(5, 7, 11);
$pdf->MultiCell(50, 9, "Sala 9, Sa\n7\n6");
$pdf->Ln();

/// Zona 5.3 "qrcode"
$pdf->Image("./qrcode.png", 122, 60, -300);

/// Linia 3
$pdf->Line(9, 92, 147, 92);

/// Zona 6.1 "adult 36"
$pdf->SetFont('Arial','', 12);
$pdf->Cell(25, 5, 'Adult 36');

/// Zona 6.2 "serie bilet"
$pdf->SetFont('Arial','', 12);
$pdf->Cell(80, 5, '1803747744502002', 0, 0, 'C');

/// Zona 6.3 "pret"
$pdf->SetFont('Arial','', 12);
$pdf->Cell(35, 5, '36.00 lei', 0, 0, 'R');

/// Linia 4
$pdf->Line(9, 105, 147, 105);

/// Zona 7.1
$pdf->SetXY(7, 110);
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(0, 5, "Biletul de elev / student / pensionar permite intrarea doar cu un act doveditor valid. Va rugam sa le prezentati plasatorilor nostri.\nAcces cu 15 minute inainte.", 0);

/// Salveaza sau trimite documentul
$pdf->Output();
