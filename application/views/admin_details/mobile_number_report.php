<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row" style="margin-top:65px;">
                <div class="col-lg-12">
                    <div class="card mb-30">
                        <div class="card-header">
                            <div class="card-title"><?= get_title('users',['id'=>$user_unique_id],'name') ?>  Mobile Number Report</div>

                        </div>
                        <div class="card-body">


                            <?php
                            if ($this->session->flashdata('msg')) { ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                    <?php echo $this->session->flashdata('msg'); ?>
                                </div>
                            <?php } ?>

                            <table class="table table-striped table-bordered table-responsive-sm dataTable">
                                <thead>
                                    <tr>
                                        <th class="sorting_disabled" rowspan="1" colspan="1" aria-label=""><input type="checkbox" id="product-selectAll"><label for="product-selectAll"></label></th>
                                        <th width="200px;">Name</th>
                                        <th>Contact</th>
                                        <th>Total Sms Used</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($parties) {
                                        foreach ($parties as $key => $row) { ?>
                                            <tr>
                                                <td class="cheking">
                                                    <input class="checkdata" type="checkbox" name="party_id[]" value="<?php echo $row->id; ?>" ?>

                                                    <!-- <span><?= $key + 1; ?></span> -->
                                                </td>
                                                <td><?= $row->name; ?></td>
                                                <td><?= $row->contact; ?></td>
                                                <td>
                                                    <?php
                                                    $this->db->select('*');

                                                    $this->db->where('customerId', $row->id);
                                                    $this->db->where(['customerMobile!=' => '']);
                                                    $q = $this->db->get('sales');
                                                    $r = $q->result();
                                                    $tota_produc = count($r);
                                                    echo $tota_produc;
                                                    ?>
                                                </td>


                                            </tr>
                                    <?php }
                                    } ?>
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
    $(function() {
        $(".dataTable").DataTable({
            dom: 'Bfrtip',
            pageLength: 20,
            lengthMenu: [
                [20, 50, 100, -1],
                [20, 50, 100, "All"]
            ],
            buttons: [{
                    extend: 'copyHtml5',

                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',

                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csvHtml5',

                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',

                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',

                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pageLength',
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'colvis',
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                }


            ],


        });
    });
</script>