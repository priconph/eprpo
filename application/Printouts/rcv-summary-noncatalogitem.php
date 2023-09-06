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

if(isset($_GET['DlenjF34KJddlkeornfk349n'])){

	$item = strtoupper(escapeString($_GET['itemcode']));
	$from = escapeString($_GET['orderdatefrom']);
	$to = escapeString($_GET['orderdateto']);
	$query=  "select item.item_code,
					 item.item_name,
					 item_description,
					 receiving_details_non_catalog.receiving_number,
					 receiving_header.received_date,
					 receiving_header.received_by,
					 receiving_header.actual_delivery_date,
					 supplier_name,
					 item.item_code,
					 unit_price,
					 quantity_received,
					 currency_code,
					 reference_po_number,
					 other_reference
				  from receiving_header, receiving_details_non_catalog, supplier, currency
				  where receiving_header.receiving_number=receiving_details_non_catalog.receiving_number and
				  		supplier.id=receiving_header.supplier_id and
				  		receiving_header.currency=currency.id and
				  		receiving_details_non_catalog.item_name like '%$item%'";
	
	if($from!=''&&$to!=''){
		$query = $query." and date(actual_delivery_date) between '$from' and '$to'";
	}
	else if($from==''&&$to!=''){
		$query = $query." and date(actual_delivery_date) <= '$to'";
	}
	else if($from!=''&&$to==''){
		$query = $query." and date(actual_delivery_date) between '$from' and curdate()";
	}

	$rs = query($query);
	$count = getNumRows($rs);

	
		$imagelogo = "../../resources/img/pricon-logo.png";
		$pdf = new PDF('L','mm','LEGAL');
		$pdf->AliasNbPages();
		$pdf->AddPage();

		$fillcolor = array(31, 58, 147);
		$rowitem = array(89, 171, 227);
		$rowitem2 = array(234, 246, 255);
		$pdf->SetFillColor($fillcolor[0],$fillcolor[1],$fillcolor[2]);
		$pdf->SetFont('Arial','B',15);
		$pdf->SetTextColor(0,0,0);

		$pdf->Cell(180, 5, $pdf->Image($imagelogo, $pdf->GetX(), $pdf->GetY(), 80), 0, 1, 'L', false );
		$pdf->Cell(120,8,'');
		$pdf->Cell(135,8,'RECEIVING SUMMARY BY ITEM',0);

		$pdf->SetTextColor(228, 241, 254);
		$pdf->SetFont('Arial','B',8);
		//$pdf->Cell(220,5,'');
		$pdf->Cell(30,5,'SEARCH ITEM','LBTR',0,'C',true);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(50,5,$item,'LBTR',1,'C');
		$pdf->Ln();
		$pdf->Ln();

		//line item header
		$pdf->SetTextColor(228, 241, 254);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(25,5,'DATE RECEIVED','LBT',0,'C',true);
		$pdf->Cell(25,5,'RCV NUMBER','LBT',0,'C',true);
		$pdf->Cell(30,5,'REFERENCE','LBT',0,'C',true);
		$pdf->Cell(60,5,'SUPPLIER','LBT',0,'C',true);
		$pdf->Cell(20,5,'ITEM CODE','LBT',0,'C',true);
		$pdf->Cell(80,5,'ITEM NAME','LBT',0,'C',true);
		$pdf->Cell(15,5,'CRNY','LBT',0,'C',true);
		$pdf->Cell(25,5,'UNIT PRICE','LBT',0,'C',true);
		$pdf->Cell(25,5,'QTY RCVD','LBT',0,'C',true);
		$pdf->Cell(30,5,'RECEIVED BY','LBTR',1,'C',true);
		//$pdf->Cell(335,10,'','T');
		
		$x = 1;
		$totalrowsperpage = 30;
		$y = 1;
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial','',7);
		$pdf->SetFillColor($rowitem[0],$rowitem[1],$rowitem[2]);
		if($count>0){
			while($obj=fetch($rs)){
				$ref = ($obj->reference_po_number==NULL)?$obj->other_reference:$obj->reference_po_number;
				if($x%2==0){
					$even = true;
					$pdf->SetFillColor($rowitem[0],$rowitem[1],$rowitem[2]);
				}
				else{
					$even = true;
					$pdf->SetFillColor($rowitem2[0],$rowitem2[1],$rowitem2[2]);
				}
			

				//NEW PAGE
				if($x>($y*$totalrowsperpage)){
					$y++;
					$pdf->AddPage();
					$pdf->SetFillColor($fillcolor[0],$fillcolor[1],$fillcolor[2]);
					$pdf->SetFont('Arial','B',15);
					$pdf->SetTextColor(0,0,0);

					$pdf->Cell(180, 5, $pdf->Image($imagelogo, $pdf->GetX(), $pdf->GetY(), 80), 0, 1, 'L', false );
					$pdf->Cell(200,8,'');
					$pdf->Cell(50,8,'PURCHASE ORDER SUMMARY',0,1);
					$pdf->Ln();

					//line item header
					$pdf->SetTextColor(228, 241, 254);
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(25,5,'DATE RECEIVED','LBT',0,'C',true);
					$pdf->Cell(25,5,'RCV NUMBER','LBT',0,'C',true);
					$pdf->Cell(30,5,'REFERENCE','LBT',0,'C',true);
					$pdf->Cell(60,5,'SUPPLIER','LBT',0,'C',true);
					$pdf->Cell(20,5,'ITEM CODE','LBT',0,'C',true);
					$pdf->Cell(80,5,'ITEM NAME','LBT',0,'C',true);
					$pdf->Cell(15,5,'CRNY','LBT',0,'C',true);
					$pdf->Cell(25,5,'UNIT PRICE','LBT',0,'C',true);
					$pdf->Cell(25,5,'QTY RCVD','LBT',0,'C',true);
					$pdf->Cell(30,5,'RECEIVED BY','LBTR',1,'C',true);
					$pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial','',7);
					$pdf->SetFillColor($rowitem[0],$rowitem[1],$rowitem[2]);
				
				}
				//NEW PAGE - END

				if((($totalrowsperpage*$y)!=$x)&&$x!=$count){
					$brd = 0;
					$brd1 = 0;

					$pdf->Cell(25,5,formatDate($obj->actual_delivery_date),$brd,0,'C',$even);
					$pdf->Cell(25,5,$obj->receiving_number,$brd,0,'C',$even);
					$pdf->Cell(30,5,$ref,$brd,0,'C',$even);
					$pdf->Cell(60,5,$obj->supplier_name,$brd,0,'L',$even);
					$pdf->Cell(20,5,$obj->item_code,$brd,0,'L',$even);
					$pdf->Cell(80,5,$obj->item_name,$brd,0,'L',$even);
					$pdf->Cell(15,5,$obj->currency_code,$brd,0,'C',$even);
					$pdf->Cell(25,5,convertWithDecimal($obj->unit_price,5),$brd,0,'R',$even);
					$pdf->Cell(25,5,convertWithDecimal($obj->quantity_received,0),$brd,0,'R',$even);
					$pdf->Cell(30,5,getUserName($obj->received_by),$brd1,1,'C',$even);

					$itemdesc = $obj->item_description;
					$flag = $itemdesc==''?1:0;
					while($flag<1){
						$substr = lineBreak($itemdesc, 45);
						$pdf->Cell(25,5,'',$brd,0,'C',$even);
						$pdf->Cell(25,5,'',$brd,0,'C',$even);
						$pdf->Cell(30,5,'',$brd,0,'C',$even);
						$pdf->Cell(60,5,'',$brd,0,'L',$even);
						$pdf->Cell(20,5,'',$brd,0,'L',$even);
						$pdf->Cell(80,5,$substr,$brd,0,'L',$even);
						$pdf->Cell(15,5,'',$brd,0,'C',$even);
						$pdf->Cell(25,5,'',$brd,0,'R',$even);
						$pdf->Cell(25,5,'',$brd,0,'R',$even);
						$pdf->Cell(30,5,'',$brd1,1,'C',$even);
						$totalrowsperpage--;

						$itemdesc = trim(str_replace($substr, '', $itemdesc));
						if($itemdesc==''){
							$flag++;
						}
					}
				}
				else{

					if($obj->item_description==''){
						$brd = 0;//'LB';
						$brd1 = 0;//'LBR';
					}
					else{
						$brd = 0;//'L';
						$brd1 = 0;//'LR';
					}
					$pdf->Cell(25,5,formatDate($obj->actual_delivery_date),$brd,0,'C',$even);
					$pdf->Cell(25,5,$obj->receiving_number,$brd,0,'C',$even);
					$pdf->Cell(30,5,$ref,$brd,0,'C',$even);
					$pdf->Cell(60,5,$obj->supplier_name,$brd,0,'L',$even);
					$pdf->Cell(20,5,$obj->item_code,$brd,0,'L',$even);
					$pdf->Cell(80,5,$obj->item_name,$brd,0,'L',$even);
					$pdf->Cell(15,5,$obj->currency_code,$brd,0,'C',$even);
					$pdf->Cell(25,5,convertWithDecimal($obj->unit_price,5),$brd,0,'R',$even);
					$pdf->Cell(25,5,convertWithDecimal($obj->quantity_received,0),$brd,0,'R',$even);
					$pdf->Cell(30,5,getUserName($obj->received_by),$brd,1,'C',$even);

					$itemdesc = $obj->item_description;
					$flag = $itemdesc==''?1:0;
					while($flag<1){
						$substr = lineBreak($itemdesc, 45);

						$itemdesc = trim(str_replace($substr, '', $itemdesc));
						if($itemdesc==''){
							$rowborder = 0;//'LB';
							$rowborder1 = 0;//'LBR';
						}
						else{
							$rowborder = 0;//'L';
							$rowborder1 = 0;//'LR';
						}
						$pdf->Cell(25,5,'',$rowborder,0,'C',$even);
						$pdf->Cell(25,5,'',$rowborder,0,'C',$even);
						$pdf->Cell(30,5,'',$rowborder,0,'C',$even);
						$pdf->Cell(60,5,'',$rowborder,0,'L',$even);
						$pdf->Cell(20,5,'',$rowborder,0,'L',$even);
						$pdf->Cell(80,5,$substr,$rowborder,0,'L',$even);
						$pdf->Cell(15,5,'',$rowborder,0,'C',$even);
						$pdf->Cell(25,5,'',$rowborder,0,'R',$even);
						$pdf->Cell(25,5,'',$rowborder,0,'R',$even);
						$pdf->Cell(30,5,'',$rowborder1,1,'C',$even);
						$totalrowsperpage--;

						if($itemdesc==''){
							$flag++;
						}
					}
				}



				$x++;


			}

			
		}
		else{
			$pdf->SetFont('Arial','',8);
			$pdf->SetTextColor(0,0,0);
			$pdf->Cell(335,10,'No Results Found','LBR',0,'C');
		}

		$pdf->Output();
	




}else{
	exit('INVALID URL');
}