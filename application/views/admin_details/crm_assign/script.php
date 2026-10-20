<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>

<script src="<?php echo base_url(); ?>assets/js/popper.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>

<script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js" defer></script>
<script>
    function assignValue(id) {
        jQuery.noConflict();
        $("#adding_party_ok .modal-dialog .modal-body").load("<?= base_url() ?>crm/crm_assign/assign_to_user/" + id);
        $("#addparty").html("Assign Company");
        $("#adding_party_ok").modal("show");
    }

    function productStock(productId) {
        jQuery.noConflict();
        $("#productStock .modal-dialog .modal-body").load(baseurl + "index.php/company_assign/stock/" + productId);
        $("#productStockLabels").html("COMPANY REPORT");
        $("#productStock").modal("show");

    }

    // $("#assign").click(function() {
    //     // let party_id = $("#party_id").val();
    //     console.log("ok");
    //     $("#adding_party_ok .modal-dialog .modal-body").load(baseurl + "index.php/party/create_party_for_sale_without_copying");
    //     $("#addparty").html("Create Party");
    //     $("#adding_party_ok").modal("show");

    // })


    $(document).ready(function() {
    $(".Datatable").DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5',
            'pageLength',
        ],
    });
})
</script>