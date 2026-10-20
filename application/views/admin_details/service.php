  <div class="content-wrapper">

  	<!-- Content Header (Page header) -->
  	<div class="content-header">
  		<div class="container-fluid">
  			<div class="row mt-5">
  				<div class="col-md-12 col-xl-12 col-lg-12">
  					<div class="header bg-blue pb-8 pt-5 pt-md-8">
  						<div class="container-fluid">
  							<div class="header-body ">
  								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Add Service</h2>
  								<!-- Card stats -->
  								<div class="row">
  									<?php if ($this->session->flashdata('msg')) { ?>
  										<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
  											<?php echo $this->session->flashdata('msg'); ?>
  										</div>
  									<?php
										}
										?>
  									<?php echo $error; ?>
  									<?php echo form_open_multipart('serviceSave'); ?>
  									<div class="row">
  										<div class="col-md-6  col-sm-12 col-lg-6">
  											<div class="form-group">
  												<textarea id="name" required name="name">Service Name</textarea>
  												<script>
                                                                                                    ClassicEditor.create(document.querySelector('#name'), {
                                                                                                    }).catch(error => {
                                                                                                        console.error(error);
                                                                                                    });                                                                                                        
  												</script>
  											</div>
  										</div>



  										<div class="col-md-6 col-md-6 col-sm-12 col-lg-6">

  											<div class="form-group">

  												<textarea id="text" required name="text">Content</textarea>
  												<script>
                                                                                                    ClassicEditor.create(document.querySelector('#text'), {
                                                                                                    }).catch(error => {
                                                                                                        console.error(error);
                                                                                                    });
  												</script>
  											</div>
  										</div>
  										<div class="col-md-2"></div>
  										<div class="col-md-8 col-md-8 col-sm-12 col-lg-8">
  											<div class="form-group">
  												<input type="file" class="form-control" required name="image" size="20" />
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
  								<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Service List</label>
  								<div class="row mt-5">
  								</div>
  								<div class="table-responsive">
  									<table class="table align-items-center table-flush" style="width: 100%" id="dataTable">
  										<thead class="thead-light">
  											<tr>
  												<th>#</th>
  												<th>Service Name</th>
  												<th>Image</th>
  												<th>Status</th>
  												<th>Content</th>
  												<th>Action</th>
  											</tr>
  										</thead>
  										<tbody class="row_position">
  											<?php
												if (isset($service) && $service != '') {
													foreach ($service as  $key => $cat) {
														// $date = $cat->created_at;
														// $newDate = date("d-m-Y", strtotime($date));
												?>
  													<tr id="<?= $cat->id ?>">
  														<td>
  															<span><?= $key + 1; ?></span>
  														</td>

  														<td><?= $cat->title ?></td>
  														<td><?= $cat->image ?></td>
  														<?php
															if ($cat->status == 1) {
															?>
  															<td class="text-blue"><button class="btn btn-success" onclick="changeStatus('<?= $cat->id ?>','0')">Active</button></td>
  														<?php
															} else {
															?>
  															<td class="text-blue"><button class="btn btn-danger" onclick="changeStatus('<?= $cat->id ?>','1')">Deactivated</button></td>
  														<?php
															}
															?>
  														<td><?= substr($cat->text, 0, 200); ?></td>

  														<td class="text-right">
  															<div class="dropdown">
  																<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  																	<i class="fas fa-ellipsis-v"></i>
  																</a>
  																<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
  																	<a href="<?= base_url('service/edit/') . $cat->id ?>" class="dropdown-item cp">Edit</a>
  																	<!--<a href="<?= base_url('service/delete/') . $cat->id ?>" onclick="return confirm('Are you sure you want to delete the Service?');" class="dropdown-item cp">Delete</a>-->


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

  <script type="text/javascript">
  	$(document).ready(function() {
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
  				url: "<?= base_url() ?>serviceChangePosition",
  				type: 'post',
  				data: {
  					position: data
  				},
  				success: function(data) {
  					toastr.success('Your Change Successfully Saved.');
  				}
  			})
  		}


  	});

  	function changeStatus(service_id, status) {
  		let returnValue = confirm("Are You Sure Want to change Status?");
  		if (returnValue) {
  			$.ajax({
  				url: "<?= base_url() ?>serviceChangeStatus",
  				type: 'post',
  				data: {
  					service_id: service_id,
  					status: status
  				},
  				dataType: 'json',
  				success: function(response) {
  					console.log(response.status);
  					if (response.status == 'success') {
  						toastr.success(response.msg);
  						setTimeout(() => {
  							window.location.reload();
  						}, 1000);
  					} else {
  						toastr.error(response.msg);
  					}
  				}
  			});
  		}
  	}
  </script>