<style type="text/css">
    div.ProductsDataAppend {
        position: absolute;
        top: 40px;
        left: 0px;
        z-index: 1;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        $("#ProductId").select2();
        
        <?php if (empty($rawMaterialId)) { ?>
        if ($('.Products tbody').length) {
            PrepareProductsDropDown();
        }
        <?php } else { ?>
        var RawMaterialId = '<?= $rawMaterialId; ?>';
        
        $.ajax({
            method  :   "GET",
            url     :   "<?= base_url(); ?>RawMaterial/getRawMaterialProducts/" + RawMaterialId,
            success :   function(Response) {
                            var ProductResponse = JSON.parse(Response);
                            if (ProductResponse.Status === true) {
                                var Products = ProductResponse.Data; 
                                for (var i = 0; i < Products.length; i++) {
                                    PrepareProductsEdit(Products[i]);
                                }
                                AddProductsDropDown();
                            } else {
                                AddProductsDropDown();
                            }
                        }
        });
        <?php } ?>
    });
    
    $('#ProductId').change(function() {
        if ($(this).val() > 0) {
            window.setTimeout(function() {
                $("#Name1").focus();
            }, 500);
        }
    });
    
    let UniqueRow = 1;
    const UniqueRows = [];
    
    const PrepareProductsDropDown = () => {
        let Sr = $('.Products tbody tr').length + 1;
        $(".Products tbody").append(
            '<tr id="tr' + UniqueRow + '">' +
            '<td>' + Sr + '</td>' +
            '<td style="position: relative;">' +
            '<input type="text" name="Products[' + UniqueRow + '][ProductName]" value="" id="ProductName' + UniqueRow + '" class="form-control form-control-sm border-0" onkeydown="return KeyDownProduct(event);" oninput="ProductSearch(' + UniqueRow + ');" onchange="ProductSearch(' + UniqueRow + ');" onkeypress="ProductSearch(' + UniqueRow + ');" autocomplete="off" />' +
            '<input type="hidden" name="Products[' + UniqueRow + '][ProductId]" id="ProductId' + UniqueRow + '" value="" />' +
            '<div class="ProductsDataAppend" id="ProductsDataAppend' + UniqueRow + '"></div>' +
            '</td>'+
            '<td>' +
            '<input type="number" name="Products[' + UniqueRow + '][Quantity]" value="" id="Quantity' + UniqueRow + '" class="form-control form-control-sm border-0" step="any" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="Products[' + UniqueRow + '][Price]" value="" id="Price' + UniqueRow + '" class="form-control form-control-sm border-0" step="any" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="Products[' + UniqueRow + '][Weight]" value="" id="Weight' + UniqueRow + '" class="form-control form-control-sm border-0" step="any" />' +
            '</td>' +
            '<td>' +
            '<input type="text" name="Products[' + UniqueRow + '][Unit]" value="" id="Unit' + UniqueRow + '" class="form-control form-control-sm border-0" />' +
            '</td>' +
            '<td>' +
            '<a href="javascript:void(0);" class="text-success" onclick="OnAddNewRowHandler();" title="Add New Row">' +
            '<i class="fa fa-plus-circle"></i>' +
            '</a>' +
            '&nbsp;' +
            '<a href="javascript:void(0);" class="text-danger" onclick="OnRemoveRowHandler(' + UniqueRow + ')" title="Remove Selected Row">'+
            '<i class="fa fa-minus-circle"></i>' +
            '</a>' +
            '</td>' +
            '</tr>'
        );
    
        if (UniqueRow >= 1) {
            UniqueRows.push(UniqueRow);
            ++UniqueRow;
        }
    };
    
    const AddProductsDropDown = () => {
        let Sr = $('.Products tbody tr').length + 1;
        $(".Products tbody").append(
            '<tr id="tr' + UniqueRow + '">' +
            '<td>' + Sr + '</td>' +
            '<td style="position: relative;">' +
            '<input type="text" name="AddProducts[' + UniqueRow + '][ProductName]" value="" id="ProductName' + UniqueRow + '" class="form-control form-control-sm border-0" onkeydown="return KeyDownProduct(event);" oninput="ProductSearch(' + UniqueRow + ');" onchange="ProductSearch(' + UniqueRow + ');" onkeypress="ProductSearch(' + UniqueRow + ');" autocomplete="off" />' +
            '<input type="hidden" name="AddProducts[' + UniqueRow + '][ProductId]" id="ProductId' + UniqueRow + '" value="" />' +
            '<div class="ProductsDataAppend" id="ProductsDataAppend' + UniqueRow + '"></div>' +
            '</td>'+
            '<td>' +
            '<input type="number" name="AddProducts[' + UniqueRow + '][Quantity]" value="" id="Quantity' + UniqueRow + '" class="form-control form-control-sm border-0" step="any" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="AddProducts[' + UniqueRow + '][Price]" value="" id="Price' + UniqueRow + '" class="form-control form-control-sm border-0" step="any" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="AddProducts[' + UniqueRow + '][Weight]" value="" id="Weight' + UniqueRow + '" class="form-control form-control-sm border-0" step="any" />' +
            '</td>' +
            '<td>' +
            '<input type="text" name="AddProducts[' + UniqueRow + '][Unit]" value="" id="Unit' + UniqueRow + '" class="form-control form-control-sm border-0" />' +
            '</td>' +
            '<td>' +
            '<a href="javascript:void(0);" class="text-success" onclick="OnAddMoreNewRowHandler();" title="Add New Row">' +
            '<i class="fa fa-plus-circle"></i>' +
            '</a>' +
            '&nbsp;' +
            '<a href="javascript:void(0);" class="text-danger" onclick="OnRemoveRowHandler(' + UniqueRow + ')" title="Remove Selected Row">'+
            '<i class="fa fa-minus-circle"></i>' +
            '</a>' +
            '</td>' +
            '</tr>'
        );
    
        if (UniqueRow >= 1) {
            UniqueRows.push(UniqueRow);
            ++UniqueRow;
        }
    };
    
    const PrepareProductsEdit = (Products) => {
        let Sr = $('.Products tbody tr').length + 1;
        $(".Products tbody").append(
            '<tr id="tr' + UniqueRow + '">' +
            '<input type="hidden" name="Products[' + UniqueRow + '][RawMaterialProductId]" value="' + Products.Id + '" id="RawMaterialProductId' + UniqueRow + '" />' +
            '<td>' + Sr + '</td>' +
            '<td>' +
            '<input type="text" name="Products[' + UniqueRow + '][ProductName]" value="' + Products.ProductName + '" id="ProductName' + UniqueRow + '" class="form-control form-control-sm border-0" onkeydown="return KeyDownProduct(event);" oninput="ProductSearch(' + UniqueRow + ');" onchange="ProductSearch(' + UniqueRow + ');" onkeypress="ProductSearch(' + UniqueRow + ');" autocomplete="off" />' +
            '<input type="hidden" name="Products[' + UniqueRow + '][ProductId]" id="ProductId' + UniqueRow + '" value="' + Products.ProductId + '" />' +
            '<div class="ProductsDataAppend" id="ProductsDataAppend' + UniqueRow + '"></div>' +
            '</td>'+
            '<td>' +
            '<input type="number" name="Products[' + UniqueRow + '][Quantity]" value="' + Products.Quantity + '" id="Quantity' + UniqueRow + '" class="form-control form-control-sm border-0" step="any" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="Products[' + UniqueRow + '][Price]" value="' + Products.Price + '" id="Price' + UniqueRow + '" class="form-control form-control-sm border-0" step="any" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="Products[' + UniqueRow + '][Weight]" value="' + Products.Weight + '" id="Weight' + UniqueRow + '" class="form-control form-control-sm border-0" step="any" />' +
            '</td>' +
            '<td>' +
            '<input type="text" name="Products[' + UniqueRow + '][Unit]" value="' + Products.Unit + '" id="Unit' + UniqueRow + '" class="form-control form-control-sm border-0" />' +
            '</td>' +
            '<td>' +
            '<a href="javascript:void(0);" class="text-success" onclick="OnAddNewRowHandler();" title="Add New Row">' +
            '<i class="fa fa-plus-circle"></i>' +
            '</a>' +
            '&nbsp;' +
            '<a href="javascript:void(0);" class="text-danger" onclick="OnRemoveRowHandler(' + UniqueRow + ')" title="Remove Selected Row">'+
            '<i class="fa fa-minus-circle"></i>' +
            '</a>' +
            '</td>' +
            '</tr>'
        );

        if (UniqueRow >= 1) {
            UniqueRows.push(UniqueRow);
            ++UniqueRow;
        }
    };
    
    const OnAddNewRowHandler = () => {
        PrepareProductsDropDown();
    };
    
    const OnAddMoreNewRowHandler = () => {
        AddProductsDropDown();
    };
    
    const OnRemoveRowHandler = (RowNo) => {
        $("#tr" + RowNo).remove();

        let RowCount = $(".Products tbody tr").length;
        if (RowCount == 0) {
            OnAddNewRowHandler();
            let Result = ArrayRemove(UniqueRows, RowNo);
            if (Result) {
                while (UniqueRows.length > 0) {
                    UniqueRows.pop();
                }
            }
        } else {
            let Result = ArrayRemove(UniqueRows, RowNo);
            if (Result) {
                while (UniqueRows.length > 0) {
                    UniqueRows.pop();
                }
            }
            if (Result) {
                for (var i = 0; i < Result.length; i++) {
                    UniqueRows.push(Result[i]);
                }
            }
        }
    };
    
    const ArrayRemove = (Array, Value) => {
        return Array.filter(function(Element) {
            return Element != Value;
        });
    };
    
    const ProductSearch = (RowId) => {
        var Search = $("#ProductName" + RowId).val();
        if (Search.length > 0) {
            ProductFetch(Search, RowId);
        } else {
            $('#ProductsDataAppend' + RowId).html("");
        }
    };

    const ProductFetch = (query, rowId) => {
        $.ajax({
            url     :   "<?= base_url(); ?>product/fetchRawMaterialProducts",
            method  :   "POST",
            data    :   {query: query, rowId: rowId},
            success :   function(response) {
                                $('#ProductsDataAppend' + rowId).html(response);
                              }
        });
    };
    
    const KeyDownProduct = (e) => {
        if (e.keyCode == '40') {
            var start = $('#ProductRow0');
            start.focus();
            start.style.backgroundColor = 'blue';
            start.style.color = 'white';
        }
    };
    
    const KeyUpForProducts = (e, Row) => {
        if (e.keyCode == '38') {
            if (Row == 0) {
                var previous = document.getElementById('ProductRow' + Row);
                previous.style.backgroundColor = 'white';
                previous.style.color = 'black';
                document.getElementById("ProductName" + Row).focus();
                return;
            }
            var elmtTable = document.getElementById('Products');
            var tableRows = elmtTable.getElementsByTagName('div');
            var rowCount = tableRows.length;
            var previrow = '';
            if (rowCount > Row) {
                if (Row <= 0) {
                    Row = 0;
                } else {
                    Row = Row - 1;
                    previrow = Row + 1;
                }
                var start = document.getElementById('ProductRow' + Row);
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
    
    const KeyDownForProducts = (e, Row) => {
        if (e.keyCode == '40') {
            var elmtTable = document.getElementById('Products');
            var tableRows = elmtTable.getElementsByTagName('div');
            var rowCount = tableRows.length;
            if (rowCount > Row) {
                if (Row < 0) {
                    Row = 0;
                }
                Row = Row + 1;
                var previrow = Row - 1;
                var start = document.getElementById('ProductRow' + Row);
                var previous = document.getElementById('ProductRow' + previrow);
                if (previous) {
                    previous.style.backgroundColor = 'white';
                    previous.style.color = 'black';
                }
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
            } else {
                var start = document.getElementById('ProductRow' + Row);
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
            }
        }
    };
    
    const OnProductSelect = (ProductId, RowNo) => {
        if (ProductId == "" || ProductId == null) {
            OnRemoveRowHandler(RowNo);
            return;
        }

        $.ajax({
            method  :   "GET",
            url     :   "<?= base_url(); ?>Product/getProduct/" + ProductId,
            success :   function(Response) {
                            var ProductResponse = JSON.parse(Response);
                            if (ProductResponse.status === true) {
                                var ProductData = ProductResponse.data;
                                $("#ProductsDataAppend" + RowNo).html("");
                                OnSetSelectProductInfo(ProductData, RowNo);
                                OnAddNewRowHandler();
                                RowNo = UniqueRows[UniqueRows.length - 1] + 1;
                            }
                        }
        });
    };  
    
    const OnSetSelectProductInfo = (Product, RowNo) => {
        $("#ProductName" + RowNo).val(Product.p_name);
        $("#ProductId" + RowNo).val(Product.id);
        $("#Price" + RowNo).val(Product.price);
        $("#Weight" + RowNo).val(Product.weight);
        $("#Unit" + RowNo).val(Product.unit_name);
    };
    
    const onRawMaterialFromSubmitHandler = (event) => {
        $("#submitButton").prop("disabled", true);
        event.preventDefault();
        var ProductId = $("#ProductId").val();
        
        if (ProductId) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>RawMaterial/Save",
                data    :   $("#RawMaterial").serialize(),
                success :   function(Response) {
                                var ResponseData = JSON.parse(Response);
                                if (ResponseData.Status === true) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: ResponseData.Message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    window.setTimeout(function() {
                                        window.location.href = "<?= base_url(); ?>RawMaterial";
                                    }, 2000);
                                } else {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: ResponseData.Message,
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
    
    const onRawMaterialInfoHandler = (Id) => {
        $("#Info .modal-dialog .modal-content").load("<?= base_url(); ?>RawMaterial/Info/" + Id);
        $('#Info').modal('show');
    };
    
    const onRawMaterialFromSubmitUpdateHandler = (event) => {
        $("#submitButton").prop("disabled", true);
        event.preventDefault();
        var ProductId = $("#ProductId").val();
        
        if (ProductId) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>RawMaterial/Update",
                data    :   $("#RawMaterialUpdate").serialize(),
                success :   function(Response) {
                                var ResponseData = JSON.parse(Response);
                                if (ResponseData.Status === true) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: ResponseData.Message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    window.setTimeout(function() {
                                        window.location.href = "<?= base_url(); ?>RawMaterial";
                                    }, 2000);
                                } else {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: ResponseData.Message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
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