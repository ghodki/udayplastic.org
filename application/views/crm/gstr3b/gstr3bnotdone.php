<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">GSTR3B NOT DONE</h5>
                    <table class="table datatable table-responsive" id="datatable">
                        <thead>
                            <tr>
                                <th scope="col" width="2%">#</th>
                                <th scope="col">Party Name</th>
                                <th scope="col">GSTR3B</th>
                                <th scope="col">GSTR1</th>
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
                            $exp = explode('-', $month);
                            $gstr1Data = $this->user_model->getPartyForSubservices();
                            if (isset($gstr1Data) && $gstr1Data != '') {
                                $count = 0;
                                foreach ($gstr1Data as  $key => $value) {
                                    $sub_service_assigns = json_decode($value->sub_services_id);
                                    if ($sub_service_assigns) {
                                        foreach ($sub_service_assigns as $services_data_id) {
                                            if ($services_data_id == $this->session->userdata('sub_service_id')) {
                                                $yes = $this->user_model->getRow('crm_gstr3b', ['party_id' => $value->id, 'subservice_id' => $this->session->userdata('sub_service_id'), 'month' => $exp[0], 'year' => $this->session->userdata('fy')]);
                                                if (empty($yes)) {
                                                    $date = $value->date;
                                                    $i++;

                            ?>
                                                    <tr scope="<?= $i ?>">
                                                        <td>
                                                            <span><?= $i; ?></span>
                                                        </td>
                                                        <td>
                                                            <?= $value->party_name ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            echo "<button class='btn btn-danger'>Not Done</button>";
                                                            ?>
                                                        </td>
                                                        <td><?php
                                                            $gstr3bId = get_title('crm_gstr1', ['party_id' => $value->id, 'subservice_id' => $this->session->userdata('sub_service_id'), 'month' => $exp[0], 'year' => $this->session->userdata('fy')], 'id');
                                                            if ($gstr3bId) {
                                                                echo "<button class='btn btn-success'>Done</button>";
                                                            } else {
                                                                echo "<button class='btn btn-danger'>Not Done</button>";
                                                            }
                                                            ?></td>




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
                    <!-- End Table with stripped rows -->

                </div>
            </div>



        </div>
    </div>
</section>