<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-blue pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Add Category</h2>
								<!-- Card stats -->
									<?php if ($this->session->flashdata('msg')) { ?>
										<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
											<?php echo $this->session->flashdata('msg'); ?>
										</div>
									<?php

									}
									?>


									<?php echo form_open_multipart('categoryfilesharing/save'); ?>
									<div class="row">
									<div class="col-md-3 col-sm-12"></div>
									<div class="col-md-6 col-sm-12 col-lg-6">
										<div class="form-group">
											<textarea type="text" class="form-control" placeholder="Category  Name" required name="name"></textarea>
										</div>
									</div>
									<div class="col-md-3 col-sm-12"></div>
									<div class="col-md-3 col-sm-12"></div>
										<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
											<div class="form-group">
												<!-- <input type="file" class="form-control" required name="image" size="20" /> -->
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
									<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Category  List</label>
									<div class="row mt-5">
									</div>
									<div class="table-responsive">
										<table class="table align-items-center table-flush" style="width: 100%" id="dataTable">
											<thead class="thead-light">
												<tr>
													<th scope="col" width="2%">#</th>

													<th scope="col" width="98%">Name</th>
													<!-- <th scope="col" width="28%">Category  File</th> -->

													<th scope="col">Action</th>
												</tr>
											</thead>
											<tbody class="row_position">


												<?php

												if (isset($categoryfilesharing) && $categoryfilesharing != '') {
													foreach ($categoryfilesharing as  $key => $cat) {
													
												?>
														<tr id="<?= $cat->id ?>">
															<td>
																<span><?= $key + 1; ?></span>
															</td>

															<td><?= $cat->name ?></td>
						
															<td class="text-right">
																<div class="dropdown">
																	<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																		<i class="fas fa-ellipsis-v"></i>
																	</a>
																	<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																		<a href="<?= base_url('categoryfilesharing/edit/') . $cat->id ?>" class="dropdown-item cp">Edit</a>
																		<a href="<?= base_url('categoryfilesharing/delete/') . $cat->id ?>" onclick="return confirm('Are you sure you want to delete the user?');" class="dropdown-item cp">Delete</a>


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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
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
    			url: "<?= base_url() ?>categoryfilesharing/changeCategory Position",
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
