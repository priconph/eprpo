<?php

include("../../config/connection.php");
include("../../config/checklogin.php");
include("../../config/functions.php");
require('../../resources/htmltopdf/fpdf.php');

//header("Content-type: application/pdf; charset=utf-8");
class PDF extends FPDF {	

	public function Header() {
		$pmi_log = './img/PRICON-LOGO.png';
		$this->Image($pmi_log, 10, 12, 80, 15, '', '', '', false, 100, '', false, false, 0);

		// $pmi_log = './img/DRAFT.png';
		// $this->Image($pmi_log, 15, 50, 180, 150, '', '', '', false, 100, '', false, false, 0);
    }

	function Footer(){
	    $this->SetY(-15);
	    $this->SetFont('Arial','B',5);
		$this->Cell(65,4,'',0,'C');
		$this->Cell(60,4,'',0,0,'C');
		$this->Cell(60,4,'',0,'C');
		$this->SetFont('Arial','',5);
		$this->Cell(60,4,'',0,0,'C');
	}  

}

$datetoday = date("Y/m/d");
$timetoday = date("h:i:sa");
$dttoday = $datetoday.' '.$timetoday;

$ponum=(isset($_GET['jkHIejPoeiwMlkdA'])?$_GET['jkHIejPoeiwMlkdA']:'');
$check = checkIfPoExist($ponum);

$rs = query("select * from purchase_order_configuration");
while($obj=fetch($rs)){
	$asstmng = $obj->assistant_manager;
	$genmng = $obj->general_manager;
	$pres = $obj->president;
}

if($ponum!=''&&$check==true){
	$query =  "select poh.order_number,	
						poh.order_date,
						poh.order_status,
						poh.supplier_id,
						poh.delivery_date,
						poh.supplier_contact,
						poh.reference_requisition_number,
						poh.next_revision_series,
						poh.expected_date,
						poh.po_remarks,
						poh.bir_po_number,
										
						supp.supplier_name,
						supp.address,
						supp.tin,

						supp_con.phone,
						supp_con.mobile,

						pt.payment_terms_code,
						pt.description,
						pt.days,

						curr.currency_code,

						mos.description,

						prh.requisition_number,
						prh.facshipto,

						us.first_name,
						us.last_name,

						ass_to.username,
						ass_to.first_name,
						ass_to.last_name,
						
						secdept.section_department_name


			   from purchase_order_header poh
				inner join supplier supp on supp.id = poh.supplier_id				
				left join supplier_contact supp_con on supp_con.supplier_id = supp.id				
				inner join payment_terms pt on pt.id = poh.terms
				inner join currency curr on curr.id = poh.currency
				inner join mode_of_shipment mos on mos.id = poh.mode_of_shipment
				inner join purchase_requisition_header prh on prh.requisition_number = poh.reference_requisition_number
				inner join user us on us.id = prh.requisitioner
				inner join user ass_to on ass_to.id = poh.assigned_to				 
				inner join section_department secdept on secdept.id = us.section_department_id
				left join purchase_requisition_allocation alloc on alloc.requisition_number = prh.requisition_number


			   where poh.order_number='$ponum' 

					 ";
	$rs = query($query);
	$currency = '';
	while($obj=fetch($rs)){
		$poh_order_number 		= $obj->order_number;
		$poh_order_date 		= $obj->order_date;
		$poh_order_status 		= $obj->order_status;
		$poh_remarks 			= $obj->po_remarks;

		if ($obj->bir_po_number == '' || $obj->bir_po_number == NULL){
			$poh_bir_po_number		= '';
		}else{
			$poh_bir_po_number		= $obj->bir_po_number;
		}
	
		$revsuffix 				= $obj->next_revision_series;
		$revsuffix 				= $revsuffix == 0 ? '' : '-REV.'.sprintf("%02d", $revsuffix);
		$dateneeded = formatDate5($obj->expected_date);

			
		if ($poh_delivery_date == ''){
			$poh_delivery_date = '---';
		}else{
			$poh_delivery_date = $obj->poh_delivery_date;
		}
		
		$poh_supplier_contact	= $obj->supplier_contact;
		$poh_ref_pr				= $obj->reference_requisition_number;

		$supp_supplier_name 	= strtoupper($obj->supplier_name);
		$supp_address 			= $obj->address;

        if ($obj->tin == ''){
            $supp_tin = '000-000-000-000';
        }else{
			$supp_tin = '000-'.substr($obj->tin, 0, 3) . '-' . substr($obj->tin, 3, 3) . '-' . substr($obj->tin, 6, 3);
        }



		if ($obj->phone == '' || $obj->phone == null){
			$supp_con_phone 	= $obj->mobile;
		}else if ($obj->mobile == '' || $obj->mobile == null){
			$supp_con_phone 	= $obj->phone;
		}else{
			$supp_con_phone 	= $obj->phone.' / '.$obj->mobile;
		}

		$pt_payment_terms_code	= $obj->payment_terms_code;
		$pt_description 		= $obj->description;
		$pt_days 				= $obj->days;

		$curr_currency_code 	= $obj->currency_code;

		$mos_description 		= $obj->description;

		$prh_requisition_number = $obj->requisition_number;
		// $prh_facshipto 			= $obj->facshipto;

		if ($obj->facshipto == 'Factory 3'){
			$prh_facshipto = 'Factory 3';
			// $prh_address = 'Lot 1-2 Block 5 Elysan Road , Light Industry and Science Park 4 Bulihan, Malvar Batangas';
			$prh_address = 'Lot 1 & 2, Block 5, Nature Road, Light Industry & Science Park IV Brgy. Bulihan, Malvar, Batangas, 4233, Philippines'; // 04052025 by Nessa
		}else{
			$prh_facshipto = 'Factory 1/2';
			$prh_address = 'No. 14 Ampere Street, Barangay Diezmo, Light Industry and Science Park 1, Cabuyao Laguna Philippines';
		}

		$us_name 		= $obj->first_name.' '.$obj->last_name;
		$ass_to 		= $obj->username;
		$ass_to_name 	= $obj->first_name.' '.$obj->last_name;
		

		$secdept_section_department_name = $obj->section_department_name;

	}

	// if($poh_order_status!='UNSERVED'&&$poh_order_status!='SERVED'&&$poh_order_status!='PARTIAL DELIVERY'){
	// 	exit('Error Occured: Unapproved Purchase Order cannot be loaded.');
	// }

	$pdf = new PDF('P','mm','LEGAL');
	$pdf->AddPage();

    $pmi_log = './img/DRAFT.png';
    $pdf->Image($pmi_log, 15, 50, 180, 180, '', '', '', false, 100, '', false, false, 0);

	// Set font
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(95,4,'',0,0,'L',0);
	$pdf->Cell(95,4,'VAT Reg TIN: 000-283-090',0,0,'L',0);
	$pdf->Ln();

	$pdf->Cell(95,4,'',0,0,'L',0);
	$pdf->Cell(95,4,'No. 14 Ampere St. Light Industry & Science Park 1',0,0,'L',0);
	$pdf->Ln();

	$pdf->Cell(95,4,'',0,0,'L',0);
	$pdf->Cell(95,4,'Brgy. Diezmo, Cabuyao, Laguna Philippines 4025',0,0,'L',0);
	$pdf->Ln();

	$pdf->Cell(95,4,'',0,0,'L',0);
	$pdf->Cell(95,4,'Tel. No.: +632 8843-0870',0,0,'L',0);

	$pdf->setXY(15,30);
	$pdf->SetFont('Arial', 'B', 15);
	$pdf->Cell(190,4,'PURCHASE ORDER',0,0,'C',0);

	$pdf->setXY(10,$pdf->getY() + 8);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(35,5,'PAYEE NAME',0,0,'L',0);
	$pdf->setXY(45,38);
	$pdf->MultiCell(60,4,': '.$supp_supplier_name,0,'L'); 

	$pdf->setXY(115,38);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(40,5,'BRANCH',0,0,'L',0);
	$pdf->Cell(50,5,': MAIN BRANCH',0,0,'L',0); // Static muna

	$pdf->setXY(10,$pdf->getY() + 5);
	$pdf->Cell(35,5,'',0,0,'L',0);
	$pdf->Cell(60,5,'',0,0,'L',0);
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(0, 0, 255);
	$pdf->Cell(10,5,'',0,0,'L',0);
	$pdf->Cell(40,5,'PO NO.',0,0,'L',0);
	$pdf->Cell(50,5,': '.$poh_bir_po_number,0,0,'L',0);

	$pdf->setXY(10,$pdf->getY() + 5);
	$pdf->SetFont('Arial','',9);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(35,5,'TIN',0,0,'L',0);
	$pdf->Cell(60,5,': '.$supp_tin,0,0,'L',0);
	$pdf->Cell(10,5,'',0,0,'L',0);
	$pdf->Cell(40,5,'PO DATE.',0,0,'L',0);
	$pdf->Cell(50,5,': '.$poh_order_date,0,0,'L',0);
	
	$pdf->setXY(10,$pdf->getY() + 5);
	$pdf->Cell(35,5,'ADDRESS',0,0,'L',0);

	$pdf->setXY(45,53);
	$pdf->MultiCell(60,4, ': '.$supp_address,0,'L'); 
	$pdf->setXY(115,53);
	$pdf->Cell(40,5,'DEL. DATE',0,0,'L',0);
	$pdf->Cell(50,5,': '.$dateneeded,0,0,'L',0);

	$pdf->setXY(10,$pdf->getY() + 5);
	$pdf->Cell(35,5,'',0,0,'L',0);
	$pdf->Cell(60,5,'',0,0,'L',0);
	$pdf->Cell(10,5,'',0,0,'L',0);
	$pdf->Cell(40,5,'REF. PR NO.',0,0,'L',0);
	$pdf->Cell(50,5,': '.$poh_ref_pr,0,0,'L',0);

	$pdf->setXY(10,$pdf->getY() + 5);
	$pdf->Cell(35,5,'TELEPHONE NO.',0,0,'L',0);
	$pdf->Cell(60,5,': '.$supp_con_phone,0,0,'L',0);
	$pdf->SetTextColor(30, 123, 30);
	$pdf->Cell(10,5,'',0,0,'L',0);
	$pdf->Cell(40,5,'PMI TRANSACTION NO.',0,0,'L',0);
	$pdf->Cell(50,5,': PMI'.$poh_order_number.$revsuffix,0,0,'L',0);

	$pdf->setXY(10,$pdf->getY() + 5);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(35,5,'ATTENTION',0,0,'L',0);
	$pdf->Cell(60,5,': '.$poh_supplier_contact,0,0,'L',0);
	$pdf->Cell(10,5,'',0,0,'L',0);
	$pdf->Cell(40,5,'PAYMENT TERMS',0,0,'L',0);
	$pdf->Cell(50,5,': '.$pt_payment_terms_code,0,0,'L',0);

	$pdf->setXY(10,$pdf->getY() + 5);
	$pdf->Cell(35,5,'DELIVERY TO',0,0,'L',0);
	$pdf->Cell(60,5,': '.$prh_facshipto,0,0,'L',0);
	$pdf->Cell(10,5,'',0,0,'L',0);
	$pdf->Cell(40,5,'DEPARTMENT',0,0,'L',0);
	$pdf->Cell(50,5,': '.$secdept_section_department_name,0,0,'L',0);

	$pdf->setXY(10,$pdf->getY() + 5);
	$pdf->Cell(35,5,'DELIVERY ADDRESS',0,0,'L',0);
	$pdf->setXY(45,78);
	$pdf->MultiCell(60,4,': '.$prh_address,0,'L'); 
	$pdf->setXY(115,78);
	$pdf->Cell(40,5,'ALLOCATION',0,0,'L',0);

	$pdf->setXY(10,$pdf->getY() + 15);
	$pdf->Cell(35,5,'PMI BUYER`S NAME',0,0,'L',0);
	$pdf->Cell(60,5,': '.$ass_to_name,0,0,'L',0);
	$pdf->setXY(115,$pdf->getY());
	$pdf->Cell(40,5,'CURRENCY',0,0,'L',0);
	$pdf->Cell(50,5,': '.$curr_currency_code,0,0,'L',0);

	$pdf->Ln();
	$pdf->Cell(35,5,'SHIPMENT METHOD',0,0,'L',0);
	$pdf->Cell(60,5,': '.$mos_description,0,0,'L',0);
	$pdf->Cell(10,5,'',0,0,'L',0);
	$pdf->Cell(40,5,'CURRENT RATE',0,0,'L',0);
	$rs = query("select * from current_rate 			
				 where month = MONTH(CURDATE())
				 ");
	while($obj=fetch($rs)){
		
		$pdf->Cell(50,5,': '.$obj->rate,0,0,'L',0);
	}
	$pdf->Line(10,108,205,108);
	$pdf->Line(10,122,205,122);
	

	$pdf->SetXY(10,$pdf->GetY() + 10);
	$pdf->Cell(10,5,'ITEM',0,0,'C',0);
	$pdf->Cell(25,5,'ITEM CODE/',0,0,'C',0);
	$pdf->Cell(50,5,'ITEM DESCRIPTION',0,0,'C',0);
	$pdf->Cell(35,5,'ACCOUNT',0,0,'C',0);
	$pdf->Cell(10,5,'QTY',0,0,'C',0);
	$pdf->Cell(15,5,'UOM',0,0,'C',0);
	$pdf->Cell(20,5,'UNIT',0,0,'C',0);
	$pdf->Cell(25,5,'TOTAL',0,0,'C',0);

	$pdf->Ln();
	$pdf->Cell(10,5,'',0,0,'C',0);
	$pdf->Cell(25,5,'PART NUMBER',0,0,'C',0);
	$pdf->Cell(50,5,'',0,0,'C',0);
	$pdf->Cell(35,5,'NAME',0,0,'C',0);
	$pdf->Cell(10,5,'',0,0,'C',0);
	$pdf->Cell(15,5,'',0,0,'C',0);
	$pdf->Cell(20,5,'PRICE',0,0,'C',0);
	$pdf->Cell(25,5,'AMOUNT',0,0,'C',0);

	$pdf->Ln();
	$pdf->Ln();
	$rowtotal = 32;

	$query ="SELECT 
			b.item_code,
			b.item_name AS item_name1,
			a.item_name,
			a.long_description,
			b.long_description AS long_description1,
			a.unit_price,
			a.classification_code,
			a.quantity,
			c.unit_of_measure_code,
			d.acct_name
		FROM purchase_order_details a
		INNER JOIN item b ON b.id = a.item_id
		INNER JOIN unit_of_measure c ON c.id = b.unit_of_measure_id
		LEFT JOIN classification_code d ON d.acct_code = a.classification_code
		WHERE a.order_number='$ponum'
		";
 				
    $rs = query($query);
	$line		= 1;
	$grandtotal = 0;

	while($obj=fetch($rs)){ //FETCH LINE ITEMS OF PO

		$code 			= $obj->item_code;
		$name 			= strUtfEncode($obj->item_name) == '' ? '*'.strUtfEncode($obj->item_name1) : strUtfEncode($obj->item_name);
		$description 	= utf8_decode($obj->long_description) == '' ? '*'.utf8_decode($obj->long_description1) : utf8_decode($obj->long_description);
		$uom 			= $obj->unit_of_measure_code;
		$classfn 		= $obj->classification_code;
		$classname 		= $obj->acct_name;
		$uprice			= $obj->unit_price;
		$quantity 		= $obj->quantity;
		$amnt 			= $uprice * $quantity;

		$price			= convertWithDecimal($uprice,5); 
		$qty 			= convertWithDecimal($quantity,0);
		$amount			= convertWithDecimal($amnt,2);
		$gt 			= $gt + round($amnt,2); 
		$grandtotal		= convertWithDecimal($gt,2);




		$pdf->setXY(10,$pdf->getY());
		$pdf->Cell(10,5,$line,0,0,'C');
		$pdf->Cell(25,5,$code,0,0,'C');

		
		
		$pdf->setXY(130,$pdf->getY());
		$pdf->Cell(10,5,$qty,0,0,'C');
		$pdf->setXY(142,$pdf->getY());
		$pdf->Cell(10,5,$uom,0,0,'C');
		$pdf->setXY(160,$pdf->getY());
		$pdf->Cell(15,5,$price,0,0,'R');
		$pdf->setXY(180,$pdf->getY());
		$pdf->Cell(15,5,$amount,0,0,'R');
		$pdf->setXY(100,$pdf->getY());
		$pdf->MultiCell(30,4,$classfn.' - '. $classname.'',0,'L');

		
		// $pdf->SetXY(45, $pdf->GetY()-12); 
		$test_lenght = (strlen($name.$description)); 
		// echo $test_lenght;
		// exit();
		if($test_lenght >= 60){
			$pdf->SetXY(45, $pdf->GetY()-12); 
			$pdf->SetFont('Arial','',9);
			$pdf->MultiCell(55,4,$name.' ['. $description.']',0,'L');
		}else{
			$pdf->SetXY(45, $pdf->GetY()-8); 
			$pdf->SetFont('Arial','',9);
			$pdf->MultiCell(55,4,$name.' ['. $description.']',0,'L');
		}


		// $pdf->setXY(100,$pdf->getY());
		// $pdf->SetFont('Arial','',8);
		// $pdf->Cell(10,5,$classfn.' - '. $classname.')',0,'L');
		// $pdf->Cell(10,5,$classfn,0,'L');
		// $pdf->setXY(110,$pdf->getY());
		// $pdf->Cell(10,5,$classname,0,'L');



		
		$rowtotal--;
		$pdf->Cell(35,4,'',0,1);
		$line++;
		$rowtotal--;

		
	}
	
	$pdf->Line(10,$pdf->getY(),205,$pdf->getY());
	$pdf->setY($pdf->getY());
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(35,5,'REMARKS:',0,0,'L',0);
	$pdf->MultiCell(160,5,$poh_remarks,0,'L');

	if ($curr_currency_code == 'USD'){
		$curr = 'DOLLARS';
	}else if ($curr_currency_code == 'PHP'){
		$curr = 'PESOS';
	}else if ($curr_currency_code == 'JPY'){
		$curr = 'YEN';
	}else if ($curr_currency_code == 'SGD'){
		$curr = 'DOLLARS';
	}else{
		$curr = '';
	}

	$pdf->setXY(10, $pdf->getY());
	$pdf->SetLineWidth(0.5);
	$pdf->Line(10,$pdf->getY(),205,$pdf->getY());
	$pdf->setY($pdf->getY());
	$pdf->Cell(35,5,'Amount in Words:',0,0,'L',0);
	$pdf->Multicell(80,5,numberTowords($gt).' '.$curr.' ONLY',0,'L');

	$pdf->setXY(125,$pdf->getY());
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(40,5,'Gross Amount:',0,0,'L',0);
	$pdf->Cell(40,5,$curr_currency_code.'   '.$grandtotal,0,0,'R',0);

	$less_discount = '';
	$pdf->Ln();
	$pdf->Cell(115,5,'',0,0,'L',0);
	$pdf->Cell(40,5,'Less Discount:',0,0,'L',0);
	$pdf->Cell(40,5,$less_discount,0,0,'R',0);

	$tatbp 	= $gt - $less_discount;
	$tatbp	= convertWithDecimal($tatbp,2);

	$pdf->Ln();
	$pdf->Cell(115,5,'',0,0,'L',0);
	$pdf->Cell(40,5,'Total Amount to be Paid:',0,0,'L',0);
	$pdf->Cell(40,5,$curr_currency_code.'   '.$tatbp,0,0,'R',0);


	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->Cell(190,4,'GENERAL INSTRUCTION TO SUPPLIER',0,0,'C',0);

	$pdf->Ln();
	$pdf->SetFont('Arial', '', 5);
	$pdf->Cell(98,3,'1.	Indicate P.O. in all invoices, delivery receipts(AWB)/BL/PL) and other applicable documents',0,0,'L',0);
	$pdf->Cell(95,3,'attached and made a part of this P.O.',0,0,'L',0); // continuation from # 4

	$pdf->Ln();
	$pdf->Cell(98,3,'2.	Goods subject to our inspection upon arrival and any merchandise rejected in account of interior quality or not as per ',0,0,'L',0);
	$pdf->Cell(95,3,'5.	Submit original and/or duplicate copy of all invoices together with Delivery Receipts in every delivery made.',0,0,'L',0);
	

	$pdf->Ln();
	$pdf->Cell(98,3,'sample or specifications will be returned with all charges, including any non-refundable duty or tax for supplier(s) account.',0,0,'L',0); // continuation from # 2
	$pdf->Cell(98,3,'6.	Supplier must confirm acceptance of issued Purchase Order 24 hours from receipt of P.O.',0,0,'L',0);

	$pdf->Ln();
	$pdf->Cell(98,3,'3.	All materials must be forwarded in accordance with routine instructions or above or as furnished by us prior to shipment,',0,0,'L',0);
	$pdf->Cell(95,3,'7.	Supplier must advise PMI of any problems that may lead to delivery delay ten (10) days before the delivery',0,0,'L',0);

	$pdf->Ln();
	$pdf->Cell(98,3,'otherwise any difference in freight rates as extra cost of transportation will be charged to your account.',0,0,'L',0); // continuation from # 3
	$pdf->Cell(95,3,'schedule. Failure to do so shall lead to charges on supplier`s account of all cost and opportunity lost on the part of PMI.',0,0,'L',0); // continuation from # 7

	$pdf->Ln();
	$pdf->Cell(98,3,'4.	Acceptance of this order constitute agreements to all Terms and Conditions shown on the face and track hereof and/or',0,0,'L',0); 
	$pdf->Cell(95,3,'8.	Other terms and conditions in other  page. ',0,0,'L',0); // continuation from # 3
	$pdf->Ln();

	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetFont('Arial', 'BI', 12);
	$pdf->Cell(190,4,'This document is system generated. No manual signature required.',0,0,'C',0);

	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(190,4,'"THIS DOCUMENT IS NOT VALID FOR CLAIM OF INPUT TAX"',0,0,'C',0);
	
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Cell(140,4,'',0,0,'L',0);
	$pdf->Cell(50,4,'ORIGINAL COPY',0,0,'C',0);

	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Cell(35,4,'BIR Acknowledgement Certificate No: AC_116_092024_000421',0,0,'L',0);
	
	$pdf->Ln();
	$pdf->Cell(35,4,'Date Issued: 09/20/2024',0,0,'L',0);
	
	$pdf->Ln();
	$pdf->Cell(150,4,'Series Range: PO0000000001 to PO9999999999',0,0,'L',0);
    $pdf->Cell(40,4,$ass_to,0,0,'R',0);
	
	$pdf->Ln();
	$pdf->Cell(150,4,'Software Provider: In-house',0,0,'L',0);
	$pdf->Cell(40,4,$dttoday,0,0,'R',0);

	// ALLOCATION
	$pdf->setXY(155,79);
	$query = "select section_department_name, percentage from purchase_requisition_allocation 	
				inner join section_department on section_department.id=purchase_requisition_allocation.department_id		
	        	 where requisition_number = '".$poh_ref_pr."'";
    $rs = query($query);
    while($obj=fetch($rs)){
		$alloc 			= $obj->section_department_name;
		$per 			= $obj->percentage;

		$pdf->MultiCell(55,3,': '.$alloc.' - '.$per.'%',0,L);
        $pdf->ln(1);
        $pdf->SetXY(155, $pdf->GetY()); 

	} 

	$pdf->Output();

}else{
	exit('ERROR: INVALID PURCHASE ORDER NUMBER');
}


?>