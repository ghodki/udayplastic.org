<!-- /.row -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">

			<?php if ($this->session->flashdata('msg')) { ?>
				<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
					<?php echo $this->session->flashdata('msg'); ?>
				</div>
			<?php } ?>

			<div class="row mb-2">
				<div class="col-sm-6">
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active"><?php echo $title ?></li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">

			<div class="row">
				<div class="col-lg-12">
					<div class="card m-b-30">
						<div class="card-body">

							<form name="formdata" action="<?= base_url() ?>permission_checker/addmodule" method="post">

								<div class="form-group row">
									<div class="col-sm-4">
										<label for="name">Parent Module</label>
										<div>
											<select id="parent_id" name="parent_id" class="form-control">
												<option>No Parent Module</option>
												<?php foreach ($permission as $per_val) {  ?>
													<option value=<?= $per_val->id ?>><?= $per_val->name ?></option>
												<?php  }  ?>
											</select>
										</div>
									</div>
									<div class="col-sm-4">
										<label for="name">Module Name</label>
										<div>
											<input class="form-control" required="required" name="name" type="text" value="" id="name">

										</div>
									</div>
									<div class="col-sm-4">
										<label for="Url">Url</label>
										<div>
											<input class="form-control" required="required" name="url" type="text" value="">

										</div>
									</div>
									<div class="col-sm-6">
										<label for="Operation">Operation</label>
										<div class="row">
											<div class="col-sm-2">
												<input type="checkbox" name="operation[]" value="add">
												<label>Add</label>
											</div>

											<div class="col-sm-2">
												<input type="checkbox" name="operation[]" value="edit">
												<label>Edit</label>
											</div>

											<div class="col-sm-2">
												<input type="checkbox" name="operation[]" value="delete">
												<label>Delete</label>
											</div>

											<div class="col-sm-2">
												<input type="checkbox" name="operation[]" value="view">
												<label>View</label>
											</div>
										</div>
									</div>
								</div>

								<div class="form-group m-b-0">
									<div>
										<button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>
										<button type="reset" class="btn btn-secondary waves-effect m-l-5"> Cancel </button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- end col -->
				<div class="col-lg-12">
					<div class="card m-b-30">
						<div class="card-body">
							<h4 class="mt-0 header-title">Permission List</h4>

							<table class="table table-dark">
								<thead>
									<tr>
										<th>#</th>
										<th>Permissions</th>
										<!-- <th>Operation</th>-->
									</tr>
								</thead>
								<tbody>
									<?php foreach ($permissions_list as $key => $per_val) {  ?>
										<tr>
											<td><?= $key + 1; ?></td>
											<td><?= $per_val->name; ?></td>
											<!-- <td>
                <a href="http://localhost/amigos/admin/permissions/5/edit" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                <form method="POST" action="http://localhost/amigos/admin/permissions/5" accept-charset="UTF-8"><input name="_method" type="hidden" value="DELETE"><input name="_token" type="hidden" value="os0LZnOfjUa6UMOpTwkS3PnNpeMgAzZzNbf11FtP">
                <input class="btn btn-danger" type="submit" value="Delete">
                </form>

                </td>-->
										</tr>
									<?php } ?>



								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- end col -->
			</div>
	</section>
</div>
