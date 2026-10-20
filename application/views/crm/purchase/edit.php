<div id="messages" class="text-center"></div>
<form id="updateForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/master/update_purchase" method="POST" enctype="multipart/form-data">

    <div class="row text-dark">

        <input type="hidden" name="id" value="<?= $result->id ?>">
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="Category">Party<span class="text-danger">*</span></label>
                <select class="form-control select2" required name="party" id="party">
                    <option value="">Select Party</option>

                    <?php
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $this->db->select('*');
            $this->db->from('crm_party');
            $this->db->where('user_id', $user_id);
            $this->db->where('crm_id', $crm_id);
            $this->db->like('sub_services_id', $this->session->userdata('sub_service_id'));
            $this->db->where('status', 1);
            $getResu = $this->db->get()->result();
                    if ($getResu) {
                        foreach ($getResu as $category) {
                    ?>
                            <option value="<?= $category->id ?>" <?= $result->party_id == $category->id ? 'selected' : '' ?>><?= $category->party_name ?></option>

                    <?php
                        }
                    }
                    ?>
                </select>

            </div>

        </div>
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <?php
                $last_month = date('Y-m-d', strtotime('-1 month', time()));
                ?>
                <label for="date">Date<span class="text-danger">*</span></label>
                <input id="date" name="date" placeholder="Date" type="date" required class="form-control" value="<?= set_value('date', $result->date) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('remark'); ?>

            </span>
        </div>



        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="form-group">
                <label for="remark">Remark<span class="text-danger"></span></label>
                <textarea id="remark" name="remark" placeholder="Remark" type="text" class="form-control" value="<?= set_value('remark') ?>"><?= $result->remark ?></textarea>
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('remark'); ?>

            </span>
        </div>
    </div>
    <div class="modal-footer">
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
        $("#exampleModals").modal("hide");
    }

    function myStopFunction() {
        clearTimeout(1000);
        window.location.reload();

    }
</script>