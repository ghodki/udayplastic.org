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
        $("#CompId").select2();
        
        <?php if (empty($StockManufacturerId)) { ?>
        PrepareProductsDropDown();
        window.setTimeout(function() {
            $("#ProductName1").focus();
        }, 500);
        <?php } else { ?>
        var StockManufacturerId = '<?= $StockManufacturerId; ?>';
        
        $.ajax({
            method  :   "GET",
            url     :   "<?= base_url(); ?>StockManufacturer/getStockManufacturerProducts/" + StockManufacturerId,
            success :   function(Response) {
                            var ProductResponse = JSON.parse(Response);
                            if (ProductResponse.Status === true) {
                                var Products = ProductResponse.Data; 
                                for (var i = 0; i < Products.length; i++) {
                                    PrepareProductsEdit(Products[i]);
                                }
                                OnAddNewRowHandler();
                            } else {
                                OnAddNewRowHandler();
                            }
                        }
        });
        <?php } ?>
    });
    
    $('#CompId').change(function() {
        if ($(this).val() > 0) {
            window.setTimeout(function() {
                $("#ProductName1").focus();
            }, 500);
        }
    });
    
    let UniqueRow = 1;
    const UniqueRows = [];
    
    const PrepareProductsDropDown = () => {
        let Sr = $('.ProductsDropDown tbody tr').length + 1;
        $(".ProductsDropDown tbody").append(
            '<tr id="tr' + UniqueRow + '">' +
            '<td>' + Sr + '</td>' +
            '<td style="position: relative;">' +
            '<input type="text" name="Products[' + UniqueRow + '][ProductName]" value="" id="ProductName' + UniqueRow + '" class="form-control form-control-sm border-0" onkeydown="return KeyDownProduct(event);" oninput="ProductSearch(' + UniqueRow + ');" onchange="ProductSearch(' + UniqueRow + ');" onkeypress="ProductSearch(' + UniqueRow + ');" autocomplete="off" />' +
            '<input type="hidden" name="Products[' + UniqueRow + '][ProductId]" id="ProductId' + UniqueRow + '" value="" />' +
            '<div class="ProductsDataAppend" id="ProductsDataAppend' + UniqueRow + '"></div>' +
            '</td>'+
            '<td>' +
            '<input type="number" name="Products[' + UniqueRow + '][ProductPrice]" value="" id="ProductPrice' + UniqueRow + '" class="form-control form-control-sm border-0" step="any" oninput="OnPriceChangeHandler(' + UniqueRow + ');" onchange="OnPriceChangeHandler(' + UniqueRow + ');" onkeyup="OnPriceChangeHandler(' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="Products[' + UniqueRow + '][Quantity]" value="" id="Quantity' + UniqueRow + '" class="form-control form-control-sm border-0" step="any" onchange="OnQuantityChangeHandler(this.value, ' + UniqueRow + ');" oninput="OnQuantityChangeHandler(this.value, ' + UniqueRow + ');" onkeyup="OnQuantityChangeHandler(this.value, ' + UniqueRow + ');" onblur="OnQuantityChangeHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="Products[' + UniqueRow + '][Total]" value="" id="Total' + UniqueRow + '" class="form-control form-control-sm border-0 Total" step="any" readonly="" />' +
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
    
    const PrepareProductsEdit = (Products) => {
        let Sr = $('.ProductsDropDown tbody tr').length + 1;
        $(".ProductsDropDown tbody").append(
            '<tr id="tr' + UniqueRow + '">' +
            '<td>' + Sr + '</td>' +
            '<td style="position: relative;">' +
            '<input type="text" name="Products[' + UniqueRow + '][ProductName]" value="' + Products.ProductName + '" id="ProductName' + UniqueRow + '" class="form-control form-control-sm border-0" onkeydown="return KeyDownProduct(event);" oninput="ProductSearch(' + UniqueRow + ');" onchange="ProductSearch(' + UniqueRow + ');" onkeypress="ProductSearch(' + UniqueRow + ');" autocomplete="off" />' +
            '<input type="hidden" name="Products[' + UniqueRow + '][ProductId]" id="ProductId' + UniqueRow + '" value="' + Products.ProductId + '" />' +
            '<div class="ProductsDataAppend" id="ProductsDataAppend' + UniqueRow + '"></div>' +
            '</td>'+
            '<td>' +
            '<input type="number" name="Products[' + UniqueRow + '][ProductPrice]" value="' + Products.Price + '" id="ProductPrice' + UniqueRow + '" class="form-control form-control-sm border-0" step="any" oninput="OnPriceChangeHandler(' + UniqueRow + ');" onchange="OnPriceChangeHandler(' + UniqueRow + ');" onkeyup="OnPriceChangeHandler(' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="Products[' + UniqueRow + '][Quantity]" value="' + Products.Quantity + '" id="Quantity' + UniqueRow + '" class="form-control form-control-sm border-0" step="any" onchange="OnQuantityChangeHandler(this.value, ' + UniqueRow + ');" oninput="OnQuantityChangeHandler(this.value, ' + UniqueRow + ');" onkeyup="OnQuantityChangeHandler(this.value, ' + UniqueRow + ');" onblur="OnQuantityChangeHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="Products[' + UniqueRow + '][Total]" value="' + Products.Total + '" id="Total' + UniqueRow + '" class="form-control form-control-sm border-0 Total" step="any" readonly="" />' +
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
    
    const OnRemoveRowHandler = (RowNo) => {
        $("#tr" + RowNo).remove();

        let RowCount = $(".ProductsDropDown tbody tr").length;
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
            url     :   "<?= base_url(); ?>product/fetchFinishMaterialProducts",
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
            var elmtTable = document.getElementById('ProductDropDown');
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
            var elmtTable = document.getElementById('ProductDropDown');
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
        $("#ProductPrice" + RowNo).val(Product.price);
        $("#Quantity" + RowNo).val(1);
        $("#Quantity" + RowNo).focus();
        $("#Quantity" + RowNo).trigger("change");
    };
    
    const OnQuantityChangeHandler = (ThisValue, RowNo) => {
        if ($("#Quantity"+RowNo).val() > 0) {
            let Price = $("#ProductPrice"+RowNo).val();
            let Quantity = $("#Quantity"+RowNo).val();
            let Total = (Number(Price) * Number(Quantity));
            $("#Total"+RowNo).val(Total.toFixed(2));
        }
        CalculateSubTotal();
    };
    
    const OnPriceChangeHandler = (RowNo) => {
        $("#Quantity" + RowNo).trigger("change");
    };
    
    const CalculateSubTotal = () => {
        let SubTotal = 0;
        const Rows = $(".ProductsDropDown").children("tbody").children("tr");
        Rows.map(function(Row) {
            let Row1 = $(".ProductsDropDown").children("tbody").children("tr")[Row];
            const trId = Row1.getAttribute("id");
            const RowNo = trId.slice(2, trId.length);
            const Total = $("#Total" + RowNo).val();
            if (Total != null && Total != "") {
                SubTotal += parseFloat(Total);
            }
        });
        $("#SubTotalTxt").text(SubTotal.toFixed(2));
        $("#SubTotal").val(SubTotal.toFixed(2));
    };
    
    const onStockManufacturerFromSubmitHandler = (event) => {
        $("#submitButton").prop("disabled", true);
        event.preventDefault();
        var VoucherNumber = $("#VoucherNumber").val();
        var Date = $("#Date").val();
        var CompId = $("#CompId").val();
        var SubTotal = $("#SubTotal").val();
        
        if (VoucherNumber && Date && CompId && SubTotal) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>StockManufacturer/Save",
                data    :   $("#StockManufacturer").serialize(),
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
                                        window.location.href = "<?= base_url(); ?>StockManufacturer/Reports";
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
    
    const onStockManufacturerInfoHandler = (Id) => {
        $("#Info .modal-dialog .modal-content").load("<?= base_url(); ?>StockManufacturer/Info/" + Id);
        $('#Info').modal('show');
    };
    
    const onStockManufacturerDeleteHandler = (Id) => {
        if (Id == 0) {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Id missing",
                showConfirmButton: false,
                timer: 2000
            });
            return;
        }
        
        var URL = "<?= base_url(); ?>StockManufacturer/Delete/" + Id;
            
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
                onStockManufacturerDelete(URL);
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
    
    function onStockManufacturerDelete(URL) {
        $.ajax({
            type    :   "GET",
            url     :   URL,
            success :   function(Response) {
                            var JSONResponse = JSON.parse(Response);
                            if (JSONResponse.status == true) {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: JSONResponse.msg,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                setTimeout(function() {
                                    window.location.href = "<?= base_url(); ?>StockManufacturer/Reports";
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
    
    const onStockManufacturerEditHandler = (Id) => {        
        if (Id) {    
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>StockManufacturer/getStockManufacturerEdit",
                data    :   {Id: Id},
                success :   function (Response) {
                                var ResponseData = JSON.parse(Response);
                                if (ResponseData.Status == true) {
                                    window.location.href = "<?= base_url(); ?>StockManufacturer/Edit/" + ResponseData.StockManufacturerId;
                                } else {
                                    Swal.fire({
                                        position: "top-end",
                                        icon: "error",
                                        title: ResponseData.msg,
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
    
    const onStockManufacturerFormSubmitUpdateHandler = (event) => {
        $("#submitButton").prop("disabled", true);
        event.preventDefault();
        var Date = $("#Date").val();
        var VoucherNumber = $("#VoucherNumber").val();
        var CompId = $("#CompId").val();
        var SubTotal = $("#SubTotal").val();
        
        if (Date && VoucherNumber && CompId && SubTotal) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>StockManufacturer/Update",
                data    :   $("#StockManufacturerUpdate").serialize(),
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
                                        window.location.href = "<?= base_url(); ?>StockManufacturer/Reports";
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
</script>