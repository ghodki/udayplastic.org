<style type="text/css">
    div.productsDataAppend {
        position: absolute;
        top: 40px;
        left: 0px;
        z-index: 1;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        $("#compId").select2();
        
        <?php if (empty($vestigeId)) { ?>
        prepareProductsDropDown();
        window.setTimeout(function() {
            $("#productName1").focus();
        }, 500);
        <?php } else { ?>
        var vestigeId = '<?= $vestigeId; ?>';
        
        $.ajax({
            method  :   "GET",
            url     :   "<?= base_url(); ?>vestige/getVestigeProducts/" + vestigeId,
            success :   function(response) {
                            var productResponse = JSON.parse(response);
                            if (productResponse.status === true) {
                                var products = productResponse.data; 
                                for (var i = 0; i < products.length; i++) {
                                    prepareProductsEdit(products[i]);
                                }
                                onAddNewRowHandler();
                            } else {
                                onAddNewRowHandler();
                            }
                        }
        });
        <?php } ?>
    });
    
    $('#compId').change(function() {
        if ($(this).val() > 0) {
            window.setTimeout(function() {
                $("#productName1").focus();
            }, 500);
        }
    });
    
    let uniqueRow = 1;
    const uniqueRows = [];
    
    const prepareProductsDropDown = () => {
        let sr = $('.productsDropDown tbody tr').length + 1;
        $(".productsDropDown tbody").append(
            '<tr id="tr' + uniqueRow + '">' +
            '<td>' + sr + '</td>' +
            '<td style="position: relative;">' +
            '<input type="text" name="products[' + uniqueRow + '][productName]" id="productName' + uniqueRow + '" class="form-control form-control-sm border-0" onkeydown="return keyDownProduct(event);" oninput="productSearch(' + uniqueRow + ');" onchange="productSearch(' + uniqueRow + ');" onkeypress="productSearch(' + uniqueRow + ');" autocomplete="off" />' +
            '<input type="hidden" name="products[' + uniqueRow + '][productId]" id="productId' + uniqueRow + '" />' +
            '<div class="productsDataAppend" id="productsDataAppend' + uniqueRow + '"></div>' +
            '</td>'+
            '<td>' +
            '<input type="number" name="products[' + uniqueRow + '][productPrice]" id="productPrice' + uniqueRow + '" class="form-control form-control-sm border-0" step="any" oninput="onPriceChangeHandler(' + uniqueRow + ');" onchange="onPriceChangeHandler(' + uniqueRow + ');" onkeyup="onPriceChangeHandler(' + uniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="products[' + uniqueRow + '][quantity]" id="quantity' + uniqueRow + '" class="form-control form-control-sm border-0" step="any" onchange="onQuantityChangeHandler(this.value, ' + uniqueRow + ');" oninput="onQuantityChangeHandler(this.value, ' + uniqueRow + ');" onkeyup="onQuantityChangeHandler(this.value, ' + uniqueRow + ');" onblur="onQuantityChangeHandler(this.value, ' + uniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="products[' + uniqueRow + '][total]" id="total' + uniqueRow + '" class="form-control form-control-sm border-0 Total" step="any" readonly="" />' +
            '</td>' +
            '<td>' +
            '<a href="javascript:void(0);" class="text-success" onclick="onAddNewRowHandler();" title="Add New Row">' +
            '<i class="fa fa-plus-circle"></i>' +
            '</a>' +
            '&nbsp;' +
            '<a href="javascript:void(0);" class="text-danger" onclick="onRemoveRowHandler(' + uniqueRow + ')" title="Remove Selected Row">'+
            '<i class="fa fa-minus-circle"></i>' +
            '</a>' +
            '</td>' +
            '</tr>'
        );
    
        if (uniqueRow >= 1) {
            uniqueRows.push(uniqueRow);
            ++uniqueRow;
        }
    };
    
    const prepareProductsEdit = (products) => {
        let sr = $('.productsDropDown tbody tr').length + 1;
        $(".productsDropDown tbody").append(
            '<tr id="tr' + uniqueRow + '">' +
            '<td>' + sr + '</td>' +
            '<td style="position: relative;">' +
            '<input type="text" name="products[' + uniqueRow + '][productName]" value="' + products.productName + '" id="productName' + uniqueRow + '" class="form-control form-control-sm border-0" onkeydown="return keyDownProduct(event);" oninput="productSearch(' + uniqueRow + ');" onchange="productSearch(' + uniqueRow + ');" onkeypress="productSearch(' + uniqueRow + ');" autocomplete="off" />' +
            '<input type="hidden" name="products[' + uniqueRow + '][productId]" id="productId' + uniqueRow + '" value="' + products.productId + '" />' +
            '<div class="productsDataAppend" id="productsDataAppend' + uniqueRow + '"></div>' +
            '</td>'+
            '<td>' +
            '<input type="number" name="products[' + uniqueRow + '][productPrice]" value="' + products.price + '" id="productPrice' + uniqueRow + '" class="form-control form-control-sm border-0" step="any" oninput="onPriceChangeHandler(' + uniqueRow + ');" onchange="onPriceChangeHandler(' + uniqueRow + ');" onkeyup="onPriceChangeHandler(' + uniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="products[' + uniqueRow + '][quantity]" value="' + products.quantity + '" id="quantity' + uniqueRow + '" class="form-control form-control-sm border-0" step="any" onchange="onQuantityChangeHandler(this.value, ' + uniqueRow + ');" oninput="onQuantityChangeHandler(this.value, ' + uniqueRow + ');" onkeyup="onQuantityChangeHandler(this.value, ' + uniqueRow + ');" onblur="onQuantityChangeHandler(this.value, ' + uniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="products[' + uniqueRow + '][total]" value="' + products.total + '" id="total' + uniqueRow + '" class="form-control form-control-sm border-0 Total" step="any" readonly="" />' +
            '</td>' +
            '<td>' +
            '<a href="javascript:void(0);" class="text-success" onclick="onAddNewRowHandler();" title="Add New Row">' +
            '<i class="fa fa-plus-circle"></i>' +
            '</a>' +
            '&nbsp;' +
            '<a href="javascript:void(0);" class="text-danger" onclick="onRemoveRowHandler(' + uniqueRow + ')" title="Remove Selected Row">'+
            '<i class="fa fa-minus-circle"></i>' +
            '</a>' +
            '</td>' +
            '</tr>'
        );

        if (uniqueRow >= 1) {
            uniqueRows.push(uniqueRow);
            ++uniqueRow;
        }
    };
    
    const onAddNewRowHandler = () => {
        prepareProductsDropDown();
    };
    
    const onRemoveRowHandler = (rowNo) => {
        $("#tr" + rowNo).remove();

        let rowCount = $(".productsDropDown tbody tr").length;
        if (rowCount == 0) {
            onAddNewRowHandler();
            let result = arrayRemove(uniqueRows, rowNo);
            if (result) {
                while (uniqueRows.length > 0) {
                    uniqueRows.pop();
                }
            }
        } else {
            let result = arrayRemove(uniqueRows, rowNo);
            if (result) {
                while (uniqueRows.length > 0) {
                    uniqueRows.pop();
                }
            }
            if (result) {
                for (var i = 0; i < result.length; i++) {
                    uniqueRows.push(result[i]);
                }
            }
        }
    };
    
    const arrayRemove = (array, value) => {
        return array.filter(function(element) {
            return element != value;
        });
    };
    
    const productSearch = (rowId) => {
        var search = $("#productName" + rowId).val();
        if (search.length > 0) {
            productFetch(search, rowId);
        } else {
            $('#productsDataAppend' + rowId).html("");
        }
    };

    const productFetch = (query, rowId) => {
        $.ajax({
            url          :   "<?= base_url(); ?>product/fetchRawMaterialProducts",
            method   :   "POST",
            data       :   {query: query, rowId: rowId},
            success :   function(response) {
                                $('#productsDataAppend' + rowId).html(response);
                              }
        });
    };
    
    const keyDownProduct = (e) => {
        if (e.keyCode == '40') {
            var start = $('#ProductRow0');
            start.focus();
            start.style.backgroundColor = 'blue';
            start.style.color = 'white';
        }
    };
    
    const KeyUpForProducts = (e, row) => {
        if (e.keyCode == '38') {
            if (Row == 0) {
                var previous = document.getElementById('ProductRow' + row);
                previous.style.backgroundColor = 'white';
                previous.style.color = 'black';
                document.getElementById("productName" + row).focus();
                return;
            }
            var elmtTable = document.getElementById('ProductDropDown');
            var tableRows = elmtTable.getElementsByTagName('div');
            var rowCount = tableRows.length;
            var previrow = '';
            if (rowCount > row) {
                if (row <= 0) {
                    row = 0;
                } else {
                    row = row - 1;
                    previrow = row + 1;
                }
                var start = document.getElementById('ProductRow' + row);
                if (previrow) {
                    var previous = document.getElementById('ProductRow' + previrow);
                    if (previous) {
                        previous.style.backgroundColor = 'white';
                        previous.style.color = 'black';
                    }
                }
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
            }
        }
    };
    
    const KeyDownForProducts = (e, row) => {
        if (e.keyCode == '40') {
            var elmtTable = document.getElementById('ProductDropDown');
            var tableRows = elmtTable.getElementsByTagName('div');
            var rowCount = tableRows.length;
            if (rowCount > row) {
                if (row < 0) {
                    row = 0;
                }
                row = row + 1;
                var previrow = row - 1;
                var start = document.getElementById('ProductRow' + row);
                var previous = document.getElementById('ProductRow' + previrow);
                if (previous) {
                    previous.style.backgroundColor = 'white';
                    previous.style.color = 'black';
                }
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
            } else {
                var start = document.getElementById('ProductRow' + row);
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
            }
        }
    };
    
    const OnProductSelect = (productId, rowNo) => {
        if (productId == "" || productId == null) {
            onRemoveRowHandler(rowNo);
            return;
        }

        $.ajax({
            method  :   "GET",
            url         :   "<?= base_url(); ?>product/getProduct/" + productId,
            success :   function(response) {
                                var productResponse = JSON.parse(response);
                                if (productResponse.status === true) {
                                    var productData = productResponse.data;
                                    $("#productsDataAppend" + rowNo).html("");
                                    onSetSelectProductInfo(productData, rowNo);
                                    onAddNewRowHandler();
                                    rowNo = uniqueRows[uniqueRows.length - 1] + 1;
                                }
                              }
        });
    };  
    
    const onSetSelectProductInfo = (product, rowNo) => {
        $("#productName" + rowNo).val(product.p_name);
        $("#productId" + rowNo).val(product.id);
        $("#productPrice" + rowNo).val(product.price);
        $("#quantity" + rowNo).val(1);
        $("#quantity" + rowNo).focus();
        $("#quantity" + rowNo).trigger("change");
    };
    
    const onQuantityChangeHandler = (thisValue, rowNo) => {
        if ($("#quantity"+rowNo).val() > 0) {
            let price = $("#productPrice"+rowNo).val();
            let quantity = $("#quantity"+rowNo).val();
            let total = (Number(price) * Number(quantity));
            $("#total"+rowNo).val(total.toFixed(2));
        }
        calculateSubTotal();
    };
    
    const onPriceChangeHandler = (rowNo) => {
        $("#quantity" + rowNo).trigger("change");
    };
    
    const calculateSubTotal = () => {
        let subTotal = 0;
        const rows = $(".productsDropDown").children("tbody").children("tr");
        rows.map(function(row) {
            let row1 = $(".productsDropDown").children("tbody").children("tr")[row];
            const trId = row1.getAttribute("id");
            const rowNo = trId.slice(2, trId.length);
            const total = $("#total" + rowNo).val();
            if (total != null && total != "") {
                subTotal += parseFloat(total);
            }
        });
        $("#subTotalTxt").text(subTotal.toFixed(2));
        $("#subTotal").val(subTotal.toFixed(2));
    };
    
    const onVestigeFromSubmitHandler = (event) => {
        $("#submitButton").prop("disabled", true);
        event.preventDefault();
        var voucherNumber = $("#voucherNumber").val();
        var date = $("#date").val();
        var compId = $("#compId").val();
        var subTotal = $("#subTotal").val();
        
        if (voucherNumber && date && compId && subTotal) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>vestige/save",
                data    :   $("#vestige").serialize(),
                success :   function(response) {
                                var responseData = JSON.parse(response);
                                if (responseData.status === true) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: responseData.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    window.setTimeout(function() {
                                        window.location.href = "<?= base_url(); ?>vestige/reports";
                                    }, 2000);
                                } else {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: responseData.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    $("#submitButton").prop("disabled", false);
                                }
                            }
            });
        } else {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Please enter required fields.',
                showConfirmButton: false,
                timer: 2000
            });
            $("#submitButton").prop("disabled", false);
        }
    };
    
    const onVestigeInfoHandler = (vestigeId) => {
        $("#info .modal-dialog .modal-content").load("<?= base_url(); ?>vestige/info/" + vestigeId);
        $('#info').modal('show');
    };
    
    const onVestigeDeleteHandler = (vestigeId) => {
        if (vestigeId == 0) {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Id missing",
                showConfirmButton: false,
                timer: 2000
            });
            return;
        }
        
        var deleteUrl = "<?= base_url(); ?>vestige/delete/" + vestigeId;
            
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                onVestigeDelete(deleteUrl);
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "info",
                    title: "Cancelled",
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    };
    
    function onVestigeDelete(deleteUrl) {
        $.ajax({
            type        :   "GET",
            url          :   deleteUrl,
            success :   function(response) {
                                var JSONResponse = JSON.parse(response);
                                if (JSONResponse.status == true) {
                                    Swal.fire({
                                        position: "top-end",
                                        icon: "success",
                                        title: JSONResponse.msg,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    setTimeout(function() {
                                        window.location.href = "<?= base_url(); ?>vestige/reports";
                                    }, 2000);
                                } else {
                                    Swal.fire({
                                        position: "top-end",
                                        icon: "error",
                                        title: JSONResponse.msg,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                }
                            }
        });
    }
    
    const onVestigeEditHandler = (vestigeId) => {        
        if (vestigeId) {    
            $.ajax({
                type        :   "POST",
                url          :   "<?= base_url(); ?>vestige/getVestigeEdit",
                data       :   {vestigeId: vestigeId},
                success  :   function (response) {
                                        var responseData = JSON.parse(response);
                                        if (responseData.status == true) {
                                            window.location.href = "<?= base_url(); ?>vestige/edit/" + responseData.vestigeId;
                                        } else {
                                            Swal.fire({
                                                position: "top-end",
                                                icon: "error",
                                                title: responseData.msg,
                                                showConfirmButton: false,
                                                timer: 2000
                                            });
                                        }
                                    }
            });
        } else {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Id required fields.",
                showConfirmButton: false,
                timer: 2000
            });
        }
    };
    
    const onVestigeFormSubmitUpdateHandler = (event) => {
        $("#submitButton").prop("disabled", true);
        event.preventDefault();
        var date = $("#date").val();
        var voucherNumber = $("#voucherNumber").val();
        var compId = $("#compId").val();
        var subTotal = $("#subTotal").val();
        
        if (date && voucherNumber && compId && subTotal) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>vestige/update",
                data    :   $("#vestigeUpdate").serialize(),
                success :   function(response) {
                                var responseData = JSON.parse(response);
                                if (responseData.status === true) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: responseData.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    window.setTimeout(function() {
                                        window.location.href = "<?= base_url(); ?>vestige/reports";
                                    }, 2000);
                                } else {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: responseData.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    $("#submitButton").prop("disabled", false);
                                }
                            }
            });
        } else {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Please enter required fields.',
                showConfirmButton: false,
                timer: 2000
            });
            $("#submitButton").prop("disabled", false);
        }
    };
</script>