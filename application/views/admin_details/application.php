<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-blue pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Add Application Form</h2>
								<!-- Card stats -->
								<!-- <div class="row"> -->
								<?php if ($this->session->flashdata('msg')) { ?>
									<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
										<?php echo $this->session->flashdata('msg'); ?>
									</div>
								<?php

								}
								?>

								<?php echo $error; ?>

								<?php echo form_open_multipart('application/upload'); ?>
								<div class="row">
									<div class="col-md-3 col-sm-12"></div>
									<div class="col-md-6 col-sm-12 col-lg-6">
										<div class="form-group">
											<textarea type="text" class="form-control" placeholder="Application Name" required name="app_name"></textarea>
										</div>
									</div>
									<div class="col-md-3 col-sm-12"></div>
									<div class="col-md-3 col-sm-12"></div>

									<div class="col-md-6 col-sm-12 col-lg-6">

										<div class="form-group">
											<input type="file" class="form-control" required name="image" size="20" />
										</div>
									</div>
									<div class="col-md-3 col-sm-12"></div>



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
							<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Application List</label>
							<div class="row mt-5">
							</div>
							<div class="table-responsive">
								<table class="table align-items-center table-flush" style="width: 100%" id="dataTable">
									<thead class="thead-light">
										<tr>
											<th scope="col" width="2%">#</th>
											<th scope="col" width="20%">Create time</th>

											<th scope="col" width="30%">Application Name</th>
											<th scope="col" width="30%">File Name</th>

											<th scope="col" width="28%">Application File</th>

											<th scope="col">Action</th>
										</tr>
									</thead>
									<tbody class="row_position">


										<?php

										if (isset($application) && $application != '') {
											foreach ($application as  $key => $cat) {
												$date = $cat->create_at;
												$newDate = date("d-m-Y", strtotime($date));
										?>
												<tr id="<?= $cat->id ?>">
													<td>
														<span><?= $key + 1; ?></span>
													</td>
													<td><?= $newDate; ?></td>

													<td><?= $cat->app_name ?></td>
													<td><?= $cat->image ?></td>

													<td>
														<button class="btn btn-lg btn-primary" onclick="downloadFile('<?= base_url() . 'uploads/application/' . $cat->image; ?>','<?= $cat->image; ?>')">Download</button>

													<td>
													<td class="text-right">
														<div class="dropdown">
															<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																<i class="fas fa-ellipsis-v"></i>
															</a>
															<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																<a href="<?= base_url('application/edit/') . $cat->id ?>" class="dropdown-item cp">Edit</a>
																<a href="<?= base_url('application/delete/') . $cat->id ?>" onclick="return confirm('Are you sure you want to delete the user?');" class="dropdown-item cp">Delete</a>


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

<script type="text/javascript">
	$(".row_position").sortable({
		delay: 150,
		stop: function() {
			var selectedData = new Array();
			$('.row_position>tr').each(function() {
				selectedData.push($(this).attr("id"));
			});
			updateOrder(selectedData);
		}
	});

	function updateOrder(data) {
		$.ajax({
			url: "<?= base_url() ?>application/changeApplicationPosition",
			type: 'post',
			data: {
				position: data
			},
			success: function(data) {
				toastr.success('Your Change Successfully Saved.');
			}
		})
	}
</script>
