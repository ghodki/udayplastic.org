<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">SERVICES LIST</h5>
                    <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->

                    <!-- Table with stripped rows -->
                    <table class="table datatable table-responsive" id="datatable">
                        <thead>
                            <tr>

                                <th scope="col" width="2%">#</th>
                                <th scope="col">Date</th>
                                <th scope="col">Service Name</th>
                                <th scope="col">STATUS</th>
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
                            $this->db->select('*');
                            $this->db->from('crm_service');
                            $this->db->where('status', 0);
                            $crm_service = $this->db->get()->result();
                            if (isset($crm_service) && $crm_service != '') {
                                foreach ($crm_service as  $key => $value) {

                                    $date = strtotime('d-m-Y', $value->created_at);

                            ?>
                                    <tr scope="<?= $key ?>">
                                        <td>
                                            <span><?= $key + 1; ?></span>
                                        </td>
                                        <td><?= $date; ?></td>

                                        <td>
                                            <?= $value->service_name ?>
                                        </td>


                                        <?php

                                        if ($value->status == 1) {
                                        ?>
                                            <td class="text-blue"><button class="btn btn-success" onclick="changeStatus(<?= $value->id ?>,0)">Active</button></td>
                                        <?php
                                        } else {
                                        ?>
                                            <td class="text-blue"><button class="btn btn-danger">Deactivated</button></td>


                                        <?php

                                        }
                                        ?>

                                       
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