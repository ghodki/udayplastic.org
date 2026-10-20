<div id="messages" class="text-center"></div>
<form id="updateForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/paymentmode/update_paymentmode" method="POST" enctype="multipart/form-data">

    <div class="row text-dark">

        <input type="hidden" name="id" value="<?= $result->id ?>">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="form-group">
                <label for="remark">paymentmode NAME:<span class="text-danger"></span></label>
                <textarea id="paymentmode_name" name="paymentmode_name" placeholder="paymentmode Name" required type="text" class="form-control" value="<?= set_value('paymentmode_name') ?>"><?= $result->name ?></textarea>
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('paymentmode_name'); ?>

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