<style type="text/css">
    .dt-buttons {
        left: 20%;
    }
    th, td {
        white-space: nowrap;
    }
    .card-body {
        padding: 0.25rem;
    }
    .form-group {
        margin-bottom: 0rem;
    }
</style>

<div class="content-wrapper">
    <div class="content-header mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                    <div class="card card-outline card-dark">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-dark">
                                    Company's List
                                </h5>
                            </div>
                            <?php if ($this->session->userdata('subuser')) { ?>
                            <button type="button" class="btn btn-dark pull-right btn-sm" data-toggle="modal" data-target="#myModal">
                                Add New Company
                            </button>
                            <?php } ?>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-sm dataTable">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Date</th>
                                            <th>Company Name</th>
                                            <th>Email</th>
                                            <th>State</th>
                                            <th>GST Number</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $i = 0;
                                            if (isset($inventory_billing) && $inventory_billing != '') {
                                                foreach ($inventory_billing as  $key => $value) {
                                                $date = $value->created_at;
                                                $newDate = date("d-m-Y", strtotime($date));
                                        ?>
                                        <tr>
                                            <td><span><?= ++$i; ?></span></td>
                                            <td><?= $newDate; ?></td>
                                            <td>
                                                <a href="<?= base_url('inventory_billing/redirect/') . $value->id ?>" class="nav-link">
                                                    <?= $value->company_name; ?>
                                                </a>
                                            </td>
                                            <td><?= $value->email ?></td>
                                            <td><?= get_title('state_list', ['id' => $value->state], 'state') ?></td>
                                            <td><?= $value->gst_no ?></td>
                                            <td>
                                                <a href="<?= base_url(); ?>inventory_billing/view/<?= $value->id; ?>" class="btn btn-primary btn-sm" title="View">
                                                    <i class="fa fa-info-circle"></i>
                                                </a>
                                                <a href="<?= base_url(); ?>inventory_billing/edit/<?= $value->id; ?>" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <!--<a href="<?= base_url(); ?>inventory_billing/delete/<?= $value->id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                    <i class="fa fa-trash"></i>
                                                </a>-->
                                            </td>
                                        </tr>
                                        <?php } } 
                                            $this->db->select('*');
                                            $this->db->from('company_assign');
                                            $this->db->where('assign_user', $this->session->userdata('user_id'));
                                            $assigned = $this->db->get()->result();
                                            if (isset($assigned) && $assigned != '') {
                                                foreach ($assigned as  $key => $value) {
                                                    $another_comp_details = $this->user_model->getRow('inventory_billing', ['id' => $value->comp_id]);
                                                    if ($another_comp_details) {
                                                        $date = $another_comp_details->created_at;
                                                        $newDate = date("d-m-Y", strtotime($date));
                                        ?>
                                        <tr>
                                            <td><span><?= ++$i; ?></span></td>
                                            <td><?= $newDate; ?></td>
                                            <td><a href="<?= base_url('inventory_billing/viewothercomp/') . $another_comp_details->id ?>" class="nav-link"><?= $another_comp_details->company_name ?></a></td>
                                            <td><?= $another_comp_details->email ?></td>
                                            <td><?= get_title('state_list', ['id' => $another_comp_details->state], 'state') ?></td>
                                            <td><?= $another_comp_details->gst_no ?></td>
                                            <td class="text-right"></td>
                                        </tr>
                                        <?php } } } ?>
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

<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Add Company</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="header-body">
                    <!-- Card stats -->
                    <div class="row">
                        <div id="message" class="text-center"></div>
                        <form id="signUpForm" action="<?= base_url(); ?>inventory_billing/save" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-lg-3">
                                    <div class="form-group">
                                        <label for="name">Company Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="<?= set_value('name'); ?>" id="name" class="form-control form-control-sm" placeholder="Company Name*" required="" />
                                        <span class="col-sm-3 control-label">
                                            <?= form_error('name'); ?>				
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-lg-3">
                                    <div class="form-group">
                                        <label for="number">Mobile No</label>
                                        <input type="text" name="number" value="<?= set_value('number'); ?>" id="number" class="form-control form-control-sm" placeholder="Mobile Number" />
                                        <span class="col-sm-3 control-label">
                                            <?= form_error('number'); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-lg-3">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" value="<?= set_value('email'); ?>" id="email" class="form-control form-control-sm" placeholder="Email" />
                                        <span class="col-sm-3 control-label">
                                            <?= form_error('email'); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-lg-3">
                                    <div class="form-group">
                                        <label for="gst">GST No</label>
                                        <input type="text" name="gst" value="<?= set_value('gst'); ?>" id="gst" class="form-control form-control-sm" placeholder="GST Number" />
                                        <span class="col-sm-3 control-label">
                                            <?= form_error('gst'); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-lg-3">
                                    <div class="form-group">
                                        <label for="contact">Contact Person</label>
                                        <input type="text" name="contact" value="<?= set_value('contact'); ?>" id="contact" class="form-control form-control-sm" placeholder="Contact Person" />
                                        <span class="col-sm-3 control-label">
                                            <?= form_error('contact'); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-lg-3">
                                    <div class="form-group">
                                        <label for="state">State Name <span class="text-danger">*</span></label>
                                        <select name="state" id="state" class="form-control form-control-sm" required="">
                                            <option value="">-Select-</option>
                                            <?php 
                                                $states = $this->db->get('state_list')->result();
                                                if ($states) {
                                                    foreach ($states as $row) { 
                                            ?>
                                            <option value="<?= $row->id; ?>" data-id="<?= $row->code; ?>" data-state="<?= $row->state; ?>" <?= set_select('state', $row->id); ?>><?= $row->state; ?></option>
                                            <?php } } ?>
                                        </select>
                                        <span class="col-sm-3 control-label">
                                            <?= form_error('state'); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="adddress">Address</label>
                                        <textarea name="address" id="address" class="form-control form-control-sm" placeholder="Address" rows="1"><?= set_value('address'); ?></textarea>
                                        <span class="col-sm-4 control-label">
                                            <?= form_error('address'); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-lg-3">
                                    <div class="form-group">
                                        <label for="financial_year">Financial Year <span class="text-danger">*</span></label>
                                        <select name="financial_year" id="financial_year" class="form-control form-control-sm" required="">
                                            <option value="">-Select-</option>
                                            <?php 
                                                $financial_year = $this->db->get('year')->result();
                                                if ($financial_year) {
                                                    foreach ($financial_year as $row) { 
                                            ?>
                                            <option value="<?= $row->id ?>" <?= set_select('financial_year', $row->id); ?>><?= $row->year; ?></option>
                                            <?php } } ?>
                                        </select>
                                        <span class="col-sm-3 control-label">
                                            <?= form_error('financial_year'); ?>				
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-lg-3">
                                    <div class="form-group">
                                        <label for="frmt">Billing Format</label>
                                        <select name="cate_id" id="cate" class="form-control form-control-sm">
                                            <option value="">Billing Format</option>
                                            <?php
                                                $cate = $this->user_model->getResult('billing_format');
                                                if ($cate) {
                                                    foreach ($cate as $key => $value) {
                                            ?>
                                            <option value="<?= $value->id ?>" <?= set_select('cate_id', $value->id); ?>><?= $value->name; ?></option>
                                            <?php } } ?>
                                        </select>
                                        <span class="col-sm-4 control-label">
                                            <?= form_error('cate_id'); ?>
                                        </span>    
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-lg-3">
                                    <div class="form-group">
                                        <label for="at">Accounting Type <span class="text-danger">*</span></label>
                                        <select name="ac" id="ac" class="form-control form-control-sm" required="">
                                            <option value="">Accounting Type</option>
                                            <option value="1">Basic</option>
                                            <option value="2">Advanced</option>
                                        </select>
                                        <span class="col-sm-4 control-label">
                                            <?= form_error('ac'); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-lg-3">
                                    <div class="form-group">
                                        <label>
                                            <?php
                                                $RoleId = get_title('roles', ['name' => 'admin'], 'id'); 
                                                $UserId = get_title('users', ['userType' => $RoleId], 'id'); 
                                                $CompanyName = get_title('inventory_billing', ['user_id' => $UserId], 'company_name');
                                            ?>
                                            Do you want to copy products of company <?= $CompanyName; ?>.?
                                            <br />
                                            <input type="checkbox" name="CopyProduct" value="Yes" id="CopyProduct" />
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <?php if ($this->session->userdata('roles') == 3) { if (IsLicensed()) { ?>
                                    <button type="submit" class="btn btn-dark btn-sm">
                                        Save
                                    </button>
                                    <?php } else { ?>
                                    <span class="text-danger">Your license has been expired, Please contact your administrator.</span>
                                    <?php } } else { ?>
                                    <button type="submit" class="btn btn-dark btn-sm">
                                        Save
                                    </button>
                                    <?php } ?>
                                    <button type="reset" class="btn btn-danger btn-sm">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $("#signUpForm").on('submit', function(e) {
            e.preventDefault();
            var contactForm = $(this);
            $.ajax({
                url     :   contactForm.attr('action'),
                type    :   'POST',
                data    :   contactForm.serialize(),
                success :   function(response) {
                                if (response.status == 'success') {
                                    $("#message").addClass('text-success');
                                    $('message').removeClass('text-danger');
                                    $("#message").html(response.message);
                                    setTimeout(function() {
                                        window.location.href = "<?= base_url('inventory_billing') ?>";
                                    }, 1000);
                                } else {
                                    $("#message").addClass('text-danger');
                                    $('message').removeClass('text-success');
                                    $("#message").html(response.message);
                                }
                            }
            });
        });
        
        $(".dataTable").DataTable({
            dom: 'lBfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5', 'print']
        });
    });
</script>