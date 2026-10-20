<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <h5 class="card-title">Purchase Not Done
                    </h5>
                    <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->

                    <!-- Table with stripped rows -->
                    <table class="table datatable table-responsive" id="datatable">
                        <thead>
                            <tr>
                                <th scope="col" width="2%">#</th>
                                <th scope="col">Party Name</th>
                                <th scope="col">Purchase</th>
                                <th scope="col">REMARK</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($this->session->flashdata('msg')) { ?>
                            <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                <?php echo $this->session->flashdata('msg'); ?>
                            </div>
                            <?php $this->session->unset_userdata('msg'); } ?>
                            <?php
                                $this->db->select('*');
                                $this->db->from('crm_party');
                                $this->db->where('user_id', $this->session->userdata('user_id'));
                                $this->db->where('crm_id', $this->session->userdata('crm_id'));
                                $this->db->like('sub_services_id', $this->session->userdata('sub_service_id'));
                                $this->db->where('status', 1);
                                $partyDataa = $this->db->get()->result();
                                $month = $this->session->userdata('months');
                                $exp = explode('-', $month);
                                if (isset($partyDataa) && $partyDataa != '') {
                                    $count = 0;
                                    foreach ($partyDataa as  $key => $value) {
                                        $this->db->select('*');
                                        $this->db->from('crm_purchase');
                                        $this->db->where('user_id', $this->session->userdata('user_id'));
                                        $this->db->where('crm_id', $this->session->userdata('crm_id'));
                                        $this->db->where('party_id', $value->id);
                                        $this->db->where('month', $exp[0]);
                                        $this->db->where('year', $this->session->userdata('fy'));
                                        $saleEntry = $this->db->get()->row();
                                        if (empty($saleEntry)) {
                            ?>
                            <tr scope="<?= $key; ?>">
                                <td><span><?= ++$count; ?></span></td>
                                <td><?= $value->party_name; ?></td>
                                <td><?= "<button class='btn btn-danger'>Not Done</button>"; ?></td>
                                <td><?= $value->remark; ?></td>
                            </tr>
                            <?php } } } ?>
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                </div>
            </div>
        </div>
    </div>
</section>