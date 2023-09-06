<?php
    include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include('../config/functions.php');
    $isPOHead = (USERID==getPoConfigData('purchasing_head'))?'':'hidden';
?>
<div class='header-page' >
	<div class='header-page-inner'>
		Dashboard 
	</div>
</div>
<div class="container-fluid">
    <div class="pageContent">
    	

        <div class="row">
	    	<div class="col-lg-9">
	    		
	    		<div id='dashboardquicklinks'>
	    				<fieldset>
			    			<div class="row style2">
			    				<div class="col-md-3 pointer hidden">
				    				<div class="sm-st clearfix qlprapproval">
				    					<span class="sm-st-icon st-red">
				    						<i class="fa fa-file-text-o"></i>
				    					</span>
				    					<div class="sm-st-info">
				    						Purchase Requisition <br>Approvals
				    						<?php 
				    								$query="select COUNT(requisition_number) as  approvalcount
																		from purchase_requisition_header 
																		where ((section_head1='$userid' and flag='0' and sh_approved is null) or
																			(section_head2='$userid' and flag='1' and sh2_approved is null) or
																			(conformance='$userid' and flag='2' and conf_approved is null) or
																			(general_manager='$userid' and flag='3' and gm_approved is null) or
																			(assistant_vice_president='$userid' and flag='4' and avp_approved is null) or
																			(senior_vice_president='$userid' and flag='5' and svp_approved is null) or
																			(president='$userid' and flag='6' and pr_approved is null)) and requisition_status='IN REVIEW'";
				    								$rs = query($query);
				    								// $approvalcount = getNumRows($rs);
													if($obj=fetch($rs)){
														$approvalcount = $obj->approvalcount;														
													}
				    								echo "(".$approvalcount.")";
				    							?>
				    					</div>
				    				</div>
			    				</div><!-- PR approval -->
			    				<div class="col-md-3 pointer hidden">
				    				<div class="sm-st clearfix qlprhistory">
				    					<span class="sm-st-icon st-red"><i class="fa fa-list"></i></span>
				    					<div class="sm-st-info">
				    						Purchase Requisition <br>History
				    					</div>
				    				</div>
			    				</div>
			    				<div class="col-md-3 pointer hidden">
				    				<div class="sm-st clearfix qlpoapproval">
				    					<span class="sm-st-icon st-red">
				    						<i class="fa fa-clipboard"></i>
				    					</span>
				    					<div class="sm-st-info">
				    						Purchase Order <br>Approvals
				    						<?php 
				    								$query = $query = "select COUNT(order_number) as  approvalcount from purchase_order_header, purchase_requisition_header 
																			  where purchase_requisition_header.requisition_number=purchase_order_header.reference_requisition_number and 
																			        order_status='IN REVIEW' and
																			        (
																			         (first_approver='$userid' and purchase_order_header.flag='1') or 
																			         (second_approver='$userid' and purchase_order_header.flag='2') or 
																			         (third_approver='$userid' and purchase_order_header.flag='3')
																			        )";
				    								$rs = query($query);
				    								// $approvalcount = getNumRows($rs);
													if($obj=fetch($rs)){
														$approvalcount = $obj->approvalcount;														
													}
				    								echo "(".$approvalcount.")";
				    							?>
				    					</div>
				    				</div>
			    				</div><!-- PO Approval -->
			    				<div class="col-md-3 pointer hidden">
				    				<div class="sm-st clearfix qlpohistory">
				    					<span class="sm-st-icon st-red"><i class="fa fa-list"></i></span>
				    					<div class="sm-st-info">
				    						Purchase Order <br>History
				    					</div>
				    				</div>
			    				</div>
			    				<div class="col-md-3 pointer hidden">
				    				<div class="sm-st clearfix qlrcvapproval">
				    					<span class="sm-st-icon st-red">
				    						<i class="fa fa-truck"></i>
				    					</span>
				    					<div class="sm-st-info">
				    						Receiving <br>Approvals
				    						<?php 
				    								$query = $query = "select COUNT(receiving_number) as  approvalcount from receiving_header 
																	  where receiving_status='IN REVIEW' and
																		(
																		 (first_approver='$userid' and flag='1') or 
																		 (second_approver='$userid' and flag='2')
																		)";
				    								$rs = query($query);
				    								// $approvalcount = getNumRows($rs);
													if($obj=fetch($rs)){
														$approvalcount = $obj->approvalcount;														
													}
				    								echo "(".$approvalcount.")";
				    							?>
				    					</div>
				    				</div>
			    				</div><!-- Receiving Approval -->
			    				<div class="col-md-3 pointer hidden">
				    				<div class="sm-st clearfix qlrcvhistory">
				    					<span class="sm-st-icon st-red"><i class="fa fa-list"></i></span>
				    					<div class="sm-st-info">
				    						Receiving <br>History
				    					</div>
				    				</div>
			    				</div>

			    				<div class="col-md-3 pointer hidden">
				    				<div class="sm-st clearfix qlholdinvoice">
				    					<span class="sm-st-icon st-red">
				    						<i class="fa fa-truck"></i>
				    					</span>
				    					<div class="sm-st-info">
				    						Hold <br>Supplier's Invoice
				    						<?php 
				    								$query = $query = "select COUNT(order_status) as holdcount from purchase_order_header 
																	  where order_status='HOLD'";
				    								$rs = query($query);
													if($obj=fetch($rs)){
														$holdcount = $obj->holdcount;														
													}
				    								echo "(".$holdcount.")";
				    							?>
				    					</div>
				    				</div>
			    				</div>
			    				<div class="col-md-3 pointer hidden">
				    				<div class="sm-st clearfix qlappstatus">
				    					<span class="sm-st-icon st-red">
				    						<i class="fa fa-truck"></i>
				    					</span>
				    					<div class="sm-st-info">
				    						Item <br> Approvals
				    						<?php 
				    								$query = $query = "select COUNT(item_code) as item_for_approval from item 
																	  where app_status='1' AND active_flag='1'";
				    								$rs = query($query);
													if($obj=fetch($rs)){
														$app_status = $obj->item_for_approval;														
													}
				    								echo "(".$app_status.")";
				    							?>
				    					</div>
				    				</div>
			    				</div>

			    			</div>
			    		</fieldset>
	    		</div>

	    		<div id='requisitioner-row' class='hidden'>
	    			<div class="row">
	    				<div class="col-md-4">
		    				<div class="sm-st clearfix">
		    					<span class="sm-st-icon st-red"><i class="fa fa-file-text-o"></i></span>
		    					<div class="sm-st-info">

		    						<span>
		    							<?php 
		    								$query = "select COUNT(requisition_number) as approvalcount from purchase_requisition_header where requisitioner='$userid'";
		    								$rs = query($query);
		    								// $approvalcount = getNumRows($rs);
											if($obj=fetch($rs)){
												$approvalcount = $obj->approvalcount;														
											}
		    								echo $approvalcount;
		    							?>
		    						</span>
		    						Total PR Created
		    					</div>
		    				</div>
	    				</div>
	    				<div class="col-md-4">
		    				<div class="sm-st clearfix">
		    					<span class="sm-st-icon st-violet"><i class="fa fa-check-square-o"></i></span>
		    					<div class="sm-st-info">
		    						<span>
		    							<?php 
		    								$queryc=$query." and requisition_status='APPROVED'";
		    								$rs = query($queryc);
		    								// $approvalcount = getNumRows($rs);
											if($obj=fetch($rs)){
												$approvalcount = $obj->approvalcount;														
											}
		    								echo $approvalcount;
		    							?>
		    						</span>
		    						Approved PR(s)
		    					</div>
		    				</div>
		    			</div>
	    				<div class="col-md-4">
		    				<div class="sm-st clearfix">
		    					<span class="sm-st-icon st-violet"><i class="fa fa-file-text"></i></span>
		    					<div class="sm-st-info">
		    						<span>
		    							<?php 
		    								$query0=$query." and requisition_status='IN REVIEW'";
		    								$rs = query($query0);
		    								// $approvalcount = getNumRows($rs);
											if($obj=fetch($rs)){
												$approvalcount = $obj->approvalcount;														
											}
		    								echo $approvalcount;
		    							?>
		    						</span>
		    						PR(s) for review
		    					</div>
		    				</div>
		    			</div>
		    			<div class="col-md-4">
		    				<div class="sm-st clearfix">
		    					<span class="sm-st-icon st-violet"><i class="fa fa-file-excel-o"></i></span>
		    					<div class="sm-st-info">
		    						<span>
		    							<?php 
		    								$querya=$query." and requisition_status='REJECTED'";
		    								$rs = query($querya);
		    								// $approvalcount = getNumRows($rs);
											if($obj=fetch($rs)){
												$approvalcount = $obj->approvalcount;														
											}
		    								echo $approvalcount;
		    							?>
		    						</span>
		    						Rejected PR(s)
		    					</div>
		    				</div>
		    			</div>
		    			<div class="col-md-4">
		    				<div class="sm-st clearfix">
		    					<span class="sm-st-icon st-violet"><i class="fa fa-file-o"></i></span>
		    					<div class="sm-st-info">
		    						<span>
		    							<?php 
		    								$queryb=$query." and requisition_status='DRAFT'";
		    								$rs = query($queryb);
		    								// $approvalcount = getNumRows($rs);
											if($obj=fetch($rs)){
												$approvalcount = $obj->approvalcount;														
											}
		    								echo $approvalcount;
		    							?>
		    						</span>
		    						Draft PR(s)
		    					</div>
		    				</div>
		    			</div>
		    			
		    			<div class="col-md-4">
		    				<div class="sm-st clearfix">
		    					<span class="sm-st-icon st-violet"><i class="fa fa-pencil-square"></i></span>
		    					<div class="sm-st-info">
		    						<span>
		    							<?php 
		    								$queryc=$query." and requisition_status='FOR REVISION'";
		    								$rs = query($queryc);
		    								// $approvalcount = getNumRows($rs);
											if($obj=fetch($rs)){
												$approvalcount = $obj->approvalcount;														
											}
		    								echo $approvalcount;
		    							?>
		    						</span>
		    						PR(s) for Revision
		    					</div>
		    				</div>
		    			</div>
	    			</div>
	    		</div>

	    		<div id='purchaser-row' class='hidden'>
	    			<div class="row">
	    				<div class="col-md-4">
		    				<div class="sm-st clearfix">
		    					<span class="sm-st-icon st-red"><i class="fa fa-file-text-o"></i></span>
		    					<div class="sm-st-info">

		    						<span>
		    							<?php 
		    								$query = "select COUNT(order_number) as approvalcount from purchase_order_header where assigned_to='$userid'";
		    								$rs = query($query);
		    								// $approvalcount = getNumRows($rs);
											if($obj=fetch($rs)){
												$approvalcount = $obj->approvalcount;														
											}
		    								echo $approvalcount;
		    							?>
		    						</span>
		    						Total PO Created
		    					</div>
		    				</div>
	    				</div>
	    				<div class="col-md-4">
		    				<div class="sm-st clearfix">
		    					<span class="sm-st-icon st-violet"><i class="fa fa-check-square-o"></i></span>
		    					<div class="sm-st-info">
		    						<span>
		    							<?php 
		    								$queryc=$query." and order_status='UNSERVED'";
		    								$rs = query($queryc);
		    								// $approvalcount = getNumRows($rs);
											if($obj=fetch($rs)){
												$approvalcount = $obj->approvalcount;														
											}
		    								echo $approvalcount;
		    							?>
		    						</span>
		    						Unserved PO(s)
		    					</div>
		    				</div>
		    			</div>
	    				<div class="col-md-4">
		    				<div class="sm-st clearfix">
		    					<span class="sm-st-icon st-violet"><i class="fa fa-file-text"></i></span>
		    					<div class="sm-st-info">
		    						<span>
		    							<?php 
		    								$query0=$query." and order_status='IN REVIEW'";
		    								$rs = query($query0);
		    								// $approvalcount = getNumRows($rs);
											if($obj=fetch($rs)){
												$approvalcount = $obj->approvalcount;														
											}
		    								echo $approvalcount;
		    							?>
		    						</span>
		    						PO(s) for review
		    					</div>
		    				</div>
		    			</div>
		    			<div class="col-md-4">
		    				<div class="sm-st clearfix">
		    					<span class="sm-st-icon st-violet"><i class="fa fa-file-excel-o"></i></span>
		    					<div class="sm-st-info">
		    						<span>
		    							<?php 
		    								$querya=$query." and order_status='REJECTED'";
		    								$rs = query($querya);
		    								// $approvalcount = getNumRows($rs);
											if($obj=fetch($rs)){
												$approvalcount = $obj->approvalcount;														
											}
		    								echo $approvalcount;
		    							?>
		    						</span>
		    						Rejected PO(s)
		    					</div>
		    				</div>
		    			</div>
		    			<div class="col-md-4">
		    				<div class="sm-st clearfix">
		    					<span class="sm-st-icon st-violet"><i class="fa fa-file-o"></i></span>
		    					<div class="sm-st-info">
		    						<span>
		    							<?php 
		    								$queryb=$query." and order_status='DRAFT'";
		    								$rs = query($queryb);
		    								// $approvalcount = getNumRows($rs);
											if($obj=fetch($rs)){
												$approvalcount = $obj->approvalcount;														
											}
		    								echo $approvalcount;
		    							?>
		    						</span>
		    						Draft PO(s)
		    					</div>
		    				</div>
		    			</div>
		    			
		    			<div class="col-md-4">
		    				<div class="sm-st clearfix">
		    					<span class="sm-st-icon st-violet"><i class="fa fa-pencil-square"></i></span>
		    					<div class="sm-st-info">
		    						<span>
		    							<?php 
		    								$queryc=$query." and order_status='FOR REVISION'";
		    								$rs = query($queryc);
		    								// $approvalcount = getNumRows($rs);
											if($obj=fetch($rs)){
												$approvalcount = $obj->approvalcount;														
											}
		    								echo $approvalcount;
		    							?>
		    						</span>
		    						PO(s) for Revision
		    					</div>
		    				</div>
		    			</div>
		    			<div class="col-md-4">
		    				<div class="sm-st clearfix">
		    					<span class="sm-st-icon st-violet"><i class="fa fa-truck"></i></span>
		    					<div class="sm-st-info">
		    						<span>
		    							<?php 
		    								$queryc=$query." and order_status='PARTIAL DELIVERY'";
		    								$rs = query($queryc);
		    								// $approvalcount = getNumRows($rs);
											if($obj=fetch($rs)){
												$approvalcount = $obj->approvalcount;														
											}
		    								echo $approvalcount;
		    							?>
		    						</span>
		    						PO(s) w/ Partial Delivery
		    					</div>
		    				</div>
		    			</div>
		    			<div class="col-md-4">
		    				<div class="sm-st clearfix">
		    					<span class="sm-st-icon st-violet"><i class="fa fa-check"></i></span>
		    					<div class="sm-st-info">
		    						<span>
		    							<?php 
		    								$queryc=$query." and order_status='SERVED'";
		    								$rs = query($queryc);
		    								// $approvalcount = getNumRows($rs);
											if($obj=fetch($rs)){
												$approvalcount = $obj->approvalcount;														
											}
		    								echo $approvalcount;
		    							?>
		    						</span>
		    						Served PO(s)
		    					</div>
		    				</div>
		    			</div>
	    			</div>
	    		</div>

	    		<div id='purchase-requisition-row' class='hidden'>
	    			<fieldset>
	    				<legend>Purchase Requisition</legend>
		    			<div class='tabpane-white tab-pane-wrapper margin-top-20 margin-bottom-10'>
							<ul class="nav nav-tabs">
							    <li role="presentation" class="active" data-pane='#dashboard-pr-approval-pane'><a>Pending Approvals</a></li>
								<li role="presentation" data-pane='#dashboard-pr-history-pane'><a>Approval History</a></li>
								<li role="presentation" data-pane='#dashboard-pr-details'><a>PR Information</a></li>
							</ul>
							<div class='tab-panes bg-white'>
								<div class='pane active' id='dashboard-pr-approval-pane'>
									<div class="pane-content">

										<div class="tablewrapper-sm">
											<table class='table table-striped table-condensed table-bordered no-side-borders mytable table-hover' id='prpendingapprovaltable' cellspacing='0' style='width:100%'>
												<thead>
													<tr>
														<th class='text-center'>LAST UPDATE</th>
														<th class='text-center'>TYPE</th>
														<th>PR NUMBER</th>
														<th>REQUISITIONER</th>
														<th class='text-center'>DATE NEEDED</th>
													</tr>
												</thead>
												<tbody>

													<?php
														$query="select requisition_number, requisition_type, date_needed, requisitioner, next_revision_series, last_made_changes
														from purchase_requisition_header 
														where ((section_head1='$userid' and flag='0' and sh_approved is null) or
															(section_head2='$userid' and flag='1' and sh2_approved is null) or
															(conformance='$userid' and flag='2' and conf_approved is null) or
															(general_manager='$userid' and flag='3' and gm_approved is null) or
															(assistant_vice_president='$userid' and flag='4' and avp_approved is null) or
															(senior_vice_president='$userid' and flag='5' and svp_approved is null) or
															(president='$userid' and flag='6' and pr_approved is null)) and requisition_status='IN REVIEW'
														order by last_made_changes desc limit 0, 100";
														$rs = query($query);
														// $count = getNumRows($rs);
														$urgentcount = 0;
														$regularcount = 0;
														while($obj=fetch($rs)){
															$prnumber = $obj->requisition_number;
															$reqtype = strtoupper($obj->requisition_type);
															$dateneeded = $obj->date_needed;
															$dateneeded = date('M d, Y', strtotime($dateneeded));
															$requisitioner = getUserName($obj->requisitioner);
															$numofrev = $obj->next_revision_series;
															$last_update = $obj->last_made_changes;
															$last_update = date('M d, Y h:i:s A', strtotime($last_update));
															$boxcolor="<div class='regular'><div class='regularinner'>";
															if($reqtype=='URGENT'){
																$boxcolor="<div class='rush'><div class='rushinner'>";
																$urgentcount++;
															}
															else{
																$regularcount++;
															}
															echo "<tr class='tablerow pointer approvalrow' id='$prnumber'>
																	<td class='text-center'>$last_update</div></div></td>
																	<td class='width-md text-center'>$boxcolor$reqtype</div></div></td>
																	<td>$prnumber".appendPrRevSuffix($prnumber)."</td>
																	<td>$requisitioner</td>
																	<td class='text-center'>$dateneeded</td>
																 </tr>";
														}
													?>
												</tbody>
											</table>
								    	</div>

									</div>
								</div>
								<div class='pane' id='dashboard-pr-history-pane'>
									<div class="pane-content">

										<div class="tablewrapper-sm">
									    					<table class='table table-striped table-condensed table-bordered no-side-borders mytable table-hover' cellspacing='0' id='prapprovalhistorytable' style='width:100%'>
									    						<thead>
									    							<tr>
																		<th class='text-center'>LAST UPDATE</th>
									    								<th class='text-center'>ACTION</th>
									    								<th class='text-center'>PR NUMBER</th>
									    								<th>REVISION NO.</th>
									    								<th>APPROVAL TYPE</th>
									    								<th>REQUISITIONER</th>
									    								<th>REMARKS</th>
									    								<th class='text-center'>DATE</th>
									    							</tr>
									    						</thead>
									    						<tbody id='prhistory'>
									    							<?php
									    								$query = "select purchase_requisition_approval_history.requisition_number, action, pr_flag, pr_approval_flag, pr_approval_remarks, requisitioner, `date`, last_made_changes
																				  from purchase_requisition_approval_history, 
																				       purchase_requisition_header 
																				  where purchase_requisition_header.requisition_number=purchase_requisition_approval_history.requisition_number and user_id=".USERID." order by date desc limit 100";
																		$rs = query($query);
																		while($obj=fetch($rs)){
																			$pr = $obj->requisition_number;
																			$action = $obj->action;
																			$revnum = $obj->pr_flag;
																			$revnum = $revnum==0?'R0':'R'.$revnum;
																			$reason = strUtfEncode($obj->pr_approval_remarks);
																			$requisitioner = getUserName($obj->requisitioner);
																			$date = formatDate2($obj->date);
																			$flag = $obj->pr_flag;
																			$last_update = $obj->last_made_changes;
																			$last_update = date('M d, Y h:i:s A', strtotime($last_update));

																			switch ($obj->pr_approval_flag){
																				case '0':
																					$approvaltype = 'Section Head 1';
																					break;
																				case '1':
																					$approvaltype = 'Section Head 2';
																					break;
																				case '2':
																					$approvaltype = 'Conformance';
																					break;
																				case '3':
																					$approvaltype = 'General Manager';
																					break;
																				case '4':
																					$approvaltype = 'Assistant Vice President';
																					break;
																				case '5':
																					$approvaltype = 'Senior Vice President';
																					break;
																				case '6':
																					$approvaltype = 'President';
																					break;
																				default:
																					$approvaltype = '';
																					break;
																			}

																			echo "<tr class='tablerow prrowlog pointer' prnumber='$pr' prflag='$flag'>
																					<td class='text-center'>$last_update</td>
																					<td class='text-center'>$action</td>
																					<td class='text-center'>$pr</td>
																					<td>$revnum</td>
																					<td>$approvaltype</td>
																					<td>$requisitioner</td>
																					<td>$reason</td>
																					<td class='text-center'>$date</td>
																				  </tr>";
																		}

									    							?>

									    						</tbody>
									    					</table>
									    </div>

									</div>
								</div>
								<div class='pane' id='dashboard-pr-details'>
									<div class="pane-content">

										<div class="tablewrapper-sm">
									    					<table class='table table-striped table-condensed table-bordered no-side-borders mytable table-hover' cellspacing='0' id='prinfotable' style='width:100%'>
									    						<thead>
									    							<tr>
									    								<th class='text-center'>STATUS</th>
									    								<th>PR NUMBER</th>
									    								<th>REVISION NO.</th>
									    								
									    								<th class='text-center'>DATE NEEDED</th>
									    							</tr>
									    						</thead>
									    						<tbody id='prdetailtbdy'>
									    							<?php

																				$query="select requisition_number, next_revision_series, requisition_status, date_needed
																						from purchase_requisition_header 
																						where ((section_head1='$userid') or
																							(section_head2='$userid') or
																							(conformance='$userid') or
																							(general_manager='$userid') or
																							(assistant_vice_president='$userid') or
																							(senior_vice_president='$userid') or
																							(president='$userid')) and requisition_status!='DRAFT'
																						order by last_made_changes desc limit 100";
																				$rs = query($query);
																				$count = getNumRows($rs);
																				$urgentcount = 0;
																				$regularcount = 0;
																				while($obj=fetch($rs)){
																					$prnumber = $obj->requisition_number;
																					$revnum = 'R'.$obj->next_revision_series;
																					$status = $obj->requisition_status;
																					$dateneeded = $obj->date_needed;
																					$dateneeded = date('M d, Y', strtotime($dateneeded));
																					echo "<tr class='tablerow pointer prinforow' id='$prnumber'>
																							<td class='text-center'>$status</td>
																							<td>$prnumber</td>
																							<td>$revnum</td>
																							<td class='text-center'>$dateneeded</td>
																						</tr>";
																				}
									    							?>

									    						</tbody>
									    					</table>
									    </div>

									</div>
								</div>
							</div>
						</div>
					</fieldset>		    	
				</div>

				<div id='purchase-order-row' class='hidden'>
	    			<fieldset>
	    				<legend>Purchase Order</legend>
		    			<div class='tabpane-white tab-pane-wrapper margin-top-20 margin-bottom-10'>
								<ul class="nav nav-tabs">
								    <li role="presentation" class="active" data-pane='#dashboard-po-approval-pane'><a>Pending Approvals</a></li>
									<li role="presentation" data-pane='#dashboard-po-history-pane'><a>Approval History</a></li>
								</ul>
								<div class='tab-panes bg-white'>
									<div class='pane active' id='dashboard-po-approval-pane'>
										<div class="pane-content">

											<div class="tablewrapper-sm">
									    			<table class='table table-striped table-condensed table-bordered no-side-borders mytable table-hover' cellspacing='0' id='popendingapprovaltable' style='width:100%'>
																			<thead>
																				<tr>
																					<th class='text-center'>LAST UPDATE</th>
																					<th class='text-center'>TYPE</th>
																					<th>PO NUMBER</th>
																					<th>REFERENCE PR NUMBER</th>
																					<th>POSTED BY</th>
																					<th class='text-center'>DATE NEEDED</th>
																				</tr>
																			</thead>
																			<tbody>
																				
																				<?php
																					$query = "select order_number, reference_requisition_number, posted_by, expected_date, date_flag, next_revision_series  from purchase_order_header 
																							  where order_status='IN REVIEW' and
																							        (
																							         (first_approver='$userid' and purchase_order_header.flag='1') or 
																							         (second_approver='$userid' and purchase_order_header.flag='2') or 
																							         (third_approver='$userid' and purchase_order_header.flag='3')
																							        )
																							  order by date_flag desc LIMIT 300";
																					
																					$rs = query($query);
																					$rscount = getNumRows($rs);
																					while($obj=fetch($rs)){
																						$po = $obj->order_number;
																						$numofrev = $obj->next_revision_series;
																						$pr = $obj->reference_requisition_number;
																						$type = strtoupper(getInfo("purchase_requisition_header","requisition_type","where requisition_number='".$pr."'"));
																						$postedby = getUserName($obj->posted_by);
																						$date = $obj->expected_date;
																						$date = date('M d, Y', strtotime($date));
																						$last_update = $obj->date_flag;
																						$last_update = date('M d, Y h:i:s A', strtotime($last_update));
																						$boxcolor="<div class='regular'><div class='regularinner'>";
																						if($type=='URGENT'){
																							$boxcolor="<div class='rush'><div class='rushinner'>";
																						}
																						echo "<tr class='tablerow clickable pointer approvalrow' po='$po'>
																								<td class='text-center'>$last_update</td>
																								<td class='width-md text-center'>$boxcolor$type</div></div></td>
																								<td>$po".appendPoRevSuffix($po)."</td>
																								<td>".$pr.appendPrRevSuffix($pr)."</td>
																								<td>$postedby</td>
																								<td class='text-center'>$date</td>
																							  </tr>";
																					}
																				?>
																			</tbody>
													</table>
											</div>

										</div>
									</div>
									<div class='pane' id='dashboard-po-history-pane'>
										<div class="pane-content">

											<div class="tablewrapper-sm">
																<table class='table table-striped table-condensed table-bordered no-side-borders mytable table-hover' cellspacing='0' id='poapprovalhistorytable' style='width:100%'>
																	<thead>
																		<tr>
																			<th class='text-center'>PO NUMBER</th>
																			<th>REVISION NO.</th>
																			<th>APPROVAL AS</th>
																			<th class='text-center'>ACTION</th>
																			<th>REMARKS</th>
																			<th class='text-center'>DATE APPROVED</th>
																		</tr>
																	</thead>
																	<tbody id='pohistory'>
																		<?php 
																			$query = "select order_number, action, po_flag, purch_revision_no, po_approval_remarks, `date`, po_approval_flag  from purchase_order_approval_history order by date desc limit 100";
																				$rs = query($query);
																				while($obj=fetch($rs)){
																					$pr = $obj->order_number;
																					$action = $obj->action;
																					$revnum = $obj->po_flag;
																					$purch_revision_no = $obj->purch_revision_no;
																					$revnum = ($revnum==0?'R0':'R'.$revnum).'.'.$purch_revision_no;
																					$reason = strUtfEncode($obj->po_approval_remarks);
																					$date = formatDate4($obj->date);
																					$fl = $obj->po_flag.'.'.$purch_revision_no;
																					switch ($obj->po_approval_flag){
																							case '1':
																								$poapprovaltype = 'Logistics Manager';
																								break;
																							case '2':
																								$poapprovaltype = 'General Manager';
																								break;
																							case '3':
																								$poapprovaltype = 'President';
																								break;
																							default:
																								$poapprovaltype = '';
																								break;
																					}
																					echo "<tr class='tablerow porowlog pointer' ponumber='$pr' poflag='$fl'>
																							<td class='text-center'>$pr</td>
																							<td>$revnum</td>
																							<td>$poapprovaltype</td>
																							<td class='text-center'>$action</td>
																							<td>$reason</td>
																							<td class='text-center'>$date</td>
																						  </tr>";
																				}

																		?>
																		
																	</tbody>
																</table>
											</div>

										</div>
									</div>
								</div>
				    	</div>
				    </fieldset>
				</div>
				
				<div id='receiving-row' class='hidden'>
	    			<fieldset>
	    				<legend>Receiving</legend>
		    			<div class='tabpane-white tab-pane-wrapper margin-top-20 margin-bottom-10'>
								<ul class="nav nav-tabs">
								    <li role="presentation" class="active" data-pane='#dashboard-rcv-approval-pane'><a>Pending Approvals</a></li>
									<li role="presentation" data-pane='#dashboard-rcv-history-pane'><a>Approval History</a></li>
								</ul>
								<div class='tab-panes bg-white'>
									<div class='pane active' id='dashboard-rcv-approval-pane'>
										<div class="pane-content">

											<div class="tablewrapper-sm">
									    			<table class='table table-striped table-condensed table-bordered no-side-borders mytable table-hover' cellspacing='0' id='rcvpendingapprovaltable' style='width:100%'>
																			<thead>
																				<tr>
																					<th class='text-center'>LAST UPDATE</th>
																					<th>RECEIVING NUMBER</th>
																					<th>PO NUMBER</th>
																					<th>SUPPLIER</th>
																					<th>ACTUAL DATE DELIVERED</th>
																					<th>RECEIVED BY</th>
																					<th class='text-center'>DATE RECEIVED</th>
																				</tr>
																			</thead>
																			<tbody>
																				
																				<?php
																					$query = "select date_flag, receiving_number, reference_po_number, supplier.supplier_name, actual_delivery_date, received_date, concat(user.first_name,' ',user.last_name) as rcvd_by  from receiving_header 
																					left join supplier on supplier.id=receiving_header.supplier_id
																					left join user on user.id=receiving_header.received_by
																					left join currency on currency.id=receiving_header.currency
																							  where receiving_status='IN REVIEW' and
																							        (
																							         (first_approver='$userid' and flag='1') or 
																							         (second_approver='$userid' and flag='2')
																							        )
																							  order by date_flag desc LIMIT 300";
																					
																					$rs = query($query);
																					$rscount = getNumRows($rs);
																					while($obj=fetch($rs)){
																						$last_update = $obj->date_flag;
																						$rcv_no = $obj->receiving_number;
																						$po = $obj->reference_po_number;
																						$supplier_name = $obj->supplier_name;
																						$actualdate = formatDate($obj->actual_delivery_date);
																						$rcvdate = formatDate2($obj->received_date);
																						$rcvby = strUtfEncode($obj->rcvd_by);
																						$date = $obj->date_flag;
																						$date = date('M d, Y', strtotime($date));
																						$last_update = date('M d, Y h:i:s A', strtotime($last_update));
																						$boxcolor="<div class='regular'><div class='regularinner'>";
																						
																						echo "<tr class='tablerow clickable pointer approvalrow' rcv='$rcv_no'>
																								<td class='text-center'>$last_update</td>
																								<td>$rcv_no</td>
																								<td>$po</td>
																								<td>$supplier_name</td>
																								<td>$actualdate</td>
																								<td>$rcvby</td>
																								<td>$rcvdate</td>
																							  </tr>";
																					}
																				?>
																			</tbody>
													</table>
											</div>

										</div>
									</div>
									<div class='pane' id='dashboard-rcv-history-pane'>
										<div class="pane-content">

											<div class="tablewrapper-sm">
																<table class='table table-striped table-condensed table-bordered no-side-borders mytable table-hover' cellspacing='0' id='rcvapprovalhistorytable' style='width:100%'>
																	<thead>
																		<tr>
																			<th class='text-center'>RECEIVING NUMBER</th>
																			<th>REVISION NO.</th>
																			<th>APPROVAL AS</th>
																			<th class='text-center'>ACTION</th>
																			<th>REMARKS</th>
																			<th class='text-center'>DATE APPROVED</th>
																		</tr>
																	</thead>
																	<tbody id='rcvhistory'>
																		<?php 
																			$query = "select receiving_number, action, rcv_flag, `rcv_approval_remarks`, `date`, `rcv_approval_flag`  from receiving_approval_history order by date desc limit 100";
																				$rs = query($query);
																				while($obj=fetch($rs)){
																					$rcvno = $obj->receiving_number;
																					$action = $obj->action;
																					$revnum = $obj->rcv_flag;
																					$reason = strUtfEncode($obj->rcv_approval_remarks);
																					$date = formatDate4($obj->date);
																					switch ($obj->rcv_approval_flag){
																							case '1':
																								$rcvapprovaltype = 'Logistics Manager';
																								break;
																							case '2':
																								$rcvapprovaltype = 'General Manager';
																								break;
																							default:
																								$rcvapprovaltype = '';
																								break;
																					}
																					echo "<tr class='tablerow rcvrowlog pointer' rcvnumber='$rcvno' rcvflag='$revnum'>
																							<td class='text-center'>$rcvno</td>
																							<td>$revnum</td>
																							<td>$rcvapprovaltype</td>
																							<td class='text-center'>$action</td>
																							<td>$reason</td>
																							<td class='text-center'>$date</td>
																						  </tr>";
																				}

																		?>
																		
																	</tbody>
																</table>
											</div>

										</div>
									</div>
								</div>
				    	</div>
				    </fieldset>
				</div>

				<div id='hold-supplier-row' class='hidden'>
	    			<fieldset>
	    				<legend>Hold Supplier's Invoice</legend>
		    			<div class='tabpane-white tab-pane-wrapper margin-top-20 margin-bottom-10'>
								<ul class="nav nav-tabs">
								    <li role="presentation" class="active" data-pane='#dashboard-hold-supplier-pane'><a>On-hold PO</a></li>
									<li role="presentation" data-pane='#dashboard-hold-supplier-history-pane'><a>Hold Supplier's History</a></li>
								</ul>
								<div class='tab-panes bg-white'>
									<div class='pane active' id='dashboard-hold-supplier-pane'>
										<div class="pane-content">

											<div class="tablewrapper-sm">
									    			<table class='table table-striped table-condensed table-bordered no-side-borders mytable table-hover' cellspacing='0' id='holdpendingapprovaltable' style='width:100%'>
																			<thead>
																				<tr>
																					<th>PO NUMBER</th>
																					<th>SUPPLIER NAME</th>
																					<th>REMARKS</th>
																					<th>HOLD BY</th>
																					<th>HOLD DATE</th>
																				</tr>
																			</thead>
																			<tbody>
																				
																				<?php
																					$query = "SELECT a.*, b.supplier_id, c.supplier_name, CONCAT(d.first_name, ' ', d.last_name) as hold_by
																						 FROM `hold_invoice_remarks` a
																						inner join purchase_order_header b on b.order_number = a.reference_po_number
																						left join supplier c on c.id = b.supplier_id
																						inner join user d on d.id = a.user_id
																						where b.order_status='HOLD'";
																					
																					$rs = query($query);
																					$rscount = getNumRows($rs);
																					while($obj=fetch($rs)){
																						$pkid = $obj->pkid;
																						$reference_po_number = $obj->reference_po_number;
																						$supplier_name = $obj->supplier_name;
																						$remarks = $obj->remarks;
																						$hold_by = $obj->hold_by;
																						$lastupdate = $obj->lastupdate;
																						$lastupdate = date('M d, Y', strtotime($lastupdate));

																						$boxcolor="<div class='regular'><div class='regularinner'>";
																						
																						echo "<tr class='tablerow clickable pointer holdsupplierinvoice'>
																								<td>$reference_po_number</td>
																								<td>$supplier_name</td>
																								<td>$remarks</td>
																								<td>$hold_by</td>
																								<td>$lastupdate</td>
																							  </tr>";
																					}
																				?>
																			</tbody>
													</table>
											</div>

										</div>
									</div>
									<div class='pane' id='dashboard-hold-supplier-history-pane'>
										<div class="pane-content">

											<div class="tablewrapper-sm">
																<table class='table table-striped table-condensed table-bordered no-side-borders mytable table-hover' cellspacing='0' id='holdsupplierhistorytable' style='width:100%'>
																	<thead>
																		<tr>
																			<th>PO NUMBER</th>
																			<th>SUPPLIER NAME</th>
																			<th>REMARKS</th>
																			<th>HOLD BY</th>
																			<th>HOLD DATE</th>
																		</tr>
																	</thead>
																	<tbody id='holdhistory'>
																		<?php 
																			$query = "SELECT a.*, b.supplier_id, c.supplier_name, CONCAT(d.first_name, ' ', d.last_name) as hold_by
																						 FROM `hold_invoice_remarks` a
																						inner join purchase_order_header b on b.order_number = a.reference_po_number
																						left join supplier c on c.id = b.supplier_id
																						inner join user d on d.id = a.user_id
																						LIMIT 0,50";
																					
																					$rs = query($query);
																					$rscount = getNumRows($rs);
																					while($obj=fetch($rs)){
																						$pkid = $obj->pkid;
																						$reference_po_number = $obj->reference_po_number;
																						$supplier_name = $obj->supplier_name;
																						$remarks = $obj->remarks;
																						$hold_by = $obj->hold_by;
																						$lastupdate = $obj->lastupdate;
																						$lastupdate = date('M d, Y', strtotime($lastupdate));

																						$boxcolor="<div class='regular'><div class='regularinner'>";
																						
																						echo "<tr class='tablerow clickable pointer holdsupplierinvoice'>
																								<td>$reference_po_number</td>
																								<td>$supplier_name</td>
																								<td>$remarks</td>
																								<td>$hold_by</td>
																								<td>$lastupdate</td>
																							  </tr>";
																					}

																		?>
																		
																	</tbody>
																</table>
											</div>

										</div>
									</div>
								</div>
				    	</div>
				    </fieldset>
				</div>

				<div id='item-approval-row' class='hidden'>
	    			<fieldset>
	    				<legend>Item</legend>
		    			<div class='tabpane-white tab-pane-wrapper margin-top-20 margin-bottom-10'>
								<ul class="nav nav-tabs">
								    <li role="presentation" class="active" data-pane='#dashboard-item-approval-pane'><a>Pending Approvals</a></li>
									<li role="presentation" data-pane='#dashboard-item-history-pane'><a>Approval History</a></li>
								</ul>
								<div class='tab-panes bg-white'>
									<div class='pane active' id='dashboard-item-approval-pane'>
										<div class="pane-content">

											<div class="tablewrapper-sm">
									    			<table class='table table-striped table-condensed table-bordered no-side-borders mytable table-hover' cellspacing='0' id='itempendingapprovaltable' style='width:100%'>
																			<thead>
																				<tr>
																					<th class='text-center'>DATE CREATED</th>
																					<th class='text-center'>CREATED BY</th>
																					<th>ITEM CODE</th>
																					<th>ITEM NAME</th>
																					<th>DESCRIPTION</th>
																					<th>UNIT PRICE</th>
																					<th>SUPPLIER NAME</th>
																				</tr>
																			</thead>
																			<tbody>
																				
																				<?php
																					$query = "SELECT a.*, c.supplier_name, CONCAT(d.first_name, ' ', d.last_name) as created_by FROM item a 
																					
																					left join item_supplier b on b.item_id = a.id
																					left join supplier c on c.id = b.supplier_id
																					inner join user d on d.id = a.created_by_user_id
																					WHERE a.app_status ='1'";
																					
																					$rs = query($query);
																					$rscount = getNumRows($rs);
																					while($obj=fetch($rs)){
																						$id = $obj->id;
																						$created_date = $obj->created_date;
																						$created_by = $obj->created_by;
																						$item_code = $obj->item_code;
																						$item_name = $obj->item_name;
																						$long_description = $obj->long_description;
																						$unit_price = $obj->unit_price;
																						$supplier_name = $obj->supplier_name;
																						$created_date = date('M d, Y', strtotime($created_date));

																						$boxcolor="<div class='regular'><div class='regularinner'>";
																						
																						echo "<tr class='tablerow clickable pointer itemapprovalrow' item_app='$id'>
																								<td class='text-center'>$created_date</td>
																								<td class='text-center'>$created_by</td>
																								<td>$item_code</td>
																								<td>$item_name</td>
																								<td>$long_description</td>
																								<td>$unit_price</td>
																								<td>$supplier_name</td>
																							  </tr>";
																					}
																				?>
																			</tbody>
													</table>
											</div>

										</div>
									</div>
									<div class='pane' id='dashboard-item-history-pane'>
										<div class="pane-content">

											<div class="tablewrapper-sm">
																<table class='table table-striped table-condensed table-bordered no-side-borders mytable table-hover' cellspacing='0' id='itemapprovalhistorytable' style='width:100%'>
																	<thead>
																		<tr>
																			<th>ITEM CODE</th>
																			<th>ITEM NAME</th>
																			<th>DESCRIPTION</th>
																			<th>UNIT PRICE</th>
																			<th>SUPPLIER NAME</th>
																			<th class='text-center'>ACTION</th>
																			<th>REMARKS</th>
																			<th class='text-center'>DATE</th>
																		</tr>
																	</thead>
																	<tbody id='itemhistory'>
																		<?php 
																			$query = "SELECT a.id, a.item_code, a.item_name, a.long_description,a.unit_price, e.unit_of_measure_code, e.description, c.supplier_name, d.action, d.reason_for_rejection, d.date
																				FROM item a 
																				left join item_supplier b on b.item_id = a.id
																				left join supplier c on c.id = b.supplier_id
																				left join item_approval_history d on d.fkid_item = a.id
																				inner join unit_of_measure e on e.id = a.unit_of_measure_id
																				WHERE a.app_status ='2' or a.app_status ='3'";
																					
																				$rs = query($query);
																				while($obj=fetch($rs)){
																					$id = $obj->id;
																					$item_code = $obj->item_code;
																					$item_name = $obj->item_name;
																					$long_description = $obj->long_description;
																					$description = $obj->description;
																					$unit_price = $obj->unit_price;
																					$supplier_name = $obj->supplier_name;
																					$action = $obj->action;
																					$reason_for_rejection = $obj->reason_for_rejection;
																					$date = $obj->date;
																					$date = formatDate4($obj->date);
																					

																					echo "<tr class='tablerow rcvrowlog pointer' itemid='$id'>
																							<td>$item_code</td>
																							<td>$item_name</td>
																							<td>$description</td>
																							<td>$unit_price</td>
																							<td>$supplier_name</td>
																							<td class='text-center'>$action</td>
																							<td>$reason_for_rejection</td>
																							<td>$date</td>
																						  </tr>";
																				}

																		?>
																		
																	</tbody>
																</table>
											</div>

										</div>
									</div>
								</div>
				    	</div>
				    </fieldset>
				</div>


			</div>
			
			<div class="col-lg-3">

				<div class="panel panel-default">
					<div class="panel-heading">
						Quick Links
					</div>
			    	<div class="panel-body" >
			    		<?php 
			    			$rs = query("select COUNT(order_number) as numrows from purchase_order_header where order_status='NEW' limit 100");
				    		// $numrows = getNumRows($rs);
							if($obj=fetch($rs)){
								$numrows = $obj->numrows;														
							}
			    		?>
						<ul class='actions-navtabs' style='list-style-type:none'>
							<li class='actql-viewpr hidden'><i class='fa fa-file-text'></i><a>View Purchase Requisitions</a></li>
							<li class='actql-newpr hidden'><i class='fa fa-file'></i><a>New Purchase Requisition</a></li>
							<?php if($isPOHead!='hidden'){
									echo "<li class='actql-assignpo'><i class='fa fa-user-plus'></i><a>Assign PO (".$numrows.")</a></li>";
								  } 
							?>
							<li class='actql-viewpo hidden'><i class='fa fa-file-text'></i><a>View Purchase Order</a></li>
							<!-- <li class='actql-viewpr-history'><i class='fa fa-list'></i><a>Purchase Requisition History</a></li>
							<li class='actql-viewpo-history'><i class='fa fa-list-alt'></i><a>Purchase Order History</a></li>-->
							<li class='actql-changepwd'><i class='fa fa-gear'></i><a>Change Password</a></li>
						</ul>
					</div>
				</div>
				
				<div class="panel panel-default">
			    		<div class="panel-body text-center" >
			    			<div style='display:table;'>
				    			<div class="col-xs-12" style='display:table-cell; vertical-align:middle'>
			    					<img class='img img-responsive' src="../resources/img/default-user.png" height='0px;' style='margin:auto; padding:30px'>
			    				</div>	
		    					<div class="col-xs-12" style='display:table; height:inherit; vertical-align:middle'>
		    						<div style='vertical-align:middle; display:table-cell'>
		    							<div class='dashname' style='font-size:16px;'>
		    								<?php echo strtoupper(getuserName($userid)); ?>
		    							</div>
		    							<div class='dashdept' style='font-size:11px; font-weight:600; color:#0F3057; letter-spacing:1px;'>
		    								<?php echo strtoupper(getDeptName($userid)); ?>
		    							</div>
		    							<div class='dashemail' style='font-size:11px'>
		    								
		    								<?php if(getUserEmail($userid)==''){
		    									echo "<span style='font:italic 600 12px sans-serif; color:#aaa'>Email not available</span>";
		    								}
		    								else{
		    									echo strtolower(getUserEmail($userid)); 
		    								}
		    								?>
		    							</div><br><br>
		    						</div>	
		    					</div>
		    				</div>
		    			</div>
		    	</div>

		    	

		
		    	<div id='po-overdue-rows' class='hidden'>
	    			
		    			<div class="panel panel-default">
				    		<div class="panel-heading">Overdue</div>
				    		<div class="panel-body">
				    			<div class="tablewithpagintaion">
									<div class='table-wrapper no-margin-top' style='height:200px'>
										<div class="table-responsive">
													<table class='table table-striped table-bordered no-side-borders table-condensed mytable table-hover' cellspacing='0' id='poapprovalhistorytable'>
														<thead>
															<tr>
																<th class='text-center'>PO NUMBER</th>
																<th class='text-center'>DATE NEEDED</th>
															</tr>
														</thead>
														<tbody id='pohistory'>
															<?php 
																$datetoday = date('Y-m-d');
																$rs = query("select * from purchase_order_header, supplier 
																			 where supplier.id=supplier_id and
																			 	   (order_status='UNSERVED' or order_status='PARTIAL DELIVERY') and 
																				   date(expected_date) < '$datetoday' LIMIT 0,1");
																if(getNumRows($rs)<1){
																	echo "<tr>
																				<td colspan='10' class='noresult'>No Results Found</td>
																				
																		</tr>";
																}
																while($obj=fetch($rs)){
																	$dateneeded = formatDate($obj->expected_date,'M-d-Y');

																	$orderNum = $obj->order_number;
																	$referenceprnum = $obj->reference_requisition_number;

																	$orderStatus = $obj->order_status;
																	$supplier = strUtfEncode($obj->supplier_name);
																	$supplierAddr = strUtfEncode($obj->address);
																	$remarks = strUtfEncode($obj->po_remarks);
																	$expdelivery = formatDate($obj->delivery_date);
																	$terms = getPaymentTermCode($obj->terms);
																	$shipvia = $obj->ship_via;
																	$mode =getModeOfShipment($obj->mode_of_shipment);
																	$orderdate = formatDate($obj->order_date);
																	$pototal = getPoGrandTotal($orderNum);
																	$attn = strUtfEncode($obj->supplier_contact);
																	$assignedto = getUserName($obj->assigned_to);
																	$postedby = getUserName($obj->posted_by);
																	$dateneeded = formatDate($obj->expected_date);
																	$lastapprovedby = getLastPoApprover($orderNum);
																	$currency = getPoCurrency($orderNum);
																	echo "<tr class='pointer ponotificationrow' lastapprovedby='$lastapprovedby' dateneeded='$dateneeded' assignedto='$assignedto' postedby='$postedby' mode='$mode' attn='$attn' terms='$terms' currency='$currency' refpr='$referenceprnum' shipvia='$shipvia' ponumber='$orderNum' status='$orderStatus' pototal='$pototal' supplier='$supplier' supplierAddr='$supplierAddr' expdelivery='$expdelivery' orderdate='$orderdate' remarks='$remarks'>
																				<td class='text-center' class='text-center'>$orderNum".appendPoRevSuffix($orderNum)."</td>
																				<td class='text-center'>$dateneeded</td>
																		</tr>";
																}
															?>
															
														</tbody>
													</table>
										</div>
									</div>

								</div>
				    		</div>
				    	</div>
				</div>
				<div id='po-today-rows' class='hidden'>
				    	<div class="panel panel-default">
				    		<div class="panel-heading">Expected Today</div>
				    		<div class="panel-body">
				    			<div class="tablewithpagintaion">
									<div class='table-wrapper no-margin-top' style='height:200px'>
										<div class="table-responsive">
													<table class='table table-striped table-condensed mytable table-hover' cellspacing='0' id='poapprovalhistorytable'>
														<thead>
															<tr>
																<th class='text-center'>PO NUMBER</th>
																<th class='text-center'>DATE NEEDED</th>
															</tr>
														</thead>
														<tbody id='expectedpodeliveries'>
															<?php 
																$datetoday = date('Y-m-d');
																$rs = query("select * from purchase_order_header, supplier 
																			 where supplier.id=supplier_id and
																			 	   (order_status='UNSERVED' or order_status='PARTIAL DELIVERY') and 
																				   date(expected_date) = '$datetoday'");
																if(getNumRows($rs)<1){
																	echo "<tr>
																				<td colspan='10' class='noresult'>No Results Found</td>
																				
																		</tr>";
																}
																while($obj=fetch($rs)){
																	$dateneeded = formatDate($obj->expected_date,'M-d-Y');

																	$orderNum = $obj->order_number;
																	$referenceprnum = $obj->reference_requisition_number;
																	$orderStatus = $obj->order_status;
																	$supplier = $obj->supplier_name;
																	$supplierAddr = $obj->address;
																	$remarks = $obj->po_remarks;
																	$expdelivery = formatDate($obj->delivery_date);
																	$terms = getPaymentTermCode($obj->terms);
																	$shipvia = $obj->ship_via;
																	$mode =getModeOfShipment($obj->mode_of_shipment);
																	$orderdate = formatDate($obj->order_date);
																	$pototal = getPoGrandTotal($orderNum);
																	$attn = $obj->supplier_contact;
																	$assignedto = getUserName($obj->assigned_to);
																	$postedby = getUserName($obj->posted_by);
																	$dateneeded = formatDate($obj->expected_date);
																	$lastapprovedby = getLastPoApprover($orderNum);
																	$currency = getPoCurrency($orderNum);
																	echo "<tr class='pointer ponotificationrow' lastapprovedby='$lastapprovedby' dateneeded='$dateneeded' assignedto='$assignedto' postedby='$postedby' mode='$mode' attn='$attn' terms='$terms' currency='$currency' refpr='$referenceprnum' shipvia='$shipvia' ponumber='$orderNum' status='$orderStatus' pototal='$pototal' supplier='$supplier' supplierAddr='$supplierAddr' expdelivery='$expdelivery' orderdate='$orderdate' remarks='$remarks'>
																				<td class='text-center' class='text-center'>$orderNum".appendPoRevSuffix($orderNum)."</td>
																				<td class='text-center'>$dateneeded</td>
																		</tr>";
																}
															?>
															
														</tbody>
													</table>
										</div>
									</div>

								</div>
				    		</div>
				    	</div>
	    		</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="poblowup">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal">
				<div class="modal-header">
					<div class='page-title'>
						Purchase Order
						<button class="close" data-dismiss="modal">&times;</button>
					</div>
				</div>
				<div class="modal-body">
								<div class='tabpane-white margin-top-20 margin-bottom-10' id='generalinfosection'>
										<ul class="nav nav-tabs">
										  <li role="presentation" class="active" data-pane='#po-geninfo' id='geninfotab'><a>General Information</a></li>
										</ul>
										<div class='tab-panes'>
											<div class='pane active' id='po-geninfo'>
												<div class="pane-content">
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label col-sm-4">Order No.</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='ponumber'></span>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4">Reference PR No.</label>
															<div class="col-sm-8">
																<span class='form-control-static' id='prnumber'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Supplier</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='vendor'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Supplier Address</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='supplierAddress'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Attn</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='attn'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Currency</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='pocurrency'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Remarks</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='remarks'></span>
															</div>
														</div>
														
													</div>
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label col-sm-4">Status</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='orderstatus'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Date Needed</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='dateneeded'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Order Date</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='orderdate'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Terms</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='paymentterms'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Ship Via</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='shipvia'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Mode of Shipment</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='shipmentmode'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Assigned to</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='assignedto'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Posted by</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='postedby'></span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Last Approved By</label>
															<div class="col-sm-8">
																 <span class='form-control-static' id='lastapprovedby'></span>
															</div>
														</div>
														
														


													</div>

												</div>
											</div>
											

										</div>
								</div>
								<br>
								<div class='tabpane-white margin-top-20 margin-bottom-10' id='polineitems'>
										<ul class="nav nav-tabs">
										  <li role="presentation" class="active" data-pane='#lineitems'><a>Line Items</a></li>
										  <li role="presentation" data-pane='#receivinghistory'><a>Receiving History</a></li>
										</ul>
										<div class='tab-panes'>
											<div class='pane active' id='lineitems'>
												<div class="pane-content">
													<div class='tablewithbuttons'>
														<div class='table-wrapper' style='height:200px'>
															<table class='table table-striped table-condensed table-bordered mytable' cellspacing='0'>
																<thead>
																	<tr>
																		<th class='text-center'>LINE NO.</th>
																		<th>ITEM CODE</th>
																		<th>NAME</th>
																		<th>DESCRIPTION</th>
																		<th class='text-center'>CLASSF'N CODE</th>
																		<th class='text-center'>UOM</th>
																		<th class='text-right'>QTY</th>
																		<th class='text-right'>RECEIVED</th>
																		<th class='text-right'>BALANCE</th>
																		<th class='text-right'>UNIT PRICE</th>
																		<th class='text-right'>AMOUNT</th>
																		

																	</tr>
																</thead>
																<tbody id='poitems'>		
																</tbody>
															</table>
														</div>
														<div class="row">
															<div class="col-md-offset-7 col-md-5" style='margin-top: 10px; margin-bottom:10px;'>
																<form class='form-horizontal'>
																	<div class="form-group" >
																		<label class='control-label col-xs-5'>Total Amount</label> 
																		<div class="col-xs-7">
																			<div class="input-group">
                                                                                <div class="input-group-addon"><b class='poblowupcurrencyaddon'></b></div>
																				<input type='text' class='form-input form-control text-right disabled' id='grandtotal' readonly>
																			</div>
																		</div>
																	</div>			
																</form>
															</div>
														</div>
													</div>
													
												</div>
											</div>
											<div class='pane' id='receivinghistory'>
												<div class="pane-content">
													<div class='tablewithbuttons'>
														<div class='table-wrapper' style='height:200px'>
															<table class='table table-striped table-condensed table-bordered mytable' cellspacing='0'>
																<thead>
																	<tr>
																		<th></th>
																		<th class='text-center'>LINE NO.</th>
																		<th>ITEM CODE</th>
																		<th>NAME</th>
																		<th class='text-right'>PRICE</th>
																		<th class='text-center'>UOM</th>
																		<th class='text-right'>QTY</th>
																		<th>RECEIVED BY</th>
																		<th class='text-center'>DATE RECEIVED</th>
																	</tr>
																</thead>
																<tbody id='poitemsreceived'></tbody>
															</table>
														</div>
													</div>
													
												</div>
											</div>
										</div>
								</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	$.post(server+'dashboard.php',{Ldou3in48Fbkuaf209janr:'Kdeiah3jsu40j20s00ne'},function(data){
		var response = data.trim();
		if(response=='po'){
			$('#dashboardquicklinks').removeClass('hidden');
			$('#dashboardquicklinks .qlpohistory').closest('.col-md-3').removeClass('hidden');
			$('#dashboardquicklinks .qlpoapproval').closest('.col-md-3').removeClass('hidden');
			$('#dashboardquicklinks .qlrcvapproval').closest('.col-md-3').removeClass('hidden');
			$('#dashboardquicklinks .qlholdinvoice').closest('.col-md-3').removeClass('hidden');
			$('#dashboardquicklinks .qlappstatus').closest('.col-md-3').removeClass('hidden');
			$('#dashboardquicklinks .qlrcvhistory').closest('.col-md-3').removeClass('hidden');

			$('#purchase-order-row').removeClass('hidden');
			$('#receiving-row').removeClass('hidden');
			$('#item-approval-row').removeClass('hidden');
			$('#hold-supplier-row').removeClass('hidden');

			$('#po-overdue-rows').removeClass('hidden');
			$('#po-today-rows').removeClass('hidden');

			$('#popendingapprovaltable').DataTable({
				 			// aaSorting: [[ 1, "desc" ]], 
							pagingType: "full_numbers" //numbers, simple, simple_numbers, full, full_numbers
			});
			$('#poapprovalhistorytable').DataTable({
							 			aaSorting: [[ 5, "desc" ]],
										pagingType: "full_numbers" //numbers, simple, simple_numbers, full, full_numbers
			});
			$('#rcvpendingapprovaltable').DataTable({
				 			// aaSorting: [[ 1, "desc" ]], 
							pagingType: "full_numbers" //numbers, simple, simple_numbers, full, full_numbers
			});

			// $('#itempendingapprovaltable').DataTable({
			// 	 			// aaSorting: [[ 1, "desc" ]], 
			// 				pagingType: "full_numbers" //numbers, simple, simple_numbers, full, full_numbers
			// });

			// $('#holdpendingapprovaltable').DataTable({
			// 		 			aaSorting: [[ 5, "desc" ]],
			// 					pagingType: "full_numbers" //numbers, simple, simple_numbers, full, full_numbers
			// });

		}
		else if(response=='pr'){
			$('#purchase-requisition-row').removeClass('hidden');
			$('#dashboardquicklinks').removeClass('hidden');
			$('#dashboardquicklinks .qlprhistory').closest('.col-md-3').removeClass('hidden');
			$('#dashboardquicklinks .qlprapproval').closest('.col-md-3').removeClass('hidden');

			$('#prpendingapprovaltable').DataTable({
		 			// aaSorting: [[ 1, "desc" ]], 
					pagingType: "full_numbers" //numbers, simple, simple_numbers, full, full_numbers
			});
			$('#prapprovalhistorytable').DataTable({
				 			aaSorting: [[ 6, "desc" ]],
							pagingType: "full_numbers" //numbers, simple, simple_numbers, full, full_numbers
			});
			$('#prinfotable').DataTable({
				 			aaSorting: [[ 1, "desc" ]],
							pagingType: "full_numbers" //numbers, simple, simple_numbers, full, full_numbers
			});
			
			$('#po-overdue-rows').removeClass('hidden');
			$('#po-today-rows').removeClass('hidden');	
		}
		else if(response=='both'){
			$('#purchase-order-row').removeClass('hidden');
			$('#purchase-requisition-row').removeClass('hidden');
			$('#receiving-row').removeClass('hidden');
			$('#item-approval-row').removeClass('hidden');
			$('#hold-supplier-row').removeClass('hidden');
			$('#po-overdue-rows').removeClass('hidden');
			$('#po-today-rows').removeClass('hidden');

			$('#dashboardquicklinks').removeClass('hidden');
			$('#dashboardquicklinks .qlpohistory').closest('.col-md-3').removeClass('hidden');
			$('#dashboardquicklinks .qlpoapproval').closest('.col-md-3').removeClass('hidden');
			$('#dashboardquicklinks .qlprhistory').closest('.col-md-3').removeClass('hidden');
			$('#dashboardquicklinks .qlprapproval').closest('.col-md-3').removeClass('hidden');
			$('#dashboardquicklinks .qlrcvapproval').closest('.col-md-3').removeClass('hidden');
			$('#dashboardquicklinks .qlholdinvoice').closest('.col-md-3').removeClass('hidden');
			$('#dashboardquicklinks .qlappstatus').closest('.col-md-3').removeClass('hidden');

			$('#dashboardquicklinks .qlrcvhistory').closest('.col-md-3').removeClass('hidden');
			$('.actql-viewpr, .actql-newpr, .actql-viewpo').removeClass('hidden');

			$('#prpendingapprovaltable').DataTable({
		 			// aaSorting: [[ 1, "desc" ]], 
					pagingType: "full_numbers" //numbers, simple, simple_numbers, full, full_numbers
			});
			$('#popendingapprovaltable').DataTable({
				 			// aaSorting: [[ 1, "desc" ]], 
							pagingType: "full_numbers" //numbers, simple, simple_numbers, full, full_numbers
			});
			
			$('#prapprovalhistorytable').DataTable({
				 			aaSorting: [[ 6, "desc" ]],
							pagingType: "full_numbers" //numbers, simple, simple_numbers, full, full_numbers
			});
			$('#poapprovalhistorytable').DataTable({
				 			aaSorting: [[ 5, "desc" ]],
							pagingType: "full_numbers" //numbers, simple, simple_numbers, full, full_numbers
			});

			// $('#holdpendingapprovaltable').DataTable({
			// 		 			aaSorting: [[ 5, "desc" ]],
			// 					pagingType: "full_numbers" //numbers, simple, simple_numbers, full, full_numbers
			// });

			$('#prinfotable').DataTable({
				 			aaSorting: [[ 1, "desc" ]],
							pagingType: "full_numbers" //numbers, simple, simple_numbers, full, full_numbers
			});
		}
		else if(response=='requisitioner'){
			$('#requisitioner-row').removeClass('hidden');
			$('.actql-viewpr, .actql-newpr').removeClass('hidden');
			
			$('#po-overdue-rows').removeClass('hidden');
			$('#po-today-rows').removeClass('hidden');
		}
		else if(response=='purchaser'){
			$('#purchaser-row').removeClass('hidden');
			$('.actql-viewpo').removeClass('hidden');
			
			$('#po-overdue-rows').removeClass('hidden');
			$('#po-today-rows').removeClass('hidden');
		} else {
			$('#requisitioner-row').removeClass('hidden');
			$('.actql-viewpr, .actql-newpr').removeClass('hidden');
			
			$('#po-overdue-rows').removeClass('hidden');
			$('#po-today-rows').removeClass('hidden');
		}
	});
	
	
	


     var time = new Date().getTime();
     $(document.body).bind("mousemove keypress", function(e) {
         time = new Date().getTime();
     });

     function refresh() {
         if(new Date().getTime() - time >= 60000) 
             window.location.reload(true);
         else 
             setTimeout(refresh, 1000);
     }
     var windowRefresh = setTimeout(refresh, 1000);


		
</script>