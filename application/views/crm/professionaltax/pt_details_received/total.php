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

                            $details_receivedData = $this->user_model->getPartyForSubservices();
                            $count = 0;
                            if (isset($details_receivedData) && $details_receivedData != '') {
                                foreach ($details_receivedData as  $key => $deta) {
                                    $sub_service_assigns = json_decode($deta->sub_services_id);
                                    if ($sub_service_assigns) {
                                        foreach ($sub_service_assigns as $services_data_id) {
                                            if ($services_data_id == $this->session->userdata('sub_service_id')) {
                                                $month = $this->session->userdata('months'); $exp = explode('-', $month);
                                                $wheres = ['party_id' => $deta->id, 'subservice_id' => $this->session->userdata('sub_service_id'), 'crm_id' => $deta->crm_id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
                                                $value = $this->user_model->getRow('crm_pt_details_received', $wheres);
                                                $date = $value->date;
                            ?>
                                                <tr scope="<?= $key ?>">
                                                    <td><span><?= ++$count; ?></span> </td>
                                                    <td><?= $deta->party_name ?></td>
                                                    <td>
                                                        <?php
                                                        if ($value) {
                                                            if ($value->status == 1) {
                                                                echo '<button class="btn btn-success">Approved</button>';
                                                            } elseif ($value->status == 0) {
                                                                echo '<button class="btn btn-danger">Pending</button>';
                                                            } else {
                                                                echo get_title('crm_status', ['id' => $value->status], 'name');
                                                            }
                                                        } else {
                                                            echo '<button class="btn btn-warning">Not Done</button>';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                            <?php
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