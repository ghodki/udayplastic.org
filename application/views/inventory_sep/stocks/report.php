<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/bootstrap-multiselect.min.css" />
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
    .custom-select {
        height: calc(2rem + 2px);
        font-size: 0.9rem;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline shadow">
                        <div class="card-header-sticky">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 class="card-title">
                                            STOCK REPORT
                                        </h4>
                                    </div>
                                    <div class="col-md-12">
                                        <form method="GET" action="<?= base_url(); ?>stocks_report/getWarehouseStockReport" id="reportForm" autocomplete="off" style="margin-top: 10px;">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <?php
                                                        $warehouse_selected = function_exists('input_get_array')
                                                            ? input_get_array('warehouse')
                                                            : (array) ($this->input->get('warehouse') ?: []);
                                                    ?>
                                                    <select name="warehouse[]" id="warehouse" class="form-control form-control-sm" title="Warehouse" multiple="multiple">
                                                        <?php
                                                            $this->db->select('id, name, location');
                                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                            }
                                                            $warehouses = $this->db->get('warehouse')->result();
                                                            if (!empty($warehouses)) { foreach ($warehouses as $key => $warehouse) {
                                                        ?>
                                                        <option <?= in_array($warehouse->id, $warehouse_selected) ? 'selected' : ''; ?> value="<?= $warehouse->id; ?>" <?= set_select('warehouse[]', $warehouse->id); ?>><?= $warehouse->name; ?> - <?= $warehouse->location; ?></option>
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
                                                        Search
                                                    </button>
                                                    &nbsp;
                                                    <a href="<?= base_url(); ?>stocks_report" class="btn btn-info btn-xs" title="Refresh">
                                                        <i class="fa fa-refresh"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="javascript:void(0);" class="btn btn-success btn-xs" id="excelExport" title="Export Excel">
                                                        <i class="fa fa-file-excel"></i>
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
    </div>
</div>

<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript">
    $('#warehouse').multiselect({
        buttonWidth: 'auto'
    });
</script>