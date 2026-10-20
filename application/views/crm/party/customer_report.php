<main id="main" class="main">

    <div class="pagetitle">
        <h1>Party</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>crm/company">Home</a></li>
                <li class="breadcrumb-item active">Party</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card">
            <div class="card-body" style="padding-bottom:10px;">
                <!-- Large Modal -->
                <button type="button" style="margin-top:10px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal">
                    Add Party
                </button>
                <button type="button" style="margin-top:10px;float: right;" class="btn btn-primary">
                    <a href="<?= base_url() ?>inventory_billing" target="_blank" class="text-white">
                        Inventory Software</a>
                </button>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Customer Report

                        </h5>
                        <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->

                        <!-- Table with stripped rows -->
                        <table class="table datatable table-responsive" id="datatable">
                            <thead>
                                <tr>
                                    <th scope="col" width="2%">#</th>
                                    <th scope="col">Party Name</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">GSTR1</th>
                                    <th scope="col">GSTR3B</th>
                                    <th scope="col">SALE</th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $user_id = $this->session->userdata('user_id');
                                $crm_id = $this->session->userdata('crm_id');
                                $this->db->select('*');
                                $this->db->from('crm_party');
                                $this->db->where('user_id', $user_id);
                                $this->db->where('crm_id', $crm_id);
                                $this->db->where('status', 1);
                                $partyDataa = $this->db->get()->result();


                                if (isset($partyDataa) && $partyDataa != '') {
                                    foreach ($partyDataa as  $key => $value) {




                                ?>
                                        <tr scope="<?= $key ?>">
                                            <td>
                                                <span><?= $key + 1; ?></span>
                                            </td>
                                            <td>
                                                <?= $value->party_name ?>
                                            </td>
                                            <td>
                                                <?= get_title('crm_party_category', ['id' => $value->category_id], 'category_name') ?>
                                            </td>
                                            <td><?php
                                                $gstr3bId = get_title('crm_gstr1', ['party_id' => $value->id], 'id');
                                                if ($gstr3bId) {
                                                    echo "<button class='btn btn-success'>Done</button>";
                                                } else {
                                                    echo "<button class='btn btn-danger'>Not Done</button>";
                                                }
                                                ?></td>

                                            <td><?php
                                                $gstr3bId = get_title('crm_gstr3b', ['party_id' => $value->id], 'id');
                                                if ($gstr3bId) {
                                                    echo "<button class='btn btn-success'>Done</button>";
                                                } else {
                                                    echo "<button class='btn btn-danger'>Not Done</button>";
                                                }
                                                ?></td>


                                            <td><?php
                                                $saleStatus = get_title('crm_sale', ['party_id' => $value->id], 'id');
                                                if ($saleStatus) {
                                                    echo "<button class='btn btn-success'>Done</button>";
                                                } else {
                                                    echo "<button class='btn btn-danger'>Not Done</button>";
                                                }
                                                ?></td>

                                         


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
</main>