 <!-- Google Font: Source Sans Pro -->
 <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
 <!-- Font Awesome Icons -->
 <link rel="stylesheet" href="<?= base_url() ?>plugins/fontawesome-free/css/all.min.css">
 <!-- IonIcons -->
 <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
 <script src="<?= base_url() ?>dist/js/pages/dashboard3.js"></script>

 <!-- Theme style -->
 <link rel="stylesheet" href="<?= base_url() ?>dist/css/adminlte.min.css">
 <style>
 	body {
 		font-family: 'none';
 	}
 </style>
 <div class="content-wrapper">
 	<!-- Content Header (Page header) -->
 	<div class="content-header">
 		<div class="container-fluid">
 			<div class="row mb-2">
 				<div class="col-sm-6">
 					<h1 class="m-0">Dashboard</h1>
 				</div><!-- /.col -->
 				<div class="col-sm-6">
 					<ol class="breadcrumb float-sm-right">
 						<li class="breadcrumb-item"><a href="#">Home</a></li>
 						<li class="breadcrumb-item active">Dashboard </li>
 					</ol>
 				</div><!-- /.col -->
 			</div><!-- /.row -->
 		</div><!-- /.container-fluid -->
 	</div>
 	<!-- /.content-header -->

 	<!-- Main content -->
 	<section class="content">
 		<div class="container-fluid">
 		    		<?php
		
				$this->db->select('email_body');
				$this->db->from('sms_reminder');
				$this->db->order_by('id','desc');
				$this->db->where('comp_id',$this->session->userdata('comp_id'));
				$notice=$this->db->get()->row();
				$sms_blocked_status = $this->user_model->getRow('inventory_billing', ['sms_blocked_status' => "Disable", 'id' => $this->session->userdata('comp_id')]);

				if ($sms_blocked_status) {
				?>
 			
 				<marquee width="100%" id="notification" style="/* background-color: brown; */background: white;color: red;">
 					<a class="dropdown-item">
 						<?= $notice->email_body ?>
 					</a>
 				</marquee>

 			<?php
				}
				?>
 			<!-- Small boxes (Stat box) -->
 			<div class="row">



 				<div class="col-lg-3 col-6">
 					<a href="<?= base_url() ?>invent/sale">
 						<div class="card text-center">
 							<div class="card-header">
								
 								<p>CREATE SALE</p>
 							</div>

 							<?php
								$current_date = date('Y-m-d');
								$first_day_this_month = date('Y-m-01');
								$tota_sale = 0;
								$this->db->select('COUNT(*) as totl');
								$this->db->select('SUM(total) as creditTotal');
								$this->db->select('SUM(taxableAmount) as taxbleAmount');
								$this->db->select('SUM(totalTax) as taxAmt');

								$user_id = $this->session->userdata('user_id');
								$comp_id = $this->session->userdata('comp_id');
								$financial_year = $this->session->userdata('financial_year');
								$this->db->where('user_id', $user_id);
								$this->db->where('comp_id', $comp_id);
								$this->db->where('status', 1);
								$this->db->where('financial_year', $financial_year);
								$this->db->where('invoiceDate >=', $first_day_this_month);
								$this->db->where('invoiceDate <=', $current_date);
								$q = $this->db->get('sales');
								$r = $q->row();
								$tota_sale = $r->totl;
								?>
 							<div class="card-body text-dark">
 								<p title="Total Sale of this month">Total Sale:<?= $tota_sale ?></p>
 								<span style="margin-left: 10px;text-align:left;" title="Taxable Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->taxbleAmount); ?></span>
 								<span style="margin-left: 10px;text-align:center;" title="Tax Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->taxAmt); ?></span>
 								<span style="margin-left: 10px;text-align:right;" title="Total Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->creditTotal); ?></span>
 								<!--<h3 class="text-success"><?= $count ?><i class="far fa-share-square"></i></h3>-->
 							</div>
 						</div>

 					</a>
 				</div>
 				<!-- ./col -->

 				<div class="col-lg-3 col-6">
 					<a href="<?= base_url() ?>invent/purchase">
 						<div class="card text-center ">
 							<div class="card-header">
 								<p>CREATE PURCHASE</p>
 							</div>

 							<?php
								$current_date = date('Y-m-d');
								$first_day_this_month = date('Y-m-01');
								$total_purchase = 0;
								$this->db->select('COUNT(*) as totl');
								$this->db->select('SUM(total) as creditTotal');
								$this->db->select('SUM(taxableAmount) as taxbleAmount');
								$this->db->select('SUM(totalTax) as taxAmt');
								$user_id = $this->session->userdata('user_id');
								$comp_id = $this->session->userdata('comp_id');
								$financial_year = $this->session->userdata('financial_year');
								$this->db->where('user_id', $user_id);
								$this->db->where('comp_id', $comp_id);
								$this->db->where('status', 1);
								$this->db->where('financial_year', $financial_year);
								$this->db->where('invoiceDate >=', $first_day_this_month);
								$this->db->where('invoiceDate <=', $current_date);
								$q = $this->db->get('purchases');
								$r = $q->row();
								$total_purchase = $r->totl;
								?>
 							<div class="card-body text-dark">
 								<p title="Total Sale of this month">Total Purchase:<?= $total_purchase; ?></p>
 								<span style="margin-left: 10px;text-align:left;" title="Taxable Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->taxbleAmount); ?></span>
 								<span style="margin-left: 10px;text-align:center;" title="Tax Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->taxAmt); ?></span>
 								<span style="margin-left: 10px;text-align:right;" title="Total Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->creditTotal); ?></span>
 								<!--<h3 class="text-success"><?= $count ?><i class="far fa-share-square"></i></h3>-->
 							</div>
 						</div>

 					</a>
 				</div>


 				<!-- ./col -->

 				<div class="col-lg-3 col-6">
 					<a href="<?= base_url() ?>invent/estimate">
 						<div class="card text-center">
 							<div class="card-header">
 								<p>CREATE ESTIMATE</p>
 							</div>
 							<?php
								$total_estimate = 0;
								$this->db->select('COUNT(*) as totl');
								$this->db->select('SUM(totalValue) as totalValue');
								$this->db->select('SUM(taxableAmount) as taxableAmount');
								$this->db->select('SUM(totalTax) as taxAmt');
								$this->db->where('user_id', $this->session->userdata('user_id'));
								$this->db->where('comp_id', $this->session->userdata('comp_id'));
								$this->db->where('status', 1);
								$this->db->where('financial_year', $this->session->userdata('financial_year'));
								$this->db->where('invoiceDate >=', date('Y-m-01'));
								$this->db->where('invoiceDate <=', date('Y-m-d'));
								$q = $this->db->get('estimates');
								$r = $q->row();
								$total_estimate = $r->totl;
								?>
 							<div class="card-body text-dark">
 								<p title="Total Sale of this month">Total Estimate: <?= $total_estimate; ?></p>
 								<span style="margin-left: 10px;text-align:left;" title="Taxable Amount">
 								    <i class="fas fa-rupee-sign"></i><?= number_format($r->taxableAmount); ?>
 							    </span>
 								<span style="margin-left: 10px;text-align:center;" title="Tax Amount">
 								    <i class="fas fa-rupee-sign"></i><?= number_format($r->taxAmt); ?>
 							    </span>
 								<span style="margin-left: 10px;text-align:right;" title="Total Amount">
 								    <i class="fas fa-rupee-sign"></i><?= number_format($r->totalValue); ?>
 							    </span>
 							</div>
 						</div>
 					</a>
 				</div>


 				<!-- ./col -->

 				<div class="col-lg-3 col-6">
 					<a href="<?= base_url() ?>invent/product">
 						<div class="card text-center " style="height:91%">
 							<div class="card-header">
 								<p>CREATE PRODUCT</p>
 							</div>
 							<?php
								$tota_produc = 0;
								$this->db->select('COUNT(*) as totl');
								$user_id = $this->session->userdata('user_id');
								$comp_id = $this->session->userdata('comp_id');
								$this->db->where('user_id', $user_id);
								$this->db->where('comp_id', $comp_id);
								$q = $this->db->get('product');
								$r = $q->row();
								$tota_produc = $r->totl;
								?>
 							<div class="card-body text-dark">
 								<p title="Total Sale of this month">Total Product:<?= $tota_produc ?></p>
 							</div>
 						</div>

 					</a>
 				</div>


 				<div class="col-lg-3 col-6">
 					<a href="<?= base_url() ?>invent/sale_report">
 						<div class="card text-center ">
 							<div class="card-header">
 								<p>Sale Report</p>
 							</div>

 							<?php
								$current_date = date('Y-m-d');
								$first_day_this_month = date('Y-m-01');
								$tota_sale = 0;
								$this->db->select('COUNT(*) as totl');
								$this->db->select('SUM(total) as creditTotal');
								$this->db->select('SUM(taxableAmount) as taxbleAmount');
								$this->db->select('SUM(totalTax) as taxAmt');
								$user_id = $this->session->userdata('user_id');
								$comp_id = $this->session->userdata('comp_id');
								$financial_year = $this->session->userdata('financial_year');
								$this->db->where('user_id', $user_id);
								$this->db->where('comp_id', $comp_id);
								$this->db->where('status', 1);
								$this->db->where('financial_year', $financial_year);
								$this->db->where('invoiceDate >=', $first_day_this_month);
								$this->db->where('invoiceDate <=', $current_date);
								$q = $this->db->get('sales');
								$r = $q->row();
								$tota_sale = $r->totl;
								?>
 							<div class="card-body text-dark">
 								<p title="Total Sale of this month">SALES:<?= $tota_sale ?></p>
 								<span style="margin-left: 10px;text-align:left;" title="Taxable Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->taxbleAmount); ?></span>
 								<span style="margin-left: 10px;text-align:center;" title="Tax Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->taxAmt); ?></span>
 								<span style="margin-left: 10px;text-align:right;" title="Total Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->creditTotal); ?></span>

 							</div>
 						</div>

 					</a>
 				</div>


 				<!-- ./col -->
 				<?php

					?>




 				<div class="col-lg-3 col-6">
 					<a href="<?= base_url() ?>invent/purchase_report">
 						<div class="card text-center ">
 							<div class="card-header">
 								<p>Purchase Report</p>
 							</div>

 							<?php
								$current_date = date('Y-m-d');
								$first_day_this_month = date('Y-m-01');
								$tota_sale = 0;
								$this->db->select('COUNT(*) as totl');
								$this->db->select('SUM(total) as creditTotal');
								$this->db->select('SUM(taxableAmount) as taxbleAmount');
								$this->db->select('SUM(totalTax) as taxAmt');
								$user_id = $this->session->userdata('user_id');
								$comp_id = $this->session->userdata('comp_id');
								$financial_year = $this->session->userdata('financial_year');
								$this->db->where('user_id', $user_id);
								$this->db->where('comp_id', $comp_id);
								$this->db->where('status', 1);
								$this->db->where('financial_year', $financial_year);
								$this->db->where('invoiceDate >=', $first_day_this_month);
								$this->db->where('invoiceDate <=', $current_date);
								$q = $this->db->get('purchases');
								// echo '<pre>';
								// print_r($this->db->last_query());
								// die;
								$r = $q->row();
								$tota_sale = $r->totl;
								?>
 							<div class="card-body text-dark">
 								<p title="Total Sale of this month">Purchase:<?= $tota_sale ?></p>
 								<span style="margin-left: 10px;text-align:left;" title="Taxable Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->taxbleAmount); ?></span>
 								<span style="margin-left: 10px;text-align:center;" title="Tax Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->taxAmt); ?></span>
 								<span style="margin-left: 10px;text-align:right;" title="Total Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->creditTotal); ?></span>

 							</div>
 						</div>

 					</a>
 				</div>


 				<div class="col-lg-3 col-6">
 					<a href="<?= base_url() ?>invent/estimate_report">
 						<div class="card text-center ">
 							<div class="card-header">
 								<p>Estimate Report</p>
 							</div>

 							<?php
								$current_date = date('Y-m-d');
								$first_day_this_month = date('Y-m-01');
								$tota_sale = 0;
								$this->db->select('COUNT(*) as totl');
								$this->db->select('SUM(total) as creditTotal');
								$this->db->select('SUM(taxableAmount) as taxbleAmount');
								$this->db->select('SUM(totalTax) as taxAmt');

								$user_id = $this->session->userdata('user_id');
								$comp_id = $this->session->userdata('comp_id');
								$financial_year = $this->session->userdata('financial_year');
								$this->db->where('user_id', $user_id);
								$this->db->where('comp_id', $comp_id);
								$this->db->where('status', 1);
								$this->db->where('financial_year', $financial_year);
								$this->db->where('invoiceDate >=', $first_day_this_month);
								$this->db->where('invoiceDate <=', $current_date);
								$q = $this->db->get('estimates');
								// echo '<pre>';
								// print_r($this->db->last_query());
								// die;
								$r = $q->row();
								$tota_sale = $r->totl;
								?>
 							<div class="card-body text-dark">
 								<p title="Total Estimate of this month">Estimate:<?= $tota_sale ?></p>
 								<span style="margin-left: 10px;text-align:left;" title="Taxable Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->taxbleAmount); ?></span>
 								<span style="margin-left: 10px;text-align:center;" title="Tax Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->taxAmt); ?></span>
 								<span style="margin-left: 10px;text-align:right;" title="Total Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->creditTotal); ?></span>

 							</div>
 						</div>

 					</a>
 				</div>


 				<div class="col-lg-3 col-6">
 					<a href="<?= base_url() ?>invent/gst_report">
 						<div class="card text-center ">
 							<div class="card-header">
 								<p>GST Report</p>
 							</div>

 							<?php
								$current_date = date('Y-m-d');
								$first_day_this_month = date('Y-m-01');
								$tota_sale = 0;
								$this->db->select('COUNT(*) as totl');
								$this->db->select('SUM(total) as creditTotal');
								$this->db->select('SUM(taxableAmount) as taxbleAmount');
								$this->db->select('SUM(totalTax) as taxAmt');

								$user_id = $this->session->userdata('user_id');
								$comp_id = $this->session->userdata('comp_id');
								$financial_year = $this->session->userdata('financial_year');
								$this->db->where('user_id', $user_id);
								$this->db->where('comp_id', $comp_id);
								$this->db->where('status', 1);
								$this->db->where('financial_year', $financial_year);
								$this->db->where('invoiceDate >=', $first_day_this_month);
								$this->db->where('invoiceDate <=', $current_date);
								$q = $this->db->get('sales');
								// echo '<pre>';
								// print_r($this->db->last_query());
								// die;
								$r = $q->row();
								$tota_sale = $r->totl;
								?>
 							<div class="card-body text-dark">
 								<p title="Total Estimate of this month">GST:<?= $tota_sale ?></p>
 								<span style="margin-left: 10px;text-align:left;" title="Taxable Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->taxbleAmount); ?></span>
 								<span style="margin-left: 10px;text-align:center;" title="Tax Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->taxAmt); ?></span>
 								<span style="margin-left: 10px;text-align:right;" title="Total Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->creditTotal); ?></span>

 							</div>
 						</div>

 					</a>
 				</div>


 				<div class="col-lg-3 col-6">
 					<a href="<?= base_url() ?>sale_recycle">
 						<div class="card text-center ">
 							<div class="card-header">
 								<p>CANCELLED Sales</p>
 							</div>

 							<?php
								$current_date = date('Y-m-d');
								$first_day_this_month = date('Y-m-01');
								$tota_sale = 0;
								$this->db->select('COUNT(*) as totl');
								$this->db->select('SUM(total) as creditTotal');
								$this->db->select('SUM(taxableAmount) as taxbleAmount');
								$this->db->select('SUM(totalTax) as taxAmt');

								$user_id = $this->session->userdata('user_id');
								$comp_id = $this->session->userdata('comp_id');
								$financial_year = $this->session->userdata('financial_year');
								$this->db->where('user_id', $user_id);
								$this->db->where('comp_id', $comp_id);
								$this->db->where('status', 0);
								$this->db->where('financial_year', $financial_year);
								// $this->db->where('invoiceDate >=', $first_day_this_month);
								// $this->db->where('invoiceDate <=', $current_date);
								$q = $this->db->get('sales');
								// echo '<pre>';
								// print_r($this->db->last_query());
								// die;
								$r = $q->row();
								$tota_sale = $r->totl;
								?>
 							<div class="card-body text-dark">
 								<p title="Total Cancelled Sales of this month">Cancelled Sales:<?= $tota_sale ?></p>
 								<span style="margin-left: 10px;text-align:left;" title="Taxable Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->taxbleAmount); ?></span>
 								<span style="margin-left: 10px;text-align:center;" title="Tax Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->taxAmt); ?></span>
 								<span style="margin-left: 10px;text-align:right;" title="Total Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->creditTotal); ?></span>

 							</div>
 						</div>

 					</a>
 				</div>


 				<div class="col-lg-3 col-6">
 					<a href="<?= base_url() ?>purchase_recycle">
 						<div class="card text-center ">
 							<div class="card-header">
 								<p>CANCELLED Purchase</p>
 							</div>

 							<?php
								$tota_sale = 0;
								$this->db->select('COUNT(*) as totl');
								$this->db->select('SUM(total) as creditTotal');
								$this->db->select('SUM(taxableAmount) as taxbleAmount');
								$this->db->select('SUM(totalTax) as taxAmt');
								$user_id = $this->session->userdata('user_id');
								$comp_id = $this->session->userdata('comp_id');
								$financial_year = $this->session->userdata('financial_year');
								$this->db->where('user_id', $user_id);
								$this->db->where('comp_id', $comp_id);
								$this->db->where('status', 0);
								$this->db->where('financial_year', $financial_year);
								// $this->db->where('invoiceDate >=', $first_day_this_month);
								// $this->db->where('invoiceDate <=', $current_date);
								$q = $this->db->get('purchases');
								// echo '<pre>';
								// print_r($this->db->last_query());
								// die;
								$r = $q->row();
								$tota_sale = $r->totl;
								?>
 							<div class="card-body text-dark">
 								<p title="Total Cancelled Purchase of this month">Cancelled Purchase:<?= $tota_sale ?></p>
 								<span style="margin-left: 10px;text-align:left;" title="Taxable Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->taxbleAmount); ?></span>
 								<span style="margin-left: 10px;text-align:center;" title="Tax Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->taxAmt); ?></span>
 								<span style="margin-left: 10px;text-align:right;" title="Total Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->creditTotal); ?></span>

 							</div>
 						</div>

 					</a>
 				</div>


 				<div class="col-lg-3 col-6">
 					<a href="<?= base_url() ?>invent/estimate">
 						<div class="card text-center ">
 							<div class="card-header">
 								<p>CANCELLED ESTIMATE</p>
 							</div>

 							<?php
								$current_date = date('Y-m-d');
								$first_day_this_month = date('Y-m-01');
								$tota_sale = 0;
								$this->db->select('COUNT(*) as totl');
								$this->db->select('SUM(total) as creditTotal');
								$this->db->select('SUM(taxableAmount) as taxbleAmount');
								$this->db->select('SUM(totalTax) as taxAmt');

								$user_id = $this->session->userdata('user_id');
								$comp_id = $this->session->userdata('comp_id');
								$financial_year = $this->session->userdata('financial_year');
								$this->db->where('user_id', $user_id);
								$this->db->where('comp_id', $comp_id);
								$this->db->where('status', 0);
								$this->db->where('financial_year', $financial_year);
								// $this->db->where('invoiceDate >=', $first_day_this_month);
								// $this->db->where('invoiceDate <=', $current_date);
								$q = $this->db->get('estimates');
								// echo '<pre>';
								// print_r($this->db->last_query());
								// die;
								$r = $q->row();
								$tota_sale = $r->totl;
								?>
 							<div class="card-body text-dark">
 								<p title="Total Cancelled Estimate of this month">Cancelled Estimate:<?= $tota_sale ?></p>
 								<span style="margin-left: 10px;text-align:left;" title="Taxable Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->taxbleAmount); ?></span>
 								<span style="margin-left: 10px;text-align:center;" title="Tax Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->taxAmt); ?></span>
 								<span style="margin-left: 10px;text-align:right;" title="Total Amount"><i class="fas fa-rupee-sign"></i><?= number_format($r->creditTotal); ?></span>

 							</div>
 						</div>

 					</a>
 				</div>


 				<div class="col-lg-3 col-6">
 					<a href="<?= base_url() ?>party/mobile_number_report">
 						<div class="card text-center ">
 							<div class="card-header">
 								<p>MOBILE NUMBER REPORT</p>
 							</div>

 							<?php
							$tota_produc = 0;
							$this->db->select('*');
							$user_id = $this->session->userdata('user_id');
							$comp_id = $this->session->userdata('comp_id');
							$this->db->where('user_id', $user_id);
							$this->db->where('comp_id', $comp_id);
							$this->db->where(['contact!=' => '']);
							$q = $this->db->get('parties');
							$r = $q->result();
							$tota_produc = count($r);
								?>
 							<div class="card-body text-dark">
 								<p title="Total Cancelled Estimate of this month">TOTAL NUMBER:<?= $tota_produc ?></p>
								 <span style="margin-left: 10px;text-align:center;" title="Mobile Number"><i class="fas fa-sms"></i><?= $tota_produc; ?></span>

 							</div>
 						</div>

 					</a>
 				</div>	
				
				 <div class="col-lg-3 col-6">
 					<a href="#">
 						<div class="card text-center ">
 							<div class="card-header">
 								<p>SMS</p>
 							</div>

 							<?php
							$tota_produc = 0;
							$this->db->select('*');
							$user_id = $this->session->userdata('user_id');
							$comp_id = $this->session->userdata('comp_id');
							$this->db->where('user_id', $user_id);
							$this->db->where('comp_id', $comp_id);
							$this->db->where(['customerMobile!=' => '']);
							$q = $this->db->get('sales');
							$r = $q->result();
							$tota_produc = count($r);
								?>
 							<div class="card-body text-dark">
 								<p title="Sms used in billing">SMS:<?= $tota_produc ?></p>
								 <span style="margin-left: 10px;text-align:left;" title="Total SMS"><i class="fas fa-sms"></i><?= 1000 ?></span>
 							<span style="margin-left: 10px;text-align:center;" title="Used SMS"><i class="fas fa-sms"></i><?= $tota_produc; ?></span>
 							<span style="margin-left: 10px;text-align:right;" title="Available Sms"><i class="fas fa-sms"></i><?= 1000 - $tota_produc; ?></span>

 							</div>
 						</div>

 					</a>
 				</div>	


                <div class="col-lg-3 col-6">
                    <a href="<?= base_url(); ?>ticket/ticket_generate">
                        <div class="card text-center">
                            <div class="card-header">
                                <p>SUPPORT</p>
                            </div>
                            <div class="card-body text-dark">
                                <div>
                                    <p title="Call Support">Chat/Call Support</p>
                                    <a style="color: currentColor;" href="tel:+916383880776">
                                        +91-6383880776
                                    </a>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
 				

 				<!-- ./col -->
 			</div>

 		</div>



 	</section>
 	<!-- /.content -->

 </div>