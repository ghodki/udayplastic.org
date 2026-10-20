<script type="text/javascript">
    // remove readonly
    document.getElementById('enable').onclick = function() {
        document.getElementById('transfer_id').removeAttribute('readonly');
    };
    
    // edit warehouse modal
    function editWarehouse() {
        jQuery.noConflict();
        if ($("#warehouse_form").find(":selected").val() == "") {
            alert("First select warehouse for edit");
        } else {
            var id = $("#warehouse_form").find(":selected").val();
            $("#edit_warehouse .modal-dialog .edit").load(baseurl + "/warehouse/editWarehouse/" + id);
            $("#edit_warehouse").modal("show");
        }
    }
    
    // create warehouse modal
    function createWarehouse() {
        $("#create_warehouse .modal-dialog .create").load(baseurl + "/warehouse/createWarehouse");
        $("#create_warehouse").modal("show");
    }
    
    // get wareohuse
    function getWarehouse() {
        var id = $("#warehouse_form").find(":selected").val();
        $.ajax({
            url     :   "<?= base_url(); ?>/warehouse/getWarehouse",
            method  :   "POST",
            data    :   {warehouse_id: id},
            success :   function(data) {
                            $('#warehouse_to').html(data);
                        }
        });
    }
    
    function productSearch(row_id, e) {
        var search = $("#product_name"+row_id).val();
        if (search.length > 0) {
            product_fetch(search, row_id);
        } else {
            document.getElementById("product_data_append"+row_id).innerHTML = "";
        }
    }

    function product_fetch(query, row_id) {
        $.ajax({
            url     :   "<?= base_url(); ?>/product/all_product_for_sales",
            method  :   "POST",
            data    :   {query: query, rowId: row_id},
            success :   function(data) {
                            $("#product_data_append"+row_id).html(data);
                        }
        });
    }
    
    var uniqueRow = 1;
    const allUniqueRows = [];
    
    const prepareProductsDropdown = (products, productsCode) => {
        let srNo = $("#ProductTable tbody tr").length + 1;
        $("#ProductTable tbody").append(
            "<tr id='tr"+uniqueRow+"'>"+
                "<input type='hidden' name='product["+uniqueRow+"][product_id]' id='product_id"+uniqueRow+"' />"+
                "<td>"+srNo+"</td>"+
                "<td><input type='text' name='product["+uniqueRow+"][product_code]' id='product_code"+uniqueRow+"' class='form-control form-control-sm' oninput='onProductCodeEnter("+uniqueRow+", event);' onchange='onProductCodeEnter("+uniqueRow+", event);' onkeypress='onProductCodeEnter("+uniqueRow+", event);' placeholder='Product Code' /></td>"+
                "<td style='position: relative;'>"+
                "<input type='text' name='product["+uniqueRow+"][product_name]' id='product_name"+uniqueRow+"' class='form-control form-control-sm' oninput='productSearch("+uniqueRow+", event);' onkeydown='return keyDownProduct(event);' placeholder='Product Name'>"+
                "<div class='product_data_append' id='product_data_append"+uniqueRow+"'></div>"+
                "</td>"+
                "<td><textarea name='product["+uniqueRow+"][description]' id='description"+uniqueRow+"' class='form-control form-control-sm' placeholder='Description' rows='1'></textarea></td>"+
                "<td><input type='text' name='product["+uniqueRow+"][unit]' id='unit"+uniqueRow+"' class='form-control form-control-sm' placeholder='Unit' /></td>"+
                "<td><input type='number' name='product["+uniqueRow+"][hsn]' id='hsn"+uniqueRow+"' class='form-control form-control-sm' placeholder='HSN' /></td>"+
                "<td><label id='available_qty"+uniqueRow+"'></label></td>"+
                "<td>"+
                "<input type='number' name='product["+uniqueRow+"][quantity]' id='quantity"+uniqueRow+"' class='form-control form-control-sm' onchange='onQuantityChangeHandler("+uniqueRow+", event); onQuantityCheckForWarehouse("+uniqueRow+");' oninput='onQuantityChangeHandler("+uniqueRow+", event); onQuantityCheckForWarehouse("+uniqueRow+");' step='any' placeholder='Quantity' />"+
                "<span class='text-danger' id='message"+uniqueRow+"'></span>"+
                "</td>"+
                "<td><input type='number' name='product["+uniqueRow+"][rate]' id='rate"+uniqueRow+"' class='form-control form-control-sm' onchange='onRateChangeHandler("+uniqueRow+", event);' oninput='onRateChangeHandler("+uniqueRow+", event);' step='any' placeholder='Rate' /></td>"+
                "<td><input type='number' name='product["+uniqueRow+"][total]' id='total"+uniqueRow+"' class='form-control form-control-sm' placeholder='Total' readonly='' /></td>"+
                "<td>"+
                "<a href='javascript:void(0);' class='text-success' onclick='onAddNewRowHandler();' title='Add New Row'>"+
                "<i class='fa fa-plus-circle'></i>"+
                "</a>&nbsp;"+
                "<a href='javascript:void(0);' class='text-danger' onclick='onRemoveRowHandler("+uniqueRow+");' title='Remove Row'>"+
                "<i class='fa fa-minus-circle'></i>"+
                "</a>&nbsp;"+
                "<span id='editProduct"+uniqueRow+"'></span>"+
                "</td>"+
            "</tr>");

        if (uniqueRow >= 1) {
            allUniqueRows.push(uniqueRow);
            ++uniqueRow;
        }
    };
    
    const onAddNewRowHandler = () => {
        getProducts();
    };
    
    const onRemoveRowHandler = (rowNo) => {        
        $("#tr" + rowNo).remove();
        $("#tr" + rowNo).remove();
        $("#tr" + rowNo).remove();
        $("#tr" + rowNo).remove();
        
        let rowCount = $("#ProductTable tbody tr").length;
        if (rowCount == 0) {
            onAddNewRowHandler();
            let res = arrayRemove(allUniqueRows, rowNo);
            for (var i = 0; i < res.length; i++) {
            }
            if (res) {
                while (allUniqueRows.length > 0) {
                    allUniqueRows.pop();
                }
            }
        } else {
            let res = arrayRemove(allUniqueRows, rowNo);
            if (res) {
                while (allUniqueRows.length > 0) {
                    allUniqueRows.pop();
                }
            }
            if (res) {
                for (i = 0; i < res.length; i++) {
                    allUniqueRows.push(res[i]);
                }
            }
        }
        totalAmount();
    };
    
    function arrayRemove(arr, value) {
        return arr.filter(function(element) {
            return element != value;
        });
    }
    
    const onProductCodeEnter = (rowNo, e) => {
        if (e.keyCode == 13) {
            e.preventDefault();
            var productId = e.target.value;
            if (productId == "" || productId == null) {
                document.getElementById('product_name' + rowNo).focus();
                return;
            }
            
            $.ajax({
                method  :   "GET",
                url     :   baseurl + "productcode/getProductCode/" + productId,
                success :   function(product) {
                                var productJson = JSON.parse(product);
                                if (productJson.status == true) {
                                    var productData = productJson.data;
                                    setSelectedProductDetails(productData, rowNo);
                                    onAddNewRowHandler();
                                    totalAmount();
                                    var NewrowNo = allUniqueRows[allUniqueRows.length - 1];
                                    window.setTimeout(function() {
                                        $("#product_code"+NewrowNo).focus();
                                    }, .1000);
                                } else {
                                    document.getElementById("product_id" + rowNo).value = '';
                                    document.getElementById("product_code" + rowNo).value = '';
                                    document.getElementById("product_name" + rowNo).value = '';
                                    document.getElementById("description" + rowNo).value = '';
                                    document.getElementById("unit" + rowNo).value = '';
                                    document.getElementById("hsn" + rowNo).value = 1;
                                    document.getElementById("quantity" + rowNo).value = 0;
                                    document.getElementById("rate" + rowNo).value = 0;
                                    document.getElementById("total" + rowNo).value = 0;
                                    document.getElementById("product_name" + rowNo).focus();
                                }
                            }
            });
        }
    };
    
    const getUnitName = (rowNo, id) => {
        $.ajax({
            url     :   "<?= base_url(); ?>/unit/getUnitName",
            method  :   "POST",
            data    :   {rowId: rowNo, unit_id: id},
            success :   function(data) {
                            $("#unit"+rowNo).val(data);
                        }
        });
    };
    
    const getTotal = (rowNo) => {
        var qty = $("#quantity" + rowNo).val();
        var rate = $("#rate" + rowNo).val();
        var total = qty * rate;
        $("#total"+rowNo).val(total);
    };
    
    const keyDownForProduct = (e, row) => {
        if (e.keyCode == '40') {
            var elmtTable = document.getElementById('example13');
            var tableRows = elmtTable.getElementsByTagName('div');
            var rowCount = tableRows.length;

            if (rowCount > row) {
                if (row < 0) {
                    row = 0;
                }
                row = row + 1;
                var previrow = row - 1;
                var start = document.getElementById('protr' + row);
                var previous = document.getElementById('protr' + previrow);
                if (previous) {
                    previous.style.backgroundColor = 'white';
                    previous.style.color = 'black';
                }
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
            } else {
                var start = document.getElementById('protr' + row);
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
            }
        }
    };
    
    function keyDownProduct(e) {
        if (e.keyCode == '40') {
            var start = document.getElementById('protr0');
            start.focus();
            start.style.backgroundColor = 'blue';
            start.style.color = 'white';

        }
    }
    
    const keyUpForProduct = (e, row) => {
        if (e.keyCode == '38') {
            if (row == 0) {
                var previous = document.getElementById('protr' + row);
                previous.style.backgroundColor = 'white';
                previous.style.color = 'black';
                document.getElementById("product_name" + row).focus();
                return;
            }
            var elmtTable = document.getElementById('example13');
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
                var start = document.getElementById('protr' + row);
                if (previrow) {
                    var previous = document.getElementById('protr' + previrow);
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
    
    const onQuantityChangeHandler = (rowNo, e) => {
        var rate = $("#rate" + rowNo).val();
        var quantity = $("#quantity" + rowNo).val();
        var total = rate * quantity;
        $("#total"+rowNo).val(total);
        totalAmount();
    };

    const onRateChangeHandler = (rowNo, e) => {
        var rate = $("#rate" + rowNo).val();
        var quantity = $("#quantity" + rowNo).val();
        var total = rate * quantity;
        $("#total"+rowNo).val(total.toFixed(2));
        totalAmount();
    };
    
    const totalAmount = () => {
        let totalValue = 0;
        const rows = $("#ProductTable").children("tbody").children("tr");
        rows.map(function(row) {
            let row1 = $("#ProductTable").children("tbody").children("tr")[row];
            const trId = row1.getAttribute("id");
            const rowNumber = trId.slice(2, trId.length);
            const total = $("#total" + rowNumber).val();
            
            if (total != null && total != "") {
                totalValue += parseFloat(total);
            }
        });
                    
        const round = Math.round(totalValue).toFixed(2);
        var roundval = round - totalValue;
        var roundOff = Math.abs(roundval);

        $("#roundoff").val(roundOff == "" || isNaN(roundOff) ? 0 : roundOff.toFixed(2));
        $("#total_amount").val(Math.round(totalValue).toFixed(2));
        $("#amount_word").val(numberToWords(parseInt(totalValue)));
    };
    
    function productForEdit(rowNumber, productId) {
        jQuery.noConflict();
        $("#rownumber").val(rowNumber);
        $("#edit_product .modal-dialog .product").load(baseurl + "/warehouse/edit_product_for_warehouse/" + productId);
        $("#edit_product").modal("show");  
    }
    
    function updateProduct(e) {
        jQuery.noConflict();
        e.preventDefault();
        var url = $("#updateProduct").attr("action");
        var form = $("#updateProduct")[0];
        var data = new FormData(form);
        $.ajax({
            type        :   "POST",
            url         :   url,
            data        :   data,
            processData :   false,
            contentType :   false,
            cache       :   false,
            dataType    :   "JSON",
            success     :   function(Response) {
                                if (Response.status == true) {
                                    toastr.success("Product Update Successfully");
                                    let row = $("#rownumber").val();
                                    var products = Response.data;
                                    setSelectedProductDetails(products, row);
                                    $("#edit_product").modal("hide");
                                    allUniqueRows.forEach((element) => {
                                        let pro_id = $("#product_id" + element).val();
                                        if (products.id == pro_id) {
                                            setSelectedProductDetails(products, element);
                                        }
                                    });
                                    document.getElementById("quantity"+row).focus();
                                } else {
                                    $("#respnce_msg").show();
                                    $("#respnce_msg").addClass("text");
                                    $("#respnce_msg").addClass("text-danger");
                                    $("#respnce_msg").html(Response.msg);
                                }
                            }
        });
    }
    
    const a = [
        "",
        "One ",
        "Two ",
        "Three ",
        "Four ",
        "Five ",
        "Six ",
        "Seven ",
        "Eight ",
        "Nine ",
        "Ten ",
        "Eleven ",
        "Twelve ",
        "Thirteen ",
        "Fourteen ",
        "Fifteen ",
        "Sixteen ",
        "Seventeen ",
        "Eighteen ",
        "Nineteen "
    ];
    const b = [
        "",
        "",
        "Twenty",
        "Thirty",
        "Forty",
        "Fifty",
        "Sixty",
        "Seventy",
        "Eighty",
        "Ninety"
    ];

    const numberToWords = (num) => {
        if ((num = num.toString()).length > 9) return "overflow";
        let n = ("000000000" + num)
            .substr(-9)
            .match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
        if (!n) return;
        var str = "";
        str +=
            n[1] != 0 ?
            (a[Number(n[1])] || b[n[1][0]] + " " + a[n[1][1]]) + "Crore " :
            "";
        str +=
            n[2] != 0 ?
            (a[Number(n[2])] || b[n[2][0]] + " " + a[n[2][1]]) + "Lakh " :
            "";
        str +=
            n[3] != 0 ?
            (a[Number(n[3])] || b[n[3][0]] + " " + a[n[3][1]]) + "Thousand " :
            "";
        str +=
            n[4] != 0 ?
            (a[Number(n[4])] || b[n[4][0]] + " " + a[n[4][1]]) + "Hundred " :
            "";
        str +=
            n[5] != 0 ?
            (str != "" ? "And " : "") +
            (a[Number(n[5])] || b[n[5][0]] + " " + a[n[5][1]]) +
            "Only " :
            "";
        return str;
    };
    
    $("#transfer_id").on("keyup change", function(e) {
        var transferid = $("#transfer_id").val();
        getInformation(transferid);
    });
    
    const onRemoveRowHandlers = (rowNo) => {
        $("#tr" + rowNo).remove();
        $("#tr" + rowNo).remove();
        $("#tr" + rowNo).remove();
        $("#tr" + rowNo).remove();

        totalAmount();
    };
    
    const setSelectedProductDetails = (product, rowNo) => {
        $("#product_id"+rowNo).val(product.id);
        $("#product_code"+rowNo).val(product.qrCode);
        $("#product_name"+rowNo).val(product.p_name);
        $("#description"+rowNo).val(product.short_de + product.long_de);
        getUnitName(rowNo, product.unit);
        $("#hsn"+rowNo).val(product != null ? product.hsn : "");
        $("#available_qty"+rowNo).text(product.qty);
        $("#quantity"+rowNo).val(1);
        $("#rate"+rowNo).val(product != null ? parseFloat(product.price) : "0");
        getTotal(rowNo);
        $("#editProduct"+rowNo).html(
            "<a href='javascript:void(0);' class='text-info' onclick='productForEdit("+rowNo+", "+product.id+");' title='Product Edit'>"+
                "<i class='fa fa-edit'></i>"+
            "</a>");
        
        totalAmount();
    };
    
    const getInformation = (transferid) => {
        $.ajax({
            method  :   "POST",
            url     :   baseurl + "warehouse/getStockTransferByTransferId",
            data    :   {id: transferid},
            success :   function(transfer_data) {
                            allUniqueRows.forEach((element) => {
                                onRemoveRowHandlers(element);
                            });
                            var transfer_data_json = JSON.parse(transfer_data);
                            if (transfer_data_json.status == true) {
                                window.location.href = '<?= base_url(); ?>warehouse/edit_stock_transfer/' + transfer_data_json.data.transfer_id;
                            } else {
                                getProducts();
                            }
                        }
        });
    };
    
    function getProducts() {
        $.ajax({
            url     :   baseurl + "product/getproducts",
            method  :   "GET",
            success :   function(products) {
                            var productsJson = JSON.parse(products);
                            if (productsJson.status == true) {
                                prepareProductsDropdown(productsJson.data.product, productsJson.data.productCode);
                                if ("" != null && "" !== "") {
                                    $("#transfer_id").val("");
                                    $("#transfer_id").trigger("change");
                                }
                            }
                        }
        });
    }
    
    const onProductSelect = (id, type = "Sale", rowNo) => {
        const productId = id;
        if (productId == "" || productId == null) {
            onRemoveRowHandler(rowNo);
            return;
        }

        $.ajax({
            method  :   "GET",
            url     :   baseurl + "product/getProduct/" + productId,
            success :   function(product) {
                            var productJosn = JSON.parse(product);
                            var productData = productJosn.data;
                            document.getElementById("product_data_append"+rowNo).innerHTML = '';
                            setSelectedProductDetails(productData, rowNo, type);
                            document.getElementById("quantity"+rowNo).focus();
                            onAddNewRowHandler();
                            rowNo = allUniqueRows[allUniqueRows.length - 1] + 1;
                            totalAmount();
                        }
        });
    };
    
    const getStockTransferInfo = (transfer_id) => {
        $.ajax({
            method  :   "POST",
            url     :   baseurl + "warehouse/TransferId",
            data    :   {id: transfer_id},
            success :   function(transferdata) {
                            var transferdatajson = JSON.parse(transferdata);
                            if (transferdatajson.status == true) {
                                var jsondata = transferdatajson.stdata;
                                // total_data
                                var productdetails = jsondata.productdata;
                                // product details
                                var transferinfo = jsondata.transferdata;
                                // transfer info
                                $('#transfer_type option[value="'+ transferinfo.transfer_type +'"]').attr("selected", "selected");
                                $("#transfer_date").val(transferinfo.transfer_date != null ? transferinfo.transfer_date : "");
                                getWarehouseFrom(transferinfo.warehouse_form != null ? transferinfo.warehouse_form : "");
                                getWarehouseTo(transferinfo.warehouse_to != null ? transferinfo.warehouse_to : "");
                                $("#amount_word").val(transferinfo.amount_word != null ? transferinfo.amount_word : "");
                                $("#total_amount").val(transferinfo.total_amount != null ? transferinfo.total_amount : "");
                                $("#roundoff").val(transferinfo.roundoff != null ? transferinfo.roundoff : "");
                                
                                for (i = 0; i < productdetails.length; i++) {
                                    prepareProducts(productdetails[i]);
                                }
                            } else {
                                getProducts();
                            }
                        }
        });
    };
    
    const getAvailableQuantity = (rowNo, productId) => {
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>warehouse/getAvailableQuantityFromProduct",
            data    :   {product_id: productId},
            success :   function (Response) {
                            var JsonReponse = JSON.parse(Response);
                            if (JsonReponse.status == true) {
                                $("#available_qty"+rowNo).text(JsonReponse.data);
                            } else {
                                $("#available_qty"+rowNo).text("Not Found");
                            }
                        }
        });
    };
    
    const getWarehouseFrom = (warehouse_id) => {
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>warehouse/getWarehouseFrom",
            data    :   {warehouse_id: warehouse_id},
            cache   :   false,
            success :   function(response) {
                            if (response) {
                                $("select[id=warehouse_form]").html(response);
                            } else {
                                $("select[id=warehouse_form]").html("");
                            }
                        }
        });
    };
    
    const getWarehouseTo = (warehouse_id) => {
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>warehouse/getWarehouseTo",
            data    :   {warehouse_id: warehouse_id},
            success :   function(response) {
                            if (response) {
                                $("#warehouse_to").html(response);
                            } else {
                                $("#warehouse_to").html("");
                            }
                        }
        });
    };

    const prepareProducts = (products) => {
        let srNo = $("#ProductTable tbody tr").length + 1;
        $("#ProductTable tbody").append(
            "<tr id='tr"+uniqueRow+"'>"+
                "<input type='hidden' name='product_update["+uniqueRow+"][id]' value='"+products.id+"' id='row_id"+uniqueRow+"' />"+
                "<input type='hidden' name='product_update["+uniqueRow+"][product_id]' value='"+products.product_id+"' id='product_id"+uniqueRow+"' />"+
                "<td>"+srNo+"</td>"+
                "<td><input type='text' name='product_update["+uniqueRow+"][product_code]' value='"+products.product_code+"' id='product_code"+uniqueRow+"' class='form-control form-control-sm' oninput='onProductCodeEnter("+uniqueRow+", event);' onchange='onProductCodeEnter("+uniqueRow+", event);' onkeypress='onProductCodeEnter("+uniqueRow+", event);' placeholder='Product Code' /></td>"+
                "<td style='position: relative;'>"+
                "<input type='text' name='product_update["+uniqueRow+"][product_name]' value='"+products.product_name+"' id='product_name"+uniqueRow+"' class='form-control form-control-sm' oninput='productSearch("+uniqueRow+", event);' onkeydown='return keyDownProduct(event);' placeholder='Product Name'>"+
                "<div class='product_data_append' id='product_data_append"+uniqueRow+"'></div>"+
                "</td>"+
                "<td><textarea name='product_update["+uniqueRow+"][description]' id='description"+uniqueRow+"' class='form-control form-control-sm' placeholder='Description' rows='1'>"+products.description+"</textarea></td>"+
                "<td><input type='text' name='product_update["+uniqueRow+"][unit]' value='"+products.unit+"' id='unit"+uniqueRow+"' class='form-control form-control-sm' placeholder='Unit' /></td>"+
                "<td><input type='number' name='product_update["+uniqueRow+"][hsn]' value='"+products.hsn+"' id='hsn"+uniqueRow+"' class='form-control form-control-sm' placeholder='HSN' /></td>"+
                "<td><label id='available_qty"+uniqueRow+"'>"+getAvailableQuantity(uniqueRow, products.product_id)+"</label></td>"+
                "<td>"+
                "<input type='number' name='product_update["+uniqueRow+"][quantity]' value='"+products.quantity+"' id='quantity"+uniqueRow+"' class='form-control form-control-sm' onchange='onQuantityChangeHandler("+uniqueRow+", event); onQuantityCheckForWarehouse("+uniqueRow+");' oninput='onQuantityChangeHandler("+uniqueRow+", event); onQuantityCheckForWarehouse("+uniqueRow+");' step='any' placeholder='Quantity' />"+
                "<span class='text-danger' id='message"+uniqueRow+"'></span>"+
                "</td>"+
                "<td><input type='number' name='product_update["+uniqueRow+"][rate]' value='"+products.rate+"' id='rate"+uniqueRow+"' class='form-control form-control-sm' onchange='onRateChangeHandler("+uniqueRow+", event);' oninput='onRateChangeHandler("+uniqueRow+", event);' step='any' placeholder='Rate' /></td>"+
                "<td><input type='number' name='product_update["+uniqueRow+"][total]' value='"+products.total+"' id='total"+uniqueRow+"' class='form-control form-control-sm' placeholder='Total' readonly='' /></td>"+
                "<td>"+
                "<a href='javascript:void(0);' class='text-success' onclick='onAddNewRowHandler();' title='Add New Row'>"+
                "<i class='fa fa-plus-circle'></i>"+
                "</a>&nbsp;"+
                "<a href='javascript:void(0);' class='text-danger' onclick='deleteRow("+uniqueRow+"); onRemoveRowHandler("+uniqueRow+");' title='Remove Row'>"+
                "<i class='fa fa-minus-circle'></i>"+
                "</a>&nbsp;"+
                "<a href='javascript:void(0);' class='text-info' onclick='productForEdit("+uniqueRow+", "+products.product_id+");' title='Product Edit'>"+
                "<i class='fa fa-edit'></i>"+
                "</a>"+
                "</td>"+
            "</tr>");

        if (uniqueRow >= 1) {
            allUniqueRows.push(uniqueRow);
            ++uniqueRow;
        }
    };
    
    function deleteRow(rowNo) {
        var rowid = document.getElementById('row_id'+rowNo).value;
        if (rowid != '' && rowid != null) {
            $.ajax({
                method  :   "POST",
                url     :   baseurl + "warehouse/trasnferProductDeleteRow",
                data    :   {id: rowid},
                success :   function(Response) {
                                var JsonResponse = JSON.parse(Response);
                                if (JsonResponse.status == true) {
                                    totalAmount();
                                } else {
                                    alert("Unable to delete record");
                                }
                            }
            });
        }
    }
    
    function preview() {
        var TransferId = document.getElementById('transfer_id').value;
        if (TransferId != '') {
            $.ajax({
                method  :   "POST",
                url     :   baseurl + "warehouse/getTransferIdByStockTransferPrevious",
                data    :   {id: TransferId},
                success :   function(TransferData) {
                                var JsonTransferData = JSON.parse(TransferData);
                                if (JsonTransferData.status) {
                                    window.location.href = '<?= base_url(); ?>warehouse/edit_stock_transfer/'+JsonTransferData.data.transfer_id;
                                } else {
                                    alert("Did not Find the Data");
                                }
                            }
            });
        } else {
            alert("Please enter transfer id");
        }
    }
    
    function next() {
        var TransferId = document.getElementById('transfer_id').value;
        if (TransferId != '') {
            $.ajax({
                method  :   "POST",
                url     :   baseurl + "warehouse/getTransferIdByStockTransferNext",
                data    :   {id: TransferId},
                success :   function(TransferData) {
                                var JsonTransferData = JSON.parse(TransferData);
                                if (JsonTransferData.data.transfer_id != null) {
                                    window.location.href = '<?= base_url(); ?>warehouse/edit_stock_transfer/'+JsonTransferData.data.transfer_id;
                                } else {
                                    alert("Did not Find the Data");
                                }
                }
            });
        } else {
            alert("Please enter transfer id");
        }
    }
    
    function deleteTransfer () {
        var transfer_id = document.getElementById('transfer_id').value;
        if (transfer_id != '') {
            $.ajax({
                method  :   "POST",
                url     :   baseurl + "warehouse/CheckTransferId",
                data    :   {transfer_id: transfer_id}, 
                success :   function (transferidstatus) {
                                   var jsondatatransfer = JSON.parse(transferidstatus);
                                if (jsondatatransfer.status == true && jsondatatransfer.data.transfer_id) {
                                    if (confirm("Are you sure want to delete this record") == true) {
                                        deleteTransferData(jsondatatransfer.data.transfer_id);
                                    }
                                } else {
                                    alert("Please enter valid transfer id for delete record");
                                }
                            }
            });
        } else {
            alert("Please enter transfer id");
        }
    }
    
    function deleteTransferData(transferid) {
        if (transferid != "") {
            $.ajax({
                method  :   "POST",
                url     :   baseurl + "warehouse/deleteStockTransfer",
                data    :   {transferid: transferid},
                success :   function (deletetransfer) {
                                var jsondeletetransfer = JSON.parse(deletetransfer);
                                if (jsondeletetransfer.status == true) {
                                    alert("Record delete successfully");
                                    window.location.href = '<?= base_url(); ?>warehouse/stock_transfer/';
                                } else {
                                    alert("somethigs went wrong, pleae try again later.");
                                }
                            }
            });
        } else {
            alert("Please enter valid trasfer id for delete record");
        }
    }
    
    const printTransfer = () => {
        var transfer_id = document.getElementById('transfer_id').value;
        if (transfer_id != '') {
            $.ajax({
                method  :   "POST",
                url     :   baseurl + "warehouse/CheckTransferId",
                data    :   {transfer_id: transfer_id}, 
                success :   function (transferidstatus) {
                                var jsondatatransfer = JSON.parse(transferidstatus);
                                if (jsondatatransfer.status == true && jsondatatransfer.data.transfer_id) {
                                    window.open('<?= base_url(); ?>warehouse/print/'+jsondatatransfer.data.transfer_id, '_blank');
                                } else {
                                    alert("Please enter valid transfer id for print record");
                                }
                            }
            });
        } else {
            alert("Please enter transfer id");
        }
    };
    
    const onQuantityCheckForWarehouse = (rowNo) => {
        var productId = document.getElementById('product_id'+rowNo).value;
        var Quantity = document.getElementById('quantity'+rowNo).value;
        if (productId != "" && productId != null || Quantity != "" && Quantity != null) {
            var warehouseForm = document.getElementById('warehouse_form').value;
            if (warehouseForm != "" && warehouseForm != null) {
                $.ajax({
                    method  :   "POST",
                    url     :   "<?= base_url(); ?>warehouse/checkQuantityFromProduct",
                    data    :   {product_id: productId, warehouse_id: warehouseForm, quantity: Quantity},
                    success :   function (Response) {
                                    var JsonReponse = JSON.parse(Response);
                                    if (JsonReponse.status == true) {
                                        $("#message"+rowNo).text("");
                                    } else if (JsonReponse.status == false) {
                                        $("#message"+rowNo).text(JsonReponse.data);
                                    } else {
                                        $("#message"+rowNo).text("Not Found");
                                    }
                                }
                });
            } else {
                alert("Select warehouse from for stock transfer quantity.");
            }
        } else {
            alert("Select product for stock transfer quantity.");
        }
    };
</script>