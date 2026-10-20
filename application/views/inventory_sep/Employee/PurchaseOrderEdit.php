<style type="text/css">
    .form-group {
        margin-bottom: 0rem;
    }
    th, td {
        white-space: nowrap;
    }
    div.ProductsDataAppend {
        position: absolute;
        top: 70px;
        left: 40px;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <div class="card card-purple card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-purple">
                                    Purchase Order Edit
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>Employee/PurchaseOrderReport" class="btn btn-xs bg-purple pull-right" title="Purchase Order Report">
                                <i class="fa fa-list"></i> Purchase Order Report
                            </a>
                        </div>
                        <?php if (!empty($PurchaseOrderId)) { ?>
                        <form action="<?= base_url(); ?>Employee/PurchaseOrderUpdate/<?= $PurchaseOrderId; ?>" method="POST" id="PurchaseOrder">
                            <input type="hidden" name="PurchaseOrderId" value="<?= set_value('PurchaseOrderId', $PurchaseOrder->Id); ?>" id="PurchaseOrderId" />
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="Date">Date <span class="text-danger">*</span></label>
                                            <input type="date" name="Date" value="<?= set_value('Date', $PurchaseOrder->PurchaseDate); ?>" id="Date" class="form-control form-control-sm" title="Date" required="" />
                                            <span class="text-danger"><?= form_error('Date'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                        <div class="form-group">
                                            <label for="CompanyId">Parent Company <span class="text-danger">*</span></label>
                                            <select name="CompanyId" id="CompanyId" class="form-control form-control-sm" required="" title="Parent Company">
                                                <option value="" selected="" disabled="">Select Parent Company</option>
                                                <?php if ($ParentCompany) { foreach ($ParentCompany as $key => $Data) { ?>
                                                <option value="<?= $Data->id; ?>" <?= $PurchaseOrder->CompanyId == $Data->id ? "selected" : ""; ?> <?= set_select('CompanyId', $Data->id)?>><?= $Data->company_name; ?></option>
                                                <?php } } ?>
                                            </select>
                                            <span class="text-danger"><?= form_error('CompanyId'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="bg-purple" />
                                <div class="row" style="min-height: 250px; height: auto;">
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm ProductsDropDown">
                                                <thead class="bg-purple" style="position: sticky; top: 0; z-index: 99;">
                                                    <tr>
                                                        <th>Sr.</th>
                                                        <th style="width: 45%;">Product Name</th>
                                                        <th>Price</th>
                                                        <th>Quantity</th>
                                                        <th>Total</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="4" style="text-align: right;">Sub Total</th>
                                                        <th colspan="2">
                                                            <i class="fa fa-inr"></i> 
                                                            <span id="SubTotalTxt">
                                                                <?= set_value('SubTotal', $PurchaseOrder->SubTotal); ?>
                                                            </span>
                                                            <input type="hidden" name="SubTotal" value="<?= set_value('SubTotal', $PurchaseOrder->SubTotal); ?>" id="SubTotal" />
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                        <div class="form-group">
                                            <label for="Remark">Remark</label>
                                            <textarea name="Remark" id="Remark" class="form-control form-control-sm" placeholder="Remark" rows="1"><?= set_value('Remark', $PurchaseOrder->Remark); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                    <button type="submit" class="btn btn-sm bg-purple">
                                        Make Changes
                                    </button>
                                    <a href="<?= base_url(); ?>Employee/PurchaseOrderReport" class="btn btn-danger btn-sm">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
 </div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#CompanyId").select2();
        
        <?php if (empty($PurchaseOrderId)) { ?>
        PrepareProductsDropDown();
        <?php } else { ?>
        var OrderBookId= '<?= $PurchaseOrderId ?>';
        $.ajax({
            method  :   "GET",
            url     :   "<?= base_url(); ?>Employee/getPurchaseOrderProduct/" + OrderBookId,
            success :   function(Response) {
                            var ProductResponse = JSON.parse(Response);
                            if (ProductResponse.Status === true) {
                                var Products = ProductResponse.Data; 
                                for (var i = 0; i < Products.length; i++) {
                                    PrepareProductsEdit(Products[i]);
                                }
                                OnAddNewRowHandler();
                            }
                        }
        });
        <?php } ?>
        
        $('#CompanyId').change(function() {
            if ($(this).val() > 0) {
                window.setTimeout(function() {
                    $("#ProductName1").focus();
                }, 500);
            }
        });
    });
    
    let UniqueRow = 1;
    const UniqueRows = [];
    
    const PrepareProductsDropDown = () => {
        let Sr = $('.ProductsDropDown tbody tr').length + 1;
        $(".ProductsDropDown tbody").append(
            '<tr id="tr' + UniqueRow + '">' +
            '<td>' + Sr + '</td>' +
            '<td>' +
            '<input type="text" name="Products[' + UniqueRow + '][ProductName]" value="" id="ProductName' + UniqueRow + '" class="form-control form-control-sm border-0" onkeydown="return KeyDownProduct(event);" oninput="ProductSearch(' + UniqueRow + ');" onchange="ProductSearch(' + UniqueRow + ');" onkeypress="ProductSearch(' + UniqueRow + ');" />' +
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
            '<td>' +
            '<input type="text" name="Products[' + UniqueRow + '][ProductName]" value="' + Products.ProductName + '" id="ProductName' + UniqueRow + '" class="form-control form-control-sm border-0" onkeydown="return KeyDownProduct(event);" oninput="ProductSearch(' + UniqueRow + ');" onchange="ProductSearch(' + UniqueRow + ');" onkeypress="ProductSearch(' + UniqueRow + ');" />' +
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