<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row" style="margin-top:65px;">
                <div class="col-lg-12">
                    <div class="card mb-30">
                        <div class="card-header">
                            <div class="card-title"> Mobile Number Report</div>

                        </div>
                        <div class="card-body">


                            <?php
                            if ($this->session->flashdata('msg')) { ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                    <?php echo $this->session->flashdata('msg'); ?>
                                </div>
                            <?php } ?>

                            <table class="table table-striped table-bordered table-responsive-sm" id="table">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="product-selectAll"><label for="product-selectAll"></label></th>
                                        <th width="200px;">Name</th>
                                        <th>Contact</th>
                                        <th>Total Sms Used</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>



                        </div>
                    </div>
                </div>

            </div>



        </div>
    </div>
</div>



<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script>
    $("#product-selectAll").click(function() {
        $(".checkdata").prop('checked', $(this).prop('checked'));
    });
    var table;
    $(document).ready(function() {
        //datatables
        table = $('#table').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo base_url('company_assign/ajax_list') ?>",
                "type": "POST"
            },

        });

    });
</script>