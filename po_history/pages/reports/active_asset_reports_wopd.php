<?php
    require('../../../media/PHPExcel.php'); //change this with your OOP path
          
	$dateToday = date("F d, Y H:i:s");
	
    //Database Connection
    mysql_connect('localhost', 'root', 'newd3v');
    mysql_select_db('db_itamsv3');

    //Create new PHPExcel Page
    $objPHPExcel = new PHPExcel;
    $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
    
	//Font Size: Global
    $objPHPExcel->getDefaultStyle()->getFont()->setSize(9);

    //Create Sheet
    $objWorkSheet = $objPHPExcel->getActiveSheet();
	$objPHPExcel->getActiveSheet()->setShowGridlines(false);

    $title = "List of Current IT Assets"; //set title cont.
    $objWorkSheet->setTitle($title); //set title cont.

    //FORMAT
    //Border Style
    $styleArray = array(
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN //set borders
        )
      ),
      'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID, //set back color
        'color' => array('rgb'=>'FFFFFF'),
        )
    );
    $styleHeader_assetDetails = array(
       'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN //set borders
        )
      ),
	  'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID, //set back color
        'color' => array('rgb'=>'D6EAF8'),
        )
    ); 
	$styleHeader_userDetails = array(
       'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN //set borders
        )
      ),
	  'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID, //set back color
        'color' => array('rgb'=>'D1F2EB'),
        )
    );
	$styleHeader_purchasedDetails = array(
       'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN //set borders
        )
      ),
	  'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID, //set back color
        'color' => array('rgb'=>'FCF3CF'),
        )
    );
	$styleText_assetStatus_green = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '008000')
    ));	
	$styleText_assetStatus_orange = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'D35400')
    ));	
	$styleText_assetStatus_red = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'FF0000')
    ));
	$styleCell_empNo = array(
	  'fill' => array(
		'type' => PHPExcel_Style_Fill::FILL_SOLID, //set back color
		'color' => array('rgb'=>'D1F2EB'),
		)
	);	
	$styleCell_serialNo = array(
	  'fill' => array(
		'type' => PHPExcel_Style_Fill::FILL_SOLID, //set back color
		'color' => array('rgb'=>'D6EAF8'),
		)
	);	

	/* Set Header */
    //Set width: Static
    $objWorkSheet->getColumnDimension('A')->setWidth(20);
    $objWorkSheet->getColumnDimension('B')->setWidth(20);
    $objWorkSheet->getColumnDimension('C')->setWidth(17);
    $objWorkSheet->getColumnDimension('D')->setWidth(23);
    $objWorkSheet->getColumnDimension('E')->setWidth(30);
    $objWorkSheet->getColumnDimension('F')->setWidth(17);
    $objWorkSheet->getColumnDimension('G')->setWidth(21);
    $objWorkSheet->getColumnDimension('H')->setWidth(21);
    $objWorkSheet->getColumnDimension('I')->setWidth(30);
    $objWorkSheet->getColumnDimension('J')->setWidth(30);
    $objWorkSheet->getColumnDimension('K')->setWidth(13);
    $objWorkSheet->getColumnDimension('L')->setWidth(35);
    $objWorkSheet->getColumnDimension('M')->setWidth(15);
    $objWorkSheet->getColumnDimension('N')->setWidth(30);
    $objWorkSheet->getColumnDimension('O')->setWidth(30);
    $objWorkSheet->getColumnDimension('P')->setWidth(20);
    $objWorkSheet->getColumnDimension('Q')->setWidth(20);
    $objWorkSheet->getColumnDimension('R')->setWidth(150);
    // $objWorkSheet->getColumnDimension('S')->setWidth(20);
    // $objWorkSheet->getColumnDimension('T')->setWidth(22);
    // $objWorkSheet->getColumnDimension('U')->setWidth(12);
    // $objWorkSheet->getColumnDimension('V')->setWidth(17);
    // $objWorkSheet->getColumnDimension('W')->setWidth(50);
    // $objWorkSheet->getColumnDimension('X')->setWidth(15);
    // $objWorkSheet->getColumnDimension('Y')->setWidth(17);
    // $objWorkSheet->getColumnDimension('Z')->setWidth(18);
    // $objWorkSheet->getColumnDimension('AA')->setWidth(22);
    // $objWorkSheet->getColumnDimension('AB')->setWidth(22);
    // $objWorkSheet->getColumnDimension('AC')->setWidth(22);
    // $objWorkSheet->getColumnDimension('AD')->setWidth(15);
	
   //Horizontal
    $objWorkSheet->getStyle('A3:R3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objWorkSheet->getStyle('A4:R4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    //Vertical
    $objWorkSheet->getStyle('A3:R3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objWorkSheet->getStyle('A4:R4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
 
    //Border header
    $objWorkSheet->getStyle('A3:J3')->applyFromArray($styleHeader_assetDetails);
    $objWorkSheet->getStyle('K3:R3')->applyFromArray($styleHeader_userDetails);
    // $objWorkSheet->getStyle('S3:AD3')->applyFromArray($styleHeader_purchasedDetails);
    $objWorkSheet->getStyle('A4:R4')->applyFromArray($styleArray);

    //Set height: Static 
	$objPHPExcel->getActiveSheet()->setAutoFilter('A4:R4');
	
    //Set font size
    $objWorkSheet->getStyle('A1:R1')->getFont()->setSize(12);
    $objWorkSheet->getStyle('A2:R2')->getFont()->setSize(9);
	$objWorkSheet->getStyle('A1:R1')->getFont()->setBold(true);
	$objWorkSheet->getStyle('A3:R4')->getFont()->setBold(true);

    //Merge Cells
    $objWorkSheet->mergeCells('A1:R1');
    $objWorkSheet->mergeCells('A2:R2');
    $objWorkSheet->mergeCells('A3:J3');
    $objWorkSheet->mergeCells('K3:R3');
    // $objWorkSheet->mergeCells('S3:AD3');
	
    //DEFAULT Values
    $objWorkSheet->setCellValue('A1', 'List of Current IT Assets');
    $objWorkSheet->setCellValue('A2', 'as of: ' .$dateToday);
    $objWorkSheet->setCellValue('A3', 'ASSET DETAILS');
    $objWorkSheet->setCellValue('K3', 'USER DETAILS');
    // $objWorkSheet->setCellValue('S3', 'PURCHASED DETAILS');
    $objWorkSheet->setCellValue('A4', 'SERIAL NO.');
    $objWorkSheet->setCellValue('B4', 'ASSET STATUS');
    $objWorkSheet->setCellValue('C4', 'ASSET TYPE');
    $objWorkSheet->setCellValue('D4', 'ASSET CATEGORY');
    $objWorkSheet->setCellValue('E4', 'MODEL DETAILS');
    $objWorkSheet->setCellValue('F4', 'LIFE CYCLE');
    $objWorkSheet->setCellValue('G4', 'OLD ASSET TAG');
    $objWorkSheet->setCellValue('H4', 'NEW ASSET TAG');
    $objWorkSheet->setCellValue('I4', 'OLD HOST/DEVICE NAME');
    $objWorkSheet->setCellValue('J4', 'NEW HOST/DEVICE NAME');
    $objWorkSheet->setCellValue('K4', 'EMPNO.');
    $objWorkSheet->setCellValue('L4', 'END USER');
    $objWorkSheet->setCellValue('M4', 'DIVISION');
    $objWorkSheet->setCellValue('N4', 'DEPARTMENT');
    $objWorkSheet->setCellValue('O4', 'LOCATION');
    $objWorkSheet->setCellValue('P4', 'DATE DEPLOYED');
    $objWorkSheet->setCellValue('Q4', 'USER STATUS');
    $objWorkSheet->setCellValue('R4', 'REMARKS');
    // $objWorkSheet->setCellValue('S4', 'AQUISITION METHOD');
    // $objWorkSheet->setCellValue('T4', 'ACQUIRED FROM');
    // $objWorkSheet->setCellValue('U4', 'PO NO.');
    // $objWorkSheet->setCellValue('V4', 'INVOICE NO.');
    // $objWorkSheet->setCellValue('W4', 'COMPANY/SUPPLIER NAME');
    // $objWorkSheet->setCellValue('X4', 'CURRENY');
    // $objWorkSheet->setCellValue('Y4', 'UNIT PRICE');
    // $objWorkSheet->setCellValue('Z4', 'TOTAL PRICE');
    // $objWorkSheet->setCellValue('AA4', 'DATE PURCHASED');
    // $objWorkSheet->setCellValue('AB4', 'DATE DELIVERED');
    // $objWorkSheet->setCellValue('AC4', 'WARRANTY EXPIRED');
    // $objWorkSheet->setCellValue('AD4', 'EOL DATE');
   
   /* Set Value */
	 $ctrRow = 5;
	//Horizontal
	$objWorkSheet->getStyle('A:R')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	//Vertical
	$objWorkSheet->getStyle('A:R')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	//Border
	$objWorkSheet->getStyle('A'.$ctrRow.':R'.$ctrRow)->applyFromArray($styleArray);

	//Set font size
	$objWorkSheet->getStyle('A'.$ctrRow)->getFont()->setSize(10);
			
		$sql = "SELECT a.pkid,
				   a.date_created,
				   a.serial_No,
				   a.old_asset_tag,
				   a.new_asset_tag,
				   a.user_empno,
				   a.end_user,
				   d.Division,
				   d.Department,
				   b.location,
				   a.date_deployed,
				   a.remarks,
				   a.logdel,
				   c.asset_status,
				   c.ad_serial_No,
				   c.ad_asset_category,
				   c.ad_model_details,
				   c.ad_asset_type,
				   c.ad_life_cycle,
				   c.old_host_device_name,
				   c.new_host_device_name,
				   c.acquisition_method,
				   c.acquired_from,
				   c.company_name,
				   c.currency,
				   c.unit_price,
				   c.price,
				   c.po_no,
				   c.invoice_no,
				   c.date_purchased,
				   c.delivery_date,
				   c.warranty_expired,
				   c.eol_date,
				   e.serialNo FROM tbl_asset_user a
						INNER JOIN tbl_location_callout b ON b.pkid = a.location
						LEFT JOIN tbl_asset_details c ON c.ad_serial_No = a.serial_No
						INNER JOIN db_hris.vw_employeeinfo d ON d.EmpNo = a.user_empno
						INNER JOIN tbl_serial_no e ON e.pkid = c.ad_serial_No	
						WHERE a.logdel = '0' AND c.asset_status = '3'
						ORDER BY a.pkid ASC";
						
	$result = mysql_query($sql);
	while ($row = mysql_fetch_array($result)) {
							
	//Horizontal
	$objWorkSheet->getStyle('A:R')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	//Vertical
	$objWorkSheet->getStyle('A:R')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	//Border
	$objWorkSheet->getStyle('A'.$ctrRow.':R'.$ctrRow)->applyFromArray($styleArray);

	//Set font size
	$objWorkSheet->getStyle('A'.$ctrRow.':R'.$ctrRow)->getFont()->setSize(10);

	if ( $row['logdel'] == '0'){
		$logdel_lbl = 'Active';
		$objWorkSheet->getStyle('Q'.$ctrRow)->applyFromArray($styleText_assetStatus_green);
	}else if ( $row['logdel'] == '0'){
		$logdel_lbl = 'Inactive';
		$objWorkSheet->getStyle('Q'.$ctrRow)->applyFromArray($styleText_assetStatus_red);
	}
			
	$status_arr = array(
				'',
				'',
				'',
				'',
				'',
				'',
				'Available',
				'Reserved',
				'Defective',
				'Pending repair',
				'Pending install',
				'Pending disposal',
				'Pending transfer',
				'Pre-allocated',
				'Available',
				'Reserved',
				'Defective',
				'Pending install',
				'Pending disposal',
				'Pre-allocated',
				'Disposed',
				'Sold',
				'Donated',
				'Vendor credit',
				'Lost',
				'Stolen'
				);
				
			$instock 		= array('1','6','7','8','9','10','11','12','13');
			$intransit 		= array('2','14','15','16','17','18','19');
			$inuse 			= array('3');
			$retired 		= array('4','20','21','22','23');
			$missing 		= array('5','24','25');

			$asset_status 		= intval($row['asset_status']);
			$asset_status_lbl 	= '';					
			
			$textcolor ='';
			if(in_array($asset_status, $instock)){
				$asset_status_lbl .= 'In stock  '.$status_arr[$asset_status].'';
				$objWorkSheet->getStyle('B'.$ctrRow)->applyFromArray($styleText_assetStatus_orange);
			}
			if(in_array($asset_status, $intransit)){
				$asset_status_lbl .= '<font color="green"><b>In transit  '.$status_arr[$asset_status].'</b></font>';
				$objWorkSheet->getStyle('B'.$ctrRow)->applyFromArray($styleText_assetStatus_orange);
			}
			if(in_array($asset_status, $inuse)){
				$asset_status_lbl .= 'In use  '.$status_arr[$asset_status].'';
				$objWorkSheet->getStyle('B'.$ctrRow)->applyFromArray($styleText_assetStatus_green);
			}
			if(in_array($asset_status, $retired)){
				$asset_status_lbl .= 'Retired  '.$status_arr[$asset_status].'';
				$objWorkSheet->getStyle('B'.$ctrRow)->applyFromArray($styleText_assetStatus_red);
			}
			if(in_array($asset_status, $missing)){
				$asset_status_lbl .= 'Missing  '.$status_arr[$asset_status].'';
				$objWorkSheet->getStyle('B'.$ctrRow)->applyFromArray($styleText_assetStatus_red);
			}
					
			// Set value
            $objWorkSheet->setCellValue('A'.$ctrRow, $row['serialNo']);
            $objWorkSheet->setCellValue('B'.$ctrRow, $asset_status_lbl);
            $objWorkSheet->setCellValue('C'.$ctrRow, $row['ad_asset_type']);
            $objWorkSheet->setCellValue('D'.$ctrRow, $row['ad_asset_category']);
            $objWorkSheet->setCellValue('E'.$ctrRow, $row['ad_model_details']);
            $objWorkSheet->setCellValue('F'.$ctrRow, $row['ad_life_cycle']);
            $objWorkSheet->setCellValue('G'.$ctrRow, $row['old_asset_tag']);
            $objWorkSheet->setCellValue('H'.$ctrRow, $row['new_asset_tag']);
            $objWorkSheet->setCellValue('I'.$ctrRow, $row['old_host_device_name']);
            $objWorkSheet->setCellValue('J'.$ctrRow, $row['new_host_device_name']);
            $objWorkSheet->setCellValue('K'.$ctrRow, $row['user_empno']);
            $objWorkSheet->setCellValue('L'.$ctrRow, $row['end_user']);
            $objWorkSheet->setCellValue('M'.$ctrRow, $row['Division']);
            $objWorkSheet->setCellValue('N'.$ctrRow, $row['Department']);
            $objWorkSheet->setCellValue('O'.$ctrRow, $row['location']);
            $objWorkSheet->setCellValue('P'.$ctrRow, $row['date_deployed']);
            $objWorkSheet->setCellValue('Q'.$ctrRow, $logdel_lbl);
            $objWorkSheet->setCellValue('R'.$ctrRow, $row['remarks']);
            // $objWorkSheet->setCellValue('S'.$ctrRow, $row['acquisition_method']);
            // $objWorkSheet->setCellValue('T'.$ctrRow, $row['acquired_from']);
            // $objWorkSheet->setCellValue('U'.$ctrRow, $row['po_no']);
            // $objWorkSheet->setCellValue('V'.$ctrRow, $row['invoice_no']);
            // $objWorkSheet->setCellValue('W'.$ctrRow, $row['company_name']);
            // $objWorkSheet->setCellValue('X'.$ctrRow, $row['currency']);
            // $objWorkSheet->setCellValue('Y'.$ctrRow, $row['unit_price']);
            // $objWorkSheet->setCellValue('Z'.$ctrRow, $row['price']);
            // $objWorkSheet->setCellValue('AA'.$ctrRow, $row['date_purchased']);
            // $objWorkSheet->setCellValue('AB'.$ctrRow, $row['delivery_date']);
            // $objWorkSheet->setCellValue('AC'.$ctrRow, $row['warranty_expired']);
            // $objWorkSheet->setCellValue('AD'.$ctrRow, $row['eol_date']);
			
			$objWorkSheet->getStyle('A'.$ctrRow)->applyFromArray($styleCell_serialNo);
			$objWorkSheet->getStyle('K'.$ctrRow)->applyFromArray($styleCell_empNo);
			$ctrRow++;
			
        }
		
		if ($ctrRow == '5' && $objWorkSheet->getCell('A5')->getValue() == ''){
			$objWorkSheet->setCellValue('A5', 'No Record Found..');
			$objWorkSheet->getStyle('A5')->getFont()->setItalic(true);
			$objWorkSheet->mergeCells('A5:R5');
		}

			
	ob_end_clean();
		
    //SAVING
    $writer = new PHPExcel_Writer_Excel2007($objPHPExcel);
    unset($styleArray);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=List of Current IT Assets ('.date("Y/m/d").').xls'); // used for excel 2003 format
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); // used for excel 2003 format
    $objWriter->save('php://output');
?>