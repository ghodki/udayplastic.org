                                    <table class="table">
                                        <tbody>
                                            <?php
                                                $Balance = 0;
                                                if (!empty($PaymentInfo)) {
                                                    foreach ($PaymentInfo as $key => $pinfo) {
                                                        $Balance += $pinfo['Total'] - $pinfo['PaidAmount'];
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="cust-name d-flex align-items-center">
                                                        <span class="custimg">
                                                            <?php
                                                                $ImageName = get_title('comp_profile', ['user_id' => $pinfo['user_id']], 'logo');
                                                                $ImageFile = 'uploads/complogo/' . $ImageName;
                                                                if (file_exists($ImageFile) && !empty($ImageName)) {
                                                            ?>
                                                            <img src="<?= base_url() . $ImageFile; ?>" alt="user-profile" />
                                                            <?php } else { ?>
                                                            <img src="<?= base_url(); ?>assets/images1/user/avatar.png" alt="user-profile" />
                                                            <?php } ?>
                                                        </span>
                                                        <div class="cutname-txt">
                                                            <h6><?= get_title('parties', ['id' => $pinfo['customerId']], 'name'); ?></h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="ms-auto">
                                                        <i class="fa fa-rupee"></i> <?= $pinfo['Total'] - $pinfo['PaidAmount']; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php } } else { ?>
                                            <tr>
                                                <td colspan="2" class="text-center">
                                                    <span class="text-danger">No record found.</span>
                                                </td>
                                            </tr>    
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Total</th>
                                                <th>
                                                    <div class="ms-auto">
                                                        <i class="fa fa-rupee"></i> <?= $Balance; ?>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <?= $PaymentPaging; ?>
                                                    &nbsp;&nbsp;
                                                    <a href="<?= base_url(); ?>Employee/PartiesOutstanding" class="btn btn-info btn-sm" title="View More">
                                                        View More
                                                    </a>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>