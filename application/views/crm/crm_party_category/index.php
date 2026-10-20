<main id="main" class="main">

    <div class="pagetitle">
        <h1>Category</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>crm/company">Home</a></li>
                <li class="breadcrumb-item active">Categories</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">CRM</h5>
                <!-- Large Modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal">
                    Add Category
                </button>

                <button type="button" class="btn btn-primary" style="margin-left:auto;float:right;">
                    <a href="<?= base_url() ?>inventory_billing" target="_blank" class="text-white">
                        Inventory Software</a>
                </button>

                <div class="modal fade" id="largeModal" tabindex="-1" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div id="message" class="text-center"></div>
                            <form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/subcategory/save" method="POST" enctype="multipart/form-data">
                                <div class="modal-body">

                                    <div class="form-group">

                                        <div class="row text-dark">
                                            <div class="col-md-2 col-sm-6 col-lg-2">
                                                <lable for="name">Category<span class="text-danger">*</span></lable>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-lg-6">
                                                <input id="name" name="name" placeholder="Subcategory Name*" type="text" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer" style="margin-top:10px;">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button class="btn btn-primary" type="submit">Save</button>
                                    </div>
                                </div>
                            </form>


                        </div>
                    </div>
                </div><!-- End Large Modal-->




            </div>
        </div>
    </section>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Sub-Category</h5>
                        <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->

                        <!-- Table with stripped rows -->
                        <table class="table datatable" id="datatable">
                            <thead>
                                <tr>
                                    <th scope="col" width="2%">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // echo '<pre>';
                                // print_r($subcategroy);
                                // die;
                                if (isset($subcategroy) && $subcategroy != '') {
                                    foreach ($subcategroy as  $key => $value) {
                                        $date = $value->created_at;
                                        $newDate = date('d-m-Y', $date);
                                ?>
                                        <tr scope="<?= $key ?>">
                                            <td>
                                                <span><?= $key + 1; ?></span>
                                            </td>
                                            <td><?= $newDate; ?></td>
                                            <td><?= $value->category_name; ?></td>


                                            <td class="text-right">


                                                <!-- <button class="btn btn-primary" onclick="view(<?= $value->id ?>)"><i class="bi bi-eye"></i></button> -->

                                                <button class="btn btn-primary"><i class="ri-edit-box-line" onclick="companyEdit(<?= $value->id ?>)"></i></button>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>



            </div>
        </div>
    </section>


    <div class="modal" id="newModel" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Category</h5>
                    <button type="button" class="btn-close" onclick="closeModel()"></button>
                </div>

                <div class="modal-body">

</div>

            </div>
        </div>
    </div><!-- End Large Modal-->


</main>


<!-- Latest compiled and minified CSS -->

<!-- jQuery library -->
<script src="<?= base_url() ?>crm/assets/js/jquery-1.11.3.min.js"></script>

<script>
    $(document).ready(function() {
        $("#signUpForm").on("submit", function(e) {
            e.preventDefault();
            var contactForm = $(this);
            $.ajax({
                url: contactForm.attr("action"),
                type: "post",
                data: contactForm.serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.status == "success") {
                        toastr.success(response.message);
                        $("#largeModal").modal("hide");
                    } else {
                        toastr.error(response.message);
                    }
                },
            });
        });
    });

    function companyEdit(productId) {
        jQuery.noConflict();
        $("#newModel .modal-dialog .modal-body").load("<?= getCrmUrl() ?>subcategory/edit/" + productId);
        $("#newModel").modal("show");
    }

    function closeModel() {
        $("#newModel").modal("hide");

    }
</script>