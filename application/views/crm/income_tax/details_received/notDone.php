<section class="section">
    <?php
    $subServiceName = getSubServiceName();
    ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?= $subServiceName ?> DONE</h5>
                    <table class="table datatable table-responsive" id="datatable">
                        <thead>
                            <tr>

                                <th scope="col">#</th>
                                <th scope="col">Party Name</th>
                                <th scope="col">Status</th>
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
                            $getResu = $this->user_model->getPartyForSubservices();
                            if (isset($getResu) && $getResu != '') {
                                foreach ($getResu as  $key => $deta) {
                                    $sub_service_assigns = json_decode($deta->sub_services_id);
                                    if ($sub_service_assigns) {
                                        foreach ($sub_service_assigns as $services_data_id) {
                                            if ($services_data_id == $this->session->userdata('sub_service_id')) {
                                                $months = $this->session->userdata('months'); $exp = explode("-", $months);
                                                $wheres = ['party_id' => $deta->id, 'subservice_id' => $this->session->userdata('sub_service_id'), 'crm_id' => $deta->crm_id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
                                                $value = $this->user_model->getRow('crm_details_received', $wheres);
                                                if (!$value) {
                                                    $date = $value->date;
                            ?>
                                                    <tr scope="<?= $key ?>">
                                                        <td>
                                                            <span><?= $key + 1; ?></span>
                                                        </td>
                                                        <td>
                                                            <?= $deta->party_name ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            echo '<button class="btn btn-warning">Not Done</button>';
                                                            ?>
                                                        </td>



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