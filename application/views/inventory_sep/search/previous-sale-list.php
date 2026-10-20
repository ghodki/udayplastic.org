                            <table class="table">
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Invoice Date</th>
                                    <th>Amount</th>
                                </tr>
                                <?php if (!empty($PreviousSale)) { foreach ($PreviousSale as $key => $pdata) { ?>
                                <tr>
                                    <td>
                                        <div class="cust-name d-flex align-items-center">
                                            <span class="custimg">
                                                <?php
                                                    $ImageName = get_title('comp_profile', ['user_id' => $pdata['user_id']], 'logo');
                                                    $ImageFile = 'uploads/complogo/' . $ImageName;
                                                    if (file_exists($ImageFile) && !empty($ImageName)) {
                                                ?>
                                                <img src="<?= base_url() . $ImageFile; ?>" alt="user-profile" />
                                                <?php } else { ?>
                                                <img src="<?= base_url(); ?>assets/images/avatar.png" alt="user-profile" />
                                                <?php } ?>
                                            </span>
                                            <div class="cutname-txt">
                                                <h6><?= get_title('parties', ['id' => $pdata['customerId']], 'name'); ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?= date('d-m-Y', strtotime($pdata['invoiceDate'])); ?>
                                    </td>
                                    <td><i class="fa fa-rupee"></i> <?= indianMoneyFormat($pdata['total']); ?></td>
                                </tr>
                                <?php } } else { ?>
                                <tr>
                                    <td colspan="3" class="text-center">
                                        <span class="text-danger">No record found.</span>
                                    </td>
                                </tr>    
                                <?php } ?>
                                <tr>
                                    <td colspan="3">
                                        <?= $SalePaging; ?>
                                        &nbsp;&nbsp;
                                        <a href="<?= base_url(); ?>sale/sale_report" class="btn btn-info btn-sm" title="View More">
                                            View More
                                        </a>
                                    </td>
                                </tr>
                            </table>