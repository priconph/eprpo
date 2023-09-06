<?php

include("../../config/connection.php");
include("../../config/checklogin.php");
include("../../config/functions.php");
//include("../../Config/db-function.php");
require('../../resources/htmltopdf/fpdf.php');


class PDF extends FPDF
{	
	function Footer()
	{
	    $this->SetY(-15);
	    $this->SetFont('Arial','I',10);
	    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}

if(isset($_GET['KFjskrlkdfjnkalel0iidjlk'])){
	$imagelogo = "../../resources/img/pricon-logo.jpg";
	$pdf = new PDF('L','mm','LEGAL');
	$pdf->AliasNbPages();
	$pdf->AddPage();

	$pdf->Cell( 110, 40, $pdf->Image($imagelogo, $pdf->GetX(), $pdf->GetY(), 100), 0, 0, 'L', false );
}