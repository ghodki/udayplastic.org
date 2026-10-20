<!-- Card stats -->
<div id="resultMessage" class="text-center"></div>
<div class="row">
    <form id="updateForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/company/update" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
                <div class="form-group">
                    <label for="name">Company Name</label>
                    <input id="name" name="name" placeholder="Company Name" type="text" class="form-control" required="true" value="<?= $result->company_name; ?>">
                </div>
            </div>
            <div class="col-md-4 col-md-4 col-sm-6 col-lg-6">
                <lable for="state">Financial Year<span class="text-danger">*</span></lable>

                <select name="financial_year" id="financial_year" class="form-control" required>
                    <option value="">-Select-</option>
                    <?php $financial_year = $this->db->get('year')->result();
                    if ($financial_year) {
                        foreach ($financial_year as $row) { ?>
                            <option value="<?= $row->id ?>" <?= $result->financial_year == $row->id ? 'selected' : ''; ?>><?= $row->year; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <input type="hidden" name="id" value="<?= $result->id; ?>">
            <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
                <div class="form-group">
                    <label for="number">Phone Number</label>
                    <input name="number" placeholder="Mobile Number" type="text" class="form-control" value="<?= $result->number; ?>">
                </div>
            </div>
            <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" placeholder=" Email" type="email" class="form-control" value="<?= $result->email; ?>">
                </div>
            </div>
            <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
                <div class="form-group">
                    <label for="gst">GST Number</label>
                    <input name="gst" placeholder="GST Number" type="text" class="form-control" value="<?= $result->gst_no; ?>">
                </div>
            </div>
            <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
                <div class="form-group">
                    <label for="contact">Contact Person</label>
                    <input name="contact" placeholder="Contact person" type="text" class="form-control" value="<?= $result->contact_person; ?>">
                </div>
            </div>
            <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
                <label for="state">State</label>
                <select name="state" id="state" class="form-control">
                    <?php
                    $acgroup = $this->user_model->getResult('state_list');
                    if ($acgroup) {
                        foreach ($acgroup as $row) {
                    ?>
                            <option value="<?= $row->id ?>" <?= $result->state == $row->id ? 'selected' : ''; ?>><?= $row->state; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
                <div class="form-group">
                    <label for="address">Address</label>
                    <input name="address" placeholder="Address" type="text" class="form-control" value="<?= $result->address; ?>">
                </div>
            </div>

            <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
                <select id="cate" name="cate_id" class="form-control" required>
                    <?php
                    $cate = $this->user_model->getResult('billing_format');
                    if ($cate) {
                        foreach ($cate as $key => $value) {
                    ?>
                            <option value="<?= $value->id ?>" <?= $result->billing_format_id == $value->id ? 'selected' : ''; ?>><?= $value->name; ?></option>


                    <?php    }
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
                <select id="ac" name="ac" class="form-control" required>
                    <option value="<?= 1 ?>" <?= $result->accounting == 1 ? 'selected' : ''; ?>><?= "Basic"; ?></option>
                    <option value="<?= 2 ?>" <?= $result->accounting == 2 ? 'selected' : ''; ?>><?= "Advanced"; ?></option>
                </select>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-default">
                    Update
                </button>
                <button class="btn btn-danger" type="reset" class="btn btn-secondary">Cancel</button>
            </div>
        </div>
    </form>
</div>


<script>
    $(document).ready(function() {

        function close() {
            window.location.reload();
        }

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
                        $("#resultMessage").addClass('text-success');
                        $('resultMessage').removeClass('text-danger');
                        $("#resultMessage").html(response.message);
                        setTimeout(function() {
                            window.location.href = "<?= getCrmUrl() ?>company";
                        }, 1000);
                    } else {
                        $("#resultMessage").addClass('text-danger');
                        $('resultMessage').removeClass('text-success');
                        $("#resultMessage").html(response.message);

                    }


                }
            });
        })
    })
</script>