<form id="updateForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/subcategory/Update" method="POST" enctype="multipart/form-data">
    <div class="modal-body">
        <input type="hidden" name="id" value="<?= $result->id ?>">
        <div class="form-group">

            <div class="row text-dark">
                <div class="col-md-2 col-sm-6 col-lg-2">
                    <lable for="name">Category<span class="text-danger">*</span></lable>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6">
                    <input id="name" name="name" placeholder="Category Name*" value="<?= $result->category_name ?>" type="text" class="form-control" required>
                    <div id="messages"></div>

                </div>
            </div>
        </div>

        <div class="modal-footer" style="margin-top:10px;">
            <button type="button" class="btn btn-secondary" onclick="closeModel()">Close</button>
            <button class="btn btn-primary" type="submit">Update</button>
        </div>
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
                        toastr.success(response.message);
                        $("#newModel").modal("hide");
                        window.location.reload();
                    } else {
                        toastr.error(response.message);

                    }


                }
            });
        })
    })
</script>