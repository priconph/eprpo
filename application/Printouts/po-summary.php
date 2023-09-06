<?php

include("../../config/connection.php");
include("../../config/checklogin.php");
include("../../config/functions.php");
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


	$supplierId = escapeString($_GET['supplierId']);
	$stat = strtoupper(escapeString($_GET['status']));
	$from = escapeString($_GET['orderdatefrom']);
	$to = escapeString($_GET['orderdateto']);
	$dnfrom = escapeString($_GET['dateneededfrom']);
	$dnto = escapeString($_GET['dateneededto']);
	$staff = escapeString($_GET['staff']);
	$pobalance = escapeString(strtoupper($_GET['pobalance']));

	$query = "select purchase_order_details.item_id,
							 item.item_code,
							 item.item_name,
							 item.long_description,
							 purchase_order_details.order_number,
							 currency_code,
							 purchase_order_details.unit_price, 
							 supplier_name,
							 order_date,
							 order_status,
							 expected_date,
							 purchase_order_details.quantity 
					  from purchase_order_header, purchase_order_details, item, currency, supplier 
					  where purchase_order_header.order_number=purchase_order_details.order_number and 
					  		supplier.id=purchase_order_header.supplier_id and
					  		purchase_order_details.item_id=item.id and 
					  		purchase_order_header.currency=currency.id";

	if($staff!='any'){
		$query = $query." and purchase_order_header.posted_by='$staff'";
	}

	if($supplierId=='any'){
		if($stat=='ANY'){
		}
		else if($stat=='OPEN'){
			$query = $query." and (order_status='UNSERVED' or order_status='PARTIAL DELIVERY')";
		}
		else{
			$query = $query." and order_status='$stat'";
		}
	}
	else{
		$query = $query." and purchase_order_header.supplier_id='$supplierId'";
		if($stat=='ANY'){
		}
		else if($stat=='OPEN'){
			$query = $query." and (order_status='UNSERVED' or order_status='PARTIAL DELIVERY')";
		}
		else{
			$query = $query." and order_status='$stat'";
		}
	}

	if($from!=''&&$to!=''){
		$query = $query." and date(order_date) between '$from' and '$to'";
	}
	else if($from==''&&$to!=''){
		$query = $query." and date(order_date) <= '$to'";
	}
	else if($from!=''&&$to==''){
		$query = $query." and date(order_date) between '$from' and curdate()";
	}

	if($dnfrom!=''&&$dnto!=''){
		$query = $query." and date(expected_date) between '$dnfrom' and '$dnto'";
	}
	else if($dnfrom==''&&$dnto!=''){
		$query = $query." and date(expected_date) <= '$dnto'";
	}
	else if($dnfrom!=''&&$dnto==''){
		$query = $query." and date(expected_date) between '$dnfrom' and curdate()";
	}

	$query .= " order by order_number asc";


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
		$pdf->Cell(130,8,'');
		$pdf->Cell(130,8,'PURCHASE ORDER SUMMARY',0,0);
		$pdf->SetTextColor(228, 241, 254);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(30,5,'DATE PRINTED','LBTR',0,'C',true);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(50,5,date('M-d-Y'),'LBTR',1,'C');
		$pdf->Ln();
		$pdf->Ln();

		//line item header
		$pdf->SetTextColor(228, 241, 254);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(15,5,'PO NO.','LBT',0,'C',true);
		$pdf->Cell(15,5,'STATUS','LBT',0,'C',true);
		$pdf->Cell(20,5,'DATE','LBT',0,'C',true);
		$pdf->Cell(20,5,'DATENEEDED','LBT',0,'C',true);
		$pdf->Cell(75,5,'SUPPLIER','LBT',0,'C',true);
		$pdf->Cell(15,5,'ITEM CODE','LBT',0,'C',true);
		$pdf->Cell(65,5,'ITEM NAME','LBT',0,'C',true);
		$pdf->Cell(10,5,'CRNY','LBT',0,'C',true);
		$pdf->Cell(25,5,'UNIT PRICE','LBT',0,'C',true);
		$pdf->Cell(20,5,'ORDERED','LBT',0,'C',true);
		$pdf->Cell(15,5,'RCVD','LBT',0,'C',true);
		$pdf->Cell(15,5,'BALANCE','LBT',0,'C',true);
		$pdf->Cell(30,5,'AMOUNT','LBTR',1,'C',true);
		//$pdf->Cell(335,10,'','T');

		$x = 1;
		$totalrowsperpage = 30;
		$y = 1;
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial','',7);
		$pdf->SetFillColor($rowitem[0],$rowitem[1],$rowitem[2]);
		if($count>0){
			$pograndtotal = 0;
			while($obj=fetch($rs)){
				
				$amount = $obj->unit_price*$obj->quantity;
				
				$rcvd = getQtyReceived($obj->order_number,$obj->item_id,$obj->unit_price);
				$bal = $obj->quantity-$rcvd;

				if($x%2==0){
					$even = true;
					$pdf->SetFillColor($rowitem[0],$rowitem[1],$rowitem[2]);
				}
				else{
					$even = true;
					$pdf->SetFillColor($rowitem2[0],$rowitem2[1],$rowitem2[2]);
				}

				if(($pobalance==1&&$bal>0)||$pobalance=='ANY'||($pobalance==0&&$bal==0)){
					$pograndtotal = $pograndtotal + round($amount,2);

					//NEW PAGE

					if($x>($y*$totalrowsperpage)){
						$y++;
						$totalrowsperpage = 30;
						$x = 1;
						$y =1;
						


						$pdf->AddPage();
						$pdf->SetFillColor($fillcolor[0],$fillcolor[1],$fillcolor[2]);
						$pdf->SetFont('Arial','B',15);
						$pdf->SetTextColor(0,0,0);

						$pdf->Cell(180, 5, $pdf->Image($imagelogo, $pdf->GetX(), $pdf->GetY(), 80), 0, 1, 'L', false );
						$pdf->Cell(130,8,'');
						$pdf->Cell(130,8,'PURCHASE ORDER SUMMARY',0,0);
						$pdf->SetTextColor(228, 241, 254);
						$pdf->SetFont('Arial','B',8);
						$pdf->Cell(30,5,'DATE PRINTED','LBTR',0,'C',true);
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetFont('Arial','B',8);
						$pdf->Cell(50,5,date('M-d-Y'),'LBTR',1,'C');
						$pdf->Ln();
						$pdf->Ln();

						//line item header
						$pdf->SetTextColor(228, 241, 254);
						$pdf->SetFont('Arial','B',8);
						$pdf->Cell(15,5,'PO NO.','LBT',0,'C',true);
						$pdf->Cell(15,5,'STATUS','LBT',0,'C',true);
						$pdf->Cell(20,5,'DATE','LBT',0,'C',true);
						$pdf->Cell(20,5,'DATENEEDED','LBT',0,'C',true);
						$pdf->Cell(75,5,'SUPPLIER','LBT',0,'C',true);
						$pdf->Cell(15,5,'ITEM CODE','LBT',0,'C',true);
						$pdf->Cell(65,5,'ITEM NAME','LBT',0,'C',true);
						$pdf->Cell(10,5,'CRNY','LBT',0,'C',true);
						$pdf->Cell(25,5,'UNIT PRICE','LBT',0,'C',true);
						$pdf->Cell(20,5,'ORDERED','LBT',0,'C',true);
						
						$pdf->Cell(15,5,'RCVD','LBT',0,'C',true);
						$pdf->Cell(15,5,'BALANCE','LBT',0,'C',true);
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
						$pdf->Cell(15,5,$obj->order_number,0,0,'C',$even);
						$pdf->Cell(15,5,lineBreak($obj->order_status,10),0,0,'C',$even);
						$pdf->Cell(20,5,formatDate($obj->order_date),0,0,'C',$even);
						$pdf->Cell(20,5,formatDate($obj->expected_date),0,0,'C',$even);
						$pdf->Cell(75,5,$obj->supplier_name,0,0,'L',$even);
						$pdf->Cell(15,5,$obj->item_code,0,0,'L',$even);
						$pdf->Cell(65,5,$obj->item_name,0,0,'L',$even);
						$pdf->Cell(10,5,$obj->currency_code,0,0,'C',$even);
						$pdf->Cell(25,5,convertWithDecimal($obj->unit_price,5),0,0,'R',$even);
						$pdf->Cell(20,5,convertWithDecimal($obj->quantity,0),0,0,'R',$even);
						$pdf->Cell(15,5,convertWithDecimal($rcvd,0),0,0,'R',$even);
						$pdf->Cell(15,5,convertWithDecimal($bal,0),0,0,'R',$even);
						$pdf->Cell(30,5,convertWithDecimal($amount,2),0,1,'R',$even);

						
						$itemdesc = $obj->long_description;
						$o = lineBreak($obj->order_status,10);
						$stat = trim(str_replace($o, '', $obj->order_status));
						$statflag = $stat==''?1:0;
						$flag = $itemdesc==''?1:0;
						while($flag<1||$statflag<1){
							$substr2 = lineBreak($stat,10);
							$substr = lineBreak($itemdesc, 45);
							$pdf->Cell(15,5,'',0,0,'C',$even);
							$pdf->Cell(15,5,$substr2,0,0,'C',$even);
							$pdf->Cell(20,5,'',0,0,'C',$even);
							$pdf->Cell(20,5,'',0,0,'C',$even);
							$pdf->Cell(75,5,'',0,0,'L',$even);
							$pdf->Cell(15,5,'',0,0,'L',$even);
							$pdf->Cell(65,5,$substr,0,0,'L',$even);
							$pdf->Cell(10,5,'',0,0,'C',$even);
							$pdf->Cell(25,5,'',0,0,'R',$even);
							$pdf->Cell(20,5,'',0,0,'R',$even);
							$pdf->Cell(15,5,'',0,0,'R',$even);
							$pdf->Cell(15,5,'',0,0,'R',$even);
							$pdf->Cell(30,5,'',0,1,'R',$even);
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

						$e = lineBreak($obj->order_status,10);
						$m = trim(str_replace($e, '', $obj->order_status));

						if($obj->long_description==''&&$m==''){
							$brd = 0;//'LB';
							$brd1 = 0;//'LBR';
						}
						else{
							$brd = 0;//'L';
							$brd1 = 0;//'LR';
						}

						$pdf->Cell(15,5,$obj->order_number,$brd,0,'C',$even);
						$pdf->Cell(15,5,lineBreak($obj->order_status,10),$brd,0,'C',$even);
						$pdf->Cell(20,5,formatDate($obj->order_date),$brd,0,'C',$even);
						$pdf->Cell(20,5,formatDate($obj->expected_date),$brd,0,'C',$even);
						$pdf->Cell(75,5,$obj->supplier_name,$brd,0,'L',$even);
						$pdf->Cell(15,5,$obj->item_code,$brd,0,'L',$even);
						$pdf->Cell(65,5,$obj->item_name,$brd,0,'L',$even);
						$pdf->Cell(10,5,$obj->currency_code,$brd,0,'C',$even);
						$pdf->Cell(25,5,convertWithDecimal($obj->unit_price,5),$brd,0,'R',$even);
						$pdf->Cell(20,5,convertWithDecimal($obj->quantity,0),$brd,0,'R',$even);
						$pdf->Cell(15,5,convertWithDecimal($rcvd,0),$brd,0,'R',$even);
						$pdf->Cell(15,5,convertWithDecimal($bal,0),$brd,0,'R',$even);
						$pdf->Cell(30,5,convertWithDecimal($amount,2),$brd1,1,'R',$even);

						$x++;
						
						$itemdesc = $obj->long_description;
						$o = lineBreak($obj->order_status,10);
						$stat = trim(str_replace($o, '', $obj->order_status));
						$statflag = $stat==''?1:0;
						$flag = $itemdesc==''?1:0;
						while($flag<1||$statflag<1){
							$substr2 = lineBreak($stat,10);
							$substr = lineBreak($itemdesc, 45);

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
							$pdf->Cell(15,5,'',$rowborder,0,'C',$even);
							$pdf->Cell(15,5,$substr2,$rowborder,0,'C',$even);
							$pdf->Cell(20,5,'',$rowborder,0,'C',$even);
							$pdf->Cell(20,5,'',$rowborder,0,'C',$even);
							$pdf->Cell(75,5,'',$rowborder,0,'L',$even);
							$pdf->Cell(15,5,'',$rowborder,0,'L',$even);
							$pdf->Cell(65,5,$substr,$rowborder,0,'L',$even);
							$pdf->Cell(10,5,'',$rowborder,0,'C',$even);
							$pdf->Cell(25,5,'',$rowborder,0,'R',$even);
							$pdf->Cell(20,5,'',$rowborder,0,'R',$even);
							$pdf->Cell(15,5,'',$rowborder,0,'R',$even);
							$pdf->Cell(15,5,'',$rowborder,0,'R',$even);
							$pdf->Cell(30,5,'',$rowborder1,1,'R',$even);
							$totalrowsperpage--;

							if($itemdesc==''){
								$flag++;
							}
							if($stat==''){
								$statflag++;
							}
						}

						
					}



					
				}


			}

			$pdf->Cell(280,5,'',0,0,'R',false);
			$pdf->SetTextColor(228, 241, 254);
			$pdf->SetFillColor($fillcolor[0],$fillcolor[1],$fillcolor[2]);
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(30,5,'TOTAL AMOUNT','LBRT',0,'C',true);
			$pdf->SetTextColor(0, 0, 0);
			
			$pdf->Cell(30,5,convertWithDecimal($pograndtotal,2),0,1,'R',false);

			
		}
		else{
			$pdf->SetFont('Arial','',9);
			$pdf->SetTextColor(0,0,0);
			$pdf->Cell(340,10,'No Results Found','LBR',0,'C');
		}

		$pdf->Output();
	




}else{
	exit('INVALID URL');
}