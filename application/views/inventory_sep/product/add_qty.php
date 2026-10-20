<form method="post" onsubmit="OnSubmitHandler(event)">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <label for="add_qty"><?= $result->p_name ?> Add Qty</label>
                <input type="number" min="0" name="add_qty" required id="add_qty" value="0" class="form-control">
                <input type="number" hidden name="product_id" required id="product_id" value="<?= $result->id ?>" class="form-control">


            </div>
        </div>
        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <button type="submit" id="subamit" class="btn btn-primary">Save</button>
            </div>
        </div>


    </div>
</form>
<script>
    function OnSubmitHandler(e) {
        e.preventDefault();
        let qty = document.getElementById('add_qty').value;
        let product_id = document.getElementById('product_id').value;
        if (product_id > 0 && qty != '') {
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>product/qtyAdd",
                // data: $("#dataForm").serialize(),
                data: {
                    qty: qty,
                    product_id: product_id,
                },
                success: function(result) {
                    result=JSON.parse(result);
                    console.log(result,"result");
                    if (result.status == true) {
                        $('#add-qty').modal('hide')
                        toastr.success(result.msg);
                    } else {
                        toastr.warning(result.msg);


                    }

                },

            });
        } else {
            alert("Please Enter Qty");
        }
    }
</script>