<style type="text/css">
    .btn-danger {
        font-size: 11px;
    }
    .btn-success {
        font-size: 11px;
    }
</style>
    
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <?php if ($this->session->flashdata('msg')) { ?>
            <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                <?= $this->session->flashdata('msg'); ?>
            </div>
            <?php $this->session->unset_userdata('msg'); } ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">GSTR1 DONE</h5>
                    <table class="table datatable table-responsive" id="datatable">
                        <thead>
                            <tr>
                                <th scope="col" width="2%">#</th>
                                <th scope="col">Date</th>
                                <th scope="col">Party Name</th>
                                <th scope="col">GSTR1</th>
                                <th scope="col">GSTR3B</th>
                                <th scope="col">SALE</th>
                                <th scope="col">PURCHASE</th>
                                <th scope="col">CORRECTION</th>
                                <th scope="col">REMARK</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $month = $this->session->userdata('months');
                                $exp = explode('-', $month);
                                $gstr1Data = $this->user_model->getMonthWiseForSubServices('crm_gstr1');
                                if (isset($gstr1Data) && $gstr1Data != '') {
                                    foreach ($gstr1Data as  $key => $value) {
                            ?>
                            <tr scope="<?= $key; ?>">
                                <td><span><?= $key + 1; ?></span></td>
                                <td><?= $value->date; ?></td>
                                <td><?= get_title('crm_party', ['id' => $value->party_id], 'party_name'); ?></td>
                                <td><?= "<button class='btn btn-success'>Done</button>"; ?></td>
                                <td>
                                    <?php
                                        $gstr3bId = get_title('crm_gstr3b', ['party_id' => $value->party_id, 'subservice_id' => $this->session->userdata('sub_service_id'), 'month' => $exp[0], 'year' => $this->session->userdata('fy')], 'id');
                                        if ($gstr3bId) {
                                            echo "<button class='btn btn-success'>Done</button>";
                                        } else {
                                            echo "<button class='btn btn-danger'>Not Done</button>";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $crm_sale = get_title('crm_sale', ['party_id' => $value->party_id, 'subservice_id' => $this->session->userdata('sub_service_id'), 'month' => $exp[0], 'year' => $this->session->userdata('fy')], 'id');
                                        if ($crm_sale) {
                                            echo "<button class='btn btn-success'>Done</button>";
                                        } else {
                                            echo "<button class='btn btn-danger'>Not Done</button>";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $crm_purchase = get_title('crm_purchase', ['party_id' => $value->party_id, 'subservice_id' => $this->session->userdata('sub_service_id'), 'month' => $exp[0], 'year' => $this->session->userdata('fy')], 'id');
                                        if ($crm_purchase) {
                                            echo "<button class='btn btn-success'>Done</button>";
                                        } else {
                                            echo "<button class='btn btn-danger'>Not Done</button>";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $crm_correction = get_title('crm_correction', ['party_id' => $value->party_id, 'subservice_id' => $this->session->userdata('sub_service_id'), 'status' => 0], 'id');
                                        if ($crm_correction) {
                                            echo "<button class='btn btn-danger'>Pending</button>";
                                        } 
                                    ?>
                                </td>
                                <td><?= $value->remark; ?></td>
                            </tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                </div>
            </div>
        </div>
    </div>
</section>