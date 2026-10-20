<div class="after-header wow fadeInUp">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb rounded-0 bg-light">
			<li class="breadcrumb-item"><a href="#">Home</a></li>
			<li class="breadcrumb-item active" aria-current="page">Sign Up</li>
		</ol>
	</nav>
</div>
<div class="row">
	<div class="col-md-3">

	</div>
	<div class="col-lg-6">

		<div class="row justify-content-center">
			<div class="col-xl-12 col-lg-12">
				<div class="card card-body shadow text-left">
					<h1 class="h5 text-center">Create Account</h1>


					<form id="registrationForm" action="<?= base_url('signin/save') ?>" method="POST">
						<div class="row">

							<div class="col-md-6">

								<?php
								if (form_error('name'))
									echo "<div class='form-group has-error' >";
								else
									echo "<div class='form-group' >";
								?>
								<label for="name">Name<span class="text-danger">*</span></label>

								<input id="name" name="name" placeholder="Name *" type="text" class="form-control" required="true" value="<?= set_value('name') ?>">
								<span class="col-sm-4 control-label">
									<?php echo form_error('name', '<div class="alert alert-danger">', '</div>'); ?>
								</span>
							</div>
						</div>
						<div class="col-md-6">
							<label for="gender">Gender<span class="text-danger">*</span></label>
							<select class="form-control" name="gender" id="gender">
								<option value="Male">Male</option>
								<option value="Female">Female</option>
								

							</select>

						</div>
						<div class="col-md-6">
							<?php
							if (form_error('password'))
								echo "<div class='form-group has-error'>";
							else
								echo "<div class='form-group' >";
							?>
							<label for="password">Password<span class="text-danger">*</span></label>

							<input id="password" name="password" placeholder="Password" type="password" class="form-control" required="true" value="">
							<span class="col-sm-4 control-label">
								<?php echo form_error('password', '<div class="alert alert-danger">', '</div>'); ?>
							</span>
						</div>
				</div>
				<div class="col-md-6">

					<?php
					if (form_error('number'))
						echo "<div class='form-group has-error' >";
					else
						echo "<div class='form-group'>";
					?>
					<label for="number">Mobile Number<span class="text-danger">*</span></label>

					<input id="number" name="number" placeholder="Number" type="text" class="form-control" value="<?= set_value('number') ?>" required>
					<span class="col-sm-4 control-label text-danger">
						<?php echo form_error('number', '<div class="alert alert-danger">', '</div>'); ?>
					</span>
				</div>
			</div>
			<div class="col-md-6">

				<?php
				if (form_error('email'))
					echo "<div class='form-group has-error' >";
				else
					echo "<div class='form-group' >";
				?>
				<label for="email">Email<span class="text-danger">*</span></label>
				<input id="email" name="email" placeholder="Email" type="email" class="form-control" required="true" value="<?= set_value('email') ?>">
				<span class="col-sm-4 control-label text-danger">
					<?php echo form_error('email', '<div class="alert alert-danger">', '</div>'); ?>
				</span>
			</div>
		</div>
		<div class="col-md-6">

			<div class="form-group">

				<label for="Referral Code">Referral Code</label>

				<select class="form-control" name="ref" id="ref">
					<option value="">Select Code</option>
					<?php
					$this->db->select('*');
					$this->db->from('users');
					//    $this->db->group_by('referral'); 
					//    $this->db->order_by('id', 'asc');  # or desc
					$this->db->where('userType', 2);
					$res = $this->db->get()->result();
					if ($res) {
						foreach ($res as $code) {
					?>
							<option value="<?= $code->referral ?>"><?= $code->referral ?></option>
					<?php
						}
					}
					?>

				</select>
			</div>
		</div>
		<div class="col-md-12">

			<div class="form-group">
				<div class="custom-control custom-checkbox text-small">
					<input type="checkbox" class="custom-control-input" required id="sign-up-agree">
					<label class="custom-control-label" for="sign-up-agree">I agree to the <a target="_blank" href="#">Terms &amp; Conditions</a>
					</label>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12">

		<div class="form-group">
			<input type="submit" class="form-control  btn btn-primary" name="submit" value="Register">
		</div>
	</div>

	</form>
</div>
</div>
<div class="col-md-3">

</div>
</div>
</div>
</div>
</div>
</div>