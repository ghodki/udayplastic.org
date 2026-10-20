<div class="row">
    <?php
        $a5_where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
        if ($this->session->userdata('BranchRole')){
            $a5_where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
        }
        $invoice_title = get_title('a5config', $a5_where, 'title');
        if($this->session->userdata('BranchRole')){
            $contact_number = get_title('companybranch', ['BranchUserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'Id' => $this->session->userdata('CompBranchId')], 'Mobile');
            $company_name = get_title('companybranch', ['BranchUserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id') , 'Id' => $this->session->userdata('CompBranchId')], 'BranchName');
            $email = get_title('companybranch', ['BranchUserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'Id' => $this->session->userdata('CompBranchId')], 'Email');
        }else{
            $contact_number = get_title('inventory_billing', ['user_id' => $this->session->userdata('user_id'), 'id' => $this->session->userdata('comp_id')], 'number');
            $company_name = get_title('inventory_billing', ['user_id' => $this->session->userdata('user_id'), 'id' => $this->session->userdata('comp_id')], 'company_name');
            $email = get_title('inventory_billing', ['user_id' => $this->session->userdata('user_id'), 'id' => $this->session->userdata('comp_id')], 'email');
        }
        $invoiceNumber = $result->invoiceNumber;
        $customer_name = $result->customerName;
        $profile_where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
        if ($this->session->userdata('BranchRole')){
            $profile_where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
        }
        $comp_profile = $this->user_model->getRow('comp_profile', $profile_where);
    
        $email_subject = $comp_profile->email_subject;
        $smtp_username = $comp_profile->smtp_username;

        $email_subject = str_replace('{{inv_config.display_name}}', $invoice_title, $email_subject);
        $email_subject = str_replace('{{{invoice.invoice_no}}}', $invoiceNumber, $email_subject);
    
        $email_body = $comp_profile->email_body;
        $email_body = str_replace('{{customer.name}}', $customer_name, $email_body);
        $email_body = str_replace('{{inv_config.display_name}}', $invoice_title, $email_body);
        $email_body = str_replace('{{company.name}}', $company_name, $email_body);
        $email_body = str_replace('{{company.email}}', $email, $email_body);
        $email_body = str_replace('{{invoice.invoice_no}}', $invoiceNumber, $email_body);
        $email_body = str_replace('{{company.contact_no}}', $contact_number, $email_body);
    ?>

    <div class="" id="respnce_msg"></div>

    <form id="send_mails" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>sale/send_mail_to_user" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="form-group col-sm-12 col-md-12">
                <label class="control-label" for="email">Email<span class="text-danger"></span></label>
                <input autocomplete="off" type="email" name="email" required id="email" value="<?= $result->customerEmail  ?>" placeholder="Email" class="form-control">
                <span class="col-sm-4 control-label">
                    <?php echo form_error('email'); ?>
                </span>
            </div>
            <div class="form-group col-sm-12 col-md-12">
                <label for="email_cc" class="text-dark">CC</label>
                <input type="text" name="email_cc" class="form-control" required value="<?= $smtp_username ?>">
                <span class="col-sm-4 control-label">
                    <?php echo form_error('email_cc'); ?>
                </span>
            </div>
            <input type="hidden" name="sale_id" id="sale_id" value="<?= $result->id ?>">
            <div class="form-group col-sm-12 col-md-12">
                <label for="subject" class="text-dark">Subject</label>
                <input type="text" name="email_subject" class="form-control" required value="<?= $email_subject ?>">
            </div>
            <div class="form-group col-sm-12 col-md-12">
                <label for="body">Body</label>
                <textarea id="YourEditor" class="form-control YourEditor" required name="email_body"><?= $email_body ?></textarea>
                <script type="text/javascript">
                    ClassicEditor.create(document.querySelector('#YourEditor')).then(editor => {
                        console.log(editor);
                    }).catch(error => {
                        console.error(error);
                    });
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

<script type="text/javascript">
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
                        $("#common_modal").modal("hide");
                    } else {
                        toastr.error(responces.message);
                    }
                }
            });
        });
    })
</script>