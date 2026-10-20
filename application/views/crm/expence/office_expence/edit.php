<div id="message" class="text-center"></div>
<form id="signUpForm" autocomplete="off" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/master/officeExpenceUpdate" method="POST" enctype="multipart/form-data">
    <div class="modal-body">
        <div class="row text-dark">

            <div class="col-md-6 col-sm-6 col-lg-6">
                <div class="form-group">
                    <label for="applicant_name">Applicant name<span class="text-danger">*</span></label>
                    <input id="applicant_name" name="applicant_name" placeholder="Applicant Name" value="<?= $result->applicant_name ?>" type="text" required class="form-control">
                </div>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('applicant_name'); ?>

                </span>
            </div>

            <div class="col-md-6 col-sm-6 col-lg-6">
                <div class="form-group">
                    <label for="amount">Amount<span class="text-danger">*</span></label>
                    <input id="amount" name="amount" min="0" placeholder="Amount" type="number" value="<?= $result->amount ?>" required class="form-control">
                </div>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('amount'); ?>

                </span>
            </div>
            <input type="hidden" name="id" value="<?= $result->id ?>">

            <div class="col-md-6 col-sm-6 col-lg-6">
                <div class="form-group">
                    <label for="paymentmode">Payment Mode<span class="text-danger"></span></label>
                    <select class="form-control select2" name="paymentmode" id="paymentmode">
                        <option value="">Select Paymentmode</option>

                        <?php
                        $this->db->select('*');
                        $this->db->from('crm_paymentmode');
                        $this->db->where('status', 1);
                        $getResu = $this->db->get()->result();
                        if ($getResu) {
                            foreach ($getResu as $category) {
                        ?>
                                <option value="<?= $category->id ?>" <?= $result->paymentmode == $category->id ? 'selected' : '' ?>><?= $category->name ?></option>


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
                    <textarea id="remark" name="remark" placeholder="Remark" type="text" class="form-control"><?= $result->reamrk ?></textarea>
                </div>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('remark'); ?>

                </span>
            </div>
        </div>



        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary" type="submit">Update</button>
        </div>
    </div>
</form>

<script>
  
        $("#signUpForm").on('submit', function(e) {
            e.preventDefault();
            var contactForm = $(this);
            $.ajax({
                url: contactForm.attr('action'),
                type: 'post',
                data: contactForm.serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.status == 'success') {
                        $("#message").html(response.message);
                        toastr.success(response.message);
                        $("#exampleModals").modal("hide");
                        myStopFunction();

                    } else {
                        toastr.error(response.message);

                    }


                }
            });
        })
    

    function closeModel() {
        $("#exampleModals").modal("hide");
    }

    function myStopFunction() {
        clearTimeout(1000);
        window.location.reload();

    }
</script>