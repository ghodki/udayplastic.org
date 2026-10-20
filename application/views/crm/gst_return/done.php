<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <h5 class="card-title">GST RETURN DONE</h5>
                    <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->

                    <!-- Table with stripped rows -->
                    <table class="table datatable table-responsive" id="datatable">
                        <thead>
                            <tr>
                                <th scope="col" width="2%">#</th>
                                <th scope="col">Date</th>
                                <th scope="col">Party Name</th>
                                <th scope="col">GST RETURN</th>
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
                            $details_receivedData=$this->user_model->getMonthWiseForSubServices('crm_gst_return');
                            if (isset($details_receivedData) && $details_receivedData != '') {
                                foreach ($details_receivedData as  $key => $value) {
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
                    <!-- End Table with stripped rows -->

                </div>
            </div>



        </div>
    </div>
</section>