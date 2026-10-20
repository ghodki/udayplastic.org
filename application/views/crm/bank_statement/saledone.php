<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <h5 class="card-title">Sale Done</h5>
                    <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->

                    <!-- Table with stripped rows -->
                    <table class="table datatable table-responsive" id="datatable">
                        <thead>
                            <tr>
                                <th scope="col" width="2%">#</th>
                                <th scope="col">Date</th>
                                <th scope="col">Party Name</th>
                                <th scope="col">Category</th>
                                <th scope="col">GSTR1</th>
                                <th scope="col">GSTR3B</th>
                                <th scope="col">SALE</th>
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
                            $user_id = $this->session->userdata('user_id');
                            $crm_id = $this->session->userdata('crm_id');
                            $months = $this->session->userdata('month'); $exp = explode("-", $months);
                            $this->db->select('*');
                            $this->db->from('crm_sale');
                            $this->db->where('user_id', $user_id);
                            $this->db->where('crm_id', $crm_id);
                            $this->db->where('category_id', $category_id);
                            $this->db->where('month', $exp[0]);
                            $this->db->where('year', $this->session->userdata('fy'));
                            $saleData = $this->db->get()->result();
                            if (isset($saleData) && $saleData != '') {
                                foreach ($saleData as  $key => $value) {

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
                                            <?= get_title('crm_party_category', ['id' => get_title('crm_party', ['id' => $value->party_id], 'category_id')], 'category_name') ?>
                                        </td>
                                        <td><?php
                                            $gstr3bId = get_title('crm_gstr1', ['party_id' => $value->party_id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')], 'id');
                                            if ($gstr3bId) {
                                                echo "<button class='btn btn-success'>Done</button>";
                                            } else {
                                                echo "<button class='btn btn-danger'>Not Done</button>";
                                            }
                                            ?></td>

                                        <td><?php
                                            $gstr3bId = get_title('crm_gstr3b', ['party_id' => $value->party_id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')], 'id');
                                            if ($gstr3bId) {
                                                echo "<button class='btn btn-success'>Done</button>";
                                            } else {
                                                echo "<button class='btn btn-danger'>Not Done</button>";
                                            }
                                            ?></td>


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