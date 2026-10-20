<div id="messages" class="text-center"></div>
<form id="updateForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/master/update_pt_details_received" method="POST" enctype="multipart/form-data">

    <div class="row text-dark">

        <input type="hidden" name="id" value="<?= $result->id ?>">
        <div class="col-md-4">
            <div class="form-group">
                <label for="Category">Party<span class="text-danger">*</span></label>
                <span class="partyDataUpdate"></span>

            </div>

        </div>
        <div class="col-md-4">
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

        <div class="col-md-4">
            <div class="form-group">

                <label for="status">Status<span class="text-danger"></span></label>
                <select class="form-control" name="status">
                    <option value="0" <?= $result->status == 0 ? 'selected' : '' ?> class="text-danger">Pending</option>
                    <option value="1" <?= $result->status == 1 ? 'selected' : '' ?> class="text-success">Approved</option>

                </select>
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('status'); ?>
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
        partyData(<?= $result->party_id ?>);
        
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
        });
    });

    function partyData(party_id) {
        $.ajax({
            method: "POST",
            url: "<?= base_url('crm/master/getPartyFromSubServiceSelected'); ?>",
            cache: false,
            data: {
                party_id: party_id,
            },
            success: function(value) {
                $('.partyDataUpdate').html(value);
            }
        });
    }
    
    function closeModel() {
        $("#exampleModals").modal("hide");
    }

    function myStopFunction() {
        clearTimeout(1000);
        window.location.reload();

    }
</script>