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
                    <table class="table datatable table-responsive">
                        <thead>
                            <tr>
                                <th scope="col" width="2%">#</th>
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
                                $count = 0;
                                $gstr1Data = $this->user_model->getPartyForSubservices();
                                if (isset($gstr1Data) && $gstr1Data != '') {
                                    foreach ($gstr1Data as  $key => $value) {
                                        $sub_service_assigns = json_decode($value->sub_services_id);
                                        if ($sub_service_assigns) {
                                            foreach ($sub_service_assigns as $services_data_id) {
                                                if ($services_data_id == $this->session->userdata('sub_service_id')) {
                                                    $wheres = ['party_id' => $value->id, 'subservice_id' => $this->session->userdata('sub_service_id'), 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
                                                    $yes = $this->user_model->getRow('crm_gstr1', $wheres);
                                                    if (empty($yes)) {
                            ?>
                            <tr scope="<?= $key; ?>">
                                <td><span><?= ++$count; ?></span></td>
                                <td><?= $value->party_name ?></td>
                                <td><?= "<button class='btn btn-danger'>Not Done</button>"; ?></td>
                                <td>
                                    <?php
                                        $gstr3bId = get_title('crm_gstr3b', ['party_id' => $value->id, 'subservice_id' => $this->session->userdata('sub_service_id'), 'month' => $exp[0], 'year' => $this->session->userdata('fy')], 'id');
                                        if ($gstr3bId) {
                                            echo "<button class='btn btn-success'>Done</button>";
                                        } else {
                                            echo "<button class='btn btn-danger'>Not Done</button>";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $crm_sale = get_title('crm_sale', ['party_id' => $value->id, 'subservice_id' => $this->session->userdata('sub_service_id'), 'month' => $exp[0], 'year' => $this->session->userdata('fy')], 'id');
                                        if ($crm_sale) {
                                            echo "<button class='btn btn-success'>Done</button>";
                                        } else {
                                            echo "<button class='btn btn-danger'>Not Done</button>";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $crm_purchase = get_title('crm_purchase', ['party_id' => $value->id, 'subservice_id' => $this->session->userdata('sub_service_id'), 'month' => $exp[0], 'year' => $this->session->userdata('fy')], 'id');
                                        if ($crm_purchase) {
                                            echo "<button class='btn btn-success'>Done</button>";
                                        } else {
                                            echo "<button class='btn btn-danger'>Not Done</button>";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $crm_correction = get_title('crm_correction', ['party_id' => $value->id, 'subservice_id' => $this->session->userdata('sub_service_id'), 'status' => 0], 'id');
                                        if ($crm_correction) {
                                            echo "<button class='btn btn-danger'>Pending</button>";
                                        }
                                    ?>
                                </td>
                                <td><?= $value->remark; ?></td>
                            </tr>
                            <?php } } } } } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>