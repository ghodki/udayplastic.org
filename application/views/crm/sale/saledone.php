<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sale Done</h5>
                    <table class="table datatable table-responsive" id="datatable">
                        <thead>
                            <tr>
                                <th scope="col" width="2%">#</th>
                                <th scope="col">Date</th>
                                <th scope="col">Party Name</th>
                                <th scope="col">SALE</th>
                                <th scope="col">Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                    <?php echo $this->session->flashdata('msg'); ?>
                                </div>
                            <?php
                                $this->session->unset_userdata('msg');
                            } ?>
                            <?php
                            $saleData = $this->user_model->getMonthWiseForSubServices('crm_sale');
                            if (isset($saleData) && $saleData != '') {
                                foreach ($saleData as  $key => $value) {
                                    $date = $value->date;
                            ?>
                                    <tr scope="<?= $key ?>">
                                        <td><span><?= $key + 1; ?></span></td>
                                        <td><?= $date; ?></td>
                                        <td>
                                            <?= get_title('crm_party', ['id' => $value->party_id], 'party_name') ?>
                                        </td>
                                        <td><?php
                                            echo "<button class='btn btn-success'>Done</button>";
                                            ?></td>
                                        <td>
                                            <?= $value->remark ?>
                                        </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>