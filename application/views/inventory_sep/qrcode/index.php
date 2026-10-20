<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row" style="margin-top:65px;">
				<div class="col-lg-12">
					<div class="card mb-30">

						<div class="card-header row">
							<div class="col-md-6">
								<h4>Barcode Download</h4>
							</div>
							<div class="col-md-6">
								<a href="<?= base_url() ?>index.qphp/sale" class="btn btn-info float-right"><i data-feather="arrow-left-circle" class="icon icon-size"></i>Back</a>
							</div>
						</div>
						<form action="" id="reportForm" onsubmit="onFormSubmitHandler(event)" method="get">
							<div class="row">


								<div class="form-group col-md-2 col-sm-6">
									<label for="invoiceNumber">From Product Code</label>
									<input type="text" name="from" id="from" value="<?= $this->input->get('from') ? $this->input->get('from') : '' ?>" class="form-control" />

								</div>
								<div class="form-group col-md-2 col-sm-6">
									<label for="to">TO Product Code</label>
									<input type="text" name="to" id="to" value="<?= $this->input->get('to') ? $this->input->get('to') : '' ?>" class="form-control" />

								</div>
								<?php
								$date = date('Y-m-d');
								?>

								<div class="form-group  col-sm-12">
									<button type="reset" id="resetbtn" class="btn btn-danger"><i class="fa fa-eraser" aria-hidden="true"></i> Reset</button> &nbsp;
                                                                        <?php if ($this->session->userdata('roles') == 3) { if (IsLicensed()) { ?>
									<button type="submit" class="btn btn-primary" onClick="return search()"><i class="fa fa-search"></i> Search</button> &nbsp;
									<button type="button" id="download" class="btn btn-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Print</button>
                                                                        <?php } else { ?>
                                                                        <span class="text-danger">Your license has been expired,<br />Please contact your administrator.</span>
                                                                        <?php } } else { ?>
                                                                        <button type="submit" class="btn btn-primary" onClick="return search()"><i class="fa fa-search"></i> Search</button> &nbsp;
									<button type="button" id="download" class="btn btn-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Print</button>
                                                                        <?php } ?>
								</div>
						</form>
						<table class="table table-striped align-items-center table-flush" id="example" style="width: 100%">

							<thead class="thead-dark">
								<tr>

									<th scope="col" width="20%">#</th>

									<th>Prduct Code</th>

									<th scope="col" width="80%">Barcode</th>


									<!-- <th scope="col">Action</th> -->
								</tr>
							</thead>
							<tbody class="text-dark">
								<?php
								if (isset($result) && !empty($result)) {
									foreach ($result as  $key => $value) {
										// $date = $value->created_at;
										// $newDate = date("d-m-Y", strtotime($date));
								?>
										<tr>
											<th>

												<span><?= 1 + $key ?></span>
											</th>
											<th>

												<span><?= $value->qrCode ?></span>
											</th>
											<th>
												<img src="<?php echo base_url() ?>uploads/qr_image/<?php echo $value->img_url; ?>" class='d-block' style="width:200px;height:100px;">

											</th>
											<!-- <th class="text-right">
												<div class="dropdown">
													<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														<i class="fas fa-ellipsis-v"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
														<a href="<?= base_url('productCode/edit/') . $value->id ?>" class="dropdown-item cp">Edit</a>
														<a href="<?= base_url('productCode/delete/') . $value->id ?>" class="dropdown-item cp">Delete</a>


													</div>
												</div>
											</th> -->
										</tr>

								<?php
									}
								}

								?>


							</tbody>

						</table>
						<div class="row">
							<div class="col-md-6">
								<?php if ($rowCount) { ?>
									<p>Showing <?= $start ? $start : 1; ?> to <?= $end ?> of <?= $rowCount ?> filtered entries
									<?php } else { ?>
									<p>No result found.
									<?php }
								if ($totalCount) { ?>
										{Total Records: <?= $totalCount ?>}</p>
								<?php } ?>
							</div>
							<div class="col-md-6">
								<div class="fr">
									<?= $this->pagination->create_links(); ?>
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
	// $(document).ready(function() {
	// 	$('#example').DataTable();
	// });
</script>
<script>
	$("#mass_select_all").click(function() {
		$(".checkdata").prop('checked', $(this).prop('checked'));
	});

	const onFormSubmitHandler = (e) => {
		e.preventDefault();
		var form = $('#reportForm').serialize();

		href = "<?= base_url() ?>qrcode/index?" + form;
		var $a = $("<a>");
		$a.attr("href", href);
		$("body").append($a);
		$a[0].click();
		$a.remove();




	}
	$('#download').click(function(e) {
		e.preventDefault();
		var form = $('#reportForm').serialize();
		href = "<?= base_url() ?>qrcode/pdfgenerate?" + form;
		var $a = $("<a>");
		$a.attr("href", href);
		$("body").append($a);
		$a[0].click();
		$a.remove();
	});
</script>
<script>
	var array = [];

	$('#raw').change(function() {
		// var year_id = $(this).val();

		var raw = document.getElementById('raw').value;



		var n = array.includes(raw);

		if (n == false) {

			if (parseInt(raw) > 0) {
				$.ajax({
					type: "POST",
					url: '<?= base_url('raw_material/getRaw'); ?>',
					data: {
						id: raw,

					},
					success: function(data) {
						console.log(data);
						$("#raw_details").append(data);
					}

				});

			}
			array.push(raw);
		}







	});
</script>
<script>
	// var overall = 0;

	function changeValue(qty, id) {

		console.log(id, "id");
		console.log(qty, "qty");
		if (qty < 0) {
			document.getElementById('qty' + id).value = 0;
			document.getElementById('mini_total' + id).value = 0;
			document.getElementById('overall').value = overall;
			document.getElementById('cost').value = overall;

		} else {
			var rate = document.getElementById('rate' + id).value;
			var total_for_single = rate * qty;
			// overall = overall + total_for_single;
			document.getElementById('mini_total' + id).value = total_for_single;
			getOverall();
			getWeight();


			// document.getElementById('overall').value = overall;
			// document.getElementById('cost').value = overall;


		}



	}
</script>
<script>
	function getOverall() {
		var x = document.getElementsByClassName("new");
		var i;
		var total = 0;
		if (x) {
			for (i = 0; i < x.length; i++) {
				var v = x[i].value;
				total = parseFloat(total) + parseFloat(v);
			}
		}
		// console.log(total);
		// var x = document.getElementsByClassName("new");
		document.getElementById('cost').value = total;
		document.getElementById('overall').value = total;

	}
</script>
<script>
	function getWeight() {
		var x = document.getElementsByClassName("weight");
		// console.log(x,'weight');

		var i;
		var total = 0;
		for (i = 0; i < x.length; i++) {
			var v = x[i].value;
			total = parseInt(total) + parseInt(v);
		}
		console.log(total, "weight");
		// var x = document.getElementsByClassName("new");
		// document.getElementById('cost').value = total;
		document.getElementById('overall_weight').value = total;
	}
</script>
<script>
	$('#extra').change(function() {
		// var year_id = $(this).val();

		var x = document.getElementsByClassName("new");
		var i;
		var total = 0;
		if (x) {
			for (i = 0; i < x.length; i++) {
				var v = x[i].value;
				total = parseFloat(total) + parseFloat(v);
			}
		}

		var extra = document.getElementById('extra').value;
		if (extra > 0) {
			total = parseFloat(total) + parseFloat(extra);
		}


		// console.log(total);
		// var x = document.getElementsByClassName("new");
		document.getElementById('cost').value = total;
		document.getElementById('overall').value = total;
	});

	$('#export').click(function(e) {
		e.preventDefault();
		// var form = $('#reportForm').serialize();
		href = "<?= base_url() ?>product/exportproduct";
		var $a = $("<a>");
		$a.attr("href", href);
		$("body").append($a);
		$a.attr("download", "product.xls");
		$a[0].click();
		$a.remove();
	});
</script>
<script>
	// $('#import_form').on('submit', function(event){
	//   event.preventDefault();
	//   $.ajax({
	//    url:"<?php echo base_url(); ?>index.php/product/importfile",
	//    method:"POST",
	//    data:new FormData(this),
	//    contentType:false,
	//    cache:false,
	//    processData:false,
	//    success:function(data){
	//     $('#file').val('');
	//     load_data();
	//     alert(data);
	//    }
	//   })
	//  });
</script>
<script>
	$(document).ready(function() {
		$('#submitButton').click(function() {
			$('#import_form').ajaxForm({
				target: '#outputImage',
				url: '<?= base_url() ?>',
				beforeSubmit: function() {
					$("#outputImage").hide();
					if ($("#uploadImage").val() == "") {
						$("#outputImage").show();
						$("#outputImage").html("<div class='error'>Choose a file to upload.</div>");
						return false;
					}
					$("#progressDivId").css("display", "block");
					var percentValue = '0%';

					$('#progressBar').width(percentValue);
					$('#percent').html(percentValue);
				},
				uploadProgress: function(event, position, total, percentComplete) {

					var percentValue = percentComplete + '%';
					$("#progressBar").animate({
						width: '' + percentValue + ''
					}, {
						duration: 5000,
						easing: "linear",
						step: function(x) {
							percentText = Math.round(x * 100 / percentComplete);
							$("#percent").text(percentText + "%");
							if (percentText == "100") {
								$("#outputImage").show();
							}
						}
					});
				},
				error: function(response, status, e) {
					alert('Oops something went.');
				},

				complete: function(xhr) {
					if (xhr.responseText && xhr.responseText != "error") {
						$("#outputImage").html(xhr.responseText);
					} else {
						$("#outputImage").show();
						$("#outputImage").html("<div class='error'>Problem in uploading file.</div>");
						$("#progressBar").stop();
					}
				}
			});
		});
	});
</script>
<script>
	function submitHandler() {
		var checkboxes = $('input:checkbox:checked').length;
		// let lenth=document.getElementsByClassName('checkdata').length;
		// alert(checkboxes);
		if (length > 0) { // alert("select first");
			alert("select fisrt")
			return false;
		}
		return true;
	}

	const getInvoiceInfo = (InvoiceNumber) => {

$.ajax({
	method: "POST",
	// url: baseurl + "get-customer",
	url: baseurl + "index.php/party/incoicepurchase",
	data: {
		id: InvoiceNumber,
	},
	success: function(customer) {
		// var idss = [];
		
		

	},
	error: function(jqXHR, exception) {
		var msg = "";
		if (jqXHR.status === 0) {
			msg = "Not connect.\n Verify Network.";
		} else if (jqXHR.status == 401) {
			msg = "Unauthorized User. [" + jqXHR.status + "]";
			window.location.href = "/gst/signout";
		} else if (jqXHR.status == 403) {
			msg = "Permission denied. [403]";
		} else if (jqXHR.status == 404) {
			msg = "Requested page not found. [404]";
		} else if (jqXHR.status == 406) {
			msg = jqXHR.responseText;
		} else if (jqXHR.status == 500) {
			msg = "Internal Server Error [500].";
		} else if (exception === "parsererror") {
			msg = "Requested JSON parse failed.";
		} else if (exception === "timeout") {
			msg = "Time out error.";
		} else if (exception === "abort") {
			msg = "Ajax request aborted.";
		} else {
			msg = "Uncaught Error.\n";
			console.error(jqXHR.responseText);
		}
		Swal.fire({
			position: "top-end",
			icon: "error",
			title: msg,
			showConfirmButton: false,
			timer: 2000,
		});
	},
});
}
</script>
