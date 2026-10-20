<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Our Team</h2>
								<!-- Card stats -->
								<?php if ($this->session->flashdata('msg')) { ?>
									<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
										<?php echo $this->session->flashdata('msg'); ?>
									</div>
								<?php

								}
								?>
								<div class="row">


									<?php echo $error; ?>

									<?php echo form_open_multipart('ourteam/upload'); ?>
									<div class="row">
										<div class="col-md-2 col-sm-6 col-lg-2">
										</div>
										<div class="col-md-4 col-sm-6 col-lg-4">
											<div class="form-group">
												<input type="file" class="form-control" required name="image" size="20" />

											</div>
										</div>

										<div class="col-md-4 col-sm-6 col-lg-4">
											<div class="form-group">
												<input type="text" class="form-control" name="title" id="title" placeholder="Title" value="<?= set_value('title') ?>" required="true">
											</div>
										</div>
										<div class="col-md-2 col-sm-6 col-lg-2">
										</div>
										<div class="col-md-2 col-sm-6 col-lg-2">
										</div>
										<div class="col-md-8 col-sm-6 col-lg-8">
											<div class="form-group">
												<textarea id="YourEditor" required name="description" required><?= set_value('description') ?></textarea>
											</div>
											<script>
												CKEDITOR.replace('YourEditor');
											</script>
										</div>

									</div>
									<div class="col-md-6 col-sm-6 col-lg-6">
										<div class="form-group">
										</div>
									</div>

								</div>
								<div class="row mt-5">
									<div class="col-md-12 text-center">
										<button type="submit" class="btn btn-default">
											Save
										</button>

										<input type="reset" class="btn btn-secondary" placeholder="Reset">
									</div>
								</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="container-fluid mb-9" style="margin-top:100px;">

				<div class="col">
					<div class="card shadow">
						<div class="card-header border-0">
							<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Driver List</label>
							<div class="row mt-5">
							</div>
							<div class="table-responsive">
								<table class="table align-items-center table-flush" style="width: 100%" id="dataTable">
									<thead class="thead-light">
										<tr>
											<th scope="col" width="2%">#</th>

											<th scope="col" width="20%">Title</th>
											<th scope="col" width="30%">Photo</th>

											<th scope="col" width="48%">Description</th>

											<th scope="col">Action</th>
										</tr>
									</thead>
									<tbody>


										<?php

										if (isset($ourteam) && !empty($ourteam)) {
											foreach ($ourteam as  $key => $cat) {

										?>
												<tr>
													<td>
														<span><?= $key + 1; ?></span>
													</td>

													<td><?= $cat->title ?></td>
													<td><?= $cat->image ?></td>
													<td><?= $cat->description ?></td>


													<td class="text-right">
														<div class="dropdown">
															<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																<i class="fas fa-ellipsis-v"></i>
															</a>
															<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																<!-- <a href="<?= base_url('ourteam/edit/') . $cat->id ?>" class="dropdown-item cp">Edit</a> -->
																<a href="<?= base_url('ourteam/delete/') . $cat->id ?>" onclick="return confirm('Are you sure you want to delete the user?');" class="dropdown-item cp">Delete</a>


															</div>
														</div>
													</td>
												</tr>

										<?php }
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
<script>
	function downloadFile(uri, name) {
		var link = document.createElement("a");
		// If you don't know the name or want to use
		// the webserver default set name = ''
		link.setAttribute('download', name);
		link.href = uri;
		document.body.appendChild(link);
		link.click();
		link.remove();
	}
</script>
