<main id="main" class="main">

    <div class="pagetitle">
        <h1>Profile Update</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>crm/company">Home</a></li>
                <li class="breadcrumb-item active">Profile Update</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->



    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Company Profile <button class="btn btn-primary" onclick="companyEdit(<?= $result->id ?>)">Edit</button>
                        </h5>

                        <hr>
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <td scope="col">Company Name</td>
                                    <th scope="col"><?= $result->company_name ?></th>
                                    <td scope="col">Email</td>
                                    <th scope="col"><?= $result->email ?></th>

                                </tr>
                                <tr>
                                    <td scope="col">GSTIN</td>
                                    <th scope="col"><?= $result->gst_no ?></th>
                                    <td scope="col">Mobile Number</td>
                                    <th scope="col"><?= $result->number ?></th>
                                </tr>

                                <tr>
                                    <td scope="col">Contact</td>
                                    <th scope="col"><?= $result->contact_person ?></th>
                                    <td scope="col">Mobile Number</td>
                                    <th scope="col"><?= $result->number ?></th>
                                </tr>

                                <tr>
                                    <td scope="col">State</td>
                                    <th scope="col"><?= get_title('state_list', ['id' => $result->state], 'state') ?></th>
                                    <td scope="col">Serial Number</td>
                                    <th scope="col"><?= $result->serial_number ?></th>
                                </tr>

                                <tr>
                                    <td scope="col">Financial Year</td>
                                    <th scope="col"><?= get_title('year', ['id' => $result->state], 'state') ?></th>
                                    <td scope="col">Address</td>
                                    <th scope="col"><?= $result->address ?></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>


            </div>
        </div>
    </section>

</main>

<div class="modal fade" id="exampleModals" role="dialog" aria-labelledby="exampleModalLabels" style="padding-top:100px;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabels"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>
<style>
    .fade:not(.show) {
        opacity: 1;
    }
</style>

<script src="<?= base_url() ?>crm/assets/js/jquery-1.11.3.min.js"></script>

<script>
    $(document).ready(function() {
        $("#signUpForm").on('submit', function(e) {
            e.preventDefault();
            var contactForm = $(this);
            $.ajax({
                url: contactForm.attr('action'),
                type: 'post',
                data: contactForm.serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.status == 'success') {
                        toastr.success(response.message);
                        // $("#exampleModals").modal("hide");
                        window.location.reload();
                    } else {
                        toastr.error(response.message);


                    }


                }
            });
        })
    })


    function companyEdit(productId) {
        jQuery.noConflict();
        $("#exampleModals .modal-dialog .modal-body").load("<?= base_url() ?>crm/company/edit/" + productId);
        $("#exampleModalLabels").html("Update Company");
        $("#exampleModals").modal("show");

    }
</script>