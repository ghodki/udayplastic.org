<style type="text/css">
    div.dt-buttons {
        left: 130px;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <?php if ($this->session->flashdata('msg')) { ?>
                <div class="alert alert-<?php echo $this->session->flashdata('class'); ?> alert-dismissible fade show" role="alert">
                    <strong><?php echo $this->session->flashdata('msg'); ?></strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php } ?>
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <label style="color: #000000; font-size: 25px;">
                                Active Users List
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" style="width: 100%;" id="example">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Contact</th>
                                            <th>Role</th>
                                            <th>Login Date</th>
                                            <th>Image</th>
                                            <th>Info</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableData" class="text-dark">
                                        <?php
                                            if (isset($result) && $result != '') {
                                                $n = 0;
                                                foreach ($result as $key => $unknown) {
                                                    $n++;
                                        ?>
                                        <tr>
                                            <td><?= $n; ?></td>
                                            <td><?= ucwords($unknown->uname); ?></td>
                                            <td><?= $unknown->email; ?></td>
                                            <td><?= $unknown->number; ?></td>
                                            <td><?= ucwords($unknown->rname); ?></td>
                                            <td><?= date('d-m-Y h:i:s A', $unknown->strtotime); ?></td>
                                            <td>
                                                <?php if (!empty($unknown->image)) { ?>
                                                <a href="<?= base_url(); ?>uploads/profile/<?= $unknown->image; ?>" target="_blank" title="View Image">
                                                    <img src="<?= base_url(); ?>uploads/profile/<?= $unknown->image; ?>" alt="profile" width="40px" height="30px" style="border-radius: 30px;" />
                                                </a>
                                                <?php } else { ?>
                                                <img src="<?= base_url(); ?>assets/images/no-image-available.jpg" style="border-radius: 30px;" alt="no-image-available" width="40px" height="30px" />
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="loginInfo(<?= $unknown->lid; ?>);" class="btn btn-info btn-sm" title="Login Info">
                                                    <i class="fa fa-info-circle"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } else { ?>
                                        <tr>
                                            <td colspan="7">
                                                <p class="text-danger text-center">There is no record found.</p>
                                            </td>
                                        </tr>
                                        <?php } ?>
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

<!-- login info modal start -->
<div class="modal fade" id="loginmodal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Login Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="Close();">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="content"></div>
        </div>
    </div>
</div>
<!-- login info modal end -->

<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable( {
            dom: 'lBfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
        });
    });
</script>