<style type="text/css">
    @media (min-width: 1024px) {
        .stickyColumn {
            position: sticky;
        }
        .stickyColumn th {
            background: #2C4668 !important;
        }
        th.stickyColumn:nth-child(3), td.stickyColumn:nth-child(3) {
            left: 30px;
        }
    }
    .content-header {
        padding: 0px 0px;
    }
    .card-header-sticky {
        position: sticky;
        top: 0;
        z-index: 99;
        background-color: #ffffff;
    }
    .table td, .table th {
        padding: 0.10rem;
    }
    .form-control-sm {
        height: calc(1.5rem + 1px);
        padding: 0.1rem 0.1rem;
        font-size: .675rem;
        font-weight: 500;
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
    thead tr {
        background: #2C4668;
        color: #ffffff;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header mt-2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form action="<?= base_url('party/BulkUpdate'); ?>" method="POST" autocomplete="off">
                        <div class="card">
                            <div class="card-header-sticky">
                                <div class="card-header">
                                    <h3 class="card-title">Update Parties</h3>
                                    <div class="pull-right">
                                        <button type="submit" class="btn btn-success btn-xs">
                                            <i class="fa fa-save text-white"></i> Update Parties
                                        </button>
                                        &nbsp;
                                        <a href="<?= base_url(); ?>party" class="btn btn-danger btn-xs pull-right" title="Back to party Report">
                                            <i class="fa fa-angle-double-left"></i> Back
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-boby">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="tableFixHead">
                                            <table class="table table-bordered table-condensed">
                                                <thead>
                                                    <tr>
                                                        <th class="text-white">Sr.</th>
                                                        <th class="text-white" style="width: 115px;">Account Group</th>
                                                        <th class="text-white stickyColumn" style="width: 286px;">Name</th>
                                                        <th class="text-white" style="width: 150px;">Contact Person</th>
                                                        <th class="text-white" style="width: 150px;">Contact Number</th>
                                                        <th class="text-white" style="width: 124px;">Email</th>
                                                        <th class="text-white" style="width: 112px;">GST</th>
                                                        <th class="text-white" style="width: 80px;">GST Type</th>
                                                        <th class="text-white" style="width: 255px;">Address</th>
                                                        <th class="text-white" style="width: 153px;">Area</th>
                                                        <th class="text-white" style="width: 100px;">City</th>
                                                        <th class="text-white" style="width: 155px;">State</th>
                                                        <th class="text-white" style="width: 125px;">Salesman</th>
                                                        <th class="text-white" style="width: 125px;">Category</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        if (!empty($parties)) {
                                                            $n = 0;
                                                            foreach ($parties as $key => $data) {
                                                                $n++;
                                                    ?>
                                                    <input type="hidden" name="party_id[]" value="<?= $data->id; ?>" />
                                                    <tr>
                                                        <td><?= $n; ?></td>
                                                        <td>
                                                            <select style="width: 115px;" name="group_head[]" id="group_head" class="form-control form-control-sm" title="Account Group">
                                                                <option selected="" value="">Select Account Group</option>
                                                                <?php
                                                                    $AccountGroup = $this->db->select('id, name')->where('status', 1)->get('accounting_group')->result();
                                                                    if (!empty($AccountGroup)) {
                                                                        foreach($AccountGroup as $key => $GroupHead) {
                                                                ?>
                                                                <option <?= $GroupHead->id == $data->group_head ? "selected" : ""; ?> value="<?= $GroupHead->id; ?>" <?= set_select('salesman', $GroupHead->id); ?>><?= $GroupHead->name; ?></option>
                                                                <?php } } ?>
                                                            </select>
                                                        </td>
                                                        <td class="stickyColumn">
                                                            <input style="width: 280px;" type="text" name="name[]" value="<?= set_value('name', $data->name); ?>" id="name" class="form-control form-control-sm" placeholder="Name" />
                                                        </td>
                                                        <td>
                                                            <input style="width: 140px;" type="text" name="ContactPerson[]" value="<?= set_value('ContactPerson', $data->ContactPerson); ?>" id="ContactPerson" class="form-control form-control-sm" placeholder="Contact Person" />
                                                        </td>
                                                        <td>
                                                            <input style="width: 140px;" type="tel" name="contact[]" value="<?= set_value('contact', $data->contact); ?>" id="contact" class="form-control form-control-sm" placeholder="Contact Number" required="" minlength="10" maxlength="10" />
                                                        </td>
                                                        <td>
                                                            <input style="width: 120px;" type="text" name="email[]" value="<?= set_value('email', $data->email); ?>" id="email" class="form-control form-control-sm" placeholder="Email" />
                                                        </td>
                                                        <td>
                                                            <input style="width: 110px;" type="text" name="gst[]" value="<?= set_value('gst', $data->gst); ?>" id="gst" class="form-control form-control-sm" placeholder="GST Number" />
                                                        </td>
                                                        <td>
                                                            <select style="width: 75px;" name="gstType[]" id="gstType" class="form-control form-control-sm" title="GST Type">
                                                                <option <?= $data->gstType == "Regular" ? "selected" : ""; ?> value="Regular" <?= set_select('gstType', 'Regular'); ?>>Regular</option>
                                                                <option <?= $data->gstType == "Composite" ? "selected" : ""; ?> value="Composite" <?= set_select('gstType', 'Composite'); ?>>Composite</option>
                                                                <option <?= $data->gstType == "Tax Deductor" ? "selected" : ""; ?> value="Tax Deductor" <?= set_select('gstType', 'Tax Deductor'); ?>>Tax Deductor</option>
                                                                <option <?= $data->gstType == "N/A" ? "selected" : ""; ?> value="N/A" <?= set_select('gstType', 'N/A'); ?>>N/A</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <textarea style="width: 250px;" name="address_1[]" id="address_1" class="form-control form-control-sm" placeholder="Address" rows="1"><?= set_value('address_1', $data->address_1); ?></textarea>
                                                        </td>
                                                        <td>
                                                            <textarea style="width: 150px;" name="area[]" id="area" class="form-control form-control-sm" placeholder="Area" rows="1"><?= set_value('area', $data->area); ?></textarea>
                                                        </td>
                                                        <td>
                                                            <select style="width: 100px;" name="city[]" id="city" class="form-control form-control-sm select2" title="Select City">
                                                                <?php if ($this->db->get('districts')->result()) { foreach ($this->db->get('districts')->result() as $crow) { ?>
                                                                <option <?= $data->city == $crow->id ? "selected" : ""; ?> value="<?= $crow->id; ?>" <?= set_select('city', $crow->id); ?>><?= $crow->name; ?></option>
                                                                <?php } } ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select style="width: 150px;" name="state[]" id="state" class="form-control form-control-sm" title="Select State">
                                                                <?php if ($this->db->get('state_list')->result()) { foreach ($this->db->get('state_list')->result() as $row) { ?>
                                                                <option <?= $data->state == $row->id ? "selected" : ""; ?> value="<?= $row->id; ?>" <?= set_select('state', $row->id); ?>><?= $row->state; ?> (<?= $row->code; ?>)</option>
                                                                <?php } } ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select style="width: 120px;" name="SalesmanId[]" id="SalesmanId" class="form-control form-control-sm" title="Salesman">
                                                                <option value="">Select Salesman</option>
                                                                <?php
                                                                    $UserId = $this->session->userdata('user_id');
                                                                    $CompId = $this->session->userdata('comp_id');
                                                                    $CompanyBranchId = $this->session->userdata('CompBranchId');
                                                                    $SalemanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');

                                                                    $SalesmanWhere = ['IsDeleted' => 1, 'Status' => 1, 'RoleId' => $SalemanRoleId, 'UserId' => $UserId, 'CompId' => $CompId];
                                                                    if ($this->session->userdata('BranchRole')) {
                                                                        $SalesmanWhere['CompanyBranchId'] = $CompanyBranchId;
                                                                    }
                                                                    $Salesmans = $this->db->select('Id, Name')->where($SalesmanWhere)->get('employees')->result();
                                                                    if (!empty($Salesmans)) {
                                                                        foreach($Salesmans as $key => $Salesman) {
                                                                ?>
                                                                <option value="<?= $Salesman->Id; ?>" <?= $data->SalesmanId == $Salesman->Id ? "selected" : ""; ?> <?= set_select('SalesmanId', $Salesman->Id); ?>><?= $Salesman->Name; ?></option>
                                                                <?php } } ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select style="width: 120px;" name="categoryId[]" id="categoryId" class="form-control form-control-sm" title="Party Category">
                                                                <option value="">Select Category</option>
                                                                <?php
                                                                    $partyCategoryWhere = ['isDeleted' => 1, 'userId' => $UserId, 'compId' => $CompId];
                                                                    if ($this->session->userdata('BranchRole')) {
                                                                        $partyCategoryWhere['compBranchId'] = $CompanyBranchId;
                                                                    }
                                                                    $partyCategories = $this->db->select('id, name')->where($partyCategoryWhere)->get('party_category')->result();
                                                                    if (!empty($partyCategories)) {
                                                                        foreach($partyCategories as $key => $categoryData) {
                                                                ?>
                                                                <option value="<?= $categoryData->id; ?>" <?= $data->categoryId == $categoryData->id ? "selected" : ""; ?> <?= set_select('categoryId', $categoryData->id); ?>><?= $categoryData->name; ?></option>
                                                                <?php } } ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <?php } } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="pull-right">
                                    <?= $links; ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>