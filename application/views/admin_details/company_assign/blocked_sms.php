<div class="row">
    <?php

    $smtp_host = get_title('general_settings', ['general_settings_id' => 72], 'value');
    $smtp_port = get_title('general_settings', ['general_settings_id' => 73], 'value');
    $smtp_user = get_title('general_settings', ['general_settings_id' => 74], 'value');
    $smtp_pass = get_title('general_settings', ['general_settings_id' => 75], 'value');
    // $footer_title = get_title('general_settings', ['general_settings_id' => 42], 'value');

    // $invoice_title = get_title('a5config', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')], 'title');
    // $contact_number = get_title('inventory_billing', ['user_id' => $this->session->userdata('user_id'), 'id' => $this->session->userdata('comp_id')], 'number');
    // $company_name = get_title('inventory_billing', ['user_id' => $this->session->userdata('user_id'), 'id' => $this->session->userdata('comp_id')], 'company_name');
    // $email = get_title('inventory_billing', ['user_id' => $this->session->userdata('user_id'), 'id' => $this->session->userdata('comp_id')], 'email');
    // $invoiceNumber = $result->invoiceNumber;
    // $customer_name = $result->customerName;
    // // $customer_name = get_title('users', ['id' => $this->session->userdata('user_id')], 'name');
    // $comp_profile = $this->user_model->getRow('comp_profile', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);

    // $email_subject      = $comp_profile->email_subject;
    // $smtp_username = $comp_profile->smtp_username;
    // // echo '<pre>';
    // // print_r($comp_profile);
    // // // die;
    // $email_subject      = str_replace('{{inv_config.display_name}}', $invoice_title, $email_subject);
    // $email_subject      = str_replace('{{{invoice.invoice_no}}}', $invoiceNumber, $email_subject);

    // $email_body      = $comp_profile->email_body;
    // $email_body      = str_replace('{{customer.name}}', $customer_name, $email_body);
    // $email_body      = str_replace('{{inv_config.display_name}}', $invoice_title, $email_body);
    // $email_body      = str_replace('{{company.name}}', $company_name, $email_body);
    // $email_body      = str_replace('{{company.email}}', $email, $email_body);
    // $email_body      = str_replace('{{invoice.invoice_no}}', $invoiceNumber, $email_body);
    // $email_body      = str_replace('{{company.contact_no}}', $contact_number, $email_body);

    ?>

    <div class="" id="respnce_msg"></div>

    <!-- <form action="<?= base_url() ?>party/update_party" method="post" > -->
    <form id="send_mails" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>company_assign/send_mail_to_user" method="POST" enctype="multipart/form-data">
        <div class="row">
            <!-- <div class="form-group col-sm-6 col-md-6">
                <label class="control-label" for="email">Email<span class="text-danger"></span></label>
                <input autocomplete="off" type="email" name="email" required id="email" value="<?= $result->email  ?>" placeholder="Email" class="form-control">
                <span class="col-sm-4 control-label">
                    <?php echo form_error('email'); ?>
                </span>
            </div>
            <div class="form-group col-sm-6 col-md-6">
                <label for="email_cc" class="text-dark">CC</label>
                <input type="text" name="email_cc" class="form-control" required value="<?= $smtp_user ?>">
                <span class="col-sm-4 control-label">
                    <?php echo form_error('email_cc'); ?>
                </span>
            </div> -->
            <input type="hidden" name="company_id" id="company_id" value="<?= $result->id ?>">
            <div class="form-group col-sm-6 col-md-6">
                <label for="subject" class="text-dark">Subject</label>
                <input type="text" name="email_subject" class="form-control" required value="Regarding over meesages of your limit on inventory">
            </div>
            <div class="form-group col-sm-6 col-md-6">
                <label for="status" class="text-dark">Status</label>
                <select class="form-control" name="status" id="status">
                    <option value="Enable" <?= $result->sms_blocked_status == 'Enable' ? 'selected' : '' ?>>Enable</option>
                    <option value="Disable" <?= $result->sms_blocked_status == 'Disable' ? 'selected' : '' ?>>Disable</option>

                </select>

            </div>
            <div class="form-group col-sm-12 col-md-12">

                <label for="body">Message</label>
                <textarea id="YourEditor123" class="form-control YourEditor" required name="email_body"></textarea>
                <script>
                    // CKEDITOR.replace('YourEditor');
                    CKEDITOR.replace('YourEditor123');
                </script>
            </div>



        </div>

        <div class="row">
            <div class="form-group">
                <input autocomplete="off" type="submit" class="btn btn-primary" id="send_mail" value="Send Mail">
                </button>
                <button class="btn btn-danger">
                    <span aria-hidden="true" class="close" data-dismiss="modal" aria-label="Close">Close</span>

                </button>
            </div>
        </div>

    </form>
</div>
<script>
    $(function() {
        $("#send_mails").on('submit', function(e) {
            e.preventDefault();
            var contactForm = $(this);
            $.ajax({
                url: contactForm.attr('action'),
                type: 'post',
                data: contactForm.serialize(),
                success: function(response) {
                    console.log(response);
                    responces = JSON.parse(response);
                    if (responces.status == true) {
                        toastr.success(responces.message);
                        $("#sms_block").modal("hide");

                    } else {
                        toastr.error(responces.message);

                    }

                }
            });
        });
    })
</script>