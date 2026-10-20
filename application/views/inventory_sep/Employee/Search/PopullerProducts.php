                                    <table class="table">
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                        </tr>
                                        <?php if (!empty($SallingProducts)) { foreach ($SallingProducts as $key => $sdata) { ?>
                                        <tr>
                                            <td>
                                                <div class="cust-name d-flex align-items-center">
                                                    <span class="custimg">
                                                        <?php
                                                            $ImageName = get_title('product', ['id' => $sdata['product_id']], 'image');
                                                            $ImageFile = 'uploads/product/' . $ImageName;
                                                            if (file_exists($ImageFile) && !empty($ImageName)) {
                                                        ?>
                                                        <img src="<?= base_url() . $ImageFile; ?>" alt="user-profile" />
                                                        <?php } else { ?>
                                                        <img src="<?= base_url(); ?>assets/images1/user/avatar.png" alt="user-profile" />
                                                        <?php } ?>
                                                    </span>
                                                    <div class="cutname-txt">
                                                        <h6><?= $sdata['productname']; ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= $sdata['SaleQty']; ?></td>
                                            <td><?= get_title('product', ['id' => $sdata['product_id']], 'unit_name'); ?></td>
                                        </tr>
                                        <?php } } else { ?>
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                <span class="text-danger">No record found.</span>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td colspan="3">
                                                <?= $ProductPaging; ?>
                                            </td>
                                        </tr>
                                    </table>