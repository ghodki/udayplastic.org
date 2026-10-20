            <div class="modal-header">
                <h5 class="modal-title" id="title">Warehouse</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="" id="respnce_msg" style="opacity: 500; display: none;"></div>
            <form action="" method="POST" id="setWarehouseForProduct" onsubmit="return false;">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <div class="form-group">
                                <select name="warehouse_for_product" id="warehouse_for_product" class="form-control form-control-sm">
                                    <option selected="" disabled="" value="">Select Warehouse</option>
                                    <?php
                                        $this->db->select('id, name, location');
                                        $this->db->where('user_id', $this->session->userdata('user_id'));
                                        $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                        $warehouses = $this->db->get('warehouse')->result();
                                        if (!empty($warehouses)) { foreach ($warehouses as $key => $warehouse) {
                                            $comp_warehouse = get_title('comp_profile', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')], 'warehouse');
                                    ?>
                                    <option <?= $comp_warehouse == $warehouse->id ? "selected" : ""; ?> value="<?= $warehouse->id; ?>" <?= set_select('warehouse', $warehouse->id); ?>><?= $warehouse->name; ?> - <?= $warehouse->location; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="setWarehouseForProduct();" class="btn btn-warning btn-sm">Set Warehouse</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cancel</button>
                </div>
            </form>