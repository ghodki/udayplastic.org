<style type="text/css">
    th, td {
        white-space: nowrap;
    }
    .table thead tr th, .table tfoot tr th {
        font-size: 15px;
    }
    .table tbody tr td {
        font-size: 12px;
    }
    .table thead tr th, .table tbody tr td, .table tfoot tr th {
        padding: 3px 3px;
        border: 0.1px solid #000; 
    }
    .card-header-sticky {
        position: sticky;
        top: 0;
        z-index: 99;
        background-color: #ffffff;
    }
    .tableFixHead {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
        overflow: auto;
    }
    .tableFixHead tbody {
        width: 100%;
        overflow: auto;
        height: 58vh;
    }
    thead {
        background: #004a7a;
        color: #ffffff;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Stock</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Report</a></li>
                        <li class="breadcrumb-item active">Stock Report</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header-sticky">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 class="card-title">
                                            Stock Report
                                        </h4>
                                    </div>
                                    <div class="col-md-12">
                                        <form method="GET" action="<?= base_url(); ?>Employee/getWarehouseStockReport" id="reportForm" autocomplete="off" style="margin-top: 10px;">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <select name="warehouse" id="warehouse" class="form-control form-control-sm" title="Warehouse" required="">
                                                        <?php if (!empty($Warehouses)) { foreach ($Warehouses as $key => $Warehouse) { ?>
                                                        <option value="<?= $Warehouse->id; ?>" <?= set_select('warehouse', $Warehouse->id); ?>><?= $Warehouse->name; ?> - <?= $Warehouse->location; ?></option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-1">
                                                    <select name="transfer_type" id="transfer_type" class="form-control form-control-sm" title="Transfer Type">
                                                        <option <?= $this->input->get('transfer_type') == "All" ? "selected" : ""; ?> value="All">All</option>
                                                        <option <?= $this->input->get('transfer_type') == "Estimate" ? "selected" : ""; ?> value="Estimate">Estimate</option>
                                                        <option <?= $this->input->get('transfer_type') == "Tax" ? "selected" : ""; ?> value="Tax">Tax</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="checkbox" name="details" value="true" id="details" <?= $this->input->get('details') == "true" ? "checked" : ""; ?> />
                                                    Details View
                                                </div>
                                                <div class="col-md-4">
                                                    <button type="submit" class="btn btn-outline-primary btn-xs">
                                                        <i class="fa fa-search"></i> Search
                                                    </button>
                                                    &nbsp;
                                                    <a href="<?= base_url(); ?>Employee/StockReport" class="btn btn-outline-info btn-xs" title="Refresh">
                                                        <i class="fa fa-refresh"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="tableFixHead">
                                        <table class="table table-bordered table-condensed">
                                            <thead>
                                                <tr>
                                                    <th class="text-white">Sr</th>
                                                    <th class="text-white">Product Name</th>
                                                    <th class="text-white">Opening Stock</th>
                                                    <th class="text-white">Purchase Quantity</th>
                                                    <th class="text-white">Sale Quantity</th>
                                                    <th class="text-white">Balance Quantity</th>
                                                    <th class="text-white">Price</th>
                                                    <th class="text-white">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="8" style="text-align: center;">Search Record</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
 </div>