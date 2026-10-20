<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <?php $role = $this->session->userdata('roles'); ?>
            <div class="row mt-5">
                <div class="header bg-blue">
                    <div class="col-md-12 col-xl-12 col-lg-12">
                        <div class="header-body">
                            <h2 style="color: white;padding:10px 0px 0px 0px;" class="text-center">Update Payment</h2>
                            <!-- Card stats -->
                            <div class="row">
                                <div class="container-fluid">
                                    <?php if ($this->session->flashdata('msg')) { ?>
                                    <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                        <?php echo $this->session->flashdata('msg'); ?>
                                    </div>
                                    <?php } ?>
                                    <form method="POST" action="<?= base_url('payment/update'); ?>">
                                        <div class="row">
                                            <input type="hidden" name="id" id="id" value="<?= $result->id; ?>" required="true" />
                                            <div class="col-md-3 col-sm-6 col-lg-3">
                                                <div class="form-group">
                                                    <label for="ladger">Ledger</label>
                                                    <select name="ladger" onchange="changeParty(event)" id="ladger" required="true" class="form-control" style="width:100%;">
                                                        <option value="">Select One</option>
                                                        <?php
                                                            $this->db->from('ladger');
                                                            $this->db->where('status', 1);
                                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                                            $this->db->order_by('name', 'ASC');
                                                            $query = $this->db->get();
                                                            $party = $query->result();
                                                            if ($party) { foreach ($party as $row) {
                                                        ?>
                                                        <option value="<?= $row->id; ?>" <?= $row->id == $result->ladger ? "selected" : '' ?>><?= ucwords($row->name); ?></option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-lg-3">
                                                <div class="form-group">
                                                    <label for="date">Date</label>
                                                    <input type="date" class="form-control" name="date" id="date" value="<?php echo $result->date ?>" required="true">
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-lg-3">
                                                <div class="form-group">
                                                    <label for="party_name">Party Name</label>
                                                    <select name="customerId" id="party_id" required="true" class="form-control">
                                                        <option id="selectedValue" value="">Select One</option>
                                                        <?php
                                                            $this->db->select('id, name, city');
                                                            $this->db->from('parties');
                                                            $this->db->where('status', 1);
                                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                                            $this->db->where_in('group_head');
                                                            $this->db->order_by('name', 'ASC');
                                                            $query = $this->db->get();
                                                            $party = $query->result();
                                                            if ($party) { foreach ($party as $row) {
                                                        ?>
                                                        <option value="<?= $row->id; ?>" <?= $row->id == $result->partyID ? "selected" : '' ?>><?= ucwords($row->name); ?>(<?= get_title('parties', ['id' => $row->id], 'name') ?>)</option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-lg-3">
                                                <div class="form-group">
                                                    <label for="amount">Amount</label>
                                                    <input type="number" class="form-control" name="amount" id="amount" value="<?= $result->amount ?>" required="true">
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-lg-3">
                                                <label for="payment_recipt">Payment Receipt</label>
                                                <select name="payment_recipt" id="payment_recipt" class="form-control ">
                                                    <option value="">-Select-</option>
                                                    <option value="Reciept" <?= $result->paymentReceipt == "Reciept" ? "selected" : '' ?>>Reciept</option>
                                                    <option value="Payment" <?= $result->paymentReceipt == "Payment" ? "selected" : '' ?>>Payment</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-lg-3">
                                                <div class="form-group">
                                                    <label for="payment_mode">Payment Mode</label>
                                                    <select name="payment_mode" onchange="changeParty(event)" id="payment_mode" required="true" class="form-control" style="width:100%;">
                                                        <option id="selectedValue" value="">Select One</option>
                                                        <?php
                                                            $this->db->select('*');
                                                            $this->db->from('payment_mode');
                                                            $this->db->where('status', 1);
                                                            $query = $this->db->get();
                                                            $payment_mode = $query->result();
                                                            if ($payment_mode) { foreach ($payment_mode as $row) {
                                                        ?>
                                                        <option value="<?= $row->id; ?>" <?= $row->id == $result->paymentmode ? "selected" : '' ?>><?= ucwords($row->payment_method); ?></option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 col-lg-6">
                                                <div class="form-group">
                                                    <label for="remark">Remarks</label>
                                                    <textarea type="text" class="form-control" required="true" name="remark" id="remark"><?= $result->remark ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group text-center">
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        Update
                                                    </button>
                                                    <a href="<?= base_url(); ?>payment" class="btn btn-danger btn-sm">Cancel</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>