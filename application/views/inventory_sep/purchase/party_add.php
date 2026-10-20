<table class="table table-bordered table-responsive-sm dataTable" style="overflow:scroll" id="example12">
    <thead class="text-dark">
        <tr>
            <th style="color:black!important;">Name</th>
            <th style="color:black!important;">Contact</th>
            <th style="color:black!important;">Email</th>
            <!-- <th style="color:black!important;">State</th> -->

            <!-- <th style="color:black!important;">Edit</th> -->
        </tr>
    </thead>
    <tbody id="tbody">
        <?php
        $role = $this->session->userdata('roles');
        //   $party = $this->db->select('party_id, name, city')->order_by('name', 'ASC')->get_where('parties', ['status' => 1])->result();
        $this->db->select('*');
        $this->db->from('parties');
        $this->db->where('status', 1);
        $this->db->where('comp_id', $this->session->userdata('comp_id'));
        $this->db->where('user_id', $this->session->userdata('user_id'));
        $this->db->where_in('group_head');
        $this->db->limit(20);
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get();
        $partys = $query->result();

        if ($partys) {

            foreach ($partys as $key => $party) {
        ?>

                <tr style="margin-top:10px;" id="trp<?= $key ?>" tabindex="-1" onkeyup="keyUpForParty(event,<?= $key ?>)" onkeypress="getCustomerDetail(<?= $party->id ?>,<?= $key ?>)" onkeydown="keyDownForParty(event,<?= $key ?>)" onclick="getCustomerDetail(<?= $party->id ?>,<?= $key ?>)">

                    <td>
                        <?= $party->name ?></td>
                    <td><?= $party->contact ?></td>
                    <td><?= $party->email ?></td>
                    <!-- <td><?= get_title('state_list',['id'=>$party->state],'state') ?></td> -->

                    
                
                </tr>
            <?php
            }
            ?>


        <?php

        }
        ?>
    </tbody>


</table>
<script>
    $('#closepartymodel').click(function() {
        $('#exampleModals').modal('hide');
    })


    function orBanaoForm(row, e) {
        e.preventDefault();
        // $(".searchselect").select2();
        var form = $("#orBanaoForm"+row);
        var data = new FormData(form);
        //(data);
        console.log(data);
        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: '<?= base_url() ?>party/updateparty',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(customer) {
                // console.log(customer);
                var customer = JSON.parse(customer);
                if (customer.status == true) {
                    document.getElementById('clik' + row).click();
                    alert("Party Updated");
                } else {
                    $("#partyMsg" + row).show();
                    $("#partyMsg" + row).addClass("text");
                    $("#partyMsg" + row).addClass("text-danger");
                    $("#partyMsg" + row).html(customer.msg);
                }

            },

        });
    }
</script>