<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Language" content="en">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?= "Dashboard" ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
	<meta name="description" content="This Page Is Regrding of Inventory System">
	<meta name="msapplication-tap-highlight" content="no">
	<!--
    =========================================================
    * ArchitectUI HTML Theme Dashboard - v1.0.0
    =========================================================
    * Product Page: https://dashboardpack.com
    * Copyright 2019 DashboardPack (https://dashboardpack.com)
    * Licensed under MIT (https://github.com/DashboardPack/architectui-html-theme-free/blob/master/LICENSE)
    =========================================================
    * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
    -->
	<link href="<?= base_url() ?>inventory_assets/main.css" rel="stylesheet">
	<!-- Latest compiled and minified CSS -->

</head>

<body>
	<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
		<div class="app-header header-shadow">
			<div class="app-header__logo">
				<div class="logo-src"></div>
				<div class="header__pane ml-auto">
					<div>
						<button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
							<span class="hamburger-box">
								<span class="hamburger-inner"></span>
							</span>
						</button>
					</div>
				</div>
			</div>
			<div class="app-header__mobile-menu">
				<div>
					<button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
						<span class="hamburger-box">
							<span class="hamburger-inner"></span>
						</span>
					</button>
				</div>
			</div>
			<div class="app-header__menu">
				<span>
					<button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
						<span class="btn-icon-wrapper">
							<i class="fa fa-ellipsis-v fa-w-6"></i>
						</span>
					</button>
				</span>
			</div>
			<div class="app-header__content">
				<div class="app-header-left">
					<div class="search-wrapper">
						<div class="input-holder">
							<input type="text" class="search-input" placeholder="Type to search">
							<button class="search-icon"><span></span></button>
						</div>
						<button class="close"></button>
					</div>
					<ul class="header-menu nav">
						<li class="nav-item">
							<a href="javascript:void(0);" class="nav-link">
								<i class="nav-link-icon fa fa-database"> </i>
								Statistics
							</a>
						</li>
						<li class="btn-group nav-item">
							<a href="javascript:void(0);" class="nav-link">
								<i class="nav-link-icon fa fa-edit"></i>
								Projects
							</a>
						</li>
						<li class="dropdown nav-item">
							<a href="javascript:void(0);" class="nav-link">
								<i class="nav-link-icon fa fa-cog"></i>
								Settings
							</a>
						</li>
					</ul>
				</div>
				<div class="app-header-right">
					<div class="header-btn-lg pr-0">
						<div class="widget-content p-0">
							<div class="widget-content-wrapper">
								<div class="widget-content-left">
									<div class="btn-group">
										<a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
											<img width="42" class="rounded-circle" src="<?= base_url() ?>inventory_assets/assets/images/avatars/1.jpg" alt="">
											<i class="fa fa-angle-down ml-2 opacity-8"></i>
										</a>
										<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
											<button type="button" tabindex="0" class="dropdown-item">User Account</button>
											<button type="button" tabindex="0" class="dropdown-item">Settings</button>

										</div>
									</div>
								</div>
								<div class="widget-content-left  ml-3 header-user-info">
									<div class="widget-heading">
										Alina Mclourd
									</div>
									<div class="widget-subheading">
										VP People Manager
									</div>
								</div>
								<div class="widget-content-right header-user-info ml-3">
									<label for="cars">YEAR:</label>

									<select name="cars" id="cars">
										<option value="volvo">2020-2021</option>
										<option value="saab">2021-2022</option>
										<option value="mercedes">2022-2023</option>
										<option value="audi">2023-2024</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	
		<div class="app-main">
			<div class="app-sidebar sidebar-shadow">
				<div class="app-header__logo">
					<div class="logo-src"></div>
					<div class="header__pane ml-auto">
						<div>
							<button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
								<span class="hamburger-box">
									<span class="hamburger-inner"></span>
								</span>
							</button>
						</div>
					</div>
				</div>
				<div class="app-header__mobile-menu">
					<div>
						<button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
							<span class="hamburger-box">
								<span class="hamburger-inner"></span>
							</span>
						</button>
					</div>
				</div>
				<div class="app-header__menu">
					<span>
						<button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
							<span class="btn-icon-wrapper">
								<i class="fa fa-ellipsis-v fa-w-6"></i>
							</span>
						</button>
					</span>
				</div>
				<div class="scrollbar-sidebar">
					<div class="app-sidebar__inner">
						<ul class="vertical-nav-menu">
							<li class="app-sidebar__heading">Dashboard</li>
							<li>
								<a href="<?= base_url() ?>" class="mm-active">
									<i class="metismenu-icon pe-7s-rocket"></i>
									Dashboard
								</a>
							</li>
							<li class="app-sidebar__heading">Voucher</li>
							<li>
								<a href="#">
									<i class="metismenu-icon pe-7s-diamond"></i>
									Voucher
									<i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
								</a>
								<ul>
									<?php
									$where = ['parents_id' => 66];
									$inve = $this->user_model->getAry('permissions', '', '', '', $where);
									if ($inve) {
										foreach ($inve as $invent) {
											$url = $invent->url;

									?>
											<li>
												<a href="<?php echo base_url() ?>index.php/invent/<?php echo  $url; ?>">
													<i class="<?= $invent->icon ?>"></i>
													<?php echo $invent->name ?>
												</a>
											</li>
									<?php
										}
									}
									?>
								


								</ul>
							</li>
						

							<li class="app-sidebar__heading">Master</li>
							<li>
								<a href="#">
									<i class="metismenu-icon pe-7s-display2d"></i>
									Master
									<i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
								</a>
								<ul>
									<?php
									$where = ['parents_id' => 47];
									$inve = $this->user_model->getAry('permissions', '', '', '', $where);
									if ($inve) {
										foreach ($inve as $invent) {
											$url = $invent->url;

									?>
											<li>
												<a href="<?php echo base_url() ?>index.php/invent/<?php echo  $url; ?>">
													<i class="<?= $invent->icon ?>"></i>
													<?php echo $invent->name ?>
												</a>
											</li>
									<?php
										}
									}
									?>
								


								</ul>
							</li>
							<li class="app-sidebar__heading">Widgets</li>
							<li>
								<a href="dashboard-boxes.html">
									<i class="metismenu-icon pe-7s-display2"></i>
									Dashboard Boxes
								</a>
							</li>
							<li class="app-sidebar__heading">Forms</li>
							<li>
								<a href="forms-controls.html">
									<i class="metismenu-icon pe-7s-mouse">
									</i>Forms Controls
								</a>
							</li>
							<li>
								<a href="forms-layouts.html">
									<i class="metismenu-icon pe-7s-eyedropper">
									</i>Forms Layouts
								</a>
							</li>
							<li>
								<a href="forms-validation.html">
									<i class="metismenu-icon pe-7s-pendrive">
									</i>Forms Validation
								</a>
							</li>
							<li class="app-sidebar__heading">Charts</li>
							<li>
								<a href="charts-chartjs.html">
									<i class="metismenu-icon pe-7s-graph2">
									</i>ChartJS
								</a>
							</li>
							<li class="app-sidebar__heading">PRO Version</li>
							<li>
								<a href="https://dashboardpack.com/theme-details/architectui-dashboard-html-pro/" target="_blank">
									<i class="metismenu-icon pe-7s-graph2">
									</i>
									Upgrade to PRO
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>