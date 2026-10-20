<div class="after-header wow fadeInUp">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb rounded-0 bg-light">
			<li class="breadcrumb-item"><a href="#">Home</a></li>
			<li class="breadcrumb-item active" aria-current="page">Sign Up</li>
		</ol>
	</nav>
</div>
<section class="row no-gutters p-0 bg-light min-vh-100">
	<div class="col-lg-6 d-none d-lg-block order-lg-2">
		<div class="divider divider-side transform-flip-y d-none d-lg-block"></div>
		<img class="flex-fill" src="<?= base_url() ?>dist/img/sign-up.jpg" width="400px;" height="700px;" alt="Image">
	</div>
	<div class="col-lg-6">
		<section class="p-0 bg-light">
			<div class="container min-vh-lg-100 d-flex flex-column justify-content-between text-center py-4 py-md-5">

				<div class="row justify-content-center my-5">
					<div class="col-xl-7 col-lg-8">
						<div class="card card-body shadow text-left">
							<h1 class="h5 text-center">Create Account</h1>



							<form id="registrationForm" action="<?= base_url('signin/save') ?>" method="POST">
								<?php
								if (form_error('name'))
									echo "<div class='form-group has-error' >";
								else
									echo "<div class='form-group' >";
								?>

								<input id="name" name="name" placeholder="Name *" type="text" class="form-control" required="true" value="<?= set_value('name') ?>">
								<span class="col-sm-4 control-label">
									<?php echo form_error('name', '<div class="alert alert-danger">', '</div>'); ?>
								</span>
						</div>

						<?php
						if (form_error('gender'))
							echo "<div class='form-group has-error' >";
						else
							echo "<div class='form-group' >";
						?>
						<div class="form-check">
							<input class="form-check-input" type="radio" value="Male" name="gender" id="gender" checked>
							<label class="form-check-label" for="checked">
								Male
							</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="radio" value="Female" name="gender" id="gender">
							<label class="form-check-label">
								Female
							</label>
						</div>
						<span class="col-sm-4 control-label">
							<?php echo form_error('gender', '<div class="alert alert-danger">', '</div>'); ?>
						</span>
					</div>

					<?php
					if (form_error('password'))
						echo "<div class='form-group has-error'>";
					else
						echo "<div class='form-group' >";
					?>
					<input id="password" name="password" placeholder="Password" type="password" class="form-control" required="true" value="">
					<span class="col-sm-4 control-label">
						<?php echo form_error('password', '<div class="alert alert-danger">', '</div>'); ?>
					</span>
				</div>


				<?php
				if (form_error('number'))
					echo "<div class='form-group has-error' >";
				else
					echo "<div class='form-group'>";
				?>
				<input id="number" name="number" placeholder="Number" type="text" class="form-control" value="<?= set_value('number') ?>" required>
				<span class="col-sm-4 control-label text-danger">
					<?php echo form_error('number', '<div class="alert alert-danger">', '</div>'); ?>
				</span>
			</div>
			<?php
			if (form_error('email'))
				echo "<div class='form-group has-error' >";
			else
				echo "<div class='form-group' >";
			?>
			<input id="email" name="email" placeholder="Email" type="email" class="form-control" required="true" value="<?= set_value('email') ?>">
			<span class="col-sm-4 control-label text-danger">
				<?php echo form_error('email', '<div class="alert alert-danger">', '</div>'); ?>
			</span>
	</div>


	<div class="form-group ">
		<input id="ref" name="ref" placeholder="Referral (optional)" type="text" class="form-control" onblur="validateReferral()" value="<?= set_value('ref') ?>">
		<small id="refByTag"></small>
	</div>

	<div class="form-group">
		<div class="custom-control custom-checkbox text-small">
			<input type="checkbox" class="custom-control-input" required id="sign-up-agree">
			<label class="custom-control-label" for="sign-up-agree">I agree to the <a target="_blank" href="#">Terms &amp; Conditions</a>
			</label>
		</div>
	</div>
	<div class="form-group">
		<input type="submit" class="form-control text-primary" name="submit" value="Register">
	</div>

	</form>
	</div>
	<div class="text-center text-small mt-3">
		Already have an account? <a href="<?= base_url() ?>signin">Sign in here</a>
	</div>
	</div>
	</div>

	</div>
</section>