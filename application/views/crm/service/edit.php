<div id="messages" class="text-center"></div>
<form id="updateForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/service/update_service" method="POST" enctype="multipart/form-data">

    <div class="row text-dark">

        <input type="hidden" name="id" value="<?= $result->id ?>">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="form-group">
                <label for="remark">SERVICE NAME:<span class="text-danger"></span></label>
                <textarea id="service_name" name="service_name" placeholder="Service Name" required type="text" class="form-control" value="<?= set_value('service_name') ?>"><?= $result->service_name ?></textarea>
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('service_name'); ?>

            </span>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="form-group">
                <label for="short_name">SHORT NAME:<span class="text-danger">*</span></label>
                <textarea id="short_name" name="short_name" placeholder="Short Name" required type="text" class="form-control" value="<?= set_value('short_name') ?>"><?= $result->short_name ?></textarea>
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('short_name'); ?>

            </span>
        </div>
        <div class="col-md-12">
            <div class="form-group">

                <label for="status">Status<span class="text-danger"></span></label>
                <select class="form-control" name="status">
                    <option value="0" <?= $result->status == 0 ? 'selected' : '' ?> class="text-danger">Pending</option>
                    <option value="1" <?= $result->status == 1 ? 'selected' : '' ?> class="text-success">Approved</option>

                </select>
            </div>
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