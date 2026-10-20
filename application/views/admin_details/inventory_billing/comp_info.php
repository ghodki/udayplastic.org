<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid" style="margin-top:85px;">

            <div class="container-fluid mb-9">

                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0" style="margin-top:70px;">
                            <!-- <label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Transaction Details</label> -->

                            <div class="row">
                                <div class="col-md-4">
                                    <h1>Company Details</h1>
                                </div>
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-2">
                                    <!-- <h1>Back</h1> -->
                                    <a class="nev-link" href="<?= base_url() ?>inventory_billing" title="Sale List">
                                        <!-- <i style="margin-top:12px;" class="fas fa-list"></i> -->
                                        Back
                                    </a>
                                </div>
                            </div>
                            <div class="table-responsive mt-5">
                                <table class="table  align-items-center table-flush" style="width: 100%" id="example_contra">
                                    <!-- <thead class="thead-dark">
										<tr>
											<th scope="col" width="2%">#</th>
											<th scope="col" width="20%">Name</th>
											<th scope="col" width="30%">Amount</th>
											<th scope="col" width="28%">Transaction Date</th>
											<th scope="col" width="18%">By Added</th>
										</tr>
									</thead> -->
                                    <tbody class="text-dark">

                                        <?php

                                        // if ($this->session->userdata('roles') != 2) {
                                        if (isset($result) && $result != '') {

                                            // foreach ($wallet as  $key => $value) {
                                            $date = $result->created_at;
                                            $newDate = date("d-m-Y", strtotime($date));
                                        ?>
                                            <tr>
                                                <td>
                                                    Company Name
                                                </td>
                                                <th><?= $result->company_name ?></th>
                                                <td>
                                                    State Name
                                                </td>
                                                <th><?= get_title('state_list', ['id' => $result->state], 'state') ?></th>

                                            </tr>
                                            <tr>
                                                <td>
                                                    GST NO
                                                </td>
                                                <th><?= $result->gst_no ?></th>
                                                <td>
                                                    Contact Number
                                                </td>
                                                <th><?= $result->contact_person ?></th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Address
                                                </td>
                                                <th><?= $result->address ?></th>
                                                <td>
                                                    Phone:
                                                </td>
                                                <th><?= $result->number ?></th>
                                            </tr>

                                            <tr>
                                                <td>
                                                    Comp Created at:
                                                </td>
                                                <th><?= $newDate ?></th>

                                            </tr>

                                        <?php }


                                        ?>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#example_contra').DataTable();
    });
</script>