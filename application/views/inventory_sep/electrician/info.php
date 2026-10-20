<div class="modal-header">
    <h5 class="modal-title">Electrician Information</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-condensed request-data">
                    <tr>
                        <th>Registration Date</th>
                        <td><?= date('d-m-Y', strtotime($electrician->date)); ?></td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td><?= $electrician->name; ?></td>
                    </tr>
                    <tr>
                        <th>Photograph</th>
                        <td>
                            <?php 
                                $photographfile = $electrician->photograph;
                                $photograph = 'uploads/electrician/' . $photographfile;
                                if (file_exists($photograph) && !empty($photographfile)) {
                            ?>
                            <img src="<?= base_url() . '/' . $photograph; ?>" alt="Photograph" style="width: 50px; height: 50px; border-radius: 25px;" />
                            <?php } else { ?>
                            <img src="<?= base_url(); ?>assets/images/avatar.png" alt="avatar" style="width: 50px; border-radius: 25px;" />
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Mobile</th>
                        <td><?= $electrician->mobile; ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= $electrician->email; ?></td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td><?= $electrician->gender; ?></td>
                    </tr>
                    <tr>
                        <th>UPI Id</th>
                        <td><?= $electrician->upiId; ?></td>
                    </tr>
                    <tr>
                        <th>Password</th>
                        <td><?= $electrician->password; ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php if ($electrician->status == '1') { ?>
                            <label class="badge badge-success">
                                <i class="fa fa-check-circle text-white"></i> Active
                            </label>
                            <?php } elseif ($electrician->status == '0') { ?>
                            <label class="badge badge-danger">
                                <i class="fa fa-ban text-white"></i> Inactive
                            </label>
                            <?php } ?>                        
                        </td>
                    </tr>
                    <tr>
                        <th>Created</th>
                        <td><?= $electrician->created; ?></td>
                    </tr>
                    <tr>
                        <th>Created By</th>
                        <td><?= $electrician->createdBy; ?></td>
                    </tr>
                    <tr>
                        <th>Updated</th>
                        <td><?= $electrician->updated; ?></td>
                    </tr>
                    <tr>
                        <th>Updated By</th>
                        <td><?= $electrician->updatedBy; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
</div>