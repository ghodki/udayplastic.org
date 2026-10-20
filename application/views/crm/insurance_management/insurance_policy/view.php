<div id="messages" class="text-center"></div>
<form id="updateForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/master/update_insurance_policy" method="POST" enctype="multipart/form-data">

    <div class="row text-dark">

        <input type="hidden" name="id" value="<?= $result->id ?>">


        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="applicant_name">Applicant name<span class="text-danger">*</span></label>
                <input id="applicant_name" name="applicant_name" placeholder="Applicant Name" type="text" value="<?= $result->applicant_name ?>" required class="form-control" readonly>
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('applicant_name'); ?>

            </span>
        </div>


        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="org_name">ORG Name<span class="text-danger"></span></label>
                <input id="org_name" name="org_name" placeholder="ORG Name" value="<?= $result->org_name ?>" type="text" class="form-control" readonly>
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('org_name'); ?>

            </span>
        </div>

        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="email">Email<span class="text-danger"></span></label>
                <input id="email" name="email" required placeholder="Email" type="email" value="<?= $result->email ?>" class="form-control" readonly>
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('email'); ?>

            </span>
        </div>



        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="mobile_number">Mobile Number<span class="text-danger"></span></label>
                <input id="mobile_number" name="mobile_number" placeholder="Mobile Number" value="<?= $result->mobile_number ?>" type="text" class="form-control" readonly>
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('mobile_number'); ?>

            </span>
        </div>

        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="type">Type<span class="text-danger">*</span></label>
                <select class="form-control select2" disabled required name="type" id="type">
                    <option value="">Select Type</option>

                    <?php
                    $this->db->select('*');
                    $this->db->from('crm_type');
                    $this->db->where('status', 1);
                    $getResu = $this->db->get()->result();
                    if ($getResu) {
                        foreach ($getResu as $category) {
                    ?>
                            <option value="<?= $category->id ?>" <?= $result->type == $category->id ? 'selected' : '' ?>><?= $category->name ?></option>


                    <?php
                        }
                    }
                    ?>
                </select>

            </div>

        </div>

        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="ref_by">Ref By<span class="text-danger"></span></label>
                <input id="ref_by" name="ref_by" placeholder="Ref By" value="<?= $result->ref_by ?>" type="text" class="form-control" readonly>
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('ref_by'); ?>
            </span>
        </div>

        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <?php
                $last_month = date('Y-m-d');
                ?>
                <label for="date">Date<span class="text-danger">*</span></label>
                <input id="date" name="date" placeholder="Date" type="date" required class="form-control" readonly value="<?= set_value('date', $result->date) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('date'); ?>

            </span>
        </div>

        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <?php
                // $last_month = date('Y-m-d');
                $new_date = date('Y-m-d', strtotime('+2 years', strtotime($last_month)));
                ?>
                <label for="expiry_date">Expiry Date<span class="text-danger">*</span></label>
                <input id="expiry_date" name="expiry_date" placeholder="Expiry Date" type="date" required class="form-control" readonly value="<?= set_value('date', $result->expiry_date) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('expiry_date'); ?>

            </span>
        </div>

        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="status">Status<span class="text-danger">*</span></label>
                <select disabled class="form-control select2" required name="status" id="status">
                    <option value="">Select Status</option>

                    <?php
                    $this->db->select('*');
                    $this->db->from('crm_status');
                    $this->db->where('status', 1);
                    $getResu = $this->db->get()->result();
                    if ($getResu) {
                        foreach ($getResu as $category) {
                    ?>
                            <option value="<?= $category->id ?>" <?= $result->status == $category->id ? 'selected' : '' ?>><?= $category->name ?></option>


                    <?php
                        }
                    }
                    ?>
                </select>

            </div>

        </div>

        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="form-group">
                <label for="remark">Remark<span class="text-danger"></span></label>
                <textarea id="remark" name="remark" placeholder="Remark" type="text" class="form-control" readonly value="<?= set_value('remark') ?>"><?= $result->remark ?></textarea>
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('remark'); ?>

            </span>
        </div>


    </div>
    <div class="modal-footer" hidden>
        <button type="button" class="btn btn-secondary" onclick="closeModel()">Close</button>
        <button class="btn btn-primary" type="submit">Update</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#updateForm").on('submit', function(e) {
            e.preventDefault();
            var contactForm = $(this);
            $.ajax({
                url: contactForm.attr('action'),
                type: 'post',
                data: contactForm.serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.status == 'success') {
                        $("#messages").html(response.message);
                        toastr.success(response.message);
                        $("#exampleModals").modal("hide");
                        myStopFunction();

                    } else {
                        toastr.error(response.message);

                    }


                }
            });
        })
    })

    function closeModel() {
        $("#viewModel").modal("hide");
    }

    function myStopFunction() {
        clearTimeout(1000);
        window.location.reload();

    }
</script>