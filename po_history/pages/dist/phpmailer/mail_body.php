<?php
	require_once('../handler/query.php');
	require_once('../handler/functions.php');
	////////////////////////////////////////////////////////////////////////// START LOAD PDR SUPERVISOR INFO
		$result 			= "";
		$array_fields 		= array('*');
		$table 	   			= 't_production_daily_report';
		$joins 	   			= '';
		$sql_where 			= 'WHERE `pdr_id`='.$return['pdr_id'];
		$sql_order 			= '';
		$sql_limit 			= '';
		$result 			= PDRDS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$pdr_report_date	= $row['pdr_report_date'];
			$pdr_supervisor_id	= $row['pdr_supervisor'];
			$pdr_date_base		= $row['pdr_date'];
			$pdr_month_base		= $row['pdr_month'];
			$pdr_year_base		= $row['pdr_year'];
		}

		if($pdr_report_date==''||$pdr_report_date=='0000-00-00'){
			$r_month 	= 'N/A';
			$r_day 		= 'N/A';
			$r_year 	= 'N/A';
		}
		else{
			$r_month 	= date('F',strtotime($pdr_report_date));
			$r_day 		= date('d',strtotime($pdr_report_date));
			$r_year 	= date('Y',strtotime($pdr_report_date));
		}
	////////////////////////////////////////////////////////////////////////// END LOAD PDR SUPERVISOR INFO
	////////////////////////////////////////////////////////////////////////// START PDR DATA
		$result 			= "";
		$array_fields 		= array('pdr_date','pdr_id');
		$table 	   			= 't_production_daily_report';
		$joins 	   			= '';
		$sql_where 			= 'WHERE `pdr_supervisor`='.$pdr_supervisor_id.' AND `pdr_date` <= "'.$pdr_date_base.'" AND `pdr_logdel`=0 AND `pdr_month`='.$pdr_month_base.' AND `pdr_year`='.$pdr_year_base;
		$sql_order 			= 'ORDER BY `pdr_date` DESC';
		$sql_limit 			= 'LIMIT 5';
		$result 			= PDRDS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ewqr 				= PDRDS::getInstance()->query_select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 				= 0;
		while($row = mysqli_fetch_array($result)){
			$pdr_id[$ctr]	= $row['pdr_id'];
			$ctr++;
		}
	////////////////////////////////////////////////////////////////////////// END PDR DATA	
?>
<html>
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
		<meta name='viewport' content='width=device-width'>
		<title></title>
		<style type="text/css">
			table td,th{
				border:solid 1px black;
   				border-collapse:collapse;
   				padding-right:2px;
			}
			td{
				font-size: 10pt;
				text-align: right;
			}
			th{
				font-size: 10pt;
			}
			body{
				font-family: Arial;
			}
			#attainment{
				font-size: 7pt;
			}
			.f8{
				font-size: 8pt;
			}
			.f8a{
				font-size: 8pt;
				background-color: #C5D9F1;
			}
			.test{
				background-color: #C5D9F1;				
			}
			.center{
				text-align: center;
			}
		</style>
	</head>
	<body>
		<span><?php 	
		if(isset($return['body_msg'])){
			echo $return['body_msg'].'
		<br>
		<br>
		<br>';
		}
		?></span>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<span style="font-weight:bold;">PRODUCTION DAILY REPORT</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<span>Month <span style="text-decoration:underline;color:#4135D1;font-size:90%;">&nbsp; <?php echo date("F", strtotime($pdr_report_date));?>&nbsp; </span></span>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<span>Day <span style="text-decoration:underline;color:#4135D1;font-size:90%;">&nbsp; <?php echo date("d", strtotime($pdr_report_date));?>&nbsp; </span></span>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<span>Year <span style="text-decoration:underline;color:#4135D1;font-size:90%;">&nbsp; <?php echo date("Y", strtotime($pdr_report_date));?>&nbsp; </span></span>
<?php
$rkla = 0;
while($rkla<5&&$pdr_id[$rkla]!=''){
	$pdr_table = '';		
	unset($total_output);
	unset($total_target);
	unset($pdrc_pl_id);
	unset($pl_category);
	unset($pl_product_line);
	unset($pl_line_capacity);
	unset($total_shipped_out);
	unset($holidays_count);
	unset($mp_boh);			
	unset($c_do_id);		
	unset($c_total_output);		
	unset($c_total_target);		
	unset($c_do_uph);		
	unset($c_progress_assess_r);		
	unset($c_line_capacity);		
	unset($c_daily_shipped_out);		
	unset($c_daily_output);		
	unset($c_daily_target);	
	$remaining_days = 0;		
	$overall_monthly_target = 0;
	$overall_total_shipped_out = 0;
	$overall_variance_monthly = 0;
	$overall_monthly_attainment = 0;
	$overall_total_target = 0;
	$overall_total_output = 0;
	$overall_variance_month_to_date = 0;
	$overall_c_daily_target = 0;
	$overall_c_daily_output = 0;
	$overall_variance_daily = 0;
	////////////////////////////////////////////////////////////////////////// START LOAD PDR INFO
		$result 			= "";
		$array_fields 		= array('pdr_date','pdr_date_sales_qty','pdr_month','pdr_year');
		$table 	   			= 't_production_daily_report';
		$joins 	   			= '';
		$sql_where 			= 'WHERE `pdr_id`='.$pdr_id[$rkla];
		$sql_order 			= '';
		$sql_limit 			= '';
		$result 			= PDRDS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$pdr_date 			= '';
		$pdr_date_sales_qty = '';
		$pdr_month			= '';
		$pdr_year 			= '';
		if($row = mysqli_fetch_array($result)){
			$pdr_date			= $row['pdr_date'];
			$pdr_date_sales_qty	= $row['pdr_date_sales_qty'];
			$pdr_month	 		= $row['pdr_month'];
			$pdr_year 			= $row['pdr_year'];
		}
	////////////////////////////////////////////////////////////////////////// END LOAD PDR INFO
	////////////////////////////////////////////////////////////////////////// START GET HOLIDAYS COUNT
		$result 			= "";
		$array_fields 		= array('*');
		$table 	   			= 't_monthly_holidays';
		$joins 	   			= '';
		$sql_where 			= 'WHERE `mh_month`='.$pdr_month.' AND `mh_year`='.$pdr_year.' AND `mh_date` > "'.$pdr_date.'"';
		$sql_order 			= '';
		$sql_limit 			= '';
		$result 			= PDRDS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$holidays_count 	= $result->num_rows;
	////////////////////////////////////////////////////////////////////////// END GET HOLIDAYS COUNT
	////////////////////////////////////////////////////////////////////////// START LOAD PDR CONTENT INFO
		$result 			= "";
		$array_fields 		= array('*');
		$table 	   			= 't_production_daily_report_content';
		$joins 	   			= 'LEFT JOIN `t_product_line` ON `t_production_daily_report_content`.`pdrc_pl_id` = `t_product_line`.`pl_id`';
		$sql_where 			= 'WHERE `pdrc_pdr_id`='.$pdr_id[$rkla].' AND `pdrc_logdel`=0';
		$sql_order 			= 'ORDER BY `pl_category`,`pl_product_line` ASC';
		$sql_limit 			= '';
		$result 			= PDRDS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 				= 0;
		while($row = mysqli_fetch_array($result)){
			$pdrc_pl_id[$ctr]		= $row['pdrc_pl_id'];
			$pl_category[$ctr]		= $row['pl_category'];
			$pl_product_line[$ctr]	= $row['pl_product_line'];
			$pl_line_capacity[$ctr]	= $row['pl_unit_per_hour']*$row['pl_production_hours'];
			$ctr++;
		}
	////////////////////////////////////////////////////////////////////////// END LOAD PDR CONTENT INFO
	////////////////////////////////////////////////////////////////////////// START LOAD DAILY OUTPUT INFO AND MONTHLY TARGET
		$a = 0;
		$b = 0;
		$date_m = date("m",strtotime($pdr_date));
		$date_y = date("Y",strtotime($pdr_date));	
		while($pdrc_pl_id[$a]!=''){
			////////////////////////////////////////////////////////////////////////// START LOAD MONTHLY TARGET
				$result 		= "";
				$array_fields 	= array('*');
				$table 	   		= 't_monthly_pdr';
				$joins 	   		= '';
				$sql_where 		= 'WHERE `mp_pl_id`='.$pdrc_pl_id[$a].' AND mp_month='.$pdr_month.' AND mp_year='.$pdr_year;
				$sql_order 		= '';
				$sql_limit 		= '';
				$result 		= PDRDS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
				if($row = mysqli_fetch_array($result)){
					$mp_monthly_target[$a]	= $row['mp_monthly_target'];
					$mp_boh[$a]				= $row['mp_boh'];
					if($row['mp_start_date']!=''||$row['mp_end_date']!=''){
						$mp_start_date[$a]		= $row['mp_start_date'];
						$mp_end_date[$a]		= $row['mp_end_date'];
						$mp_id[$a] 				= $row['mp_id'];
					}
					else{
						$mp_id[$a] 				= $row['mp_id'];
						$starts = 01;
						$mp_start_date[$a] 		= date("Y-m-d",strtotime($row['mp_year'].'-'.$row['mp_month'].'-'.$starts));
						$mp_end_date[$a]		= date("Y-m-t",strtotime($row['mp_year'].'-'.$row['mp_month'].'-'.$starts));
					}
				}
				else{
					$mp_monthly_target[$a] = 0;
			}
			////////////////////////////////////////////////////////////////////////// END LOAD MONTHLY TARGET
			////////////////////////////////////////////////////////////////////////// START LOAD DAILY OUTPUT FOR OVERALL VALUES
				$result 			= "";
				$array_fields 		= array('*');
				$table 	   			= 't_daily_output';
				$joins 	   			= '';
				$sql_where 			= 'WHERE `do_logdel`=0 AND `do_mp_id`='.$mp_id[$a].' AND do_production_date BETWEEN "'.$mp_start_date[$a].'" AND "'.$pdr_date.'"';
				$sql_order 			= '';
				$sql_limit 			= '';
				$result 			= PDRDS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
				$total_output[$a] 	= $mp_boh[$a];	
				while($row = mysqli_fetch_array($result)){				
					$dt_count[$a] 					= $row['do_dt_count'];	
					$total_target[$a] 				= $total_target[$a]+(int)$row['do_daily_target'];
					$total_output[$a] 				= $total_output[$a]+(int)$row['do_daily_output'];
					if($row['do_production_date']<=$pdr_date_sales_qty){
						$total_shipped_out[$a]		= $total_shipped_out[$a]+(int)$row['do_daily_shipped_out'];
					}
					$b++;
				}
			////////////////////////////////////////////////////////////////////////// END LOAD DAILY OUTPUT FOR OVERALL VALUES
			////////////////////////////////////////////////////////////////////////// START LOAD DAILY OUTPUT FOR CERTAIN DATE
				$result 			= "";
				$array_fields 		= array('*');
				$table 	   			= 't_daily_output';
				$joins 	   			= '';
				$sql_where 			= 'WHERE `do_logdel`=0 AND `do_mp_id`='.$mp_id[$a].' AND `do_production_date`="'.$pdr_date.'"';
				$sql_order 			= '';
				$sql_limit 			= '';
				$result 			= PDRDS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
				if($row = mysqli_fetch_array($result)){
					$c_daily_target[$a]			= $row['do_daily_target'];	
					$c_daily_output[$a]			= $row['do_daily_output'];		
					$c_daily_shipped_out[$a]	= $row['do_daily_shipped_out'];
					$c_line_capacity[$a]		= $row['do_line_capacity'];		
					$c_progress_assess_r[$a]	= $row['do_progressive_assessment_remarks'];	
					$c_do_id[$a]				= $row['do_id'];		
					$c_do_uph[$a]				= $row['do_uph'];		
					$c_total_target[$a] 		= $total_target[$a]+(int)$row['do_daily_target'];
					$c_total_output[$a] 		= $total_output[$a]+(int)$row['do_daily_output'];
				}
				else{
					$p[$a] = '669';
				}
			////////////////////////////////////////////////////////////////////////// END LOAD DAILY OUTPUT FOR CERTAIN DATE

			$a++;
		}
		$no_saved_output_today = '';
	////////////////////////////////////////////////////////////////////////// END LOAD DAILY OUTPUT INFO AND MONTHLY TARGET
	////////////////////////////////////////////////////////////////////////// START PDR TABLE
	$pdr_table 	.= '<table style="border-collapse:collapse;">';
	$pdr_table 	.= '	<thead>';
	$pdr_table 	.= '		<tr bgcolor="#FEFf86">';
	$pdr_table 	.= '			<th colspan="2" rowspan="2">PRODUCT LINES</th>';
	$pdr_table 	.= '			<th colspan="4">SALES QTY MONITORING AS OF<br/>('.date("M d, Y",strtotime($pdr_date_sales_qty)).')</th>';
	$pdr_table 	.= '			<th colspan="3">PPC PLAN<br/>(Month-To-Date)</th>';
	$pdr_table 	.= '			<th colspan="3">DAY RESULT<br/>('.date("M d, Y",strtotime($pdr_date)).')</th>';
	$pdr_table 	.= '			<th colspan="3">DOWNTIME DETAILS/ HIGHLIGHTS</th>';
	$pdr_table 	.= '			<th colspan="4" bgcolor="#C5D9F1" class="test">PROGRESSIVE ASSESSMENT</th>';
	$pdr_table 	.= '		</tr>';
	$pdr_table 	.= '		<tr bgcolor="#FEFf86">';
	$pdr_table 	.= '			<th class="f8">TARGET</th>';
	$pdr_table 	.= '			<th class="f8">ACTUAL</th>';
	$pdr_table 	.= '			<th class="f8">VARIANCE</th>';
	$pdr_table 	.= '			<th id="attainment">% ATTAINMENT</th>';
	$pdr_table 	.= '			<th class="f8">TARGET</th>';
	$pdr_table 	.= '			<th class="f8">ACTUAL</th>';
	$pdr_table 	.= '			<th class="f8">VARIANCE</th>';
	$pdr_table 	.= '			<th class="f8">TARGET</th>';
	$pdr_table 	.= '			<th class="f8">ACTUAL</th>';
	$pdr_table 	.= '			<th class="f8">VARIANCE</th>';
	$pdr_table 	.= '			<th class="f8">CONDITION</th>';
	$pdr_table 	.= '			<th class="f8">MINUTES</th>';
	$pdr_table 	.= '			<th class="f8">EQUIV.<br/>OUTPUT</th>';
	$pdr_table 	.= '			<th class="f8" bgcolor="#C5D9F1">(Month-To-Date)<br/>Actual vs. Sales</th>';
	$pdr_table 	.= '			<th class="f8" bgcolor="#C5D9F1"># FO Days<br/>Needed</th>';
	$pdr_table 	.= '			<th class="f8a" bgcolor="#C5D9F1"># of Days<br/>Available</th>';
	$pdr_table 	.= '			<th class="f8" bgcolor="#C5D9F1">Remarks</th>';
	$pdr_table 	.= '		</tr>';
	$pdr_table 	.= '	</thead>';
	$pdr_table 	.= '	<tbody>';
	$c = 0;
	while($pdrc_pl_id[$c]!=''){
		////////////////////////////////////////////////////////////////////////// START COMPUTATIONS AT TBODY
				if($p[$c]==669&&$mp_monthly_target[$c]!=0){
					$no_saved_output_today .= ', '.$pl_product_line[$c];
				}

				if($c_daily_target[$c]>$c_daily_output[$c]){
					$variance_daily = $c_daily_target[$c]-$c_daily_output[$c];
					$variance_daily = '('.number_format($variance_daily).')';				
					$vd_color = 'red_vdc';
				}
				else{
					$variance_daily = number_format($c_daily_output[$c]-$c_daily_target[$c]);
					$vd_color = 'black_vdc';
				}

				if($total_target[$c]>$total_output[$c]){
					$variance_month_to_date = $total_target[$c]-$total_output[$c];
					$variance_month_to_date = '('.number_format($variance_month_to_date).')';				
					$vmtd_color = 'red_vmtdc';
				}
				else{
					$variance_month_to_date = number_format($total_output[$c]-$total_target[$c]);
					$vmtd_color = 'black_vmtdc';
				}

				
				if($mp_monthly_target[$c]>$total_shipped_out[$c]){
					$variance_monthly = (int)$mp_monthly_target[$c]-(int)$total_shipped_out[$c];
					$variance_monthly = '('.number_format($variance_monthly).')';
					$vm_color = 'red_vmc';
				}
				else{
					$variance_monthly = number_format((int)$total_output[$c]-(int)$mp_monthly_target[$c]);
					$vm_color = 'black_vmc';
				}
				$attainment_monthly = ((int)$total_shipped_out[$c]/(int)$mp_monthly_target[$c])*100;
				
				if($mp_monthly_target[$c]>$total_output[$c]){
					$mtd_actual_vs_sales = (int)$mp_monthly_target[$c]-(int)$total_output[$c];
					$mtdavs_color = 'red_mtdavsc';

					$remaining_days = dateDifference($pdr_date,$mp_end_date[$c]);						
					$remaining_days = (int)$remaining_days-(int)$holidays_count;

					if($c_line_capacity[$c]>$mtd_actual_vs_sales){
						$fo_days_needed = 1;
						$line_capacity_x_rem_days = (int)$pl_line_capacity[$c]*(int)$remaining_days;
					}
					else{
						if($c_line_capacity[$c]==0){
							$fo_days_needed = (int)$mtd_actual_vs_sales/(int)$pl_line_capacity[$c];
							$line_capacity_x_rem_days = (int)$pl_line_capacity[$c]*(int)$remaining_days;
						}
						else{
							$fo_days_needed = (int)$mtd_actual_vs_sales/(int)$c_line_capacity[$c];
							$line_capacity_x_rem_days = (int)$c_line_capacity[$c]*(int)$remaining_days;
						}
					}

					$fdn_color = 'red_fdnc';	
	
					if($c_progress_assess_r[$c]==''){
						if($line_capacity_x_rem_days>=$mtd_actual_vs_sales){
							$pa_remarks = 'Can still meet sales forecast.';
							$par_color = 'green';
						}
						else{
							$pa_remarks = 'Cannot meet sales forecast.';
							$par_color = 'red';
						}
					}
					else{
						$pa_remarks = $c_progress_assess_r[$c];
						$par_color = 'black';
					}

					if($mtd_actual_vs_sales==0){
						$mtd_actual_vs_sales = '';
					}
					else{
						$mtd_actual_vs_sales = '('.number_format($mtd_actual_vs_sales).')';	
					}
				}
				else{				
					$mtd_actual_vs_sales = number_format((int)$total_output[$c]-(int)$mp_monthly_target[$c]);	
					$mtdavs_color = 'black_mtdavsc';
					$fo_days_needed = '-';
					$fdn_color = 'black_fdnc';			
					$par_color = 'blue';
					if($c_progress_assess_r[$c]==''){
						$pa_remarks = 'Already exceeded forecast quantity.';
					}
					else{
						$pa_remarks = $c_progress_assess_r[$c];
					}
				}

				$d = array();
				$d[$c] = 1;
				$z = $c+1;
				while($pl_category[$c]==$pl_category[$z]){
					$d[$c]++;
					$z++;
				}
				
				$overall_monthly_target = (int)$overall_monthly_target+(int)$mp_monthly_target[$c];
				$overall_total_output = (int)$overall_total_output+(int)$total_output[$c];
				$overall_total_target = (int)$overall_total_target+(int)$total_target[$c];
				$overall_total_shipped_out = (int)$overall_total_shipped_out+(int)$total_shipped_out[$c];
				$overall_c_daily_target = (int)$overall_c_daily_target+(int)$c_daily_target[$c];
				$overall_c_daily_output = (int)$overall_c_daily_output+(int)$c_daily_output[$c];

				if($fo_days_needed!=''&&$fo_days_needed!=0){
					$fo_days_needed = '('.number_format($fo_days_needed).')';
				}
		////////////////////////////////////////////////////////////////////////// END COMPUTATIONS AT TBODY
		////////////////////////////////////////////////////////////////////////// START LOAD OUTPUT DOWNTIME
			$total_dod_minutes = '';
			if($dt_count[$c]==0||isset($dt_count[$c])==false){
				$dod_condition = ' ';
				$total_dod_minutes = ' ';
				$equivalent_output = ' ';
			}
			else{
				$result 			= "";
				$array_fields 		= array('*');
				$table 	   			= 't_daily_output_downtime';
				$joins 	   			= '';
				$sql_where 			= 'WHERE `dod_do_id`='.$c_do_id[$c].' AND dod_logdel=0';
				$sql_order 			= 'ORDER BY `dod_minutes` DESC';
				$sql_limit 			= '';
				$result 			= PDRDS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
				$dod_condition 		= '';
				$ctr 				= 0;
				if($result==false){
					$dod_condition = '';
				}
				while($row = mysqli_fetch_array($result)){
					if($ctr<3){
						if($dod_condition==''){
							if($row['dod_minutes']==0){
								$dod_condition	= $dod_condition.'> '.$row['dod_station'].' - '.$row['dod_condition'];				
							}
							else{
								$dod_condition	= $dod_condition.'> '.$row['dod_station'].' - '.$row['dod_condition'].' ('.$row['dod_minutes'].' mins.)';
							}
						}
						else{
							if($row['dod_minutes']==0){
								$dod_condition	= $dod_condition.'<br/> > '.$row['dod_station'].' - '.$row['dod_condition'];				
							}
							else{
								$dod_condition	= $dod_condition.'<br/> > '.$row['dod_station'].' - '.$row['dod_condition'].' ('.$row['dod_minutes'].' mins.)';
							}
						}
					}
					$total_dod_minutes		= (int)$total_dod_minutes+(int)$row['dod_minutes'];
					$ctr++;
				}
						
				$dod_condition = trim($dod_condition);
				if($dod_condition[3]=="-"){
					$dod_condition = substr($dod_condition,5);
					$dod_condition = '> '.$dod_condition;
				}
				if(substr($dod_condition, -1)=='-'){
					$dod_condition = substr_replace($dod_condition, "", -1);						
				}

				$equivalent_output = ((int)$total_dod_minutes/60)*(int)$c_do_uph[$c];
				if($equivalent_output==0){
					$equivalent_output=' ';
				}
				else{
					$equivalent_output=number_format($equivalent_output);
				}
			}
		////////////////////////////////////////////////////////////////////////// END LOAD OUTPUT DOWNTIME
		////////////////////////////////////////////////////////////////////////// START FORMAT OVERALL VALUES' DISPLAY
			if($mp_monthly_target[$c]==0){
				$mp_monthly_target[$c] = '';
				$dod_condition = '<i>No forecast!</i>';
				$total_output[$c] = '';
				$attainment_monthly = '';
				$total_shipped_out[$c] = '';
				$variance_monthly = '';
				$total_target[$c] = '';
				$variance_month_to_date = '';
				$c_daily_target[$c] = '';
				$c_daily_output[$c] = '';
				$variance_daily = '';
				$total_dod_minutes = '';
				$equivalent_output = '';
				$mtd_actual_vs_sales = '';
				$fo_days_needed = '';
				$remaining_days = '';
				$pa_remarks = '';
			}
			else{
				$mp_monthly_target[$c] = number_format($mp_monthly_target[$c]);
				$total_output[$c] = number_format($total_output[$c]);

				if($attainment_monthly==0){
					$attainment_monthly = '-';
					$attainment_monthly_talign = 'leftd';
				}
				else{
					$attainment_monthly = number_format($attainment_monthly,2,'.',',').'%';
					$attainment_monthly_talign = 'rightd';
				}

				if($total_dod_minutes==''||$total_dod_minutes==0){
					$total_dod_minutes = '';
				}
				else{					
					$total_dod_minutes = number_format($total_dod_minutes);
				}

				$total_target[$c] = number_format($total_target[$c]);
				$total_shipped_out[$c] = number_format($total_shipped_out[$c]);
				$c_daily_target[$c] = number_format($c_daily_target[$c]);
				$c_daily_output[$c] = number_format($c_daily_output[$c]);	
			}				
		////////////////////////////////////////////////////////////////////////// END FORMAT OVERALL VALUES' DISPLAY
		////////////////////////////////////////////////////////////////////////// START OVERALL VALUES COMPUTATION
			$overall_monthly_attainment = ($overall_total_shipped_out/$overall_monthly_target)*100;

			if($overall_total_target>=$overall_total_output){
				$overall_variance_month_to_date = $overall_total_target-$overall_total_output;
				if($overall_variance_month_to_date==0){
					$overall_variance_month_to_date = ' ';
				}
				else{
					$overall_variance_month_to_date = '('.number_format($overall_variance_month_to_date).')';				
				}
				$ovmtd_color = 'red_ovmtdc';
			}
			else{
				$overall_variance_month_to_date = number_format($overall_total_output-$overall_total_target);
				$ovmtd_color = 'black_ovmtdc';
			}

			if($overall_monthly_target>=$overall_total_shipped_out){
				$overall_variance_monthly = (int)$overall_monthly_target-(int)$overall_total_shipped_out;
				if($overall_variance_monthly==0){
					$overall_variance_monthly = ' ';
				}
				else{
					$overall_variance_monthly = '('.number_format($overall_variance_monthly).')';
				}
				$ovm_color = 'red_ovmc';
			}
			else{
				$overall_variance_monthly = number_format((int)$overall_total_output-(int)$overall_monthly_target);
				$ovm_color = 'black_ovmc';
			}

			if($overall_c_daily_target>=$overall_c_daily_output){
				$overall_variance_daily = (int)$overall_c_daily_target-(int)$overall_c_daily_output;
				if($overall_variance_daily==0){
					$overall_variance_daily = ' ';
				}
				else{
					$overall_variance_daily = '('.number_format($overall_variance_daily).')';				
				}
				$ovd_color = 'red_ovdc';
			}
			else{
				$overall_variance_daily = number_format($overall_c_daily_output-$overall_c_daily_target);
				$ovd_color = 'black_ovdc';
			}

			$ppc_plan_to_date_vs_forecast = ($overall_total_target/$overall_monthly_target)*100;
			$ppc_plan_to_date_vs_forecast = number_format($ppc_plan_to_date_vs_forecast,2,'.',',');
			$prodn_actual_to_date_vs_forecast = ($overall_total_output/$overall_monthly_target)*100;
			$prodn_actual_to_date_vs_forecast = number_format($prodn_actual_to_date_vs_forecast,2,'.',',');
		////////////////////////////////////////////////////////////////////////// END OVERALL VALUES COMPUTATION
					////////////////////////////////////////////////////////////////////////// START PDR TABLE TBODY 
					$pdr_table  .= '<style>';

					if($rkla==0){
						$pdr_table  .= '.red_vmc'.$c.'{
											color: red;
										}
										.black_vmc'.$c.'{
											color: black;
										}									
										.red_vdc'.$c.'{
											color: red;
										}
										.black_vdc'.$c.'{
											color: black;
										}									
										.red_vmtdc'.$c.'{
											color: red;
										}
										.black_vmtdc'.$c.'{
											color: black;
										}				
										.center'.$c.'{
											text-align: center;
										}
										.black_mtdavsc'.$c.'{
											color: black;
											text-align: right;
											font-weight: normal;
										}
										.red_mtdavsc'.$c.'{
											color: red;
											text-align: right;
											font-weight: normal;
										}
										.red_fdnc'.$c.'{
											color: red;
											text-align: right;
											font-weight: normal;
										}
										.black_fdnc'.$c.'{
											color: black;
											text-align: right;
										}';
						}
						$pdr_table  .= '#lefta'.$c.$rkla.'{
											text-align: left;
										}
										#leftb'.$c.$rkla.'{
											text-align: left;
										}
										.leftd'.$c.$rkla.'{
											text-align: left;
										}
										.rightd'.$c.$rkla.'{
											text-align: right;
										}
										#'.$c.$rkla.'{
											text-align: left;
											color: '.$par_color.';
										}
								</style>';				
						$pdr_table  .= '<tr>';
						if($pl_category[$c]!=$pl_category[$c-1]){
							$pdr_table  .= '	<td class="center'.$c.'" rowspan="'.$d[$c].'" width="80">'.$pl_category[$c].'</td>';
						}		
						$pdr_table  .= '	<td id="lefta'.$c.$rkla.'" width="110">'.$pl_product_line[$c].'</td>';
						$pdr_table  .= '	<td bgcolor="#CCFFFF" width="60">'.$mp_monthly_target[$c].'</td>';
						$pdr_table  .= '	<td bgcolor="#CCFFFF" width="60">'.$total_shipped_out[$c].'</td>';
						$pdr_table  .= '	<td bgcolor="#CCFFFF" class="'.$vm_color.$c.'" width="60">'.$variance_monthly.'</td>';
						$pdr_table  .= '	<td width="60" class="'.$attainment_monthly_talign.$c.$rkla.'">'.$attainment_monthly.'</td>';
						$pdr_table  .= '	<td bgcolor="#CCFFFF" width="60">'.$total_target[$c].'</td>';
						$pdr_table  .= '	<td bgcolor="#CCFFFF" width="60">'.$total_output[$c].'</td>';
						$pdr_table  .= '	<td class="'.$vmtd_color.$c.'" width="60">'.$variance_month_to_date.'</td>';
						$pdr_table  .= '	<td bgcolor="#CCFFFF" width="60">'.$c_daily_target[$c].'</td>';
						$pdr_table  .= '	<td bgcolor="#CCFFFF" width="60">'.$c_daily_output[$c].'</td>';
						$pdr_table  .= '	<td class="'.$vd_color.$c.'" width="60">'.$variance_daily.'</td>';
						$pdr_table  .= '	<td width="205" id="leftb'.$c.$rkla.'">'.rtrim(chunk_split($dod_condition)).'</td>';
						$pdr_table  .= '	<td width="50">'.$total_dod_minutes.'</td>';
						$pdr_table  .= '	<td width="50">'.$equivalent_output.'</td>';
						$pdr_table 	.= '	<td width="30" class="'.$mtdavs_color.$c.'">'.$mtd_actual_vs_sales.'</td>';
						$pdr_table 	.= '	<td width="60" class="'.$fdn_color.$c.'">'.$fo_days_needed.'</td>';
						$pdr_table 	.= '	<td width="60">'.$remaining_days.'</td>';
						$pdr_table 	.= '	<td width="200" id="'.$c.$rkla.'">'.rtrim(chunk_split($pa_remarks)).'</td>';
						$pdr_table  .= '</tr>';
						$c++;
					////////////////////////////////////////////////////////////////////////// END PDR TABLE TBODY
	}			
					////////////////////////////////////////////////////////////////////////// START FORMAT OVERALL VALUES' DISPLAY		
					if($overall_monthly_target==0){
						$overall_monthly_target = ' ';
					}
					else{
						$overall_monthly_target = number_format($overall_monthly_target);
					}

					if($overall_total_target==0){
						$overall_total_target = ' ';
					}
					else{
						$overall_total_target = number_format($overall_total_target);
					}

					if($overall_total_output==0){
						$overall_total_output = ' ';
					}
					else{
						$overall_total_output = number_format($overall_total_output);
					}

					if($overall_total_shipped_out==0){
						$overall_total_shipped_out = '';
					}
					else{
						$overall_total_shipped_out = number_format($overall_total_shipped_out);
					}	

					if($overall_monthly_attainment==0){
						$overall_monthly_attainment = ' ';
					}
					else{
						$overall_monthly_attainment = number_format($overall_monthly_attainment,2,'.',',').'%';
					}		

					if($overall_c_daily_target==0){
						$overall_c_daily_target = ' ';
					}
					else{
						$overall_c_daily_target = number_format($overall_c_daily_target);
					}			

					if($overall_c_daily_output==0){
						$overall_c_daily_output = ' ';
					}
					else{
						$overall_c_daily_output = number_format($overall_c_daily_output);
					}	
					////////////////////////////////////////////////////////////////////////// END FORMAT OVERALL VALUES' DISPLAY
					$pdr_table  .= '<style type="text/css">										
										.noborder{
											border: none;
										}
										#red_ovmc'.$rkla.'{
											color: red;
										}
										#black_ovmc'.$rkla.'{
											color: black;
										}
										#black_ovmtdc'.$rkla.'{
											color: black;
										}
										#red_ovmtdc'.$rkla.'{
											color: red;
										}
										#black_ovdc'.$rkla.'{
											color: black;
										}
										#red_ovdc'.$rkla.'{
											color: red;
										}
									</style>';

			$pdr_table 	.= '	<tr>';
			$pdr_table 	.= '		<td class="noborder"> </td>';
			$pdr_table 	.= '		<td bgcolor="#66FEFF">TOTAL</td>';
			$pdr_table 	.= '		<td bgcolor="#66FEFF">'.$overall_monthly_target.'</td>';
			$pdr_table 	.= '		<td bgcolor="#66FEFF">'.$overall_total_shipped_out.'</td>';
			$pdr_table 	.= '		<td bgcolor="#66FEFF" id="'.$ovm_color.$rkla.'">'.$overall_variance_monthly.'</td>';
			$pdr_table 	.= '		<td bgcolor="#66FEFF">'.$overall_monthly_attainment.'</td>';
			$pdr_table 	.= '		<td bgcolor="#66FEFF">'.$overall_total_target.'</td>';
			$pdr_table 	.= '		<td bgcolor="#66FEFF">'.$overall_total_output.'</td>';
			$pdr_table 	.= '		<td bgcolor="#66FEFF" id="'.$ovmtd_color.$rkla.'">'.$overall_variance_month_to_date.'</td>';
			$pdr_table 	.= '		<td bgcolor="#66FEFF">'.$overall_c_daily_target.'</td>';
			$pdr_table 	.= '		<td bgcolor="#66FEFF">'.$overall_c_daily_output.'</td>';
			$pdr_table 	.= '		<td bgcolor="#66FEFF" id="'.$ovd_color.$rkla.'">'.$overall_variance_daily.'</td>';
			$pdr_table 	.= '		<td class="noborder"> </td>';
			$pdr_table 	.= '		<td class="noborder"> </td>';
			$pdr_table 	.= '		<td class="noborder"> </td>';
			$pdr_table 	.= '		<td class="noborder"> </td>';
			$pdr_table 	.= '		<td class="noborder"> </td>';
			$pdr_table 	.= '		<td class="noborder"> </td>';
			$pdr_table 	.= '	</tr>';
			$pdr_table 	.= '	</tbody>';
			$pdr_table 	.= '</table>';
					$pdr_table  .= '<style type="text/css">										
										#vs_forecast{
											font-size: 8pt;
										}										
										.nobord{
											border: none;
											padding-right:5px;
										}
										.bandc{
											border: solid 1px black;
											font-size: 10pt;
											text-align: center;
										}
									</style>';	
			$pdr_table 	.= '<br><table id="vs_forecast" cellpadding="1" cellspacing="4">';	
			$pdr_table 	.= '	<tr>';
			$pdr_table 	.= '		<td class="nobord" width="301"> </td>';
			$pdr_table 	.= '		<td class="nobord" colspan="6" width="300">PPC plan to date vs. forecast</td>';
			$pdr_table 	.= '		<td class="bandc" bgcolor="#66FEFF" width="51"> '.$ppc_plan_to_date_vs_forecast.'%</td>';
			$pdr_table 	.= '	</tr>';
			$pdr_table 	.= '	<tr>';
			$pdr_table 	.= '		<td class="nobord" width="301"> </td>';
			$pdr_table 	.= '		<td class="nobord" colspan="6" width="300"></td>';
			$pdr_table 	.= '		<td class="nobord"></td>';
			$pdr_table 	.= '	</tr>';
			$pdr_table 	.= '	<tr>';
			$pdr_table 	.= '		<td class="nobord" width="301"> </td>';
			$pdr_table 	.= '		<td class="nobord" colspan="6" width="300">Production actual to date vs. forecast</td>';
			$pdr_table 	.= '		<td class="bandc" bgcolor="#66FEFF" width="51"> '.$prodn_actual_to_date_vs_forecast.'%</td>';
			$pdr_table 	.= '	</tr>';	
			$pdr_table 	.= '</table>';
			echo $pdr_table;
	$rkla++;
	echo '<br><br>';
}
?>	
		<br>
		<br>
		<span>For our common reference and information.</span><br><br>	
		<i style="font-size:90%;">
			This E-mail is generated by Production Daily Report Database System (PDRDS).
		</i>
	</body>
</html>
