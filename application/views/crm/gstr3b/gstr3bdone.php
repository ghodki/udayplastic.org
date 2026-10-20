<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">GSTR3B DONE</h5>
                    <table class="table datatable table-responsive" id="datatable">
                        <thead>
                            <tr>
                                <th scope="col" width="2%">#</th>
                                <th scope="col">Date</th>
                                <th scope="col">Party Name</th>
                                <th scope="col">GSTR3B</th>
                                <th scope="col">GSTR1</th>
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
                            $gstr1Data = $this->user_model->getMonthWiseForSubServices('crm_gstr3b');
                            if (isset($gstr1Data) && $gstr1Data != '') {
                                foreach ($gstr1Data as  $key => $value) {
                                    $date = $value->date;
                            ?>
                                    <tr scope="<?= $key ?>">
                                        <td>
                                            <span><?= $key + 1; ?></span>
                                        </td>
                                        <td><?= $date; ?></td>
                                        <td>
                                            <?= get_title('crm_party', ['id' => $value->party_id], 'party_name') ?>
                                        </td>
                                        <td><?php
                                            echo "<button class='btn btn-success'>Done</button>";
                                            ?></td>
                                        <td><?php
                                            $gstr3bId = get_title('crm_gstr1', ['party_id' => $value->party_id, 'subservice_id' => $this->session->userdata('sub_service_id'), 'month' => $mont, 'year' => $this->session->userdata('fy')], 'id');
                                            if ($gstr3bId) {
                                                echo "<button class='btn btn-success'>Done</button>";
                                            } else {
                                                echo "<button class='btn btn-danger'>Not Done</button>";
                                            }
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