<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <style>
        .control-label {
            color: red;
        }

        label {
            color: aliceblue;
        }
    </style>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header  pb-8 pt-5 pt-md-8" style="background: #2C4668;">
                        <div class="container-fluid mb-9" style="margin-top:10px;">
                            <div class="col">
                                <div class="card shadow">
                                    <div class="card-header border-0">
                                        <label class="mb-0 btn btn-warning btn-sm" style="float:right;"><a href="<?= base_url() ?>digitalSignatureApply">Back</a></label>
                                        <div class="table-responsive mt-2">
                                            <table class="table table-striped align-items-center table-flush" style="width: 100%" id="dataList">
                                                <tbody class="text-dark">
                                                    <?php
                                                    if (isset($result) && $result != '') {
                                                        $date = $result->created_at;
                                                    ?>
                                                        <tr>
                                                            <th>Date</th>
                                                            <td><?= $date; ?></td>
                                                            <th>Service</th>
                                                            <td><?= get_title('special_services', ['id' => $result->digital_certificate_id], 'service_name') ?></td>
                                                        </tr>

                                                        <tr>
                                                            <th>Email</th>
                                                            <td><?= $result->email; ?></td>
                                                            <th>Phone Number</th>
                                                            <td><?= $result->phone; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>User</th>
                                                            <td class="text-primary"><?= get_title('users', ['id' => $result->user_id], 'name') ?></td>
                                                            <th>Amount</th>
                                                            <td><?= $result->amount; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Payment Id</th>
                                                            <td class="text-warning"><?= $result->payment_id; ?></td>
                                                            <th>GST No</th>
                                                            <td class="text-success"><?= $result->gst_number; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>USB Token</th>
                                                            <td><?= $result->usb_token; ?></td>
                                                            <th>Certificate Type</th>
                                                            <td><?= $result->certificate_type; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Validity</th>
                                                            <td><?= $result->validity; ?></td>
                                                            <th> Type</th>
                                                            <td><?= $result->user_type; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>State</th>
                                                            <td><?= $result->state; ?></td>
                                                            <th> Address</th>
                                                            <td><?= $result->address; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Name</th>
                                                            <td><?= $result->name; ?></td>
                                                            <th>Status</th>
                                                            <td><?php
                                                                if ($result->status == 0)
                                                                    echo '<button class="btn btn-danger">Failed</button>';
                                                                elseif ($result->status == 1)
                                                                    echo '<button class="btn btn-warning">Under Processing</button>';
                                                                elseif ($result->status == 2)
                                                                    echo '<button class="btn btn-success">Approved</button>';
                                                                elseif ($value['status'] == 4)
                                                                    echo '<button class="btn btn-danger">Declined</button>';
                                                                ?></td>
                                                        </tr>
                                                        <tr>
                                                            <?php
                                                            if ($result->aadhar) {
                                                            ?>
                                                                <th>Aadhar:</th>
                                                                <td><a download href="<?= base_url() ?>uploads/digital_signature/aadhar/<?= $result->aadhar ?>"><?= $result->aadhar; ?></a></td>
                                                            <?php
                                                            }
                                                            if ($result->aadhar) {
                                                            ?>
                                                                <th> Pan Card:</th>
                                                                <td><a download href="<?= base_url() ?>uploads/digital_signature/pancard/<?= $result->pancard ?>"><?= $result->pancard; ?></a></td>
                                                            <?php
                                                            }
                                                            if ($result->org_doc) {
                                                            ?>
                                                        </tr>
                                                        <tr>
                                                            <th>Organisation Document:</th>
                                                            <td><a download href="<?= base_url() ?>uploads/digital_signature/org_doc/<?= $result->org_doc ?>"><?= $result->pancard; ?></a></td>
                                                        <?php
                                                            }
                                                        ?>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>

                                            <h3 class="text-center">Upload Documents</h3>
                                            <form id="signUpForm" class="card bg-white border border-primary p-3" action="<?= base_url() ?>supermaster/updateDigitalSignature" method="POST" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="form-group col-lg-6 pl-0 resaat" style=" color: black; ">
                                                        <label class="m-0" for="pancard" style=" color: black; "><b>Pan Card:</b></label>
                                                        <input type="file" class="form-control form-control-sm" id="pancard" name="pancard">
                                                        <td><a download href="<?= base_url() ?>uploads/digital_signature/pancard/<?= $result->pancard ?>"><?= $result->pancard; ?></a></td>

                                                    </div>
                                                    <input type="hidden" name="service_id" value="<?= $result->id ?>">
                                                    <div class="form-group col-lg-6 pl-0 resaat">
                                                        <label class="m-0" for="aadhar" style=" color: black; "><b>Address Proof(Aadhar Card):</b></label>
                                                        <input type="file" class="form-control form-control-sm" id="aadhar" name="aadhar">
                                                        <td><a download href="<?= base_url() ?>uploads/digital_signature/aadhar/<?= $result->aadhar ?>"><?= $result->aadhar; ?></a></td>

                                                    </div>
                                                    <div class="form-group col-lg-6 pl-0 resaat" id="organisation_documents">
                                                        <label class="m-0" for="org_doc" style=" color: black; "><b>Organisation Document:</b></label>
                                                        <input type="file" class="form-control form-control-sm" id="org_doc" name="org_doc">
                                                        <a download href="<?= base_url() ?>uploads/digital_signature/org_doc/<?= $result->org_doc ?>"><?= $result->pancard; ?></a>
                                                    </div>
                                                         <div class="form-group col-lg-6 pl-0 resaat" id="ice_certificate_data">
                                                        <label class="m-0" for="org_doc" style=" color: black; "><b>ICE Certificate:</b></label>
                                                        <input type="file" class="form-control form-control-sm" id="ice_certificate" name="ice_certificate">
                                                        <a download href="<?= base_url() ?>uploads/digital_signature/ice_certificate/<?= $result->ice_certificate ?>"><?= $result->ice_certificate; ?></a>
                                                    </div>
                                                    <div class="form-group col-lg-3 pl-0 resaat" style=" margin-top: 21px; ">
                                                        <input type="submit" class="btn btn-primary" id="submit" name="submit" value="upload Again">
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
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
        $("#signUpForm").on('submit', function(e) {
            e.preventDefault();
            var contactForm = $(this);
            $.ajax({
                url: "<?= base_url() ?>supermaster/updateDigitalSignature",
                type: "POST",
                enctype: "multipart/form-data",
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                success: function(response) {
                    if (response.status == true) {
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        });

        //         <form runat="server">
        //   <input accept="image/*" type='file' id="imgInp" />
        //   <img id="blah" src="#" alt="your image" />
        // </form>
        // imgInp.onchange = evt => {
        //     const [file] = imgInp.files
        //     if (file) {
        //         blah.src = URL.createObjectURL(file)
        //     }
        // }
    })
</script>