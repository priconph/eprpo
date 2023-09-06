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
	    $this->SetFont('Arial','B',5);
		$this->Cell(65,4,'',0,'C');
		$this->Cell(60,4,'"THIS DOCUMENT IS NOT VALID FOR CLAIMING INPUT TAXES"','B',1,'C');
		$this->Cell(60,4,'',0,'C');
		$this->SetFont('Arial','',5);
		$this->Cell(70,4,'THIS PURCHASE ORDER SHALL BE VALID FOR FIVE(5) YEARS FROM DATE OF ATP','B',1,'C');
	}
}

$ponum=(isset($_GET['jkHIejPoeiwMlkdA'])?$_GET['jkHIejPoeiwMlkdA']:'');
$check = checkIfPoExist($ponum);

if($ponum!=''&&$check==true){
	$query =  "select payment_terms.description as paymentterms,
					  order_status,
					  reference_requisition_number,
					  delivery_date,
					  order_date,
					  ship_via,
					  supplier_name,
					  supplier_address,
					  first_approver,
					  second_approver,
					  third_approver,
					  posted_by,
					  po_remarks,
					  currency_code, 
					  supplier_contact,
					  mode_of_shipment.description,
					  expected_date
			   from purchase_order_header, supplier, payment_terms, currency, mode_of_shipment
			   where payment_terms.id=purchase_order_header.terms and 
			         supplier.id=supplier_id and 
			         currency=currency.id and
			         order_number='$ponum' and
			         mode_of_shipment=mode_of_shipment.id";
	$rs = query($query);
	$currency = '';
	while($obj=fetch($rs)){
		$supplier = strtoupper($obj->supplier_name);
		$terms = $obj->paymentterms;
		$tradeterms = $obj->description;
		$via = getInfo('ship_via','ship_by',"where id='".$obj->ship_via."'");
		$postat = $obj->order_status;
		$ref = $obj->reference_requisition_number;
		$delivery = formatDate($obj->delivery_date);
		$orderdate=formatDate($obj->order_date);
		$supplieraddr = $obj->supplier_address;
		$firstapprover = $obj->first_approver;
		$secondapprover = $obj->second_approver;
		$thirdapprover = $obj->third_approver;
		$preparedby = getUserName($obj->posted_by);
		$dateneeded = formatDate($obj->expected_date);
		$remarks = $obj->po_remarks;
		$currency = $obj->currency_code;
		$attn = $obj->supplier_contact;
	}

	$rs = query("select * from purchase_order_configuration");
	while($obj=fetch($rs)){
		$asstmng = $obj->assistant_manager;
		$genmng = $obj->general_manager;
		$pres = $obj->president;
	}
	if($postat!='UNSERVED'&&$postat!='SERVED'&&$postat!='PARTIAL DELIVERY'){
		exit('Error Occured: Unapproved Purchase Order cannot be loaded.');
	}

	$imagelogo = "../../resources/img/pdf-logo.jpg";

	$pdf = new PDF('P','mm','LETTER');
	$pdf->AliasNbPages();
	$pdf->AddPage();

	$fillcolor = array(31, 58, 147);
	$rowitem = array(89, 171, 227);
	$pdf->SetFillColor($fillcolor[0],$fillcolor[1],$fillcolor[2]);
	$pdf->SetFont('Arial','B',15);

	$labelfont = 8;
	

	//$pdf->Cell(110,8,'PRICON MICROELECTRONICS INC.');
	$pdf->Cell( 110, 5, $pdf->Image($imagelogo, $pdf->GetX(), $pdf->GetY(), 120), 0, 1, 'L');
	$pdf->Cell(140,8,'');
	$pdf->SetTextColor(228, 241, 254);
	$pdf->SetFont('Arial','B',$labelfont);
	$pdf->Cell(60,6,'PURCHASE ORDER','LBRT',1,'C',true);
	$pdf->SetTextColor(0,0,0);
	

	$pdf->Cell(140,3,'');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(25,9,'No.','LB',0,'R');
	$pdf->SetFont('Arial','',10);
	$ordernumber = substr($ponum, 3);
	$pdf->Cell(35,9,$ordernumber,'BR',1);

	$pdf->Cell(150,10,'');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(15,10,'Date',0,0,'R');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(45,10,$orderdate,0,1);
	$pdf->Cell(45,3,'',0,1);

	
	$pdf->SetTextColor(228, 241, 254);
	$pdf->SetFont('Arial','B',$labelfont);
	$pdf->Cell(95,6,'SUPPLIER','LBRT',0,'C',true);
	$pdf->Cell(10);
	$pdf->Cell(95,6,'SHIP TO','LBRT',0,'C',true);
	$pdf->Ln();
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial','B',8);

	$pdf->Cell(95,3,'','LR');
	$pdf->Cell(10);
	$pdf->Cell(95,3,'','LR',1);
	
	$supplier = lineBreak($supplier,70);
	$pdf->Cell(95,4,'  '.$supplier,'LR');

	$pdf->Cell(10);

	$pdf->Cell(95,4,'  '.'PRICON MICROELECTRONICS INC.','LR');
	$pdf->Cell(95,4,'','LR');
	$pdf->Cell(10);
	$pdf->Cell(95,4,'','LR',1);
	$pdf->SetFont('Arial','',8);


	//ADDRESS SECTION
	$lineheight = 3;
	$shiptoaddr = "No. 14 Ampere Street, Barangay Diezmo, Light Industry and Science Park 1, Cabuyao Laguna";
	$maxwidth = 70;


	$flag = 0;
	while($flag<1){
		$supplieradd = lineBreak($supplieraddr,$maxwidth);
		$shippingadd = lineBreak($shiptoaddr,$maxwidth);
		$pdf->Cell(95,$lineheight,'  '.$supplieradd,'LR');
		$pdf->Cell(10,$lineheight);
		$pdf->Cell(95,$lineheight,'  '.$shippingadd,'LR',1);
		$supplieraddr = trim(str_replace($supplieradd, '', $supplieraddr));
		$shiptoaddr = trim(str_replace($shippingadd, '', $shiptoaddr));
		if($supplieraddr==''&&$shiptoaddr==''){
			$flag++;

		}
	}
	$pdf->Cell(95,$lineheight,'','LR');
	$pdf->Cell(10,$lineheight);
	$pdf->Cell(95,$lineheight,'','LR',1);
	$pdf->Cell(95,$lineheight,'  Attn: '.$attn,'LR');
	$pdf->Cell(10);
	$pdf->Cell(95,$lineheight,'','LR',1);
	$pdf->Cell(95,$lineheight,'','LBR');
	$pdf->Cell(10);
	$pdf->Cell(95,$lineheight,'','LBR',1);
	$pdf->Ln();

	//GENERAL INFO
	$fontsize = 7;
	$pdf->SetTextColor(228, 241, 254);
	$pdf->SetFont('Arial','B',$labelfont);
	$pdf->Cell(25,6,'DELIVERY','LBRT',0,'C',true);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial','',$fontsize);
	$pdf->Cell(40,6,'  PMI ARRIVAL  '.$dateneeded,'BT');
	$pdf->SetTextColor(228, 241, 254);
	$pdf->SetFont('Arial','B',$labelfont);
	$pdf->Cell(20,6,'REF.','LBRT',0,'C',true);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial','',$fontsize);
	$pdf->Cell(115,6,' '.$ref,'BRT',1);
	$pdf->SetTextColor(228, 241, 254);
	$pdf->SetFont('Arial','B',$labelfont);
	$pdf->Cell(25,6,'SHIP VIA','LBR',0,'C',true);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial','',$fontsize);
	$pdf->Cell(40,6,' '.$via,'B');
	$pdf->SetTextColor(228, 241, 254);
	$pdf->SetFont('Arial','B',$labelfont);
	$pdf->Cell(20,6,'TERMS','LBR',0,'C',true);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial','',$fontsize);
	$pdf->Cell(70,6,' '.$terms,'BR');
	$pdf->SetTextColor(228, 241, 254);
	$pdf->SetFont('Arial','B',$labelfont);
	$pdf->Cell(45,6,'TRADE TERMS','LBR',1,'C',true);
	$pdf->SetTextColor(217, 30, 24);
	$pdf->SetFont('Arial','B',$fontsize);
	$pdf->Cell(155,6,'LOCAL PURCHASES > PMI IS A VAT ZERO RATED COMPANY PLEASE ISSUE A ZERO RATED INVOICE','LBR',0,'C');
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(45,6,$tradeterms,'LBR',1,'C');
	//GENERAL INFO - END

	//LINE ITEMS
	$pdf->SetTextColor(228, 241, 254); //LINE ITEMS - HEADER
	$pdf->SetFont('Arial','B',$labelfont);
	$pdf->Cell(10,6,'NO.','LBR',0,'C',true);
	$pdf->Cell(20,6,'QTY','LBR',0,'C',true);
	$pdf->Cell(15,6,'UNIT','LBR',0,'C',true);
	$pdf->Cell(85,6,'DESCRIPTION','LBR',0,'C',true);
	$pdf->Cell(25,6,'UNIT PRICE','LBR',0,'C',true);
	$pdf->Cell(45,6,'AMOUNT','LBR',1,'C',true); //LINE ITEMS - HEADER - END

	//space/
	$lineheight=4;
	$pdf->Cell(10,$lineheight,'','RL',0);
	$pdf->Cell(20,$lineheight,'','R',0);
	$pdf->Cell(15,$lineheight,'','R',0);
	$pdf->Cell(85,$lineheight,'','R',0);
	$pdf->Cell(25,$lineheight,'','R',0);
	$pdf->Cell(10,$lineheight,'');
	$pdf->Cell(35,$lineheight,'','R',1);
	////

	$rowtotal = 33;
	$fontsize = 8;
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial','',$fontsize);
	$query = "select item.item_code,
    					 item.item_name,
    					 long_description,
    					 purchase_order_details.unit_price,
    					 classification_code,
    					 purchase_order_details.quantity,
    					 unit_of_measure_code
    			  from purchase_order_details, item, unit_of_measure 
    			  where item.id=item_id and 
    			        order_number='$ponum' and
    			        unit_of_measure_id=unit_of_measure.id";
    $rs = query($query);

    $line=1;
    $grandtotal = 0;

    $pdf->SetFillColor($rowitem[0],$rowitem[1],$rowitem[2]);
    $maxwidth = 60;
    while($obj=fetch($rs)){ //FETCH LINE ITEMS OF PO
    		if($line%2==0){
				$odd = false;
			}
			else{
				 $odd = false;
			}
    		$code = $obj->item_code;
    		$name=$obj->item_name;
    		$description = $obj->long_description;
    		$uom = $obj->unit_of_measure_code;
    		$classfn = $obj->classification_code;
    		$price=$obj->unit_price;
    		$qty = $obj->quantity;
    		$amount = $price*$qty;
    		$grandtotal = $grandtotal+round($amount,2);
    		$qty = convertWithDecimal($qty,0);
    		$amount=convertWithDecimal($amount,2);
    		$price=convertWithDecimal($price,5);
    		
    		$itemlineheight = 3;
    		$pdf->Cell(10,$itemlineheight,$line,'LR',0,'C',$odd);
			$pdf->Cell(20,$itemlineheight,$qty,'R',0,'C',$odd);
			$pdf->Cell(15,$itemlineheight,$uom,'R',0,'C',$odd);
			$pdf->SetFont('Arial','B',$fontsize);
			$pdf->Cell(75,$itemlineheight,$name,'',0,'L',$odd);
			$pdf->Cell(10,$itemlineheight,$classfn,'R',0,'C',$odd);
			$pdf->SetFont('Arial','',$fontsize);
			$pdf->Cell(25,$itemlineheight,$price,'R',0,'R',$odd);
			$pdf->Cell(10,$itemlineheight,$currency,0,0,'C',$odd);
			$pdf->Cell(35,$itemlineheight,$amount,'R',1,'R',$odd);
			$rowtotal--;

			$flag = 0;
			while($flag<1){
				$substr = lineBreak($description, $maxwidth);
				$pdf->Cell(10,$itemlineheight,'','LR',0,'C',$odd);
				$pdf->Cell(20,$itemlineheight,'','R',0,'C',$odd);
				$pdf->Cell(15,$itemlineheight,'','R',0,'C',$odd);
				$pdf->Cell(85,$itemlineheight,$substr,'R',0,'L',$odd);
				$pdf->Cell(25,$itemlineheight,'','R',0,'R',$odd);
				$pdf->Cell(10,$itemlineheight,'',0,0,'C',$odd);
				$pdf->Cell(35,$itemlineheight,'','R',1,'R',$odd);
				$rowtotal--;

				$description = trim(str_replace($substr, '', $description));
				if($description==''){
					$flag++;
				}
			}


			$itemlineheight = 3;
			$pdf->Cell(10,$itemlineheight,'','LR');
			$pdf->Cell(20,$itemlineheight,'','R');
			$pdf->Cell(15,$itemlineheight,'','R');
			$pdf->Cell(85,$itemlineheight,'','R');
			$pdf->Cell(25,$itemlineheight,'','R');
			$pdf->Cell(10,$itemlineheight,'',0);
			$pdf->Cell(35,$itemlineheight,'','R',1);
    		$line++;
    		$rowtotal--;
    }
    $grandtotal = convertWithDecimal($grandtotal,5);
    //FETCH LINE ITEMS OF PO - END

    //TOTAL SECTION
    $gtotalineheight = 3;
    $pdf->SetFont('Arial','B',$fontsize);
    $pdf->Cell(10,$gtotalineheight,'','RL',0,'C');
	$pdf->Cell(20,$gtotalineheight,'','R',0,'C');
	$pdf->Cell(15,$gtotalineheight,'','R',0,'C');
	$pdf->Cell(85,$gtotalineheight,'','R',0);
	$pdf->Cell(25,$gtotalineheight,'Grand Total','R',0,'R');
	$pdf->Cell(10,$gtotalineheight,$currency,0,0,'R');
	$pdf->Cell(35,$gtotalineheight,$grandtotal,'R',1,'R');

	$pdf->Cell(10,$gtotalineheight,'','RL',0,'C');
	$pdf->Cell(20,$gtotalineheight,'','R',0,'C');//date needed
	$pdf->Cell(15,$gtotalineheight,'','R',0,'C');
	$pdf->Cell(85,$gtotalineheight,'*** nothing follows ***','R',0,'C');
	$pdf->Cell(25,$gtotalineheight,'','R',0,'R');
	$pdf->Cell(10,$gtotalineheight,'',0,0,'R');
	$pdf->Cell(35,$gtotalineheight,'','R',1,'R');
	
	$pdf->Cell(10,$gtotalineheight,'','RL',0,'C');
	$pdf->Cell(20,$gtotalineheight,'','R',0,'C');//date needed
	$pdf->Cell(15,$gtotalineheight,'','R',0,'C');
	$pdf->Cell(85,$gtotalineheight,'    Note:','R',0);
	$pdf->Cell(25,$gtotalineheight,'','R',0,'R');
	$pdf->Cell(10,$gtotalineheight,'',0,0,'R');
	$pdf->Cell(35,$gtotalineheight,'','R',1,'R');

	$pdf->SetFont('Arial','',$fontsize);
	$flag = 0;
	while($flag<1){
		$substr = lineBreak($remarks, 45);
		$pdf->Cell(10,$itemlineheight,'','LR',0,'C',$odd);
		$pdf->Cell(20,$itemlineheight,'','R',0,'C',$odd);
		$pdf->Cell(15,$itemlineheight,'','R',0,'C',$odd);
		$pdf->Cell(85,$itemlineheight,'    '.$substr,'R',0,'L',$odd);
		$pdf->Cell(25,$itemlineheight,'','R',0,'R',$odd);
		$pdf->Cell(10,$itemlineheight,'',0,0,'C',$odd);
		$pdf->Cell(35,$itemlineheight,'','R',1,'R',$odd);
		$rowtotal--;

		$remarks = trim(str_replace($substr, '', $remarks));
		if($remarks==''){
			$flag++;
		}
	}


	//TOTAL SECTION- END

	for($i=0;$i<$rowtotal;$i++){// TO EXTEND ROW ITEMS TO MAX
		$pdf->Cell(10,$gtotalineheight,'','RL',0,'C');
		$pdf->Cell(20,$gtotalineheight,'','R',0,'C');
		$pdf->Cell(15,$gtotalineheight,'','R',0,'C');
		$pdf->Cell(85,$gtotalineheight,'','R',0);
		$pdf->Cell(25,$gtotalineheight,'','R',0,'R');
		$pdf->Cell(10,$gtotalineheight,'',0,0,'R');
		$pdf->Cell(35,$gtotalineheight,'','R',1,'R');
	}
	$pdf->Cell(10,$gtotalineheight,'','RLB',0,'C');
	$pdf->Cell(20,$gtotalineheight,'','RB',0,'C');
	$pdf->Cell(15,$gtotalineheight,'','RB',0,'C');
	$pdf->Cell(85,$gtotalineheight,'','BR',0);
	$pdf->Cell(25,$gtotalineheight,'','RB',0,'R');
	$pdf->Cell(10,$gtotalineheight,'','B',0,'R');
	$pdf->Cell(35,$gtotalineheight,'','RB',1,'R'); // TO EXTEND ROW ITEMS TO MAX - END
	

	//LINE ITEMS - END
	//GENERAL INSTRUCTION TO SUPPLIER
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(200,4,'GENERAL INSTRUCTION TO SUPPLIER','LR',1,'C');
	$pdf->SetFont('Arial','',6);
	$lineheight = 2;
	$space = '  ';
	$pdf->Cell(100,$lineheight,$space.'1. lndicate PO. in all invoices, delivery receipts(AWB)/BUPL)and other doc','L',0,'L');
	$pdf->Cell(100,$lineheight,$space.'    face and track hereof andior attached and made a part ofthis PO','R',1,'L');

	$pdf->Cell(100,$lineheight,$space.'2. Goods subject to our inspection upon arrival and any merchandise rejected in account','L',0,'L');
	$pdf->Cell(100,$lineheight,$space.'5. Submit original and/or duplicate copy of all invoices together with Delivery Receipts in every','R',1,'L');

	$pdf->Cell(100,$lineheight,$space.'    of interior quality or not as per sample or specifications will be returned with all charges','L',0,'L');
	$pdf->Cell(100,$lineheight,$space.'    deliveries made.','R',1,'L');

	$pdf->Cell(100,$lineheight,$space.'    including any non-refundable duty or tax for supplier(s) account.','L',0,'L');
	$pdf->Cell(100,$lineheight,$space.'6. Supplier must confirm acceptance of issued purchase order 24 hours from receipt of PO.','R',1,'L');

	$pdf->Cell(100,$lineheight,$space.'3. All materials must be forwarded in acordance with the routine instructions or above as','L',0,'L');
	$pdf->Cell(100,$lineheight,$space.'7. Supplier must advise PMI of any problems that may lead to delivery delay ten(10) days','R',1,'L');
	
	$pdf->Cell(100,$lineheight,$space.'    furnished by us prior to shipment, otherwise any difference in freight rates as extra cost','L',0,'L');
	$pdf->Cell(100,$lineheight,$space.'	   before the deliver schedule. Failure to do so shall lead to charges on supplier account of all','R',1,'L');

	$pdf->Cell(100,$lineheight,$space.'    of transportation will be charged to your account.','L',0,'L');
	$pdf->Cell(100,$lineheight,$space.'    cost and opportunity lost on the part of PMI.','R',1,'L');

	$pdf->Cell(100,$lineheight,$space.'4. Acceptance of this order constitute agreements to all Terms and Conditions shown on the','L',0,'L');
	$pdf->Cell(100,$lineheight,$space.'8. Other terms and condition at the back','R',1,'L');

	$pdf->Cell(100,$lineheight,$space.'   ','LB',0,'L');
	$pdf->Cell(100,$lineheight,$space.'','RB',1,'L');
	//GENERAL INSTRUCTION - END
	$pdf->Ln();
	$lineheight = 3;
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(100,$lineheight,$space.'   SUPPLIER\'S ACCEPTANCE AND ',0,0,'L');
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(100,$lineheight,'PRICON MICROELECTRONICS, INC.',0,1,'R');
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(100,$lineheight,$space.'   ACKNOWLEDGEMENT OF PURCHASE ORDER',0,1,'L');
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(100,$lineheight,$space.'       (PLS. ACKNOWLEDGE AND SEND/FAXE-MAIL TO PMI UPON RECEIPT)',0,0,'L');
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(30,$lineheight,$space.'BY:',0,0,'L');


	//$pdf->Cell(40,$lineheight,$pdf->Image("../Signatures/julie-anne.png", $pdf->GetX(), $pdf->GetY(), 15),0,0,'C');
	//$pdf->Cell(35,$lineheight,$pdf->Image("../Signatures/julie-anne.png", $pdf->GetX(), $pdf->GetY(), 15),0,1,'C');
	$pdf->Cell(40,$lineheight,'',0,0,'C');
	$pdf->Cell(35,$lineheight,'',0,1,'C');

	$lineheight = 5;
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(120,$lineheight,$space.'               NAME/SIGN:_______________________________',0,0,'L');
	
	$pdf->Cell(40,$lineheight,getPoApprovalDate($ponum,$firstapprover),0,0,'C');
	$pdf->Cell(35,$lineheight,getPoApprovalDate($ponum,$secondapprover),0,1,'C');

	$pdf->Cell(120,$lineheight,$space.'                         DATE:_______________________________',0,0,'L');
	$lineheight = 4;
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(35,$lineheight,getUserName($asstmng),'B',0,'C');
	//$pdf->Cell(35,$lineheight,'Julie Anne G. Lacre','B',0,'C');
	$pdf->Cell(5,$lineheight,$space.'   ',0,0,'C');
	//$pdf->Cell(35,$lineheight,'Evangeline Molina','B',1,'C');
	$pdf->Cell(35,$lineheight,getUserName($genmng),'B',1,'C');

	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(75,$lineheight,'',0,'C');
	$pdf->Cell(40,$lineheight,'ACCOUNTING COPY',0,'C');
	$pdf->Cell(5,$lineheight,'',0,'C');
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(35,$lineheight,'LOGISTICS SECTION HEAD',0,0,'C');
	$pdf->Cell(5,$lineheight,$space.'   ',0,0,'C');
	$pdf->Cell(35,$lineheight,'ADMIN DIVISION ASST. VICE PRES.',0,1,'C');
	
	$pdf->Output();




}else{
	exit('ERROR: INVALID PURCHASE ORDER NUMBER');
}

?>