<?php


require('fpdf.php');
class PDF extends FPDF
         {}


$pdf = new PDF('L', 'mm', array(290, 210));
$pdf->AddPage();

$pageWidth = 290;
$partWidth = $pageWidth / 3;
$pdf->SetXY(10, 10); // Set the position for the first part
$pdf->Cell($partWidth, 100, 'Part 1', 1, 1, 'C');

$pdf->SetXY(10 + $partWidth, 10); // Set the position for the second part
$pdf->Cell($partWidth, 100, 'Part 2', 1, 1, 'C');



$pdf->SetXY(10 + ($partWidth * 2), 10); // Set the position for the third part
$pdf->Cell($partWidth, 100, 'Part 3', 1, 1, 'C');



// Output the PDF
$pdf->Output('divided_page.pdf', 'F');




?>