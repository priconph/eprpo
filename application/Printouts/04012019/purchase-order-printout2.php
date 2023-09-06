<?php

include("../../config/connection.php");
include("../../config/checklogin.php");
include("../../config/functions.php");
require('../../resources/htmltopdf/fpdf.php');

//header("Content-type: application/pdf; charset=utf-8");
class PDF extends FPDF
{	
	function Footer()
	{
	    $this->SetY(-15);
	    $this->SetFont('Arial','B',5);
		$this->Cell(65,4,'',0,'C');
		$this->Cell(60,4,'',0,1,'C');
		$this->Cell(60,4,'',0,'C');
		$this->SetFont('Arial','',5);
		$this->Cell(70,4,'',0,1,'C');
	}
}

$ponum=(isset($_GET['jkHIejPoeiwMlkdA'])?$_GET['jkHIejPoeiwMlkdA']:'');
// $revsuffix=(isset($_GET['revsuffix'])?$_GET['revsuffix']:'');
// $revsuffix = $revsuffix!=''?'-'.$revsuffix:'';
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
					  expected_date,
					  next_revision_series,
					  currency.id as currId
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
		$dateneeded = formatDate5($obj->expected_date);
		$remarks = $obj->po_remarks;
		$currId = $obj->currId;
		$currency = $obj->currency_code;
		$attn = $obj->supplier_contact;
		$revsuffix = $obj->next_revision_series;
		$revsuffix = $revsuffix == 0 ? '' : '-REV.'.sprintf("%02d", $revsuffix);
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

	$pdf = new PDF('P','mm','LEGAL');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetMargins(5,0);

	$fillcolor = array(31, 58, 147);
	$rowitem = array(89, 171, 227);
	$pdf->SetFillColor($fillcolor[0],$fillcolor[1],$fillcolor[2]);
	$pdf->SetFont('Arial','B',15);


	$labelfont = 9;
	

	//$pdf->Cell(110,8,'PRICON MICROELECTRONICS INC.');

	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',16);
	
	//top margin adjustment
	$margintopsetup = getInfo("purchase_order_configuration","po_printing_alignment","where id='1'");
	for ($x=0; $x<$margintopsetup ; $x++) { 
		$pdf->Cell(150,1,'',0,1);
	}
	//

	$pdf->Cell(150,5,'');
	$ordernumber = substr($ponum, 3);
	$pdf->Cell(60,5,$ordernumber.$revsuffix,'',1,'C');
	$pdf->Cell(125,7,'',0,1);


	
	$labelfont = 11;
	$pdf->SetFont('Arial','',$labelfont);
	$pdf->Cell(170,5,'');
	$pdf->Cell(45,5,$orderdate,0,1);


	$pdf->Cell(45,3,'',0,1);
	$labelfont = 9;
	$pdf->Ln();
	$pdf->SetFont('Arial','B',$labelfont);
	$pdf->Cell(138,6,'');
	$pdf->Cell(65,6,'***FINAL DESTINATION***',0,1,"C");
	
	$supplier = lineBreak($supplier,70);
	$pdf->Cell(8);
	$pdf->Cell(70,4,'  '.$supplier);

	$pdf->Cell(55);

	$pdf->Cell(80,4,'PRICON MICROELECTRONICS INC.',0,1,"C");
	$pdf->SetFont('Arial','',$labelfont);


	//ADDRESS SECTION
	$lineheight = 4;
	$shiptoaddr = "No. 14 Ampere Street, Barangay Diezmo, Light Industry and Science Park 1, Cabuyao Laguna Philippines";
	$maxwidth = 50;


	$flag = 0;
	while($flag<1){
		$supplieradd = lineBreak($supplieraddr,$maxwidth);
		$shippingadd = lineBreak($shiptoaddr,35);
		$pdf->SetFont('Arial','',$labelfont);
		$pdf->Cell(8,$lineheight,'  ');
		$pdf->Cell(90,$lineheight,'  '.$supplieradd);
		$pdf->Cell(45,$lineheight);
		$pdf->SetFont('Arial','B',$labelfont);
		$pdf->Cell(70,$lineheight,'  '.$shippingadd,0,1);
		$supplieraddr = trim(str_replace($supplieradd, '', $supplieraddr));
		$shiptoaddr = trim(str_replace($shippingadd, '', $shiptoaddr));
		if($supplieraddr==''&&$shiptoaddr==''){
			$flag++;

		}
	}
	$pdf->SetFont('Arial','',$labelfont);
	$pdf->Cell(10,2,'',0,1);
	$pdf->Cell(8);
	$pdf->Cell(95,$lineheight,'           '.strtoupper($attn),0,1);
	$pdf->Cell(10,2,'',0,1);


	//GENERAL INFO
	$fontsize = 7;
	$pdf->Cell(20,3,'');
	$pdf->Cell(40,3,' PMI Arrival ');
	$pdf->Cell(10,5,'');
	$pdf->Cell(15,3,' '.$ref.appendPrRevSuffix($ref),0,1);

	/**/
	$pdf->Cell(20,3,'');
	$pdf->Cell(40,3,' '.$dateneeded,0,1);



	/*$pdf->Cell(10,5,'');
	$pdf->Cell(15,5,' '.$ref,0,1);*/

	$pdf->Cell(20,7,'');
	$pdf->Cell(40,7,' '.$via);

	$pdf->Cell(10,7,'');
	$pdf->Cell(70,7,' '.$terms);

	$pdf->Cell(45,7,'',0,1,'C');

	$pdf->Cell(150,6,'','',0,'C');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(45,6,$tradeterms,0,1,'C');
	//GENERAL INFO - END
	$pdf->Cell(10,4,'',0,1);

	//space/
	$lineheight=5;
	$pdf->Cell(35,$lineheight,'',0,1);
	$pdf->Cell(35,$lineheight,'',0,1);
	////

	$rowtotal = 32;
	$fontsize = 9;
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial','',$fontsize);
	$query = "select item.item_code,
						 item.item_name as item_name1,
						 purchase_order_details.item_name,
						 purchase_order_details.long_description,
						 item.long_description as long_description1,
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
    $maxwidth = 50;
    while($obj=fetch($rs)){ //FETCH LINE ITEMS OF PO
    		if($line%2==0){
				$odd = false;
			}
			else{
				 $odd = false;
			}
    		$code = $obj->item_code;
			$name = strUtfEncode($obj->item_name) == '' ? '*'.strUtfEncode($obj->item_name1) : strUtfEncode($obj->item_name);
			$description = strUtfEncode($obj->long_description) == '' ? '*'.strUtfEncode($obj->long_description1) : strUtfEncode($obj->long_description);
    		$uom = $obj->unit_of_measure_code;
    		$classfn = $obj->classification_code;
    		$price=$obj->unit_price;
    		$qty = $obj->quantity;
    		$amount = $price*$qty;
    		$grandtotal = $grandtotal+ round($amount,2);
    		$qty = convertWithDecimal($qty,0);
    		$amount=convertWithDecimal($amount,2);
    		$price=convertWithDecimal($price,5);
    		
    		$itemlineheight = 4;
    		$pdf->SetFont('Arial','',$fontsize);
    		$pdf->Cell(15,$itemlineheight,$line,0,0,'L',$odd);
			$pdf->Cell(25,$itemlineheight,$qty,0,0,'L',$odd);
			$pdf->Cell(15,$itemlineheight,$uom,0,0,'L',$odd);
			$pdf->SetFont('Arial','B',$fontsize);
			$flname = lineBreak($name, 35);
			$pdf->Cell(74,$itemlineheight,$flname,0,0,'L',$odd);
			$pdf->SetFont('Arial','',$fontsize);
			$pdf->Cell(10,$itemlineheight,$classfn,0,0,'C',$odd);
			$pdf->Cell(30,$itemlineheight,$price,0,0,'R',$odd);
			$pdf->Cell(13,$itemlineheight,$currency,0,0,'R',$odd);
			$pdf->Cell(20,$itemlineheight,$amount,0,1,'R',$odd);
			$rowtotal--;

			$name = trim(str_replace($flname, '', $name));
			$flag1 = 0;
			while($flag1<1&&$name!=''){
				$substr = lineBreak($name, 35);
				$pdf->Cell(15,$itemlineheight,'',0,0,'C',$odd);
				$pdf->Cell(25,$itemlineheight,'',0,0,'C',$odd);
				$pdf->Cell(15,$itemlineheight,'',0,0,'L',$odd);
				$pdf->SetFont('Arial','B',$fontsize);
				$pdf->Cell(74,$itemlineheight,$substr,0,0,'L',$odd);
				$pdf->Cell(30,$itemlineheight,'',0,0,'R',$odd);
				$pdf->Cell(13,$itemlineheight,'',0,0,'C',$odd);
				$pdf->Cell(20,$itemlineheight,'',0,1,'R',$odd);
				$rowtotal--;
				$name = trim(str_replace($substr, '', $name));
				if($name==''){
					$flag1++;
				}
			}

			$pdf->SetFont('Arial','',$fontsize);
			$flag = 0;
			while($flag<1){
				$substr = lineBreak($description, $maxwidth);
				$pdf->Cell(15,$itemlineheight,'',0,0,'C',$odd);
				$pdf->Cell(25,$itemlineheight,'',0,0,'C',$odd);
				$pdf->Cell(15,$itemlineheight,'',0,0,'L',$odd);
				$pdf->Cell(74,$itemlineheight,$substr,0,0,'L',$odd);
				$pdf->Cell(30,$itemlineheight,'',0,0,'R',$odd);
				$pdf->Cell(13,$itemlineheight,'',0,0,'C',$odd);
				$pdf->Cell(20,$itemlineheight,'',0,1,'R',$odd);
				$rowtotal--;

				$description = trim(str_replace($substr, '', $description));
				if($description==''){
					$flag++;
				}
			}


			$itemlineheight = 4;
			$pdf->Cell(35,$itemlineheight,'',0,1);
    		$line++;
    		$rowtotal--;
    }
    $grandtotal = convertWithDecimal($grandtotal,2);
    //FETCH LINE ITEMS OF PO - END

    //TOTAL SECTION
    $gtotalineheight = 5;
    $pdf->SetFont('Arial','B',$fontsize);
    $pdf->Cell(15,$gtotalineheight,'',0,0,'C');
	$pdf->Cell(25,$gtotalineheight,'',0,0,'C');
	$pdf->Cell(15,$gtotalineheight,'',0,0,'C');
	$pdf->Cell(74,$gtotalineheight,'',0,0);
	$pdf->Cell(30,$gtotalineheight,'Grand Total >>>',0,0,'R');
	$pdf->Cell(23,$gtotalineheight,$currency,'TB',0,'L');
	$pdf->Cell(20,$gtotalineheight,$grandtotal,'TB',1,'R');

	$pdf->Cell(15,$gtotalineheight,'',0,0,'C');
	$pdf->Cell(25,$gtotalineheight,'',0,0,'C');//date needed
	$pdf->Cell(15,$gtotalineheight,'',0,0,'C');
	$pdf->Cell(84,$gtotalineheight,'*** nothing follows ***',0,0,'C');
	$pdf->Cell(30,$gtotalineheight,'',0,0,'R');
	$pdf->Cell(13,$gtotalineheight,'',0,0,'R');
	$pdf->Cell(30,$gtotalineheight,'',0,1,'R');
	
	$notelabel = 'Note: ';
	if(trim($remarks,' ')==''){
		$notelabel = '';
	}
	$pdf->Cell(15,$gtotalineheight,'',0,0,'C');
	$pdf->Cell(25,$gtotalineheight,'',0,0,'C');//date needed
	$pdf->Cell(15,$gtotalineheight,'',0,0,'C');
	$pdf->Cell(84,$gtotalineheight,$notelabel,0,0);
	$pdf->Cell(30,$gtotalineheight,'',0,0,'R');
	$pdf->Cell(13,$gtotalineheight,'',0,0,'R');
	$pdf->Cell(30,$gtotalineheight,'',0,1,'R');

	$pdf->SetFont('Arial','',$fontsize);
	$flag = 0;
	while($flag<1){
		$substr = lineBreak($remarks, 45);
		$pdf->Cell(15,$itemlineheight,'',0,0,'C',$odd);
		$pdf->Cell(25,$itemlineheight,'',0,0,'C',$odd);
		$pdf->Cell(15,$itemlineheight,'',0,0,'C',$odd);
		$pdf->Cell(84,$itemlineheight,$substr,0,0,'L',$odd);
		$pdf->Cell(30,$itemlineheight,'',0,0,'R',$odd);
		$pdf->Cell(13,$itemlineheight,'',0,0,'C',$odd);
		$pdf->Cell(33,$itemlineheight,'',0,1,'R',$odd);
		$rowtotal--;

		$remarks = trim(str_replace($substr, '', $remarks));
		if($remarks==''){
			$flag++;
			
		}

	}
	$grandtotal = str_replace(',', '', $grandtotal);

	//TOTAL SECTION- END
	/*if(($currId=='1'&&$grandtotal>=228153)||($currId=='2'&&$grandtotal>=100000)||($currId=='3'&&$grandtotal>=2500)){//JPY
		$rowtotal-=2;

	}
	else{
		$rowtotal-=2;
	}*/

	$gtotalineheight = 4;
	for($i=0;$i<$rowtotal;$i++){// TO EXTEND ROW ITEMS TO MAX
		$pdf->Cell(10,$gtotalineheight,'',0,0,'C');
		$pdf->Cell(20,$gtotalineheight,'',0,0,'C');
		$pdf->Cell(15,$gtotalineheight,'',0,0,'C');
		$pdf->Cell(84,$gtotalineheight,'',0,0);
		$pdf->Cell(28,$gtotalineheight,'',0,0,'R');
		$pdf->Cell(13,$gtotalineheight,'',0,0,'R');
		$pdf->Cell(33,$gtotalineheight,'',0,1,'R');
	}
	$pdf->Cell(10,$gtotalineheight,'',0,0,'C');
	$pdf->Cell(20,$gtotalineheight,'',0,0,'C');
	$pdf->Cell(15,$gtotalineheight,'',0,0,'C');
	$pdf->Cell(84,$gtotalineheight,'',0,0);
	$pdf->Cell(28,$gtotalineheight,'',0,0,'R');
	$pdf->Cell(13,$gtotalineheight,'',0,0,'R');
	$pdf->Cell(33,$gtotalineheight,'',0,1,'R'); // TO EXTEND ROW ITEMS TO MAX - END
	

	//LINE ITEMS - END
	$space = "     ";

	$lineheight = 4;
	$gap = 5;
	$font = 9;

	
	$pdf->Cell(300,$lineheight,'',0,1,'C');
	
	

	$lineheight = 5;
	$pdf->SetFont('Arial','',9);

	
	$pdf->Cell(85,$lineheight,'',0,0,'L');
	$lineheight = 4;
	$pdf->SetFont('Arial','B',$font);
	if(($currId=='1'&&$grandtotal>=228153)||($currId=='2'&&$grandtotal>=100000)||($currId=='3'&&$grandtotal>=2500)){//JPY
		
	}
	else{
		$pdf->Cell(35,$lineheight,'',0,0,'C');
	}
	$pdf->Cell(35,$lineheight,getUserName($asstmng),'',0,'C');
	$pdf->Cell($gap,$lineheight,'',0,0,'C');
	
	$pdf->Cell(35,$lineheight,getUserName($genmng),'',0,'C');
	$pdf->Cell($gap,$lineheight,'',0,0,'C');
	if(($currId=='1'&&$grandtotal>=228153)||($currId=='2'&&$grandtotal>=100000)||($currId=='3'&&$grandtotal>=2500)){//JPY
		$pdf->Cell(30,$lineheight,getUserName($pres),'',1,'C');
	}
	else{
		$pdf->Ln();
	}
	$lineheight = 3;
	////////////////////////////////////////////////////////////////////////////////////////////
	$pdf->Cell(85,$lineheight,'',0,'C');
	$pdf->SetFont('Arial','',$font);
	if(($currId=='1'&&$grandtotal>=228153)||($currId=='2'&&$grandtotal>=100000)||($currId=='3'&&$grandtotal>=2500)){//JPY
		
	}
	else{
		$pdf->Cell(35,$lineheight,'',0,0,'C');
	}
	$pdf->Cell(35,$lineheight,'Logistics General Manager',0,0,'C');
	$pdf->Cell($gap,$lineheight,$space.'   ',0,0,'C');
	$pdf->Cell(35,$lineheight,'GAD General Manager',0,0,'C');
	$pdf->Cell($gap,$lineheight,$space.'   ',0,0,'C');
	
	if(($currId=='1'&&$grandtotal>=228153)||($currId=='2'&&$grandtotal>=100000)||($currId=='3'&&$grandtotal>=2500)){//JPY
		$pdf->Cell(30,$lineheight,'President',0,1,'C');
	}

	$pdf -> SetY(200);    // set the cursor at Y position 5
	$pdf -> SetX(60);
	$pdf->Cell(40,5,"Allocation Summary:",0,1);
	$rs = query("select * 
				 from purchase_requisition_allocation 
				 inner join section_department on section_department.id=purchase_requisition_allocation.department_id 
				 where requisition_number='".$ref."'");
	while($obj=fetch($rs)){
		$pdf -> SetX(60);  
		$pdf->Cell(40,5,$obj->section_department_name." - ".$obj->percentage."%",0,1);
	}
		
	
	$pdf->Output();




}else{
	exit('ERROR: INVALID PURCHASE ORDER NUMBER');
}

?>