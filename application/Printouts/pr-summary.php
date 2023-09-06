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

if(isset($_GET['KJdjh4lJlekNfkj3Iur3H2'])){

	$department = escapeString($_GET['department']);
	$requestedby = escapeString($_GET['requester']);
	$supplier = escapeString($_GET['supplier']);
	$reqfrom = escapeString($_GET['reqfrom']);
	$reqto = escapeString($_GET['reqto']);
	$neededfrom = escapeString($_GET['neededfrom']);
	$neededto = escapeString($_GET['neededto']);
	$qtymin = escapeString($_GET['qtymin']);
	$qtymax = escapeString($_GET['qtymax']);
	$pricemin = escapeString($_GET['pricemin']);
	$pricemax = escapeString($_GET['pricemax']);
	$prnumber = escapeString($_GET['prnumber']);
	$prstatus = escapeString($_GET['prstatus']);

	


	$query=  "select purchase_requisition_header.requisition_number,
					 requisition_status,
					 item.item_code,
					 item.item_name,
					 item.long_description,
					 requisition_type,
					 first_name,
					 middle_name,
					 last_name,
					 requisition_date,
					 date_needed,
					 supplier_name,
					 purchase_requisition_details.unit_price,
					 purchase_requisition_details.quantity,
					 currency_code,
					 unit_of_measure_code,
					 section_department_id
			  from purchase_requisition_header, 
			  	   purchase_requisition_details,
			  	   user,
			  	   supplier,
			  	   currency,
			  	   unit_of_measure,
			  	   item
			  where purchase_requisition_header.requisition_number=purchase_requisition_details.requisition_number and
			        requisitioner=user.id and
			        purchase_requisition_details.supplier_id=.supplier.id and
			        purchase_requisition_details.item_id=item.id and
			        item.unit_of_measure_id=unit_of_measure.id and
			        purchase_requisition_header.currency=currency.id";
	
    if($prstatus!='ANY'){
		$query = $query." and purchase_requisition_header.requisition_status='$prstatus'";
	}
	if($prnumber!=''){
		$query = $query." and purchase_requisition_header.requisition_number='$prnumber'";
	}
	if($department!='any'){
		$query = $query." and section_department_id='$department'";


	}
	if($requestedby!='any'&&$requestedby!=''){
		$query = $query." and requisitioner='$requestedby'";
	}
	if($supplier!='any'){
		$query = $query." and purchase_requisition_details.supplier_id='$supplier'";
	}

	if($reqfrom!=''&&$reqto!=''){
		$query = $query." and date(requisition_date) between '$reqfrom' and '$reqto'";
	}
	else if($reqfrom==''&&$reqto!=''){
		$query = $query." and date(requisition_date)<='$reqto'";
	}
	else if($reqfrom!=''&&$reqto==''){
		$query = $query." and date(requisition_date) between '$reqfrom' and curdate()";
	}

	if($neededfrom!=''&&$neededto!=''){
		$query = $query." and date(date_needed) between '$neededfrom' and '$neededto'";
	}
	else if($neededfrom==''&&$neededto!=''){
		$query = $query." and date(date_needed) <= '$neededto'";
	}
	else if($neededfrom!=''&&$neededto==''){
		$query = $query." and date(date_needed) between '$neededfrom' and curdate()";
	}

	if($qtymin!=''&&$qtymax!=''){
		$query = $query." and purchase_requisition_details.quantity between ".$qtymin." and ".$qtymax;
	}
	else if($qtymin==''&&$qtymax!=''){
		$query = $query." and purchase_requisition_details.quantity<=".$qtymax;
	}
	else if($qtymin!=''&&$qtymax==''){
		$query = $query." and purchase_requisition_details.quantity>=".$qtymin;
	}

	if($pricemin!=''&&$pricemax!=''){
		$query = $query." and purchase_requisition_details.unit_price between ".$pricemin." and ".$pricemax;
	}
	else if($pricemin==''&&$pricemax!=''){
		$query = $query." and purchase_requisition_details.unit_price<=".$pricemax;
	}
	else if($pricemin!=''&&$pricemax==''){
		$query = $query." and purchase_requisition_details.unit_price>=".$pricemin;
	}
	
	$rs = query($query);
	$count = getNumRows($rs);

	
		$imagelogo = "../../resources/img/pricon-logo.png";
		$pdf = new PDF('L','mm','LEGAL');
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetMargins(2,0);

		$fillcolor = array(31, 58, 147);
		$rowitem = array(89, 171, 227);
		$rowitem2 = array(234, 246, 255);
		$pdf->SetFillColor($fillcolor[0],$fillcolor[1],$fillcolor[2]);
		$pdf->SetFont('Arial','B',15);
		$pdf->SetTextColor(0,0,0);

		$pdf->Cell(180, 5, $pdf->Image($imagelogo, $pdf->GetX(), $pdf->GetY(), 80), 0, 1, 'L', false );
		$pdf->Cell(130,8,'');
		$pdf->Cell(135,8,'PURCHASE REQUISITION SUMMARY',0);


		$pdf->SetTextColor(228, 241, 254);
		$pdf->SetFont('Arial','B',8);
		//$pdf->Cell(220,5,'');
		$pdf->Cell(30,5,'DATE PRINTED','LBTR',0,'C',true);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(50,5,date('M-d-Y'),'LBTR',1,'C');
		$pdf->Ln();
		$pdf->Ln();
		//line item header
		$pdf->SetTextColor(228, 241, 254);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(40,5,'PR NO.','LBT',0,'C',true);
		$pdf->Cell(15,5,'STATUS','LBT',0,'C',true);
		$pdf->Cell(15,5,'TYPE','LBT',0,'C',true);
		$pdf->Cell(30,5,'REQUISITIONER','LBT',0,'C',true);
		$pdf->Cell(20,5,'PR DATE','LBT',0,'C',true);
		$pdf->Cell(20,5,'NEEDED','LBT',0,'C',true);
		$pdf->Cell(55,5,'SUPPLIER','LBT',0,'C',true);
		$pdf->Cell(15,5,'CODE','LBT',0,'C',true);
		$pdf->Cell(55,5,'ITEM NAME','LBT',0,'C',true);
		$pdf->Cell(20,5,'UNIT PRICE','LBT',0,'C',true);
		$pdf->Cell(10,5,'CRNY','LBT',0,'C',true);
		$pdf->Cell(15,5,'QTY','LBT',0,'C',true);
		$pdf->Cell(10,5,'UOM','LBT',0,'C',true);
		$pdf->Cell(30,5,'AMOUNT','LBTR',1,'C',true);
		//$pdf->Cell(335,10,'','T')
		
		$x = 1;
		$totalrowsperpage = 28;
		$y = 1;
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial','',7);
		$pdf->SetFillColor($rowitem[0],$rowitem[1],$rowitem[2]);
		$prgrandtotal = 0;
		if($count>=1){
			while($obj=fetch($rs)){
				$amount = $obj->unit_price*$obj->quantity;
				$prgrandtotal = $prgrandtotal + round($amount,2);
				$amount = convertWithDecimal($amount,2);
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

					$totalrowsperpage = 28;
					$x = 1;
					$y =1;

					$pdf->AddPage();
					$pdf->SetFillColor($fillcolor[0],$fillcolor[1],$fillcolor[2]);
					$pdf->SetFont('Arial','B',15);
					$pdf->SetTextColor(0,0,0);
					$pdf->Ln();
					$pdf->Cell(180, 5, $pdf->Image($imagelogo, $pdf->GetX(), $pdf->GetY(), 80), 0, 1, 'L', false );
					$pdf->Cell(130,8,'');
					$pdf->Cell(135,8,'PURCHASE ORDER SUMMARY',0,0);
					$pdf->SetTextColor(228, 241, 254);
					$pdf->SetFont('Arial','B',8);
					//$pdf->Cell(220,5,'');
					$pdf->Cell(30,5,'DATE PRINTED','LBTR',0,'C',true);
					$pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(50,5,date('M-d-Y'),'LBTR',1,'C');
					$pdf->Ln();
					$pdf->Ln();

					//line item header
					$pdf->SetTextColor(228, 241, 254);
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(40,5,'PR NO.','LBT',0,'C',true);
					$pdf->Cell(15,5,'STATUS','LBT',0,'C',true);
					$pdf->Cell(15,5,'TYPE','LBT',0,'C',true);
					$pdf->Cell(30,5,'REQUISITIONER','LBT',0,'C',true);
					$pdf->Cell(20,5,'PR DATE','LBT',0,'C',true);
					$pdf->Cell(20,5,'NEEDED','LBT',0,'C',true);
					$pdf->Cell(55,5,'SUPPLIER','LBT',0,'C',true);
					$pdf->Cell(15,5,'CODE','LBT',0,'C',true);
					$pdf->Cell(55,5,'ITEM NAME','LBT',0,'C',true);
					$pdf->Cell(20,5,'UNIT PRICE','LBT',0,'C',true);
					$pdf->Cell(15,5,'CRNY','LBT',0,'C',true);
					$pdf->Cell(10,5,'QTY','LBT',0,'C',true);
					$pdf->Cell(10,5,'UOM','LBT',0,'C',true);
					$pdf->Cell(30,5,'AMOUNT','LBTR',1,'C',true);
					$pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial','',7);
					
					if($x%2==0){
					$even = true;
					$pdf->SetFillColor($rowitem[0],$rowitem[1],$rowitem[2]);
					}
					else{
						$even = true;
						$pdf->SetFillColor($rowitem2[0],$rowitem2[1],$rowitem2[2]);
					}
				
				}
				//NEW PAGE - END

				if((($totalrowsperpage*$y)!=$x)&&$x!=$count){
					$brd = '0';
					$brd1 = '0';
					$pdf->Cell(40,5,' '.$x.' '.$totalrowsperpage,$brd,0,'L',$even);
					$pdf->Cell(15,5,lineBreak($obj->requisition_status,10),$brd,0,'C',$even);
					$pdf->Cell(15,5,strtoupper($obj->requisition_type),$brd,0,'C',$even);
					$pdf->Cell(30,5,$obj->first_name.' '.$obj->middle_name.' '.$obj->last_name,$brd,0,'L',$even);
					$pdf->Cell(20,5,formatDate($obj->requisition_date),$brd,0,'C',$even);
					$pdf->Cell(20,5,formatDate($obj->date_needed),$brd,0,'C',$even);
					$pdf->Cell(55,5,$obj->supplier_name,$brd,0,'',$even);
					$pdf->Cell(15,5,$obj->item_code,$brd,0,'C',$even);
					$pdf->Cell(55,5,$obj->item_name,$brd,0,'',$even);
					$pdf->Cell(20,5,convertWithDecimal($obj->unit_price,5),$brd,0,'R',$even);
					$pdf->Cell(10,5,$obj->currency_code,$brd,0,'C',$even);
					$pdf->Cell(15,5,convertWithDecimal($obj->quantity,0),$brd,0,'R',$even);
					$pdf->Cell(10,5,$obj->unit_of_measure_code,$brd,0,'C',$even);
					$pdf->Cell(30,5,$amount,$brd1,1,'R',$even);

					$itemdesc = $obj->long_description;
					$o = lineBreak($obj->requisition_status,10);
					$stat = trim(str_replace($o, '', $obj->requisition_status));
					$statflag = $stat==''?1:0;
					$flag = $itemdesc==''?1:0;
					while($flag<1||$statflag<1){
						$substr2 = lineBreak($stat,10);
						$substr = lineBreak($itemdesc, 50);
						$pdf->Cell(40,5,'',$brd,0,'L',$even);
						$pdf->Cell(15,5,$substr2,$brd,0,'C',$even);
						$pdf->Cell(15,5,'',$brd,0,'C',$even);
						$pdf->Cell(30,5,'',$brd,0,'L',$even);
						$pdf->Cell(20,5,'',$brd,0,'C',$even);
						$pdf->Cell(20,5,'',$brd,0,'C',$even);
						$pdf->Cell(55,5,'',$brd,0,'',$even);
						$pdf->Cell(15,5,'',$brd,0,'C',$even);
						$pdf->Cell(55,5,$substr,$brd,0,'',$even);
						$pdf->Cell(20,5,'',$brd,0,'R',$even);
						$pdf->Cell(10,5,'',$brd,0,'C',$even);
						$pdf->Cell(15,5,'',$brd,0,'R',$even);
						$pdf->Cell(10,5,'',$brd,0,'C',$even);
						$pdf->Cell(30,5,'',$brd1,1,'R',$even);
						$totalrowsperpage--;

						$itemdesc = trim(str_replace($substr, '', $itemdesc));
						$stat = trim(str_replace($substr2, '', $stat));
						if($itemdesc==''){
							$flag++;
						}
						if($stat==''){
							$statflag++;
						}
					}
					$x++;
				}
				else{
					$e = lineBreak($obj->requisition_status,10);
					$m = trim(str_replace($e, '', $obj->requisition_status));
					if($obj->long_description==''&&$m==''){
						$brd = 0;//'LB';
						$brd1 = 0;//'LBR';
					}
					else{
						$brd = 0;//'L';
						$brd1 = 0;//'LR';
					}

					$pdf->Cell(40,5,$obj->requisition_number,$brd,0,'L',$even);
					$pdf->Cell(15,5,lineBreak($obj->requisition_status,10),$brd,0,'C',$even);
					$pdf->Cell(15,5,strtoupper($obj->requisition_type),$brd,0,'C',$even);
					$pdf->Cell(30,5,$obj->first_name.' '.$obj->middle_name.' '.$obj->last_name,$brd,0,'L',$even);
					$pdf->Cell(20,5,formatDate($obj->requisition_date),$brd,0,'C',$even);
					$pdf->Cell(20,5,formatDate($obj->date_needed),$brd,0,'C',$even);
					$pdf->Cell(55,5,$obj->supplier_name,$brd,0,'',$even);
					$pdf->Cell(15,5,$obj->item_code,$brd,0,'C',$even);
					$pdf->Cell(55,5,$obj->item_name,$brd,0,'',$even);
					$pdf->Cell(20,5,convertWithDecimal($obj->unit_price,5),$brd,0,'R',$even);
					$pdf->Cell(10,5,$obj->currency_code,$brd,0,'C',$even);
					$pdf->Cell(15,5,convertWithDecimal($obj->quantity,0),$brd,0,'R',$even);
					$pdf->Cell(10,5,$obj->unit_of_measure_code,$brd,0,'C',$even);
					$pdf->Cell(30,5,$amount,$brd1,1,'R',$even);

					$itemdesc = $obj->long_description;
					$o = lineBreak($obj->requisition_status,10);
					$stat = trim(str_replace($o, '', $obj->requisition_status));
					$statflag = $stat==''?1:0;
					$flag = $itemdesc==''?1:0;
					while($flag<1||$statflag<1){
						$substr2 = lineBreak($stat,10);
						$substr = lineBreak($itemdesc, 50);

						$itemdesc = trim(str_replace($substr, '', $itemdesc));
						$stat = trim(str_replace($substr2, '', $stat));
						if($itemdesc==''&&$stat==''){
							$rowborder = 0;//'LB';
							$rowborder1 = 0;//'LBR';
						}
						else{
							$rowborder = 0;//'L';
							$rowborder1 = 0;//'LR';
						}
						$pdf->Cell(40,5,'',$rowborder,0,'L',$even);
						$pdf->Cell(15,5,$substr2,$rowborder,0,'C',$even);
						$pdf->Cell(15,5,'',$rowborder,0,'C',$even);
						$pdf->Cell(30,5,'',$rowborder,0,'L',$even);
						$pdf->Cell(20,5,'',$rowborder,0,'C',$even);
						$pdf->Cell(20,5,'',$rowborder,0,'C',$even);
						$pdf->Cell(55,5,'',$rowborder,0,'',$even);
						$pdf->Cell(15,5,'',$rowborder,0,'C',$even);
						$pdf->Cell(55,5,$substr,$rowborder,0,'',$even);
						$pdf->Cell(20,5,'',$rowborder,0,'R',$even);
						$pdf->Cell(10,5,'',$rowborder,0,'C',$even);
						$pdf->Cell(15,5,'',$rowborder,0,'R',$even);
						$pdf->Cell(10,5,'',$rowborder,0,'C',$even);
						$pdf->Cell(30,5,'',$rowborder1,1,'R',$even);
						$totalrowsperpage--;

						if($itemdesc==''){
							$flag++;
						}
						if($stat==''){
							$statflag++;
						}
					}
					$x++;
				}



				


			}
			$pdf->Cell(295,5,'',0,0,'R',false);
			$pdf->SetTextColor(228, 241, 254);
			$pdf->SetFont('Arial','B',8);
			$pdf->SetFillColor($fillcolor[0],$fillcolor[1],$fillcolor[2]);
			$pdf->Cell(25,5,'TOTAL AMOUNT','LBRT',0,'C',true);
			$pdf->SetTextColor(0, 0, 0);

			$pdf->Cell(30,5,convertWithDecimal($prgrandtotal,2),0,1,'R',false);

		}
		else{
			$pdf->SetFont('Arial','',8);
			$pdf->SetTextColor(0,0,0);
			$pdf->Cell(350,10,'No Results Found','LBR',1,'C');
		}

		$pdf->Output();
	




}else{
	exit('INVALID URL');
}