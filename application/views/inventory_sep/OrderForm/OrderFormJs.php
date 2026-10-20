<style type="text/css">
    div.ProductsDataAppend {
        position: absolute;
        top: 40px;
        left: 0px;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        $("#PartyId").select2();
        
        <?php if (empty($OrderFormId)) { ?>
        PrepareProductsDropDown();
        <?php } else { ?>
        SalesmanName($("#PartyId").val());
                
        var OrderBookId = "<?= $OrderFormId; ?>";
        
        $.ajax({
            method  :   "GET",
            url     :   "<?= base_url(); ?>Orderform/getOrderformProduct/" + OrderBookId,
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
        
        $('#PartyId').change(function() {
            if ($(this).val() > 0) {
                window.setTimeout(function() {
                    $("#ProductName1").focus();
                }, 500);
            }
        });
    });
    
    
    const SalesmanName = (PartyId) => {
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>Orderform/SalesmanName",
            data    :   {PartyId: PartyId},
            success :   function(Response) {
                            if (Response) {
                                $("#SalesmanId").html(Response);
                            } else {
                                $("#SalesmanId").html("");
                            }
                        }
        });
    };
    
    let UniqueRow = 1;
    const UniqueRows = [];
    
    const PrepareProductsDropDown = () => {
        let Sr = $('.ProductsDropDown tbody tr').length + 1;
        $(".ProductsDropDown tbody").append(
            '<tr id="tr' + UniqueRow + '">' +
            '<td>' + Sr + '</td>' +
            '<td style="position: absolute; width: 44.4%;">' +
            '<input type="text" name="Products[' + UniqueRow + '][ProductName]" value="" id="ProductName' + UniqueRow + '" class="form-control form-control-sm border-0" onkeydown="return KeyDownProduct(event);" oninput="ProductSearch(' + UniqueRow + ');" onchange="ProductSearch(' + UniqueRow + ');" onkeypress="ProductSearch(' + UniqueRow + ');" autocomplete="off" />' +
            '<input type="hidden" name="Products[' + UniqueRow + '][ProductId]" id="ProductId' + UniqueRow + '" value="" />' +
            '<div class="ProductsDataAppend" id="ProductsDataAppend' + UniqueRow + '"></div>' +
            '</td>'+
            '<td>' +
            '<input type="number" name="Products[' + UniqueRow + '][ProductPrice]" value="" id="ProductPrice' + UniqueRow + '" class="form-control form-control-sm border-0" step="any" oninput="OnPriceChangeHandler(' + UniqueRow + ');" onchange="OnPriceChangeHandler(' + UniqueRow + ');" onkeyup="OnPriceChangeHandler(' + UniqueRow + ');" autocomplete="off" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="Products[' + UniqueRow + '][Quantity]" value="" id="Quantity' + UniqueRow + '" class="form-control form-control-sm border-0" step="any" onchange="OnQuantityChangeHandler(this.value, ' + UniqueRow + ');" oninput="OnQuantityChangeHandler(this.value, ' + UniqueRow + ');" onkeyup="OnQuantityChangeHandler(this.value, ' + UniqueRow + ');" onblur="OnQuantityChangeHandler(this.value, ' + UniqueRow + ');" autocomplete="off" />' +
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
            '<td style="position: absolute; width: 44.4%;">' +
            '<input type="text" name="Products[' + UniqueRow + '][ProductName]" value="' + Products.ProductName + '" id="ProductName' + UniqueRow + '" class="form-control form-control-sm border-0" onkeydown="return KeyDownProduct(event);" oninput="ProductSearch(' + UniqueRow + ');" onchange="ProductSearch(' + UniqueRow + ');" onkeypress="ProductSearch(' + UniqueRow + ');" autocomplete="off" />' +
            '<input type="hidden" name="Products[' + UniqueRow + '][ProductId]" id="ProductId' + UniqueRow + '" value="' + Products.ProductId + '" />' +
            '<div class="ProductsDataAppend" id="ProductsDataAppend' + UniqueRow + '"></div>' +
            '</td>'+
            '<td>' +
            '<input type="number" name="Products[' + UniqueRow + '][ProductPrice]" value="' + Products.Price + '" id="ProductPrice' + UniqueRow + '" class="form-control form-control-sm border-0" step="any" oninput="OnPriceChangeHandler(' + UniqueRow + ');" onchange="OnPriceChangeHandler(' + UniqueRow + ');" onkeyup="OnPriceChangeHandler(' + UniqueRow + ');" autocomplete="off" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="Products[' + UniqueRow + '][Quantity]" value="' + Products.Quantity + '" id="Quantity' + UniqueRow + '" class="form-control form-control-sm border-0" step="any" onchange="OnQuantityChangeHandler(this.value, ' + UniqueRow + ');" oninput="OnQuantityChangeHandler(this.value, ' + UniqueRow + ');" onkeyup="OnQuantityChangeHandler(this.value, ' + UniqueRow + ');" onblur="OnQuantityChangeHandler(this.value, ' + UniqueRow + ');" autocomplete="off" />' +
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

    const ProductFetch = (Query, RowId) => {
        $.ajax({
            url     :   "<?= base_url(); ?>Product/ProductsForOrderbook",
            method  :   "POST",
            data    :   {Query: Query, RowId: RowId},
            success :   function(Response) {
                            $('#ProductsDataAppend' + RowId).html(Response);
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
</script>