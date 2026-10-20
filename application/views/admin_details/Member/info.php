                <div class="modal-header">
                    <h5 class="modal-title">Member Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm table-condensed">
                                    <tr>
                                        <th>First Name</th>
                                        <td><?= $MemberData->FirstName; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Last Name</th>
                                        <td><?= $MemberData->LastName; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Designation</th>
                                        <td><?= $MemberData->Designation; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Mobile</th>
                                        <td><?= $MemberData->Mobile; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td><?= $MemberData->Email; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Updated</th>
                                        <td>
                                            <?php 
                                                $photographfile = $MemberData->Photograph;
                                                $photograph = 'uploads/Member/' . $photographfile;
                                                if (file_exists($photograph) && !empty($photographfile)) {
                                            ?>
                                            <img src="<?= base_url() . '/' . $photograph; ?>" alt="Photograph" style="width: 50px; border-radius: 25px;" />
                                            <?php } else { ?>
                                            <img src="<?= base_url(); ?>assets/images/avatar.png" alt="avatar" style="width: 50px; border-radius: 25px;" />
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Facebook</th>
                                        <td>
                                            <a href="<?= $MemberData->Facebook; ?>" class="btn btn-primary btn-sm" title="Facebook" target="_blank">
                                                Facebook
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>LinkedIn</th>
                                        <td>
                                            <a href="<?= $MemberData->LinkedIn; ?>" class="btn bg-purple btn-sm" title="LinkedIn" target="_blank">
                                                LinkedIn
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Twitter</th>
                                        <td>
                                            <a href="<?= $MemberData->Twitter; ?>" class="btn btn-info btn-sm" title="Twitter" target="_blank">
                                                Twitter
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Instagram</th>
                                        <td>
                                            <a href="<?= $MemberData->Instagram; ?>" class="btn btn-danger btn-sm" title="Instagram" target="_blank">
                                                Instagram
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Comment</th>
                                        <td style="white-space: unset;"><?= $MemberData->Comment; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Position</th>
                                        <td><?= $MemberData->Position; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <?php if ($MemberData->Status == 1) { ?>
                                            <label class="btn bg-navy btn-sm">
                                                <i class="fa fa-check-circle"></i> Active
                                            </label>
                                            <?php } elseif ($MemberData->Status == 0) { ?>
                                            <label class="btn bg-maroon btn-sm">
                                                <i class="fa fa-ban"></i> Inactive
                                            </label>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created</th>
                                        <td><?= $MemberData->Created; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Updated</th>
                                        <td><?= $MemberData->Updated; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
                </div>