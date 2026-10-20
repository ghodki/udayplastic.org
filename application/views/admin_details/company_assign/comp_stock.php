<!-- Card stats -->
<div class="row">
    <div class="col-md-12">
        <h1 class="text-center"><?= $comp_details->company_name ?></h1>
        <br>
        <br>
        <table class="table table-striped table-bordered">

            <thead>
                <tr>
                    <th>Total Sale</th>
                    <th>Total Purchase</th>
                    <th>Total Product</th>


                </tr>
            </thead>
            <tbody>
                <tr>

                    <?php
                    if (isset($comp_details) && !empty($comp_details)) {
                        $tota_sale = 0;
                        $this->db->select('COUNT(*) as totl');
                        $user_id = $comp_details->user_id;
                        $comp_id = $comp_details->id;
                        $this->db->where('user_id', $user_id);
                        $this->db->where('comp_id', $comp_id);
                        $q = $this->db->get('sales');
                        $r = $q->row();
                        $tota_sale = $r->totl;


                        $total_purchase = 0;
                        $this->db->select('COUNT(*) as totl');
                        $user_id = $comp_details->user_id;
                        $comp_id = $comp_details->id;
                        $this->db->where('user_id', $user_id);
                        $this->db->where('comp_id', $comp_id);
                        $q = $this->db->get('purchases');
                        $r = $q->row();
                        $total_purchase = $r->totl;


                        $total_product = 0;
                        $this->db->select('COUNT(*) as totl');
                        $user_id = $comp_details->user_id;
                        $comp_id = $comp_details->id;
                        $this->db->where('user_id', $user_id);
                        $this->db->where('comp_id', $comp_id);
                        $q = $this->db->get('product');
                        $r = $q->row();
                        $total_product = $r->totl;

                        $total_sale_transaction = 0;
                        $this->db->select('COUNT(totalValue) as totl');
                        $user_id = $comp_details->user_id;
                        $comp_id = $comp_details->id;
                        $this->db->where('user_id', $user_id);
                        $this->db->where('comp_id', $comp_id);
                        $q = $this->db->get('sales');
                        $r = $q->row();
                        $total_sale_transaction = $r->totl;

                        $total_purchase_transaction = 0;
                        $this->db->select('COUNT(totalValue) as totl');
                        $user_id = $comp_details->user_id;
                        $comp_id = $comp_details->id;
                        $this->db->where('user_id', $user_id);
                        $this->db->where('comp_id', $comp_id);
                        $q = $this->db->get('purchases');
                        $r = $q->row();
                        $total_purchase_transaction = $r->totl;

                    ?>
                        <th><?= $tota_sale ?></th>
                        <th><?= $total_purchase ?></th>
                        <th><?= $total_product ?></th>
                   
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th><?= $total_sale_transaction ?></th>
                    <th><?= $total_purchase_transaction ?></th>

                    <th></th>


                </tr>
            </tfoot>
            <?php
                    }
                    ?>
        </table>


    </div>
</div>