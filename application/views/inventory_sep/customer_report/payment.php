<table class="table table-striped table-bordered table-responsive-sm dataTable" id="example">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Party Name</th>
            <th>Payment Mode</th>
            <th>Debit</th>
            <th>Credit</th>
            <th class="text-danger">Delete</th>
        </tr>
    </thead>
    <?php
    $financial_year = get_title('comp_profile', ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')], 'financial_year');

    $this->db->select('*');
    $this->db->from('payment');
    $this->db->where('comp_id', $this->session->userdata('comp_id'));
    $this->db->where('user_id', $this->session->userdata('user_id'));
    $this->db->where('financial_year', $financial_year);

    $this->db->where('partyID', $party_id);
    $result = $this->db->get()->result();
    ?>
    <tbody id="tbody">
        <?php
        $debit_amount = 0;
        $credit_amount = 0;
        $total_amount = 0;
        if ($result) {
            foreach ($result as $key => $row) {
                if ($row->paymentReceipt == 'Payment') {
                    $debit_amount += $row->amount;
                    $total_amount += $debit_amount;
                } else {
                    $credit_amount += $row->amount;
                    $total_amount += $credit_amount;
                }
                $dbt = 0;
                $cr = 0;



        ?>
                <tr id="trp<?= $key ?>" tabindex="<?= $key + 5 ?>">
                    <td><?= $key + 1 ?></td>

                    <td><?= number_format($row->amount,2); ?></td>
                    <td><?= $row->date; ?></td>

                    <td><?= get_title('parties', ['id' => $row->partyID], 'name') ?></td>
                    <td><?= get_title('payment_mode', ['id' => $row->paymentmode], 'payment_method') ?></td>
                    <?php
                    if ($row->paymentReceipt == 'Payment') {
                        $dbt = $row->amount;
                    } else {
                        $cr = $row->amount;
                    }
                    ?>
                    <td><?= number_format($dbt,2) ?></td>
                    <td><?= number_format($cr,2) ?></td>
                    <td>

                        <a href="<?= base_url('payment/delete/') . $row->id ?>" class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 icon icon-size">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg></a>
                    </td>


                </tr>
        <?php }
        } ?>
    </tbody>

    <tfoot>
        <th colspan="4">Total</th>
        <th><i class="fa fa-inr total" aria-hidden="true">
                <?php

                // $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                // $this->db->select_sum('taxableAmount');
                // $this->db->from('sales');
                // $this->db->where($where);
                // $res = $this->db->get()->row();
                // if ($res) {
                // 	echo $res->taxableAmount;
                // } else {
                // 	echo 0;
                // }
                ?>
        </th>
        <th><i class="fa fa-inr" aria-hidden="true">
                <?php
                echo $debit_amount;
                // $this->db->select_sum('totalTax');
                // $this->db->from('sales');
                // $this->db->where($where);

                // $res = $this->db->get()->row();
                // if ($res) {
                // 	echo $res->totalTax;
                // } else {
                // 	echo 0;
                // }
                ?>
        </th>
        <th><i class="fa fa-inr" aria-hidden="true">
                <?php
                // $this->db->select_sum('total');
                // $this->db->from('sales');
                // $this->db->where($where);
                // $res = $this->db->get()->row();
                // if ($res) {
                // 	echo $res->total;
                // } else {
                // 	echo 0;
                // }
                echo $credit_amount;
                ?>
        </th>
        <th></th>
    </tfoot>
</table>


<script>
    $(function() {
        $(".dataTable").DataTable({
            dom: 'Bfrtip',
            pageLength: 20,
            lengthMenu: [
                [20, 50, 100, -1],
                [20, 50, 100, "All"]
            ],
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5',
                'print',
                'pageLength',
            ]

        });
    });
</script>