<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <?php if ($this->session->flashdata('msg')) { ?>
            <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                <?= $this->session->flashdata('msg'); ?>
            </div>
            <?php $this->session->unset_userdata('msg'); } ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Purchase Done</h5>
                </div>
                <div class="card-body">
                    <table class="table datatable table-responsive" id="datatable">
                        <thead>
                            <tr>
                                <th scope="col" width="2%">#</th>
                                <th scope="col">Date</th>
                                <th scope="col">Party Name</th>
                                <th scope="col">Purchase</th>
                                <th scope="col">Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $purchaseData = $this->user_model->getMonthWiseForSubServices('crm_purchase');
                                if (isset($purchaseData) && $purchaseData != '') {
                                    foreach ($purchaseData as $key => $value) {
                            ?>
                            <tr scope="<?= $key ?>">
                                <td><span><?= $key + 1; ?></span></td>
                                <td><?= $value->date; ?></td>
                                <td><?= get_title('crm_party', ['id' => $value->party_id], 'party_name'); ?></td>
                                <td><?= "<button class='btn btn-success'>Done</button>";?></td>
                                <td><?= $value->remark; ?></td>
                            </tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>