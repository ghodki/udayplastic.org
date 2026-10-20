<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active"><?= $title ?></li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- Main content -->

	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<!-- left column -->
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							Feed
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-6">
									<label>Feed</label>
								</div>
								<div class="col-md-6">
									<p class="card-text"><?= $result->name ?></p>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<label>Quantity</label>
								</div>
								<div class="col-md-6">
									<p class="card-text"><?= $result->quantity ?></p>
								</div>
							</div>


							<div class="row">
								<div class="col-md-6">
									<label>Price</label>
								</div>
								<div class="col-md-6">
									<p class="card-text"><?= $result->price ?></p>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<a href="<?= base_url('feed/index/') ?>" class="btn btn-primary">Go Back</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
