<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sale Not Done
                    </h5>
                    <table class="table datatable table-responsive" id="datatable">
                        <thead>
                            <tr>
                                <th scope="col" width="2%">#</th>
                                <th scope="col">Party Name</th>
                                <th scope="col">SALE</th>
                                <th scope="col">REMARK</th>
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
                            $month = $this->session->userdata('months');
                            $user_id = $this->session->userdata('user_id');
                            $crm_id = $this->session->userdata('crm_id');

                            $exp = explode('-', $month);

                            $gstr1Data = $this->user_model->getPartyForSubservices();
                            if (isset($gstr1Data) && $gstr1Data != '') {
                                $count = 0;
                                foreach ($gstr1Data as  $key => $value) {
                                    $sub_service_assigns = json_decode($value->sub_services_id);
                                    if ($sub_service_assigns) {
                                        foreach ($sub_service_assigns as $services_data_id) {
                                            if ($services_data_id == $this->session->userdata('sub_service_id')) {

                                                $this->db->select('*');
                                                $this->db->from('crm_sale');
                                                $this->db->where('user_id', $user_id);
                                                $this->db->where('crm_id', $crm_id);
                                                $this->db->where('party_id', $value->id);
                                                $this->db->where('month', $exp[0]);
                                                $this->db->where('year', $this->session->userdata('fy'));
                                                $saleEntry = $this->db->get()->row();
                                                if (empty($saleEntry)) {
                            ?>
                                                    <tr scope="<?= $key ?>">
                                                        <td><span><?= ++$count; ?></span></td>
                                                        <td><?= $value->party_name ?></td>
                                                        <td><?php echo "<button class='btn btn-danger'>Not Done</button>"; ?></td>
                                                        <td><?= $value->remark ?> </td>
                                                    </tr>
                            <?php
                                                }
                                            }
                                        }
                                    }
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