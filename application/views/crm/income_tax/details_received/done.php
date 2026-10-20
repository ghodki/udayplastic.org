<section class="section">
    <div class="row">
        <?php
        $subServiceName = getSubServiceName();
        ?>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?= $subServiceName ?> DONE</h5>
                    <table class="table datatable table-responsive" id="datatable">
                        <thead>
                            <tr>
                                <th scope="col" width="2%">#</th>
                                <th scope="col">Date</th>
                                <th scope="col">Party Name</th>
                                <th scope="col">Status</th>
                                <th scope="col">Remark</th>
                                <th scope="col">Action</th>
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
                            $result = $this->user_model->getSubServiceData('crm_details_received');
                            if (isset($result) && $result != '') {
                                foreach ($result as  $key => $value) {
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
                                        <td>
                                            <?php
                                            if ($value->status == 1) {
                                                echo '<button class="btn btn-success">Approved</button>';
                                            } elseif ($value->status == 0) {
                                                echo '<button class="btn btn-danger">Pending</button>';
                                            } else {
                                                echo get_title('crm_status', ['id' => $value->status], 'name');
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?= $value->remark ?>
                                        </td>
                                        <td class="text-right">
                                            <a href="<?= getCrmUrl() ?>master/delete_details_received/<?= $value->id ?>"><button class="btn btn-danger" onclick='return confirm("Are You Sure Want to delete details_received?")'><i class="bi bi-trash"></i></button></a>
                                            <button class="btn btn-primary" onclick="viewModel(<?= $value->id ?>)"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-primary" onclick="companyEdit(<?= $value->id ?>)"><i class="ri-edit-box-line"></i></button>
                                        </td>
                                    </tr>
                            <?php
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